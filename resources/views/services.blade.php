<x-layout>
    <x-slot name="title">Services | Developer Portfolio</x-slot>

    <section style="padding: 2.5rem 0 1.5rem; display: grid; gap: 1.5rem;">
        <div class="section-title">Services</div>
        <p class="section-subtitle">Professional software and systems services for mobile, web, database, API, IoT, and integration projects.</p>
    </section>

    <section style="display: grid; gap: 1rem; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));">
        @foreach($services as $service)
            <div class="glass-panel" style="padding: 1.5rem;">
                <span class="badge">{{ $service['icon'] }}</span>
                <h3 style="margin-top: 1rem; color: #fff;">{{ $service['title'] }}</h3>
                <p style="color: #cbd5e1;">{{ $service['description'] }}</p>
            </div>
        @endforeach
    </section>
</x-layout>
