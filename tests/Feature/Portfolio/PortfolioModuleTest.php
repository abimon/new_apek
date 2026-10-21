<?php

use App\Mail\PortfolioAccountCredentials;
use App\Models\AuditLog;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;

function portfolioProject(User $owner, array $overrides = []): Project
{
    return Project::query()->create(array_merge([
        'user_id' => $owner->id,
        'portfolio_owner_id' => $owner->id,
        'title' => 'Portfolio submission',
        'slug' => 'portfolio-submission-'.fake()->unique()->numerify('####'),
        'project_type' => 'client',
        'client' => 'Example client',
        'summary' => 'A published-safe project summary.',
        'challenge' => 'Private challenge details.',
        'solution' => 'A project solution.',
        'features' => ['Accessible design'],
        'is_published' => false,
        'portfolio_review_status' => 'pending',
    ], $overrides));
}

test('only superadmins can provision portfolio accounts', function () {
    Mail::fake();
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin)->post(route('admin.portfolio-users.store'), [
        'name' => 'Portfolio User',
        'email' => 'portfolio@example.test',
        'password' => 'SecurePass12345',
        'password_confirmation' => 'SecurePass12345',
    ])->assertForbidden();

    $superadmin = User::factory()->create(['role' => 'superadmin']);
    $this->actingAs($superadmin)->post(route('admin.portfolio-users.store'), [
        'name' => 'Portfolio User',
        'email' => 'portfolio@example.test',
        'password' => 'SecurePass12345',
        'password_confirmation' => 'SecurePass12345',
    ])->assertRedirect();

    $user = User::query()->where('email', 'portfolio@example.test')->sole();
    expect($user->role)->toBe('worker')
        ->and($user->portfolio_enabled)->toBeTrue()
        ->and($user->portfolio_slug)->toHaveLength(32)
        ->and($user->force_password_change)->toBeTrue();

    Mail::assertSent(PortfolioAccountCredentials::class, fn (PortfolioAccountCredentials $mail) => $mail->hasTo('portfolio@example.test')
        && strlen($mail->temporaryPassword) >= 24
        && Hash::check($mail->temporaryPassword, $user->password));
    expect(AuditLog::query()->where('event', 'admin_portfolio-users_store')->where('status', 'succeeded')->sole()->status)->toBe('succeeded');
});

test('portfolio users must replace the emailed temporary password before accessing the system', function () {
    $user = User::factory()->create([
        'role' => 'worker',
        'portfolio_enabled' => true,
        'portfolio_slug' => 'first-login-profile',
        'force_password_change' => true,
        'password' => 'temporary-password',
    ]);

    $this->post(route('login'), ['email' => $user->email, 'password' => 'temporary-password'])
        ->assertRedirect(route('password.first-login.edit'));

    $this->get(route('portfolio.profile.edit'))->assertRedirect(route('password.first-login.edit'));
    $this->get(route('password.first-login.edit'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('auth/first-login-password'));

    $this->put(route('password.first-login.update'), [
        'password' => 'A-New-Secure-Password-123',
        'password_confirmation' => 'A-New-Secure-Password-123',
    ])->assertRedirect(route('portfolio.profile.edit'));

    expect($user->fresh()->force_password_change)->toBeFalse()
        ->and(Hash::check('A-New-Secure-Password-123', $user->fresh()->password))->toBeTrue();
    $this->get(route('portfolio.profile.edit'))->assertOk();
    expect(AuditLog::query()->where('event', 'auth.password.first_login')->where('status', 'succeeded')->exists())->toBeTrue();
});

test('disabled accounts cannot sign in', function () {
    $user = User::factory()->create(['is_active' => false]);

    $this->post(route('login'), ['email' => $user->email, 'password' => 'password'])
        ->assertSessionHasErrors('email');

    $this->assertGuest();
});

test('superadmins can revoke and restore access but cannot revoke their own or the last superadmin access', function () {
    $superadmin = User::factory()->create(['role' => 'superadmin']);
    $otherSuperadmin = User::factory()->create(['role' => 'superadmin']);
    $worker = User::factory()->create(['role' => 'worker']);

    $this->actingAs($superadmin)->get(route('admin.users.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('admin/users/index')->has('users.data'));

    $this->patch(route('admin.users.access', $worker), ['is_active' => false])->assertRedirect();
    expect($worker->fresh()->is_active)->toBeFalse();

    $this->patch(route('admin.users.access', $worker), ['is_active' => true])->assertRedirect();
    expect($worker->fresh()->is_active)->toBeTrue();

    $this->patch(route('admin.users.access', $superadmin), ['is_active' => false])
        ->assertSessionHasErrors('access');
    $this->patch(route('admin.users.access', $otherSuperadmin), ['is_active' => false])->assertRedirect();
    expect($otherSuperadmin->fresh()->is_active)->toBeFalse();

    $this->patch(route('admin.users.access', $otherSuperadmin), ['is_active' => true])->assertRedirect();

    $this->actingAs($otherSuperadmin)
        ->patch(route('admin.users.access', $otherSuperadmin), ['is_active' => false])
        ->assertSessionHasErrors('access');

    expect($superadmin->fresh()->is_active)->toBeTrue()
        ->and($otherSuperadmin->fresh()->is_active)->toBeTrue()
        ->and(AuditLog::query()->where('event', 'admin_users_access')->where('status', 'succeeded')->count())->toBe(4);
});

test('revoked access terminates an existing authenticated session on the next request', function () {
    $user = User::factory()->create(['is_active' => false, 'portfolio_enabled' => true]);

    $this->actingAs($user)->get(route('portfolio.profile.edit'))
        ->assertRedirect(route('login'));

    $this->assertGuest();
});

test('portfolio user can edit only their own submissions and edits require review again', function () {
    $owner = User::factory()->create(['role' => 'guest', 'portfolio_enabled' => true, 'portfolio_slug' => 'owner-profile']);
    $other = User::factory()->create(['role' => 'guest', 'portfolio_enabled' => true, 'portfolio_slug' => 'other-profile']);
    $project = portfolioProject($owner, [
        'portfolio_review_status' => 'approved',
        'portfolio_reviewed_at' => now(),
        'portfolio_reviewed_by' => User::factory()->create(['role' => 'superadmin'])->id,
    ]);
    $foreignProject = portfolioProject($other);

    $this->actingAs($owner)->get(route('portfolio.projects.edit', $foreignProject))
        ->assertNotFound();

    $this->actingAs($owner)->put(route('portfolio.projects.update', $project), [
        'title' => 'Updated submission',
        'project_type' => 'product',
        'summary' => 'A revised short summary.',
        'challenge' => 'The updated challenge.',
        'solution' => 'The updated solution.',
        'features' => ['Accessible design', 'Mobile support'],
    ])->assertRedirect(route('portfolio.projects.index'));

    expect($project->fresh()->portfolio_review_status)->toBe('pending')
        ->and($project->fresh()->portfolio_reviewed_at)->toBeNull();
});

test('portfolio project creation always binds the submission to its authenticated owner', function () {
    Storage::fake('public');
    $owner = User::factory()->create(['role' => 'guest', 'portfolio_enabled' => true]);
    $other = User::factory()->create(['role' => 'guest', 'portfolio_enabled' => true]);

    $this->actingAs($owner)->post(route('portfolio.projects.store'), [
        'title' => 'A SaaS product',
        'project_type' => 'product',
        'client' => '',
        'summary' => 'A subscription management platform.',
        'challenge' => 'Businesses need a reliable subscription service.',
        'solution' => 'A secure SaaS platform was built.',
        'live_url' => '',
        'features' => ['Billing', 'Reporting'],
        'user_id' => $other->id,
        'portfolio_owner_id' => $other->id,
        'is_published' => true,
        'cover' => UploadedFile::fake()->create('cover.jpg', 1, 'image/jpeg'),
    ])->assertRedirect(route('portfolio.projects.index'));

    $project = Project::query()->where('title', 'A SaaS product')->sole();
    expect($project->user_id)->toBe($owner->id)
        ->and($project->portfolio_owner_id)->toBe($owner->id)
        ->and($project->project_type)->toBe('product')
        ->and($project->is_published)->toBeFalse()
        ->and($project->portfolio_review_status)->toBe('pending');
    Storage::disk('public')->assertExists($project->cover_image);
});

test('only enabled and public owners expose approved portfolio projects', function () {
    $owner = User::factory()->create([
        'role' => 'guest',
        'portfolio_enabled' => true,
        'portfolio_slug' => 'public-profile',
        'portfolio_is_public' => true,
        'professional_title' => 'Senior Developer',
        'portfolio_summary' => 'A public professional summary.',
        'portfolio_email' => 'public@example.test',
    ]);
    $approved = portfolioProject($owner, [
        'title' => 'Approved work',
        'portfolio_review_status' => 'approved',
        'is_published' => false,
    ]);
    portfolioProject($owner, ['title' => 'Pending work']);
    $privateOwner = User::factory()->create(['portfolio_enabled' => true, 'portfolio_slug' => 'private-profile']);

    $this->get(route('portfolio.public', $owner->portfolio_slug))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('portfolio/public')
            ->where('profile.name', $owner->name)
            ->where('profile.portfolio_email', 'public@example.test')
            ->missing('profile.password')
            ->has('projects', 1, fn (Assert $project) => $project
                ->where('title', $approved->title)
                ->where('summary', $approved->summary)
                ->missing('portfolio_review_status')
                ->missing('portfolio_reviewed_by')
                ->etc()));

    $this->get(route('portfolio.public', $privateOwner->portfolio_slug))->assertNotFound();

    $owner->update(['is_active' => false]);
    $this->get(route('portfolio.public', $owner->portfolio_slug))->assertNotFound();
    $this->get(route('portfolio.cv', $owner->portfolio_slug))->assertNotFound();
});

test('only superadmins can approve submissions and approval never publishes a project on the company site', function () {
    $owner = User::factory()->create(['portfolio_enabled' => true]);
    $project = portfolioProject($owner);

    $this->actingAs(User::factory()->create(['role' => 'admin']))
        ->patch(route('admin.portfolio-projects.approve', $project))
        ->assertForbidden();

    $superadmin = User::factory()->create(['role' => 'superadmin']);
    $this->actingAs($superadmin)
        ->patch(route('admin.portfolio-projects.approve', $project))
        ->assertRedirect();

    $project->refresh();
    expect($project->portfolio_review_status)->toBe('approved')
        ->and($project->portfolio_reviewed_by)->toBe($superadmin->id)
        ->and($project->is_published)->toBeFalse();
});

test('portfolio profile updates safely replace a professional photo', function () {
    Storage::fake('public');
    $owner = User::factory()->create([
        'portfolio_enabled' => true,
        'portfolio_slug' => 'photo-profile',
        'professional_photo' => 'portfolios/photos/1/old.jpg',
    ]);
    Storage::disk('public')->put('portfolios/photos/1/old.jpg', 'old-photo');

    $this->actingAs($owner)->put(route('portfolio.profile.update'), [
        'professional_title' => 'Software Engineer',
        'portfolio_summary' => 'Experienced software engineer.',
        'portfolio_email' => 'public@example.test',
        'portfolio_phone' => '',
        'portfolio_location' => 'Nairobi',
        'portfolio_website' => '',
        'linkedin_url' => '',
        'github_url' => '',
        'portfolio_skills' => ['PHP', 'TypeScript'],
        'portfolio_experience' => [],
        'portfolio_education' => [],
        'portfolio_certifications' => [],
        'portfolio_languages' => ['English'],
        'portfolio_is_public' => true,
        'professional_photo' => UploadedFile::fake()->create('portrait.jpg', 1, 'image/jpeg'),
    ])->assertRedirect();

    $owner->refresh();
    expect($owner->portfolio_is_public)->toBeTrue()
        ->and($owner->portfolio_skills)->toBe(['PHP', 'TypeScript'])
        ->and($owner->professional_photo)->not->toBe('portfolios/photos/1/old.jpg');
    Storage::disk('public')->assertExists($owner->professional_photo);
    Storage::disk('public')->assertMissing('portfolios/photos/1/old.jpg');
});

test('CV download returns a PDF with approved work only', function () {
    $owner = User::factory()->create([
        'portfolio_enabled' => true,
        'portfolio_slug' => 'cv-profile',
        'portfolio_is_public' => true,
        'professional_title' => 'Software Engineer',
        'portfolio_summary' => 'Builds useful software.',
    ]);
    portfolioProject($owner, ['portfolio_review_status' => 'approved', 'title' => 'Approved CV project']);
    portfolioProject($owner, ['portfolio_review_status' => 'pending', 'title' => 'Pending CV project']);

    $response = $this->get(route('portfolio.cv', $owner->portfolio_slug));

    $response->assertOk()
        ->assertHeader('content-type', 'application/pdf');
    expect($response->getContent())->toStartWith('%PDF');
});

test('shared authenticated user data does not expose private portfolio fields', function () {
    $user = User::factory()->create([
        'portfolio_enabled' => true,
        'portfolio_slug' => 'privacy-check',
        'portfolio_summary' => 'Confidential until published.',
        'portfolio_is_public' => false,
    ]);

    $this->actingAs($user)->get(route('portfolio.profile.edit'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('portfolio/profile')
            ->missing('auth.user.portfolio_summary')
            ->missing('auth.user.portfolio_slug')
            ->where('profile.portfolio_summary', 'Confidential until published.'));
});
