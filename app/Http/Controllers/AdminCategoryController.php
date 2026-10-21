<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class AdminCategoryController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/categories/index', ['categories' => Category::withCount('projects')->orderBy('name')->get()]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/categories/form', ['category' => null]);
    }

    public function store(AdminCategoryRequest $request): RedirectResponse
    {
        $category = Category::create($request->validated());
        request()->attributes->set('audit_subject', $category);

        return to_route('admin.categories.index')->with('success', 'Category created.');
    }

    public function edit(Category $category): Response
    {
        return Inertia::render('admin/categories/form', ['category' => $category]);
    }

    public function update(AdminCategoryRequest $request, Category $category): RedirectResponse
    {
        $category->update($request->validated());

        return to_route('admin.categories.index')->with('success', 'Category updated.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return back()->with('success', 'Category removed.');
    }
}