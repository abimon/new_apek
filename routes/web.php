<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminBlogPostController;
use App\Http\Controllers\Admin\AdminProjectController;
use App\Http\Controllers\Admin\AdminSkillController;
use App\Http\Controllers\Admin\AdminTestimonialController;
use App\Http\Controllers\Admin\AdminMessageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::controller(PageController::class)->group(function(){
    Route::get('/', 'home')->name('home');
    Route::get('/about', 'about')->name('about');
    Route::get('/skills', 'skills')->name('skills');
    Route::get('/services', 'services')->name('services');
    Route::get('/projects', 'projects')->name('projects');
    Route::get('/projects/{slug}', 'project')->name('project.show');
    Route::get('/blog', 'blog')->name('blog');
    Route::get('/blog/{slug}', 'blogPost')->name('blog.post');
    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'sendContact')->name('contact.send');
    Route::get('/sitemap.xml', 'sitemap')->name('sitemap');
    Route::get('/downloads', 'downloads')->name('downloads');
    Route::get('/privacy-policy/{company}','privacy')->name('privacy');

});

Route::middleware('guest')->controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login');
    Route::get('/register', 'showRegisterForm')->name('register');
    Route::post('/register',  'register');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::prefix('admin')->middleware('auth')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resources([
        'projects'=>AdminProjectController::class,
        'skills'=>AdminSkillController::class,
        'blog-posts'=>AdminBlogPostController::class,
        'testimonials'=>AdminTestimonialController::class,
        'product'=>ProductController::class,
        'career'=>CareerController::class,
        'profile'=>ProfileController::class,
        'users'=>UsersController::class,
    ]);
    Route::get('messages', [AdminMessageController::class, 'index'])->name('messages.index');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
