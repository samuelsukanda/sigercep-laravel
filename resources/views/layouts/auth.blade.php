<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIGERCEP — Masuk')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('images/logors.png') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400&family=Nunito+Sans:ital,wght@0,300;0,400;0,600;1,300&display=swap"
        rel="stylesheet">

    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --bg: #0a0f1a;
            --surface: #111827;
            --surface2: #1a2233;
            --border: rgba(255, 255, 255, 0.07);
            --border2: rgba(255, 255, 255, 0.13);
            --text: #f0f4ff;
            --muted: #6b7a99;
            --muted2: #8b9bbf;
            --accent: #3b6ef8;
            --accent2: #5b8afb;
            --accent-glow: rgba(59, 110, 248, 0.18);
            --danger: #f87171;
            --danger-bg: rgba(248, 113, 113, 0.08);
            --success: #34d399;
            --sans: 'Nunito Sans', sans-serif;
            --serif: 'Nunito', sans-serif;
            --radius: 14px;
            --radius-sm: 8px;
            --trans: 0.2s ease;
        }

        html,
        body {
            height: 100%;
            overflow: hidden;
            font-family: var(--sans);
            background: var(--bg);
            color: var(--text);
            -webkit-font-smoothing: antialiased;
        }

        /* ── Background grid ─────────────────────────── */
        .bg-grid {
            position: fixed;
            inset: 0;
            z-index: 0;
            background-image:
                linear-gradient(rgba(59, 110, 248, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(59, 110, 248, 0.03) 1px, transparent 1px);
            background-size: 60px 60px;
        }

        .bg-glow {
            position: fixed;
            z-index: 0;
            border-radius: 50%;
            filter: blur(90px);
            pointer-events: none;
        }

        .bg-glow-1 {
            width: 500px;
            height: 500px;
            top: -120px;
            right: -80px;
            background: rgba(59, 110, 248, 0.10);
        }

        .bg-glow-2 {
            width: 380px;
            height: 380px;
            bottom: -100px;
            left: -60px;
            background: rgba(91, 138, 251, 0.07);
        }

        /* ── Page layout ─────────────────────────────── */
        .page {
            position: relative;
            z-index: 1;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            overflow: hidden;
        }

        /* ── Form panel — centered card ─────────────── */
        .form-panel {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            max-width: 460px;
        }

        .form-inner {
            width: 100%;
            background: var(--surface);
            border: 1px solid var(--border2);
            border-radius: 20px;
            padding: 36px 40px;
        }

        /* Logo mark */
        .logo-mark {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 32px;
        }

        .logo-icon {
            width: 36px;
            height: 36px;
            background: var(--accent);
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .logo-icon svg {
            width: 18px;
            height: 18px;
        }

        .logo-text {
            font-family: var(--serif);
            font-size: 17px;
            font-weight: 700;
            letter-spacing: 0.04em;
            color: var(--text);
        }

        .logo-version {
            font-size: 10px;
            font-weight: 500;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--muted);
            background: var(--surface2);
            border: 1px solid var(--border2);
            border-radius: 4px;
            padding: 2px 6px;
        }

        /* Heading */
        .form-heading {
            margin-bottom: 32px;
        }

        .form-heading h1 {
            font-family: var(--serif);
            font-size: 28px;
            font-weight: 700;
            line-height: 1.2;
            color: var(--text);
            margin-bottom: 8px;
        }

        .form-heading h1 em {
            font-style: italic;
            color: var(--accent2);
        }

        .form-heading p {
            font-size: 14px;
            color: var(--muted2);
            line-height: 1.6;
        }

        /* Error */
        .alert-error {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            background: var(--danger-bg);
            border: 1px solid rgba(248, 113, 113, 0.2);
            border-radius: var(--radius-sm);
            padding: 12px 14px;
            margin-bottom: 24px;
            font-size: 13px;
            color: var(--danger);
            line-height: 1.5;
            animation: fadeIn 0.25s ease;
        }

        .alert-error svg {
            flex-shrink: 0;
            margin-top: 1px;
        }

        /* Fields */
        .field {
            margin-bottom: 18px;
        }

        .field-label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 7px;
        }

        .field-label span {
            font-size: 13px;
            font-weight: 500;
            color: var(--muted2);
        }

        .field-wrap {
            position: relative;
        }

        .field-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            display: flex;
            align-items: center;
            pointer-events: none;
        }

        .field-icon svg {
            width: 16px;
            height: 16px;
        }

        .field-input {
            width: 100%;
            padding: 11px 14px 11px 42px;
            font-size: 14px;
            font-family: var(--sans);
            border-radius: var(--radius-sm);
            border: 1px solid var(--border2);
            background: var(--surface2);
            color: var(--text);
            outline: none;
            transition: border-color var(--trans), box-shadow var(--trans), background var(--trans);
        }

        .field-input::placeholder {
            color: var(--muted);
        }

        .field-input:focus {
            border-color: var(--accent);
            background: rgba(59, 110, 248, 0.04);
            box-shadow: 0 0 0 4px var(--accent-glow);
        }

        .field-input:focus+.field-icon,
        .field-wrap:focus-within .field-icon {
            color: var(--accent);
        }

        .pw-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--muted);
            padding: 4px;
            display: flex;
            align-items: center;
            transition: color var(--trans);
            border-radius: 4px;
        }

        .pw-toggle:hover {
            color: var(--muted2);
        }

        .pw-toggle svg {
            width: 16px;
            height: 16px;
        }

        .field-input.has-toggle {
            padding-right: 42px;
        }

        /* Remember row */
        .options-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
        }

        .check-label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .check-label input[type=checkbox] {
            appearance: none;
            width: 16px;
            height: 16px;
            border: 1px solid var(--border2);
            border-radius: 4px;
            background: var(--surface2);
            cursor: pointer;
            position: relative;
            transition: background var(--trans), border-color var(--trans);
            flex-shrink: 0;
        }

        .check-label input[type=checkbox]:checked {
            background: var(--accent);
            border-color: var(--accent);
        }

        .check-label input[type=checkbox]:checked::after {
            content: '';
            position: absolute;
            left: 4px;
            top: 1.5px;
            width: 6px;
            height: 9px;
            border: 2px solid #fff;
            border-top: none;
            border-left: none;
            transform: rotate(43deg);
        }

        .check-label span {
            font-size: 13px;
            color: var(--muted2);
            user-select: none;
        }

        /* Submit button */
        .btn-submit {
            width: 100%;
            padding: 12px;
            font-size: 14px;
            font-weight: 600;
            font-family: var(--sans);
            letter-spacing: 0.01em;
            border: none;
            border-radius: var(--radius-sm);
            background: var(--accent);
            color: #fff;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: background var(--trans), transform 0.1s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-submit::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.08) 0%, transparent 60%);
            pointer-events: none;
        }

        .btn-submit:hover {
            background: #3060e8;
        }

        .btn-submit:active {
            transform: scale(0.99);
        }

        .btn-submit .arrow {
            transition: transform var(--trans);
        }

        .btn-submit:hover .arrow {
            transform: translateX(3px);
        }

        /* Footer note */
        .form-footer {
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .status-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--success);
            box-shadow: 0 0 0 3px rgba(52, 211, 153, 0.15);
            animation: pulse 2.4s ease-in-out infinite;
        }

        .form-footer span {
            font-size: 12px;
            color: var(--muted);
        }

        /* ── Animations ──────────────────────────────── */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-6px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                box-shadow: 0 0 0 3px rgba(52, 211, 153, 0.15);
            }

            50% {
                box-shadow: 0 0 0 5px rgba(52, 211, 153, 0.08);
            }
        }

        .form-inner>* {
            animation: fadeIn 0.4s ease both;
        }

        .form-inner>*:nth-child(1) {
            animation-delay: 0.05s;
        }

        .form-inner>*:nth-child(2) {
            animation-delay: 0.10s;
        }

        .form-inner>*:nth-child(3) {
            animation-delay: 0.15s;
        }

        .form-inner>*:nth-child(4) {
            animation-delay: 0.20s;
        }

        .form-inner>*:nth-child(5) {
            animation-delay: 0.25s;
        }

        .form-inner>*:nth-child(6) {
            animation-delay: 0.30s;
        }

        /* ── Responsive ──────────────────────────────── */
        @media (max-width: 520px) {
            .form-inner {
                padding: 28px 20px;
            }
        }

        @media (max-height: 680px) {
            .form-inner {
                padding: 24px 36px;
            }

            .logo-mark {
                margin-bottom: 20px;
            }

            .form-heading {
                margin-bottom: 20px;
            }

            .field {
                margin-bottom: 12px;
            }

            .options-row {
                margin-bottom: 16px;
            }

            .form-footer {
                margin-top: 20px;
                padding-top: 16px;
            }
        }
    </style>
</head>

<body>
    <div class="bg-grid"></div>
    <div class="bg-glow bg-glow-1"></div>
    <div class="bg-glow bg-glow-2"></div>

    <div class="page">

        {{-- ── FORM PANEL ─────────────────────────────── --}}
        <div class="form-panel">
            <div class="form-inner">

                {{-- Logo --}}
                <div class="logo-mark">
                    <img src="{{ asset('images/logors.png') }}" alt="Logo RS" style="width:18px; height:18px;">
                    <span class="logo-text">SIGERCEP</span>
                </div>

                {{-- Heading --}}
                <div class="form-heading">
                    <h1>Selamat <em>datang</em></h1>
                    <p>Silahkan Login untuk masuk ke dashboard SIGERCEP.</p>
                </div>

                {{-- Error alert --}}
                @if (session('error'))
                    <div class="alert-error">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="8" x2="12" y2="12" />
                            <line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert-error">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="8" x2="12" y2="12" />
                            <line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                        <div>
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Form --}}
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="field">
                        <div class="field-label">
                            <span>Username</span>
                        </div>
                        <div class="field-wrap">
                            <span class="field-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                            </span>
                            <input class="field-input" type="text" name="username" value="{{ old('username') }}"
                                placeholder="Masukkan username" required autofocus autocomplete="username">
                        </div>
                    </div>

                    <div class="field">
                        <div class="field-label">
                            <span>Password</span>
                        </div>
                        <div class="field-wrap">
                            <span class="field-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                    <path d="M7 11V7a5 5 0 0110 0v4" />
                                </svg>
                            </span>
                            <input class="field-input has-toggle" type="password" id="pw-field" name="password"
                                placeholder="Masukkan password" required autocomplete="current-password">
                            <button class="pw-toggle" type="button" onclick="togglePw()" id="pw-toggle-btn"
                                title="Tampilkan password">
                                <svg id="eye-show" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12S5 5 12 5s11 7 11 7-4 7-11 7S1 12 1 12z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                <svg id="eye-hide" style="display:none" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path
                                        d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24" />
                                    <line x1="1" y1="1" x2="23" y2="23" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="options-row">
                        <label class="check-label">
                            <input type="checkbox" name="remember">
                            <span>Tetap masuk</span>
                        </label>
                    </div>

                    <button class="btn-submit" type="submit">
                        Masuk ke Dashboard
                        <svg class="arrow" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12" />
                            <polyline points="12 5 19 12 12 19" />
                        </svg>
                    </button>
                </form>

                {{-- Footer --}}
                <div class="form-footer">
                    <div class="status-dot"></div>
                    <span>Sistem Informasi Gerakan Cepat</span>
                </div>
            </div>
        </div>

    </div>

    <script>
        function togglePw() {
            var f = document.getElementById('pw-field');
            var show = document.getElementById('eye-show');
            var hide = document.getElementById('eye-hide');
            if (f.type === 'password') {
                f.type = 'text';
                show.style.display = 'none';
                hide.style.display = 'block';
            } else {
                f.type = 'password';
                show.style.display = 'block';
                hide.style.display = 'none';
            }
        }
    </script>

</body>

</html>
