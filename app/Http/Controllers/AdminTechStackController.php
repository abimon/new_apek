<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminTechStackRequest;
use App\Models\TechStack;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class AdminTechStackController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/tech-stacks/index', ['techStacks' => TechStack::withCount('projects')->orderBy('name')->get()]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/tech-stacks/form', ['techStack' => null]);
    }

    public function store(AdminTechStackRequest $request): RedirectResponse
    {
        $techStack = TechStack::create($request->validated());
        request()->attributes->set('audit_subject', $techStack);

        return to_route('admin.tech-stacks.index')->with('success', 'Tech stack created.');
    }

    public function edit(TechStack $techStack): Response
    {
        return Inertia::render('admin/tech-stacks/form', ['techStack' => $techStack]);
    }

    public function update(AdminTechStackRequest $request, TechStack $techStack): RedirectResponse
    {
        $techStack->update($request->validated());

        return to_route('admin.tech-stacks.index')->with('success', 'Tech stack updated.');
    }

    public function destroy(TechStack $techStack): RedirectResponse
    {
        $techStack->delete();

        return back()->with('success', 'Tech stack removed.');
    }
}