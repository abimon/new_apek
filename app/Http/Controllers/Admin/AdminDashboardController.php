<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Message;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Testimonial;

class AdminDashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'projects' => Project::count(),
            'skills' => Skill::count(),
            'posts' => BlogPost::count(),
            'messages' => Message::count(),
            'testimonials' => Testimonial::count(),
        ]);
    }
}
