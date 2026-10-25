<?php

use App\Http\Controllers\AdminAuditLogController;
use App\Http\Controllers\AdminBlogCommentController;
use App\Http\Controllers\AdminBlogPostController;
use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminPortfolioReviewController;
use App\Http\Controllers\AdminPortfolioUserController;
use App\Http\Controllers\AdminProjectController;
use App\Http\Controllers\AdminTechStackController;
use App\Http\Controllers\AdminUserManagementController;
use App\Http\Controllers\Auth\FirstLoginPasswordController;
use App\Http\Controllers\PortfolioProfileController;
use App\Http\Controllers\PortfolioProjectController;
use App\Http\Controllers\PublicBlogController;
use App\Http\Controllers\PublicPortfolioController;
use App\Http\Controllers\PublicProjectsController;
use App\Http\Controllers\PublicSiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicSiteController::class, 'home'])->name('home');
Route::get('/about', [PublicSiteController::class, 'about'])->name('about');
Route::get('/services', [PublicSiteController::class, 'services'])->name('services');
Route::get('/contact', [PublicSiteController::class, 'contact'])->name('contact');
Route::get('/projects', [PublicProjectsController::class, 'index'])->name('projects.index');
Route::get('/blog', [PublicBlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post:slug}', [PublicBlogController::class, 'show'])->name('blog.show');
Route::post('/blog/{post:slug}/comments', [PublicBlogController::class, 'comment'])->middleware('throttle:3,10')->name('blog.comments.store');
Route::get('/work/{project:slug}', [PublicSiteController::class, 'project'])->name('projects.show');
Route::get('/portfolio/{slug}', [PublicPortfolioController::class, 'show'])->name('portfolio.public');
Route::get('/portfolio/{slug}/cv.pdf', [PublicPortfolioController::class, 'cv'])->name('portfolio.cv');
Route::get('/robots.txt', [PublicSiteController::class, 'robots'])->name('robots');
Route::get('/sitemap.xml', [PublicSiteController::class, 'sitemap'])->name('sitemap');
Route::post('/contact', [PublicSiteController::class, 'inquiry'])->middleware('throttle:contact')->name('contact.store');

Route::middleware(['audit.sensitive', 'auth'])->group(function () {
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->middleware('role:admin,superadmin,worker')->name('dashboard');
});

Route::middleware(['audit.sensitive', 'auth', 'role:superadmin'])->group(function () {
    Route::patch('dashboard/inquiries/{inquiry}/read', [AdminDashboardController::class, 'markInquiryRead'])->name('admin.inquiries.read');
    Route::resource('admin/projects', AdminProjectController::class)->except(['show'])->names('admin.projects');
    Route::resource('admin/blog', AdminBlogPostController::class)->except(['show'])->parameters(['blog' => 'post'])->names('admin.blog');
    Route::get('admin/blog-comments', [AdminBlogCommentController::class, 'index'])->name('admin.blog-comments.index');
    Route::patch('admin/blog-comments/{comment}/approval', [AdminBlogCommentController::class, 'toggleApproval'])->name('admin.blog-comments.approval');
    Route::delete('admin/blog-comments/{comment}', [AdminBlogCommentController::class, 'destroy'])->name('admin.blog-comments.destroy');
    Route::resource('admin/categories', AdminCategoryController::class)->except(['show'])->names('admin.categories');
    Route::resource('admin/tech-stacks', AdminTechStackController::class)->except(['show'])->names('admin.tech-stacks');
    Route::get('admin/users', [AdminUserManagementController::class, 'index'])->name('admin.users.index');
    Route::patch('admin/users/{user}/access', [AdminUserManagementController::class, 'updateAccess'])->name('admin.users.access');
    Route::get('admin/audit-logs', [AdminAuditLogController::class, 'index'])->name('admin.audit-logs.index');
    Route::get('admin/portfolio-users', [AdminPortfolioUserController::class, 'index'])->name('admin.portfolio-users.index');
    Route::post('admin/portfolio-users', [AdminPortfolioUserController::class, 'store'])->name('admin.portfolio-users.store');
    Route::get('admin/portfolio-projects', [AdminPortfolioReviewController::class, 'index'])->name('admin.portfolio-projects.index');
    Route::patch('admin/portfolio-projects/{project}/approve', [AdminPortfolioReviewController::class, 'approve'])->name('admin.portfolio-projects.approve');
    Route::patch('admin/portfolio-projects/{project}/reject', [AdminPortfolioReviewController::class, 'reject'])->name('admin.portfolio-projects.reject');
});

Route::middleware('auth')->group(function () {
    Route::get('password/first-login', [FirstLoginPasswordController::class, 'edit'])->name('password.first-login.edit');
    Route::put('password/first-login', [FirstLoginPasswordController::class, 'update'])->middleware('audit.sensitive')->name('password.first-login.update');
});

Route::middleware(['audit.sensitive', 'auth', 'portfolio.enabled'])->prefix('settings/portfolio')->name('portfolio.')->group(function () {
    Route::get('/', [PortfolioProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/', [PortfolioProfileController::class, 'update'])->middleware('throttle:30,1')->name('profile.update');
    Route::resource('projects', PortfolioProjectController::class)->except(['show'])->names('projects')->middleware('throttle:30,1');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

Route::get('/password', function () {
    return bcrypt('@Admin!2345');
});
