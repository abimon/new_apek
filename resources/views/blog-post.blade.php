<x-layout>
    <x-slot name="title">{{ $post->title }} | Developer Portfolio</x-slot>

    <section style="padding: 2.5rem 0 1.5rem; display: grid; gap: 1.5rem;">
        <div class="section-title">{{ $post->title }}</div>
        <p class="section-subtitle">{{ $post->category }} · {{ $post->created_at->format('M d, Y') }}</p>
    </section>

    <section class="glass-panel" style="padding: 1.75rem; display: grid; gap: 1.25rem;">
        @if($post->featured_image)
            <img src="{{ $post->featured_image }}" alt="{{ $post->title }} image" style="width: 100%; border-radius: 1.25rem; object-fit: cover;" />
        @endif
        <div style="color: #cbd5e1;">{!! nl2br(e($post->content)) !!}</div>
        @if($related->isNotEmpty())
            <div>
                <h3 style="margin-bottom: 1rem; color: #fff;">Related Articles</h3>
                <div style="display: grid; gap: 0.75rem;">
                    @foreach($related as $item)
                        <a href="{{ route('blog.post', $item->slug) }}" class="button-secondary" style="display: inline-flex;">{{ $item->title }}</a>
                    @endforeach
                </div>
            </div>
        @endif
    </section>
</x-layout>
