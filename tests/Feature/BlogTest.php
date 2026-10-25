<?php

use App\Models\BlogComment;
use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

function blogPostPayload(array $overrides = []): array
{
    return array_merge([
        'title' => 'A useful article',
        'slug' => 'a-useful-article',
        'excerpt' => 'A short description of the article.',
        'content' => [
            'type' => 'doc',
            'content' => [
                ['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'Article body']]],
            ],
        ],
        'status' => 'published',
        'categories' => 'Engineering, Product',
        'tags' => 'Laravel, Design',
        'seo_title' => 'SEO title',
        'seo_description' => 'SEO description',
    ], $overrides);
}

test('public projects page features published work and filters categories', function () {
    $category = Category::query()->create(['name' => 'Engineering', 'slug' => 'engineering']);
    $published = Project::query()->create([
        'title' => 'Published project',
        'slug' => 'published-project',
        'summary' => 'Published project summary',
        'challenge' => 'Challenge',
        'solution' => 'Solution',
        'is_published' => true,
        'is_featured' => true,
    ]);
    $published->categories()->attach($category);
    Project::query()->create([
        'title' => 'Private project',
        'slug' => 'private-project',
        'summary' => 'Private project summary',
        'challenge' => 'Challenge',
        'solution' => 'Solution',
        'is_published' => false,
    ]);

    $this->get(route('projects.index', ['category' => 'engineering']))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('projects/index')
            ->where('projects.data.0.slug', 'published-project')
            ->where('activeCategory', 'engineering')
            ->has('projects.data', 1));
});

test('public blog search and category filters only show posts whose publication time has arrived', function () {
    $writer = User::factory()->create(['role' => 'superadmin']);
    $this->actingAs($writer);
    $this->post(route('admin.blog.store'), blogPostPayload(['published_at' => now()->subDay()->toDateTimeString()]))
        ->assertRedirect(route('admin.blog.index'))
        ->assertSessionDoesntHaveErrors();
    $this->post(route('admin.blog.store'), blogPostPayload([
        'title' => 'Future article',
        'slug' => 'future-article',
        'status' => 'scheduled',
        'published_at' => now()->addDay()->toDateTimeString(),
    ]))->assertRedirect();

    $this->get(route('blog.index', ['q' => 'useful', 'category' => 'engineering']))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('blog/index')
            ->has('posts.data', 1)
            ->where('posts.data.0.slug', 'a-useful-article'));

    $this->get(route('blog.show', 'future-article'))->assertNotFound();

    $this->travel(2)->days();
    $this->get(route('blog.show', 'future-article'))->assertOk()
        ->assertInertia(fn ($page) => $page->component('blog/show')->where('post.slug', 'future-article'));
});

test('superadmin can create scheduled posts with taxonomy and uploaded covers', function () {
    Storage::fake('public');
    $this->actingAs(User::factory()->create(['role' => 'superadmin']));

    $this->post(route('admin.blog.store'), blogPostPayload([
        'status' => 'scheduled',
        'published_at' => now()->addDay()->format('Y-m-d H:i:s'),
        'cover' => UploadedFile::fake()->create('cover.jpg', 1, 'image/jpeg'),
    ]))->assertRedirect(route('admin.blog.index'));

    $post = BlogPost::query()->with(['categories', 'tags'])->sole();
    expect($post->status)->toBe('scheduled')
        ->and($post->categories->pluck('name')->all())->toContain('Engineering', 'Product')
        ->and($post->tags->pluck('name')->all())->toContain('Laravel', 'Design')
        ->and($post->cover_image)->not->toBeNull();
    Storage::disk('public')->assertExists($post->cover_image);
});

test('scheduled posts require a future publication time and blog management is restricted to superadmins', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin)->get(route('admin.blog.index'))->assertForbidden();

    $this->actingAs(User::factory()->create(['role' => 'superadmin']))
        ->post(route('admin.blog.store'), blogPostPayload([
            'status' => 'scheduled',
            'published_at' => now()->subMinute()->format('Y-m-d H:i:s'),
        ]))
        ->assertSessionHasErrors('published_at');

    expect(BlogPost::query()->count())->toBe(0);
});

test('anonymous comments are held for moderation until approved', function () {
    $post = BlogPost::query()->create([
        ...blogPostPayload(),
        'user_id' => null,
        'published_at' => now()->subMinute(),
    ]);

    $this->post(route('blog.comments.store', $post->slug), [
        'name' => 'Reader',
        'email' => 'reader@example.test',
        'body' => 'A thoughtful comment.',
    ])->assertRedirect()
        ->assertSessionHas('success');

    $comment = BlogComment::query()->sole();
    expect($comment->approved_at)->toBeNull();

    $this->get(route('blog.show', $post->slug))
        ->assertInertia(fn ($page) => $page->component('blog/show')->has('comments', 0));

    $this->actingAs(User::factory()->create(['role' => 'superadmin']))
        ->patch(route('admin.blog-comments.approval', $comment))
        ->assertRedirect();

    $this->get(route('blog.show', $post->slug))
        ->assertInertia(fn ($page) => $page->component('blog/show')->has('comments', 1));
});

test('sitemap includes the projects and blog landing pages and published articles', function () {
    $post = BlogPost::query()->create([
        ...blogPostPayload(),
        'published_at' => now()->subDay(),
    ]);

    $this->get(route('sitemap'))
        ->assertOk()
        ->assertSee(route('projects.index'), false)
        ->assertSee(route('blog.index'), false)
        ->assertSee(route('blog.show', $post->slug), false);
});
