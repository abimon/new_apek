<x-dash>
    <x-slot name="title">Edit Project | Admin Panel</x-slot>

    <section style="padding: 2.5rem 0; display: grid; gap: 1rem; max-width: 720px; margin: 0 auto;">
        <div>
            <h1 style="margin: 0; color: #fff;">Edit Project</h1>
            <p style="margin: 0.5rem 0 0; color: #94a3b8;">Update the details for this project entry.</p>
        </div>

        <form method="POST" action="{{ route('admin.projects.update', $project) }}" class="glass-panel" style="padding: 1.5rem; display: grid; gap: 1rem;">
            @csrf
            @method('PUT')
            <input type="text" name="title" value="{{ old('title', $project->title) }}" placeholder="Title" required style="padding: 1rem; border-radius: 1rem; border: 1px solid rgba(255,255,255,0.12); background: rgba(255,255,255,0.05); color: #f8fafc;" />
            <input type="text" name="slug" value="{{ old('slug', $project->slug) }}" placeholder="Slug" required style="padding: 1rem; border-radius: 1rem; border: 1px solid rgba(255,255,255,0.12); background: rgba(255,255,255,0.05); color: #f8fafc;" />
            <textarea name="description" rows="5" placeholder="Description" required style="padding: 1rem; border-radius: 1rem; border: 1px solid rgba(255,255,255,0.12); background: rgba(255,255,255,0.05); color: #f8fafc;">{{ old('description', $project->description) }}</textarea>
            <input type="text" name="technologies" value="{{ old('technologies', implode(', ', $project->technologies ?? [])) }}" placeholder="Technologies (comma separated)" style="padding: 1rem; border-radius: 1rem; border: 1px solid rgba(255,255,255,0.12); background: rgba(255,255,255,0.05); color: #f8fafc;" />
            <input type="text" name="category" value="{{ old('category', $project->category) }}" placeholder="Category" required style="padding: 1rem; border-radius: 1rem; border: 1px solid rgba(255,255,255,0.12); background: rgba(255,255,255,0.05); color: #f8fafc;" />
            <input type="url" name="github_url" value="{{ old('github_url', $project->github_url) }}" placeholder="GitHub URL" style="padding: 1rem; border-radius: 1rem; border: 1px solid rgba(255,255,255,0.12); background: rgba(255,255,255,0.05); color: #f8fafc;" />
            <input type="url" name="live_url" value="{{ old('live_url', $project->live_url) }}" placeholder="Live URL" style="padding: 1rem; border-radius: 1rem; border: 1px solid rgba(255,255,255,0.12); background: rgba(255,255,255,0.05); color: #f8fafc;" />
            <label style="display: inline-flex; align-items: center; gap: 0.75rem; color: #cbd5e1;"><input type="checkbox" name="featured" value="1" {{ old('featured', $project->featured) ? 'checked' : '' }} /> Featured</label>
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;"><button type="submit" class="btn btn-primary">Update Project</button><a href="{{ route('admin.projects.index') }}" class="btn btn-secondary">Cancel</a></div>
        </form>
    </section>
</x-layout>
