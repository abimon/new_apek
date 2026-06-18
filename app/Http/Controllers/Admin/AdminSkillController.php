<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;

class AdminSkillController extends Controller
{
    public function index()
    {
        return view('admin.skills.index', ['skills' => Skill::orderBy('category')->orderBy('name')->get()]);
    }

    public function create()
    {
        return view('admin.skills.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'category' => 'required|string|max:120',
            'proficiency' => 'required|integer|min:0|max:100',
            'icon' => 'nullable|string|max:120',
        ]);

        Skill::create($validated);

        return redirect()->route('admin.skills.index')->with('success', 'Skill created successfully.');
    }

    public function edit(Skill $skill)
    {
        return view('admin.skills.edit', ['skill' => $skill]);
    }

    public function update(Request $request, Skill $skill)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'category' => 'required|string|max:120',
            'proficiency' => 'required|integer|min:0|max:100',
            'icon' => 'nullable|string|max:120',
        ]);

        $skill->update($validated);

        return redirect()->route('admin.skills.index')->with('success', 'Skill updated successfully.');
    }

    public function destroy(Skill $skill)
    {
        $skill->delete();

        return redirect()->route('admin.skills.index')->with('success', 'Skill removed successfully.');
    }
}
