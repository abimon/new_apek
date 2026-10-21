<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $profile->name }} - CV</title>
    <style>
        @page { margin: 38px 46px; }
        body { color: #1e293b; font-family: DejaVu Sans, sans-serif; font-size: 10px; line-height: 1.5; }
        h1 { color: #2c3e50; font-size: 25px; margin: 0; }
        h2 { border-bottom: 1px solid #cbd5e1; color: #9a5636; font-size: 12px; letter-spacing: 1px; margin: 19px 0 8px; padding-bottom: 4px; text-transform: uppercase; }
        h3 { font-size: 11px; margin: 0; }
        p { margin: 4px 0; }
        .header { border-bottom: 2px solid #2c3e50; padding-bottom: 12px; }
        .title { color: #64748b; font-size: 13px; margin-top: 3px; }
        .contact { color: #475569; font-size: 9px; margin-top: 8px; }
        .photo { border-radius: 50%; float: right; height: 72px; object-fit: cover; width: 72px; }
        .entry { margin: 0 0 11px; }
        .meta { color: #64748b; font-size: 9px; }
        .skills span { background: #f1f5f9; display: inline-block; margin: 0 4px 4px 0; padding: 3px 6px; }
        ul { margin: 4px 0; padding-left: 17px; }
    </style>
</head>
<body>
    <header class="header">
        @if ($photoData)<img class="photo" src="{{ $photoData }}" alt="Professional photo">@endif
        <h1>{{ $profile->name }}</h1>
        @if ($profile->professional_title)<p class="title">{{ $profile->professional_title }}</p>@endif
        <p class="contact">
            {{ collect([$profile->portfolio_email, $profile->portfolio_phone, $profile->portfolio_location, $profile->portfolio_website])->filter()->implode(' | ') }}
        </p>
        <p class="contact">{{ collect([$profile->linkedin_url, $profile->github_url])->filter()->implode(' | ') }}</p>
    </header>
    @if ($profile->portfolio_summary)<section><h2>Professional Summary</h2><p>{{ $profile->portfolio_summary }}</p></section>@endif
    @if ($profile->portfolio_skills)<section><h2>Skills</h2><div class="skills">@foreach ($profile->portfolio_skills as $skill)<span>{{ $skill }}</span>@endforeach</div></section>@endif
    @if ($profile->portfolio_experience)<section><h2>Professional Experience</h2>@foreach ($profile->portfolio_experience as $entry)<div class="entry"><h3>{{ $entry['title'] ?? '' }} — {{ $entry['organization'] ?? '' }}</h3><p class="meta">{{ collect([$entry['location'] ?? null, $entry['start_date'] ?? null, $entry['end_date'] ?? null])->filter()->implode(' | ') }}</p>@if (!empty($entry['description']))<p>{{ $entry['description'] }}</p>@endif</div>@endforeach</section>@endif
    @if ($profile->portfolio_education)<section><h2>Education</h2>@foreach ($profile->portfolio_education as $entry)<div class="entry"><h3>{{ $entry['qualification'] ?? '' }} — {{ $entry['institution'] ?? '' }}</h3><p class="meta">{{ collect([$entry['location'] ?? null, $entry['start_date'] ?? null, $entry['end_date'] ?? null])->filter()->implode(' | ') }}</p>@if (!empty($entry['description']))<p>{{ $entry['description'] }}</p>@endif</div>@endforeach</section>@endif
    @if ($profile->portfolio_certifications)<section><h2>Certifications</h2><ul>@foreach ($profile->portfolio_certifications as $item)<li>{{ $item }}</li>@endforeach</ul></section>@endif
    @if ($profile->portfolio_languages)<section><h2>Languages</h2><p>{{ implode(' | ', $profile->portfolio_languages) }}</p></section>@endif
    @if ($projects->isNotEmpty())<section><h2>Selected Projects</h2>@foreach ($projects as $project)<div class="entry"><h3>{{ $project->title }} <span class="meta">({{ $project->project_type === 'product' ? 'SaaS / product' : 'Client project' }})</span></h3><p>{{ $project->summary }}</p>@if ($project->features)<p class="meta">{{ implode(' · ', $project->features) }}</p>@endif</div>@endforeach</section>@endif
</body>
</html>
