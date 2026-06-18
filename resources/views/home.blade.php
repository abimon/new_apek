<x-layout>
    <x-slot name="title">Home | Developer Portfolio</x-slot>

    <section style="padding: 3rem 0 2rem; display: grid; gap: 2rem;">
        <div style="display: grid; gap: 1.5rem; max-width: 680px;">
            <span class="badge">Edimon Ombati | Software Engineer | Full-Stack</span>
            <h1 style="font-size: clamp(2.75rem, 5vw, 4.5rem); line-height: 1.05; margin: 0; color: #fff;">Building Scalable Web, Mobile & IoT Solutions</h1>
            <p style="color: #cbd5e1; font-size: 1.1rem; max-width: 680px;">Full-Stack Software Engineer specializing in Flutter, Laravel and MySQL.</p>
            <div style="display: flex; flex-wrap: wrap; gap: 1rem;">
                <a href="{{ $hero['cta_primary'] }}" class="btn  btn-secondary">View Projects</a>
                <a href="{{ $hero['cta_secondary'] }}" class="btn  btn-primary">Hire Us</a>
            </div>
        </div>
        <div class="glass-panel rounded p-3">
            <div style="display: grid; gap: 1rem; text-align:center">
                <div class="row">
                    <div class="col-md-3">
                        <img src="/storage/images/Profile.png" alt="Professional profile image" style="width: 100%; border-radius: 1.5rem; object-fit: cover;" />
                    </div>
                    <div class="col-md-9 d-flex align-items-center">
                        <div class="w-75">
                            <div class="text-start text-white">
                                <p>Delivering modern, responsive portfolio websites and custom software solutions for mobile, web, and connected systems.</p>
                                <p>Software Engineer specializing in robust mobile experiences and scalable backend architectures. I bridge the gap between elegant frontend design and high-performance server logic, building data-compliant applications tailored for the modern web and mobile ecosystems.</p>
                            </div>
                            <div class="">
                                <span class="badge">Flutter</span>
                                <span class="badge">Laravel</span>
                                <span class="badge">MySQL</span>
                                <span class="badge">IoT</span>
                                <span class="badge">Next.js</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section style="display: grid; gap: 1.25rem; margin-top: 2rem;">
        <div class="section-title">Featured Achievements</div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            @foreach($statistics as $stat)
            <div class="glass-panel" style="padding: 1.5rem;">
                <p style="color: #38bdf8; font-weight: 700; margin: 0;">{{ $stat['value'] }}</p>
                <p style="margin: 0.65rem 0 0; color: #cbd5e1;">{{ $stat['label'] }}</p>
            </div>
            @endforeach
        </div>
    </section>

    <section style="margin-top: 2rem;">
        <div class="section-title">Featured Projects</div>
        <div style="display: grid; gap: 1rem; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));">
            @forelse($featuredProjects as $project)
            @include('partials.project-card', ['project' => $project])
            @empty
            <div class="glass-panel" style="padding: 1.5rem;">No featured projects available yet.</div>
            @endforelse
        </div>
    </section>

    <section style="margin-top: 2rem;">
        <div class="section-title">Trusted by clients</div>
        <div style="display: grid; gap: 1rem; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));">
            @forelse($testimonials as $testimonial)
            <div class="glass-panel" style="padding: 1.5rem;">
                <p style="color: #cbd5e1;">"{{ $testimonial->testimonial }}"</p>
                <p style="margin: 1rem 0 0; font-weight: 700; color: #fff;">{{ $testimonial->client_name }}</p>
                <p style="margin: 0; color: #94a3b8;">{{ $testimonial->designation }} — {{ $testimonial->company }}</p>
            </div>
            @empty
            <div class="glass-panel" style="padding: 1.5rem;">No testimonials available yet.</div>
            @endforelse
        </div>
    </section>
</x-layout>