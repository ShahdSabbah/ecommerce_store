<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.forgot_password') }} | {{ config('app.name', 'Store') }}</title>
    <!-- خطوط عربية ولاتينية حسب اللغة -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
    body {
        font-family: {{ app()->getLocale() === 'ar' ? "'Tajawal', sans-serif" : "'Inter', system-ui, sans-serif" }};
        background: radial-gradient(circle at 10% 20%, rgba(238, 242, 255, 0.8) 0%, rgba(245, 247, 250, 0.9) 90%);
        background-color: #f8fafc;
        min-height: 100vh;
        position: relative;
        overflow-x: hidden;
    }

    body::before {
        content: '';
        position: absolute;
        width: 320px;
        height: 320px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.15), rgba(168, 85, 247, 0.12));
        top: 10%;
        right: 15%;
        filter: blur(60px);
        z-index: -1;
    }

    body::after {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.12), rgba(236, 72, 153, 0.1));
        bottom: 10%;
        left: 15%;
        filter: blur(60px);
        z-index: -1;
    }

    .card-custom {
        border: 1px solid rgba(255, 255, 255, 0.8);
        border-radius: 24px;
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.05), 0 5px 15px rgba(0, 0, 0, 0.02);
    }

    .form-control {
        border-radius: 12px;
        padding: 12px 16px;
        border: 1px solid #e2e8f0;
        background-color: rgba(248, 250, 252, 0.8);
        transition: all 0.2s ease;
    }
    .form-control:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.12);
        background-color: #fff;
    }

    .btn-custom-primary {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        border: none;
        color: #fff;
        border-radius: 12px;
        padding: 12px 24px;
        font-weight: 700;
        transition: all 0.25s ease;
    }
    .btn-custom-primary:hover {
        background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(79, 70, 229, 0.25);
    }

    .icon-box {
        width: 60px;
        height: 60px;
        background: #eef2ff;
        color: #6366f1;
        border-radius: 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        box-shadow: inset 0 0 0 1px rgba(99, 102, 241, 0.1);
    }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center p-3">

    <div class="position-absolute top-0 {{ app()->getLocale() === 'ar' ? 'start-0' : 'end-0' }} p-3 d-flex gap-2">
        <a href="{{ route('home') }}" class="btn btn-sm btn-light border shadow-sm rounded-pill px-3 py-1 fw-bold text-secondary text-decoration-none">
            <i class="bi bi-arrow-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}"></i> {{ __('messages.home') }}
        </a>
        <a href="{{ route('lang.switch', app()->getLocale() === 'ar' ? 'en' : 'ar') }}" class="btn btn-sm btn-light border shadow-sm rounded-pill px-3 py-1 fw-bold text-dark text-decoration-none">
            {{ app()->getLocale() === 'ar' ? '🇬🇧 English' : '🇸🇦 عربي' }}
        </a>
    </div>

    <div class="card-custom p-4 p-md-5 my-5" style="max-width: 440px; width: 100%;">
        
        <div class="text-center mb-4">
            <div class="icon-box mb-3">
                <i class="bi bi-key-fill"></i>
            </div>
            <h3 class="fw-extrabold text-dark mb-1">{{ __('messages.forgot_password') }}</h3>
            <p class="text-secondary fs-6">
                {{ app()->getLocale() === 'ar' ? 'أدخل بريدك الإلكتروني وسنرسل لك رابط إعادة تعيين كلمة المرور.' : 'Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.' }}
            </p>
        </div>

        @if (session('status'))
            <div class="alert alert-success border-0 rounded-3 mb-4 fs-6">
                {{ session('status') }}
            </div>
        @endif

        @if (isset($errors) && $errors->any())
            <div class="alert alert-danger border-0 rounded-3 mb-4">
                <ul class="mb-0 ps-3 fs-6">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="form-label text-dark fw-bold small">{{ __('messages.email') }}</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="form-control" placeholder="{{ __('messages.email_placeholder') }}">
            </div>

            <button type="submit" class="btn btn-custom-primary w-100 mb-3">
                {{ app()->getLocale() === 'ar' ? 'إرسال رابط استعادة كلمة المرور' : 'Email Password Reset Link' }}
            </button>

            <div class="text-center">
                <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none small">
                    <i class="bi bi-arrow-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}"></i> {{ app()->getLocale() === 'ar' ? 'العودة لتسجيل الدخول' : 'Back to login' }}
                </a>
            </div>
        </form>

    </div>

</body>
</html>
