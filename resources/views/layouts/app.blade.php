<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Register for the Cloud Computing 2025 program.">
    <title>@yield('title', 'Student Registration') — Cloud Computing 2025</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="page-shell">
        <header class="site-header flex items-center justify-between">
            <a class="brand" href="{{ route('registrations.create') }}" aria-label="Cloud Computing 2025 home">
                <span class="brand-mark" aria-hidden="true">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                        <path d="M7 17.5h10a4 4 0 0 0 .4-7.98A6 6 0 0 0 6.1 8.7 4.5 4.5 0 0 0 7 17.5Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="m9.5 13 2.5-2.5 2.5 2.5M12 10.8v5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
                <span class="brand-name">Cloud Computing 2025</span>
            </a>
            <span class="header-label">Student Registration Portal</span>
        </header>

        <main class="main-content grid grid-cols-1 items-center">
            @yield('content')
        </main>

        <footer class="site-footer">
            <span>Cloud Computing 2025</span>
            <span>Student Registration Portal</span>
        </footer>
    </div>
</body>
</html>
