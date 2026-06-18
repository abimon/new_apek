<x-layout>
    <x-slot name="title">New Blog Post | Admin Panel</x-slot>

    <section style="padding: 2.5rem 0; display: grid; gap: 1rem; max-width: 720px; margin: 0 auto;">
        <div>
            <h1 style="margin: 0; color: #fff;">New Blog Post</h1>
            <p style="margin: 0.5rem 0 0; color: #94a3b8;">Create a new article for your blog.</p>
        </div>

        <form method="POST" action="{{ route('admin.blog-posts.store') }}" class="glass-panel" style="padding: 1.5rem; display: grid; gap: 1rem;">
            @csrf
            <input type="text" name="title" value="{{ old('title') }}" placeholder="Title" required style="padding: 1rem; border-radius: 1rem; border: 1px solid rgba(255,255,255,0.12); background: rgba(255,255,255,0.05); color: #f8fafc;" />
            <input type="text" name="slug" value="{{ old('slug') }}" placeholder="Slug" required style="padding: 1rem; border-radius: 1rem; border: 1px solid rgba(255,255,255,0.12); background: rgba(255,255,255,0.05); color: #f8fafc;" />
            <textarea name="content" rows="6" placeholder="Content" required style="padding: 1rem; border-radius: 1rem; border: 1px solid rgba(255,255,255,0.12); background: rgba(255,255,255,0.05); color: #f8fafc;">{{ old('content') }}</textarea>
            <input type="url" name="featured_image" value="{{ old('featured_image') }}" placeholder="Featured Image URL" style="padding: 1rem; border-radius: 1rem; border: 1px solid rgba(255,255,255,0.12); background: rgba(255,255,255,0.05); color: #f8fafc;" />
            <input type="text" name="category" value="{{ old('category') }}" placeholder="Category" required style="padding: 1rem; border-radius: 1rem; border: 1px solid rgba(255,255,255,0.12); background: rgba(255,255,255,0.05); color: #f8fafc;" />
            <select name="status" required style="padding: 1rem; border-radius: 1rem; border: 1px solid rgba(255,255,255,0.12); background: rgba(255,255,255,0.05); color: #f8fafc;">
                <option value="published"{{ old('status') === 'published' ? ' selected' : '' }}>Published</option>
                <option value="draft"{{ old('status') === 'draft' ? ' selected' : '' }}>Draft</option>
            </select>
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;"><button type="submit" class="button-primary">Publish Post</button><a href="{{ route('admin.blog-posts.index') }}" class="button-secondary">Cancel</a></div>
        </form>
    </section>
</x-layout>
