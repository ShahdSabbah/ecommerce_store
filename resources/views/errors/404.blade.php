<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - {{ __('messages.page_not_found') }} | {{ __('messages.site_name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&family=Tajawal:wght@400;500;700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    @if(app()->getLocale() === 'ar')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    @else
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    @endif

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --primary-color: #3b82f6;
            --primary-hover: #1d4ed8;
            --accent-gradient: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
        }

        body {
            font-family: {{ app()->getLocale() === 'ar' ? "'Cairo', 'Tajawal', sans-serif" : "'Inter', sans-serif" }};
            background: linear-gradient(135deg, #f8fafc 0%, #eef2f7 50%, #e2e8f0 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            color: #1e293b;
            margin: 0;
            overflow-x: hidden;
        }

        .navbar-custom {
            padding: 1.25rem 2rem;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        }

        .brand-logo {
            font-weight: 800;
            font-size: 1.4rem;
            color: #1e293b;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .lang-btn {
            font-size: 0.88rem;
            font-weight: 600;
            border-radius: 999px;
            padding: 0.4rem 1rem;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .error-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            border-radius: 2rem;
            box-shadow: 0 20px 45px -15px rgba(15, 23, 42, 0.08), 0 0 1px 1px rgba(15, 23, 42, 0.05);
            padding: 3.5rem 2.5rem;
            max-width: 620px;
            width: 100%;
            margin: auto;
            text-align: center;
            position: relative;
        }

        .error-code {
            font-size: clamp(5.5rem, 15vw, 8.5rem);
            font-weight: 900;
            line-height: 1;
            letter-spacing: -2px;
            background: var(--accent-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
            user-select: none;
        }

        .icon-bubble {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.1) 0%, rgba(124, 58, 237, 0.15) 100%);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            color: #2563eb;
            font-size: 2.75rem;
            box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.2);
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
        }

        .btn-home {
            background: var(--accent-gradient);
            border: none;
            color: #ffffff;
            font-weight: 700;
            padding: 0.85rem 2rem;
            border-radius: 999px;
            transition: all 0.25s ease;
            box-shadow: 0 10px 20px -5px rgba(37, 99, 235, 0.35);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 28px -5px rgba(37, 99, 235, 0.45);
            color: #ffffff;
        }

        .btn-back {
            font-weight: 600;
            padding: 0.85rem 1.75rem;
            border-radius: 999px;
            transition: all 0.25s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
    </style>
</head>
<body>

    <!-- Header / Navbar -->
    <header class="navbar-custom">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="{{ route('home') }}" class="brand-logo">
                <span class="fs-3">🛒</span>
                <span>{{ __('messages.brand') }}</span>
            </a>

            <!-- Language Switcher -->
            <div class="d-flex align-items-center gap-2">
                @if(app()->getLocale() === 'ar')
                    <a href="{{ route('lang.switch', 'en') }}" class="btn btn-outline-secondary lang-btn">
                        🇬🇧 English
                    </a>
                @else
                    <a href="{{ route('lang.switch', 'ar') }}" class="btn btn-outline-secondary lang-btn">
                        🇵🇸 العربية
                    </a>
                @endif
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow-1 d-flex align-items-center justify-content-center p-4">
        <div class="error-card">
            
            <div class="icon-bubble">
                <i class="bi bi-compass"></i>
            </div>

            <div class="error-code">404</div>

            <h1 class="h3 fw-bolder mb-3 text-dark">
                {{ __('messages.page_not_found') }}
            </h1>

            <p class="text-muted fs-6 mb-4 px-md-4">
                {{ __('messages.page_not_found_desc') }}
            </p>

            <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
                <a href="{{ route('home') }}" class="btn btn-home">
                    <i class="bi bi-cart3"></i>
                    <span>{{ __('messages.back_to_shopping') }}</span>
                </a>

                <a href="javascript:history.back()" class="btn btn-outline-secondary btn-back">
                    <i class="bi bi-arrow-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}"></i>
                    <span>{{ __('messages.back') }}</span>
                </a>
            </div>

            <div class="mt-5 pt-3 border-top text-muted small">
                <span>&copy; {{ date('Y') }} {{ __('messages.site_name') }}. {{ __('messages.all_rights_reserved') ?? 'All rights reserved.' }}</span>
            </div>

        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
