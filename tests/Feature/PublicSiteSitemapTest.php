<?php

use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\URL;

test('public pages and eligible public portfolios are included in the sitemap', function () {
    $publishedProject = Project::create([
        'title' => 'Published project',
        'slug' => 'published-project',
        'summary' => 'A public project summary.',
        'challenge' => 'A real challenge.',
        'solution' => 'A practical solution.',
        'is_published' => true,
    ]);
    Project::create([
        'title' => 'Unpublished project',
        'slug' => 'unpublished-project',
        'summary' => 'A private project summary.',
        'challenge' => 'A real challenge.',
        'solution' => 'A practical solution.',
        'is_published' => false,
    ]);
    $publicProfile = User::factory()->create([
        'portfolio_slug' => 'public-profile',
        'portfolio_enabled' => true,
        'portfolio_is_public' => true,
    ]);
    User::factory()->create([
        'portfolio_slug' => 'private-profile',
        'portfolio_enabled' => true,
        'portfolio_is_public' => false,
    ]);
    User::factory()->create([
        'portfolio_slug' => 'disabled-profile',
        'portfolio_enabled' => false,
        'portfolio_is_public' => true,
    ]);

    $response = $this->get(route('sitemap'));

    $response->assertOk()
        ->assertHeader('Content-Type', 'application/xml; charset=UTF-8');

    $xml = simplexml_load_string($response->getContent());
    expect($xml)->not->toBeFalse();

    $namespace = $xml->getNamespaces(true)[''];
    $locations = [];
    foreach ($xml->children($namespace)->url as $url) {
        $path = parse_url((string) $url->children($namespace)->loc, PHP_URL_PATH);
        $locations[] = rtrim($path, '/') ?: '/';
    }

    expect($locations)
        ->toContain('/')
        ->toContain(parse_url(URL::route('about'), PHP_URL_PATH))
        ->toContain(parse_url(URL::route('services'), PHP_URL_PATH))
        ->toContain(parse_url(URL::route('contact'), PHP_URL_PATH))
        ->toContain(parse_url(URL::route('projects.show', $publishedProject), PHP_URL_PATH))
        ->toContain(parse_url(URL::route('portfolio.public', $publicProfile->portfolio_slug), PHP_URL_PATH))
        ->not->toContain('/work/unpublished-project')
        ->not->toContain('/portfolio/private-profile')
        ->not->toContain('/portfolio/disabled-profile');
});

test('about services and contact pages are public and render their intended content', function () {
    $this->get(route('about'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('about'));

    $this->get(route('services'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('services'));

    $this->get(route('contact'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('contact'));
});

test('sitemap renders valid XML when last-modified dates are missing or null', function () {
    $content = view('sitemap', [
        'urls' => [
            ['loc' => URL::route('home')],
            ['loc' => URL::route('about'), 'lastmod' => null],
        ],
    ])->render();

    expect(simplexml_load_string($content))->not->toBeFalse();
});

test('contact page inquiries are submitted through the existing contact flow', function () {
    $this->post(route('contact.store'), [
        'name' => 'Example Person',
        'email' => 'person@example.test',
        'company' => 'Example Co',
        'service' => 'Web application',
        'message' => 'We need help improving a business workflow.',
    ])->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('contact_inquiries', [
        'email' => 'person@example.test',
        'company' => 'Example Co',
        'service' => 'Web application',
    ]);
});
