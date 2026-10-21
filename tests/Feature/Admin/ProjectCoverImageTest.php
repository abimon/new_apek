<?php

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

function projectPayload(array $overrides = []): array
{
    return array_merge([
        'title' => 'Portfolio project',
        'project_type' => 'client',
        'client' => 'Example client',
        'summary' => 'A short summary',
        'challenge' => 'The challenge',
        'solution' => 'The solution',
        'live_url' => '',
        'accent' => '#D4A373',
        'owner_involvement' => true,
        'is_featured' => false,
        'is_published' => true,
    ], $overrides);
}

test('project creation stores an uploaded cover image', function () {
    Storage::fake('public');
    $this->actingAs(User::factory()->create(['role' => 'admin']));

    $this->post(route('admin.projects.store'), projectPayload([
        'cover' => UploadedFile::fake()->create('cover.jpg', 1, 'image/jpeg'),
    ]))->assertRedirect(route('admin.projects.index'));

    $project = Project::query()->firstOrFail();

    expect($project->cover_image)->not->toBeNull();
    Storage::disk('public')->assertExists($project->cover_image);
});

test('project updates replace a cover image and keep it when no replacement is uploaded', function () {
    Storage::fake('public');
    $this->actingAs(User::factory()->create(['role' => 'admin']));
    $project = Project::query()->create(projectPayload([
        'slug' => 'portfolio-project',
        'cover_image' => 'projects/covers/original.jpg',
    ]));
    Storage::disk('public')->put($project->cover_image, 'original');

    $this->post(route('admin.projects.update', $project), [
        ...projectPayload(),
        '_method' => 'put',
        'cover' => UploadedFile::fake()->create('replacement.jpg', 1, 'image/jpeg'),
    ])->assertRedirect(route('admin.projects.index'));

    $project->refresh();
    $replacement = $project->cover_image;

    expect($replacement)->not->toBe('projects/covers/original.jpg');
    Storage::disk('public')->assertExists($replacement);
    Storage::disk('public')->assertMissing('projects/covers/original.jpg');

    $this->post(route('admin.projects.update', $project), [
        ...projectPayload(),
        '_method' => 'put',
    ])->assertRedirect(route('admin.projects.index'));

    expect($project->fresh()->cover_image)->toBe($replacement);
});

test('product projects do not require or retain a client name', function () {
    $this->actingAs(User::factory()->create(['role' => 'admin']));

    $this->post(route('admin.projects.store'), projectPayload([
        'project_type' => 'product',
        'client' => 'Should not be retained',
    ]))->assertRedirect(route('admin.projects.index'));

    $project = Project::query()->firstOrFail();
    expect($project->project_type)->toBe('product')
        ->and($project->client)->toBeNull();
});

test('project features are saved and restored as an editable list', function () {
    $this->actingAs(User::factory()->create(['role' => 'admin']));
    $features = ['User authentication', 'Offline mode', 'Custom reports'];

    $this->post(route('admin.projects.store'), projectPayload([
        'features' => $features,
    ]))->assertRedirect(route('admin.projects.index'));

    expect(Project::query()->firstOrFail()->features)->toBe($features);
});

test('only admins and superadmins can manage projects', function () {
    $guest = User::factory()->create(['role' => 'guest']);
    $this->actingAs($guest);

    $this->get(route('admin.projects.index'))->assertForbidden();
    $this->post(route('admin.projects.store'), projectPayload())->assertForbidden();
});

test('project cover uploads reject non-image files', function () {
    Storage::fake('public');
    $this->actingAs(User::factory()->create(['role' => 'admin']));

    $this->post(route('admin.projects.store'), projectPayload([
        'cover' => UploadedFile::fake()->create('payload.html', 1, 'text/html'),
    ]))->assertSessionHasErrors('cover');

    expect(Project::query()->count())->toBe(0);
});

test('project cover uploads reject files larger than five megabytes', function () {
    Storage::fake('public');
    $this->actingAs(User::factory()->create(['role' => 'admin']));

    $this->post(route('admin.projects.store'), projectPayload([
        'cover' => UploadedFile::fake()->create('large.jpg', 5121, 'image/jpeg'),
    ]))->assertSessionHasErrors('cover');

    expect(Project::query()->count())->toBe(0);
});
