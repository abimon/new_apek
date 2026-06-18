<x-layout>
    <x-slot name="title">About | Developer Portfolio</x-slot>

    <section style="padding: 2.5rem 0 1.5rem; display: grid; gap: 1.5rem;">
        <div class="section-title">About Me</div>
        <p class="section-subtitle">Experienced Software Engineer with expertise in Flutter Development, Laravel Development, API Integration, MySQL Database Architecture, IoT Systems, Payment Gateway Integration, and Python Automation.</p>
    </section>

    <section style="display: grid; gap: 1rem;">
        <div class="glass-panel" style="padding: 1.75rem;">
            <h2 style="margin-top: 0; color: #fff;">Career Journey</h2>
            <div style="display: grid; gap: 1rem;">
                @php
                $items =[['year'=>'2021','title'=>'Started building cross-platform mobile apps','description'=>'Delivered Flutter apps for logistics and field operations.'],['year'=>'2022','title'=>'Expanded into Laravel and backend systems','description'=>'Designed REST APIs and database architectures for SaaS applications.'],['year'=>'2023','title'=>'Delivered IoT and automation solutions','description'=>'Built ESP32 monitoring systems with cloud dashboards.'],['year'=>'2024','title'=>'Built scalable full-stack solutions','description'=>'Integrated payment gateways, analytics, and DevOps workflows.']]
                @endphp
                @foreach($items as $item)
                <div style="display: grid; gap: 0.5rem; padding: 1rem; border-left: 4px solid #2563EB;">
                    <span style="color: #38bdf8; font-weight: 700;">{{ $item['year'] }}</span>
                    <p style="margin: 0; font-weight: 700; color: #fff;">{{ $item['title'] }}</p>
                    <p style="margin: 0; color: #cbd5e1;">{{ $item['description'] }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <div style="display: grid; gap: 1rem; grid-template-columns: 1fr 1fr;">
            <div class="glass-panel" style="padding: 1.75rem;">
                <h3 style="margin-top: 0; color: #fff;">Education</h3>
                <p style="margin: 0.5rem 0 0; color: #cbd5e1;">BSc in Computer Science, University of Nairobi — 2020</p>
            </div>
            <div class="glass-panel" style="padding: 1.75rem;">
                <h3 style="margin-top: 0; color: #fff;">Certifications</h3>
                <ul style="margin: 0; padding-left: 1.25rem; color: #cbd5e1;">
                    <li>Laravel Certified Developer — Laravel, 2024</li>
                    <li>Flutter & Dart Professional — Google, 2023</li>
                    <li>AWS Cloud Practitioner — AWS, 2024</li>
                </ul>
            </div>
        </div>
    </section>
</x-layout>