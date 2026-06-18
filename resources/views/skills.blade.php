<x-layout>
    <x-slot name="title">Skills | Developer Portfolio</x-slot>

    <section style="padding: 2.5rem 0 1.5rem; display: grid; gap: 1.5rem;">
        <div class="section-title">Skills</div>
        <p class="section-subtitle">A modern, professional skill map for mobile, backend, frontend, database, IoT, and programming languages.</p>
    </section>

    <section style="display: grid; gap: 1rem;">
        <div class="glass-panel" style="padding: 1.5rem; display: grid; gap: 1rem;">
            @foreach($skills->groupBy('category') as $category => $group)
                <div>
                    <h3 style="margin: 0 0 0.75rem; color: #fff;">{{ $category }}</h3>
                    <div style="display: grid; gap: 0.85rem;">
                        @foreach($group as $skill)
                            <div style="display: grid; gap: 0.35rem;">
                                <div style="display: flex; justify-content: space-between; font-weight: 700; color: #f8fafc;"><span>{{ $skill->name }}</span><span>{{ $skill->proficiency }}%</span></div>
                                <div style="background: rgba(255,255,255,0.08); border-radius: 9999px; overflow: hidden; height: 0.75rem;">
                                    <div style="width: <?php echo $skill->proficiency ;?>%; background: linear-gradient(90deg, #2563EB, #14B8A6); height: 100%;"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</x-layout>
