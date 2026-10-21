<?php

use App\Models\AuditLog;
use App\Models\Project;
use App\Models\User;

test('sensitive project creation attempts are audited without storing request contents', function () {
    $this->actingAs(User::factory()->create(['role' => 'admin']));

    $this->post(route('admin.projects.store'), [
        'title' => 'Audit test project',
        'project_type' => 'client',
        'client' => 'Test client',
        'summary' => 'Summary',
        'challenge' => 'Challenge',
        'solution' => 'Solution',
        'live_url' => '',
        'accent' => '#D4A373',
        'owner_involvement' => true,
        'is_featured' => false,
        'is_published' => true,
        'password' => 'must-not-be-logged',
    ])->assertRedirect(route('admin.projects.index'));

    $log = AuditLog::query()->where('event', 'admin_projects_store')->sole();

    expect($log->status)->toBe('succeeded')
        ->and($log->subject_type)->toBe(Project::class)
        ->and($log->subject_id)->not->toBeNull()
        ->and($log->ip_address)->not->toBeNull()
        ->and(json_encode($log->metadata))->not->toContain('must-not-be-logged');
});

test('failed login attempts are audited without retaining submitted credentials', function () {
    $this->post(route('login'), [
        'email' => 'unknown@example.com',
        'password' => 'secret-password',
    ])->assertSessionHasErrors('email');

    $log = AuditLog::query()->where('event', 'auth.login')->sole();

    expect($log->status)->toBe('failed')
        ->and($log->user_id)->toBeNull()
        ->and(json_encode($log->metadata))->not->toContain('unknown@example.com')
        ->and(json_encode($log->metadata))->not->toContain('secret-password')
        ->and($log->metadata['identifier_fingerprint'])->toHaveLength(64);
});

test('successful login attempts are audited with their authenticated actor', function () {
    $user = User::factory()->create(['role' => 'admin']);

    $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect(route('dashboard', absolute: false));

    $log = AuditLog::query()->where('event', 'auth.login')->sole();

    expect($log->status)->toBe('succeeded')
        ->and($log->user_id)->toBe($user->id);
});

test('failed creation and project update and delete attempts are audited', function () {
    $this->actingAs(User::factory()->create(['role' => 'admin']));

    $this->post(route('admin.projects.store'), ['title' => 'Incomplete project'])
        ->assertSessionHasErrors();

    $project = Project::query()->create([
        'title' => 'Existing project',
        'slug' => 'existing-project',
        'project_type' => 'client',
        'summary' => 'Summary',
        'challenge' => 'Challenge',
        'solution' => 'Solution',
        'accent' => '#D4A373',
    ]);

    $this->put(route('admin.projects.update', $project), [
        'title' => 'Updated project',
        'project_type' => 'client',
        'client' => '',
        'summary' => 'Updated summary',
        'challenge' => 'Updated challenge',
        'solution' => 'Updated solution',
        'live_url' => '',
        'accent' => '#D4A373',
        'owner_involvement' => true,
        'is_featured' => false,
        'is_published' => true,
    ])->assertRedirect(route('admin.projects.index'));

    $this->delete(route('admin.projects.destroy', $project))
        ->assertRedirect();

    expect(AuditLog::query()->where('event', 'admin_projects_store')->where('status', 'failed')->exists())->toBeTrue()
        ->and(AuditLog::query()->where('event', 'admin_projects_update')->where('status', 'succeeded')->exists())->toBeTrue()
        ->and(AuditLog::query()->where('event', 'admin_projects_destroy')->where('status', 'succeeded')->exists())->toBeTrue();
});

test('only superadmins can view audit logs', function () {
    $this->actingAs(User::factory()->create(['role' => 'admin']))
        ->get(route('admin.audit-logs.index'))
        ->assertForbidden();

    $this->actingAs(User::factory()->create(['role' => 'superadmin']))
        ->get(route('admin.audit-logs.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('admin/audit-logs/index')->has('logs.data'));
});
