<x-layout>
    <x-slot name="title">Projects | Developer Portfolio</x-slot>

    <section style="padding: 2.5rem 0 1.5rem; display: grid; gap: 1.5rem;">
        <div class="section-title">Projects</div>
        <p class="section-subtitle">A curated showcase of solution-focused software and IoT projects built with modern technologies.</p>
    </section>

    <section style="display: grid; gap: 1rem; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));">
        @forelse($projects as $project)
            @include('partials.project-card', ['project' => $project])
        @empty
            <div class="glass-panel" style="padding: 1.5rem;">No projects found.</div>
        @endforelse
    </section>
</x-layout>
