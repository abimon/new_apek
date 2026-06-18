<x-layout>
    <x-slot name="title">{{ $project->title }} | Developer Portfolio</x-slot>

    <section style="padding: 2.5rem 0 1.5rem; display: grid; gap: 1.5rem;">
        <div class="section-title">{{ $project->title }}</div>
        <p class="section-subtitle">{{ $project->category }} · {{ implode(', ', $project->technologies ?? []) }}</p>
    </section>

    <section style="display: grid; gap: 1.25rem;">
        <div class="glass-panel" style="padding: 1.75rem;">
            <img src="https://via.placeholder.com/1080x540?text={{ urlencode($project->title) }}" alt="{{ $project->title }} screenshot" style="width: 100%; border-radius: 1.25rem; object-fit: cover; margin-bottom: 1.25rem;" />
            <h2 style="margin-top: 0; color: #fff;">Problem Statement</h2>
            <p style="color: #cbd5e1;">{{ $project->description }}</p>
            <div style="display: grid; gap: 1rem; margin-top: 1.5rem;">
                <div style="display: grid; gap: 0.5rem;">
                    <h3 style="margin: 0; color: #fff;">Technologies Used</h3>
                    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                        @foreach($project->technologies ?? [] as $technology)
                            <span class="badge">{{ $technology }}</span>
                        @endforeach
                    </div>
                </div>
                <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                    @if($project->github_url)
                        <a href="{{ $project->github_url }}" class="button-secondary">GitHub Repository</a>
                    @endif
                    @if($project->live_url)
                        <a href="{{ $project->live_url }}" class="button-primary">Live Demo</a>
                    @endif
                </div>
            </div>
        </div>
    </section>
</x-layout>
