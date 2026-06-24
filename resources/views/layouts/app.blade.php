<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Full-Stack Software Engineer Portfolio - Flutter, Laravel, MySQL, Python, Next.js, and IoT solutions.">
    <meta name="theme-color" content="#0F172A">
    <meta property="og:title" content="Full-Stack Software Engineer Portfolio">
    <meta property="og:description" content="Building scalable web, mobile, and IoT solutions with Flutter, Laravel, MySQL, Python, and Next.js.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('images/portfolio-og.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://gstatic.com" crossorigin>
    <link href="https://googleapis.com" rel="stylesheet">
    <link rel="canonical" href="{{ url()->current() }}">
    <title>{{ $title ?? 'Developer Portfolio' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
        :root {
            color-scheme: dark;
            background: #0F172A;
            color: #F8FAFC;
        }

        body {
            background: radial-gradient(circle at top, rgba(56, 189, 248, 0.16), transparent 35%),
                linear-gradient(180deg, #020617 0%, #0F172A 100%);
            min-height: 100vh;
            margin: 0;
            line-height: 1.6;
            /* font-family: 'Share Tech Mono', monospace; */
            font-family: 'JetBrains Mono', monospace;
        }

        .page-container {
            max-width: 90vw;
            margin: 0 auto;
            padding: 0 1.25rem 3rem;
        }

        .glass-panel {
            background: rgba(15, 23, 42, 0.76);
            border: 1px solid rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(16px);
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.24);
        }

        .button-primary {
            background-color: #2563EB;
            color: white;
            border: none;
            border-radius: 9999px;
            padding: 0.95rem 1.75rem;
            cursor: pointer;
            transition: transform 0.2s ease, background-color 0.2s ease;
        }

        .button-primary:hover {
            transform: translateY(-1px);
            background-color: #1d4ed8;
        }

        .button-secondary {
            background-color: rgba(255, 255, 255, 0.08);
            color: #f8fafc;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 9999px;
            padding: 0.95rem 1.75rem;
            cursor: pointer;
            transition: transform 0.2s ease, background-color 0.2s ease;
        }

        .button-secondary:hover {
            transform: translateY(-1px);
            background-color: rgba(255, 255, 255, 0.14);
        }

        .section-title {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.15rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #e2e8f0;
        }

        .section-subtitle {
            color: #94a3b8;
            max-width: 720px;
            margin-bottom: 2rem;
        }

        .nav-link {
            color: #cbd5e1;
            text-decoration: none;
            font-weight: 600;
            padding: 0.5rem 0.75rem;
            transition: color 0.2s ease;
        }

        .nav-link:hover,
        .nav-link.active {
            color: #ffffff;
        }

        a {
            text-decoration: none;
        }
    </style>
</head>

<body style="background-image:url('/storage/images/back.jpg'); background-size: cover;">
    <header class="page-container glass-panel p-3 mb-4" style="padding-top: 1rem; ">
        <nav class="navbar navbar-expand-lg" style="display: flex; justify-content: space-between; align-items: center; gap: 1rem; color:white;">
            <a href="{{ route('home') }}" class="navbar-brand text-white">
                <h1 class="fw-bold fs-x-large" style="font-weight:bolder;font-size:x-large">APEK TECHNOLOGIES</h1>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0" style="gap: 3px;">
                    <li class="nav-item">
                        <a href="{{ route('home') }}" class="nav-link{{ request()->routeIs('home') ? ' active' : '' }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('about') }}" class="nav-link{{ request()->routeIs('about') ? ' active' : '' }}">About</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('skills') }}" class="nav-link{{ request()->routeIs('skills') ? ' active' : '' }}">Skills</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('services') }}" class="nav-link{{ request()->routeIs('services') ? ' active' : '' }}">Services</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('projects') }}" class="nav-link{{ request()->routeIs('projects*') ? ' active' : '' }}">Projects</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('downloads') }}" class="nav-link{{ request()->routeIs('projects*') ? ' active' : '' }}">Downloads</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('blog') }}" class="nav-link{{ request()->routeIs('blog*') ? ' active' : '' }}">Blog</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('contact') }}" class="nav-link{{ request()->routeIs('contact') ? ' active' : '' }}">Contact</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <div class="page-container glass-panel p-3">
        <main class="m-4">
            {{ $slot ?? '' }}
        </main>

        <footer class="page-container" style="padding-top: 0;color:white;">
            <div class="glass-panel" style="padding: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; margin-top: 2rem;">
                <div>
                    <p style="margin: 0; font-weight: 600;">Ready to build your next digital product?</p>
                    <p style="margin: 0.5rem 0 0; color: #94a3b8;">Let's connect and build solutions that grow with your business.</p>
                </div>
                <a href="{{ route('contact') }}" class="button-primary">Hire Us</a>
            </div>
            <p style="margin-top: 1.5rem; color: #94a3b8; font-size: 0.9rem;">© {{ date('Y') }} APEK Technologies. All rights reserved.</p>
        </footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

</body>

</html>