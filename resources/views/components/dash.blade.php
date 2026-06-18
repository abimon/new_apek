<!DOCTYPE html>
<html lang="en">

<head>
    @include("partials.head.head-meta")
    <title>APEK |{{ $title ??'Dashboard' }}</title>
    <link rel="stylesheet" href="/storage/node_modules/swiper/swiper-bundle.min.css" />
    @include("partials.head.head-links")
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
            /* font-family: 'JetBrains Mono', monospace; */
        }


        .glass-panel {
            background: rgba(15, 23, 42, 0.76);
            border: 1px solid rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(16px);
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.24);
        }

        a {
            text-decoration: none;
        }
    </style>
</head>

<body>


    <!-- Vertical Sidebar -->
    <div>
        @include("partials.sidebar-collapse")

        @include("partials.topbar-second")
        <!-- Main Content -->
        <div id="content" class="position-relative h-100">
            <!-- @yield('content') -->
            {{ $slot ?? '' }}
        </div>
    </div>

    @include("partials.scripts")
    <!-- jsvectormap -->
</body>

</html>