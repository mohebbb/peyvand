@php($appName = config('app.name', 'QR Dynamic'))
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ \App\Support\Locales::dir() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>404 &middot; {{ __('app.not_found.title') }}</title>
    <style>
        :root {
            --bg: #0b0f19;
            --panel: #131a2a;
            --border: #24304a;
            --text: #e6ebf5;
            --muted: #93a1bd;
            --accent: #f59e0b;
        }
        * { box-sizing: border-box; }
        html, body { height: 100%; margin: 0; }
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background:
                radial-gradient(900px 500px at 50% -5%, rgba(245, 158, 11, 0.10), transparent),
                var(--bg);
            color: var(--text);
            font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        .card {
            width: 100%;
            max-width: 460px;
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 40px 36px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.45);
        }
        .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 76px;
            height: 76px;
            margin-bottom: 22px;
            border-radius: 50%;
            background: rgba(245, 158, 11, 0.12);
            border: 1px solid rgba(245, 158, 11, 0.35);
            color: var(--accent);
        }
        .code {
            font-size: 13px;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--accent);
            font-weight: 600;
            margin: 0 0 6px;
        }
        h1 {
            font-size: 22px;
            font-weight: 700;
            margin: 0 0 12px;
            line-height: 1.3;
        }
        p {
            color: var(--muted);
            font-size: 15px;
            line-height: 1.6;
            margin: 0 0 26px;
        }
        .home {
            display: inline-block;
            padding: 11px 22px;
            border-radius: 10px;
            background: var(--accent);
            color: #1a1205;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            transition: filter 0.15s ease;
        }
        .home:hover { filter: brightness(1.07); }
        .foot {
            margin-top: 28px;
            font-size: 12px;
            color: var(--muted);
            opacity: 0.7;
        }
    </style>
</head>
<body>
    <main class="card">
        <div class="badge" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" width="38" height="38" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                <line x1="2" y1="2" x2="22" y2="22"></line>
            </svg>
        </div>
        <p class="code">{{ __('app.not_found.code') }}</p>
        <h1>{{ __('app.not_found.title') }}</h1>
        <p>{{ __('app.not_found.description') }}</p>
        <a class="home" href="{{ url('/') }}">{{ __('app.not_found.back_home') }}</a>
        <div class="foot">{{ $appName }}</div>
    </main>
</body>
</html>
