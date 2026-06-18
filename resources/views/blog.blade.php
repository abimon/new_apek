<x-layout>
    <x-slot name="title">Blog | Developer Portfolio</x-slot>

    <section style="padding: 2.5rem 0 1.5rem; display: grid; gap: 1.5rem;">
        <div class="section-title">Blog</div>
        <p class="section-subtitle">Insights on Flutter, Laravel, IoT, MySQL, Python, and software engineering best practices.</p>
    </section>

    <section style="display: grid; gap: 1.5rem;">
        <form method="GET" action="{{ route('blog') }}" style="display: flex; flex-wrap: wrap; gap: 0.75rem; align-items: center;">
            <input type="search" name="search" value="{{ $search }}" placeholder="Search articles" style="flex: 1; min-width: 220px; padding: 0.9rem 1rem; border-radius: 9999px; border: 1px solid rgba(255,255,255,0.12); background: rgba(255,255,255,0.05); color: #f8fafc;" />
            <select name="category" style="padding: 0.9rem 1rem; border-radius: 9999px; border: 1px solid rgba(255,255,255,0.12); background: rgba(255,255,255,0.05); color: #f8fafc;">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category }}"{{ $selectedCategory === $category ? ' selected' : '' }}>{{ $category }}</option>
                @endforeach
            </select>
            <button type="submit" class="button-primary">Search</button>
        </form>

        <div style="display: grid; gap: 1rem;">
            @forelse($posts as $post)
                <div class="glass-panel" style="padding: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                        <div>
                            <h3 style="margin: 0; color: #fff;"><a href="{{ route('blog.post', $post->slug) }}" style="color: inherit; text-decoration: none;">{{ $post->title }}</a></h3>
                            <p style="margin: 0.75rem 0 0; color: #94a3b8;">{{ $post->category }} · {{ $post->created_at->format('M d, Y') }}</p>
                        </div>
                        <a href="{{ route('blog.post', $post->slug) }}" class="button-secondary">Read Article</a>
                    </div>
                </div>
            @empty
                <div class="glass-panel" style="padding: 1.5rem;">No blog posts found.</div>
            @endforelse
        </div>
    </section>
</x-layout>
