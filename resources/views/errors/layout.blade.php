<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('code', 'Error') - @yield('title', 'Terjadi Kesalahan')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --accent-color: #818cf8;
            --accent-bg: rgba(99, 102, 241, 0.12);
            --accent-border: rgba(99, 102, 241, 0.35);
            --accent-glow: rgba(99, 102, 241, 0.14);
            --btn-primary-bg: #4f46e5;
            --btn-primary-hover: #6366f1;
            --btn-primary-shadow: rgba(79, 70, 229, 0.32);
        }

        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html, body {
            min-height: 100%;
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #020617;
            color: #f1f5f9;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.25rem;
            background-image:
                radial-gradient(circle at 50% 0%, var(--accent-glow), transparent 55%),
                radial-gradient(circle at 85% 90%, rgba(15, 23, 42, 0.9), transparent 50%);
        }

        .error-card {
            width: 100%;
            max-width: 28rem;
            background-color: #0f172a;
            border: 1px solid #1e293b;
            border-radius: 1.5rem;
            padding: 2rem 1.5rem;
            text-align: center;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.65);
        }

        @media (min-width: 640px) {
            .error-card {
                padding: 2.25rem 2rem;
            }
        }

        .icon-wrap {
            width: 5rem;
            height: 5rem;
            margin: 0 auto 1rem;
            border-radius: 1rem;
            background-color: var(--accent-bg);
            border: 1px solid var(--accent-border);
            color: var(--accent-color);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 25px -5px var(--accent-glow);
        }

        .icon-wrap svg {
            width: 2.5rem;
            height: 2.5rem;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.6875rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            background-color: var(--accent-bg);
            border: 1px solid var(--accent-border);
            color: var(--accent-color);
            margin-bottom: 0.75rem;
        }

        .status-dot {
            width: 0.45rem;
            height: 0.45rem;
            border-radius: 9999px;
            background-color: var(--accent-color);
        }

        .error-code {
            font-size: 3.25rem;
            line-height: 1;
            font-weight: 800;
            letter-spacing: 0.08em;
            color: #ffffff;
            margin-bottom: 0.85rem;
        }

        .error-title {
            font-size: 1.25rem;
            line-height: 1.35;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 0.5rem;
        }

        .error-message {
            font-size: 0.8125rem;
            line-height: 1.65;
            color: #94a3b8;
            max-width: 23rem;
            margin: 0 auto 1.25rem;
        }

        .error-hint {
            background-color: rgba(2, 6, 23, 0.55);
            border: 1px solid #1e293b;
            border-radius: 0.875rem;
            padding: 0.75rem 1rem;
            font-size: 0.75rem;
            line-height: 1.55;
            color: #cbd5e1;
            margin-bottom: 1.25rem;
            text-align: left;
            display: flex;
            align-items: flex-start;
            gap: 0.625rem;
        }

        .error-hint svg {
            width: 1rem;
            height: 1rem;
            color: var(--accent-color);
            flex-shrink: 0;
            margin-top: 0.125rem;
        }

        .actions {
            padding-top: 1.25rem;
            border-top: 1px solid rgba(30, 41, 59, 0.9);
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            justify-content: center;
            align-items: center;
        }

        @media (min-width: 640px) {
            .actions {
                flex-direction: row;
                flex-wrap: wrap;
            }
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            padding: 0.6875rem 1.15rem;
            border-radius: 0.75rem;
            font-size: 0.75rem;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.18s ease;
            border: 1px solid transparent;
            font-family: inherit;
        }

        @media (min-width: 640px) {
            .btn {
                width: auto;
            }
        }

        .btn svg {
            width: 1rem;
            height: 1rem;
            flex-shrink: 0;
        }

        .btn-primary {
            background-color: var(--btn-primary-bg);
            color: #ffffff;
            box-shadow: 0 10px 20px -5px var(--btn-primary-shadow);
        }

        .btn-primary:hover {
            background-color: var(--btn-primary-hover);
            transform: translateY(-1px);
        }

        .btn-secondary {
            background-color: #1e293b;
            border-color: #334155;
            color: #e2e8f0;
        }

        .btn-secondary:hover {
            background-color: #334155;
            color: #ffffff;
        }

        .error-footer {
            margin-top: 1.5rem;
            font-size: 0.6875rem;
            font-weight: 500;
            color: #64748b;
        }
    </style>
    @yield('theme_styles')
</head>
<body>
    <main class="error-card" role="main">
        <div class="icon-wrap" aria-hidden="true">
            @yield('icon')
        </div>

        <div class="status-badge">
            <span class="status-dot"></span>
            <span>@yield('badge', 'Status HTTP')</span>
        </div>

        <div class="error-code">@yield('code')</div>

        <h1 class="error-title">@yield('title')</h1>

        <p class="error-message">@yield('message')</p>

        @hasSection('hint')
            <div class="error-hint">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>@yield('hint')</div>
            </div>
        @endif

        <div class="actions">
            @hasSection('actions')
                @yield('actions')
            @else
                <a href="{{ url('/') }}" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Kembali ke Beranda</span>
                </a>
            @endif
        </div>

        <p class="error-footer">
            Website Resmi &copy; {{ date('Y') }}. UPA. TIK UNTAN
        </p>
    </main>
</body>
</html>

