<x-layout>
    <x-slot name="title">New Skill | Admin Panel</x-slot>

    <section style="padding: 2.5rem 0; display: grid; gap: 1rem; max-width: 720px; margin: 0 auto;">
        <div>
            <h1 style="margin: 0; color: #fff;">New Skill</h1>
            <p style="margin: 0.5rem 0 0; color: #94a3b8;">Add a new skill to your portfolio.</p>
        </div>

        <form method="POST" action="{{ route('admin.skills.store') }}" class="glass-panel" style="padding: 1.5rem; display: grid; gap: 1rem;">
            @csrf
            <input type="text" name="name" value="{{ old('name') }}" placeholder="Skill Name" required style="padding: 1rem; border-radius: 1rem; border: 1px solid rgba(255,255,255,0.12); background: rgba(255,255,255,0.05); color: #f8fafc;" />
            <input type="text" name="category" value="{{ old('category') }}" placeholder="Category" required style="padding: 1rem; border-radius: 1rem; border: 1px solid rgba(255,255,255,0.12); background: rgba(255,255,255,0.05); color: #f8fafc;" />
            <input type="number" name="proficiency" value="{{ old('proficiency', 70) }}" placeholder="Proficiency" min="0" max="100" required style="padding: 1rem; border-radius: 1rem; border: 1px solid rgba(255,255,255,0.12); background: rgba(255,255,255,0.05); color: #f8fafc;" />
            <input type="text" name="icon" value="{{ old('icon') }}" placeholder="Icon name" style="padding: 1rem; border-radius: 1rem; border: 1px solid rgba(255,255,255,0.12); background: rgba(255,255,255,0.05); color: #f8fafc;" />
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;"><button type="submit" class="button-primary">Save Skill</button><a href="{{ route('admin.skills.index') }}" class="button-secondary">Cancel</a></div>
        </form>
    </section>
</x-layout>
