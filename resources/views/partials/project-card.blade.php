<article class="glass-panel" style="padding: 1.5rem;">
    <h3 style="margin-top: 0; color: #fff;">{{ $project->title }}</h3>
    <p style="color: #cbd5e1;">{{ \Illuminate\Support\Str::limit($project->description, 140) }}</p>
    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin: 1rem 0;">
        @foreach($project->technologies ?? [] as $technology)
            <span class="badge bg-secondary">{{ $technology }}</span>
        @endforeach
    </div>
    <div style="display: flex; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem;">
        <a href="{{ route('project.show', $project->slug) }}" class="btn btn-secondary w-75">View Details</a>
        @if($project->live_url)
            <a href="{{ $project->live_url }}" class="button-secondary">Live Demo</a>
        @endif
    </div>
</article>
