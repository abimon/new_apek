<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Message;
use App\Models\Product;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        return view('home', [
            'hero' => [
                'headline' => 'Building Scalable Web, Mobile & IoT Solutions',
                'subheadline' => 'Full-Stack Software Engineer specializing in Flutter, Laravel, MySQL, Python, and Next.js.',
                'cta_primary' => route('projects'),
                'cta_secondary' => route('contact'),
            ],
            'statistics' => [
                ['label' => 'Years of Experience', 'value' => (date('Y')-2022).'+'],
                ['label' => 'Projects Completed', 'value' => '25+'],
                ['label' => 'Technologies Mastered', 'value' => '18'],
                ['label' => 'Happy Clients', 'value' => '14+'],
            ],
            'featuredProjects' => Project::where('featured', true)->orderBy('created_at', 'desc')->take(3)->get(),
            'testimonials' => Testimonial::latest()->take(3)->get(),
            'skills' => Skill::orderBy('proficiency', 'desc')->take(6)->get(),
        ]);
    }

    public function about()
    {
        return view('about', [
            'summary' => [
                'Experienced Software Engineer with expertise in Flutter Development, Laravel Development, API Integration, MySQL Database Architecture, IoT Systems, Payment Gateway Integration, and Python Automation.',
            ],
            'timeline' => [
                ['year' => '2021', 'title' => 'Started building cross-platform mobile apps', 'description' => 'Delivered Flutter apps for logistics and field operations.'],
                ['year' => '2022', 'title' => 'Expanded into Laravel and backend systems', 'description' => 'Designed REST APIs and database architectures for SaaS applications.'],
                ['year' => '2023', 'title' => 'Delivered IoT and automation solutions', 'description' => 'Built ESP32 monitoring systems with cloud dashboards.'],
                ['year' => '2024', 'title' => 'Built scalable full-stack solutions', 'description' => 'Integrated payment gateways, analytics, and DevOps workflows.'],
            ],
            'education' => [
                ['qualification' => 'BSc in Computer Science', 'institution' => 'University of Nairobi', 'year' => '2020'],
            ],
            'certifications' => [
                ['name' => 'Laravel Certified Developer', 'issuer' => 'Laravel', 'year' => '2024'],
                ['name' => 'Flutter & Dart Professional', 'issuer' => 'Google', 'year' => '2023'],
                ['name' => 'AWS Cloud Practitioner', 'issuer' => 'Amazon Web Services', 'year' => '2024'],
            ],
        ]);
    }

    public function skills()
    {
        return view('skills', [
            'skills' => Skill::query()->orderBy('category')->get(),
        ]);
    }

    public function services()
    {
        return view('services', [
            'services' => [
                ['title' => 'Mobile App Development', 'description' => 'Flutter applications for Android and iOS with native performance and beautiful UI.', 'icon' => 'phone'],
                ['title' => 'Web Application Development', 'description' => 'Laravel and Next.js applications built for reliability and strong user experience.', 'icon' => 'laptop'],
                ['title' => 'Database Design', 'description' => 'MySQL architecture and optimization that scales with your data.', 'icon' => 'database'],
                ['title' => 'API Development', 'description' => 'Secure RESTful API solutions for web, mobile, and third-party integrations.', 'icon' => 'cloud'],
                ['title' => 'IoT Solutions', 'description' => 'ESP32-based monitoring and automation systems for connected devices.', 'icon' => 'wifi'],
                ['title' => 'System Integration', 'description' => 'MPesa, Pesapal, and enterprise API integrations for seamless workflows.', 'icon' => 'puzzle-piece'],
            ],
        ]);
    }

    public function projects()
    {
        return view('projects', [
            'projects' => Project::query()->orderBy('featured', 'desc')->orderBy('created_at', 'desc')->get(),
        ]);
    }

    public function project(string $slug)
    {
        $project = Project::query()->where('slug', $slug)->firstOrFail();

        return view('project', [
            'project' => $project,
        ]);
    }

    public function blog(Request $request)
    {
        $query = BlogPost::query()->where('status', 'published');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        return view('blog', [
            'posts' => $query->orderBy('created_at', 'desc')->get(),
            'categories' => BlogPost::query()->select('category')->distinct()->pluck('category'),
            'search' => $request->search,
            'selectedCategory' => $request->category,
        ]);
    }

    public function blogPost(string $slug)
    {
        $post = BlogPost::query()->where('slug', $slug)->where('status', 'published')->firstOrFail();

        return view('blog-post', [
            'post' => $post,
            'related' => BlogPost::query()->where('category', $post->category)->where('id', '!=', $post->id)->take(3)->get(),
        ]);
    }

    public function contact()
    {
        return view('contact');
    }

    public function sendContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:160',
            'phone' => 'nullable|string|max:40',
            'subject' => 'required|string|max:160',
            'message' => 'required|string|max:1200',
            'website' => 'nullable|string|max:100',
        ]);

        if (!empty($validated['website'])) {
            return back()->withErrors(['spam' => 'Spam protection check failed.']);
        }

        Message::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'subject' => $validated['subject'],
            'message' => $validated['message'],
        ]);

        return redirect()->route('contact')->with('success', 'Your message has been sent. I will get back to you shortly.');
    }

    public function sitemap()
    {
        $urls = [
            route('home'),
            route('about'),
            route('skills'),
            route('services'),
            route('projects'),
            route('blog'),
            route('contact'),
        ];

        $posts = BlogPost::query()->where('status', 'published')->pluck('slug');
        $projects = Project::query()->pluck('slug');

        return response()->view('sitemap', compact('urls', 'posts', 'projects'))->header('Content-Type', 'application/xml');
    }
    public function downloads(){
        return view('downloads',[
            'items'=>Product::where('is_active','available')->get()
        ]);
    }
}
