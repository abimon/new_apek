<x-dash>
    <x-slot name="title">Edit Blog Post | Admin Panel</x-slot>

    <section style="padding: 2.5rem 0; display: grid; gap: 1rem; max-width: 720px; margin: 0 auto;">
        <div>
            <h1 style="margin: 0; color: #fff;">Edit Blog Post</h1>
            <p style="margin: 0.5rem 0 0; color: #94a3b8;">Update the blog post details.</p>
        </div>

        <form method="POST" action="{{ route('admin.blog-posts.update', $post) }}" class="glass-panel" style="padding: 1.5rem; display: grid; gap: 1rem;">
            @csrf
            @method('PUT')
            <input type="text" name="title" value="{{ old('title', $post->title) }}" placeholder="Title" required style="padding: 1rem; border-radius: 1rem; border: 1px solid rgba(255,255,255,0.12); background: rgba(255,255,255,0.05); color: #f8fafc;" />
            <input type="text" name="slug" value="{{ old('slug', $post->slug) }}" placeholder="Slug" required style="padding: 1rem; border-radius: 1rem; border: 1px solid rgba(255,255,255,0.12); background: rgba(255,255,255,0.05); color: #f8fafc;" />
            <textarea name="content" rows="6" placeholder="Content" required style="padding: 1rem; border-radius: 1rem; border: 1px solid rgba(255,255,255,0.12); background: rgba(255,255,255,0.05); color: #f8fafc;">{{ old('content', $post->content) }}</textarea>
            <input type="url" name="featured_image" value="{{ old('featured_image', $post->featured_image) }}" placeholder="Featured Image URL" style="padding: 1rem; border-radius: 1rem; border: 1px solid rgba(255,255,255,0.12); background: rgba(255,255,255,0.05); color: #f8fafc;" />
            <input type="text" name="category" value="{{ old('category', $post->category) }}" placeholder="Category" required style="padding: 1rem; border-radius: 1rem; border: 1px solid rgba(255,255,255,0.12); background: rgba(255,255,255,0.05); color: #f8fafc;" />
            <select name="status" required style="padding: 1rem; border-radius: 1rem; border: 1px solid rgba(255,255,255,0.12); background: rgba(255,255,255,0.05); color: #f8fafc;">
                <option value="published"{{ old('status', $post->status) === 'published' ? ' selected' : '' }}>Published</option>
                <option value="draft"{{ old('status', $post->status) === 'draft' ? ' selected' : '' }}>Draft</option>
            </select>
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;"><button type="submit" class="button-primary">Update Post</button><a href="{{ route('admin.blog-posts.index') }}" class="button-secondary">Cancel</a></div>
        </form>
    </section>
</x-dash>
