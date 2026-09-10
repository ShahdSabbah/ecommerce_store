<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء حساب جديد</title>
    <!-- خط Tajawal الأنيق -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
    body {
        font-family: 'Tajawal', sans-serif;
        background: radial-gradient(circle at 10% 20%, rgba(238, 242, 255, 0.8) 0%, rgba(245, 247, 250, 0.9) 90%);
        background-color: #f8fafc;
        min-height: 100vh;
        position: relative;
        overflow-x: hidden;
    }

    /* لمسة توهج ناعم بالخلفية */
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

    .register-card {
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

    <div class="register-card p-4 p-md-5" style="max-width: 480px; width: 100%;">
        
        <div class="text-center mb-4">
            <div class="icon-box mb-3">
                <i class="bi bi-person-plus-fill"></i>
            </div>
            <h3 class="fw-extrabold text-dark mb-1">إنشاء حساب جديد</h3>
            <p class="text-secondary fs-6">أدخِل بياناتك للتسجيل والاستمتاع بالتسوق</p>
        </div>

        <!-- عرض رسائل الأخطاء إن وجدت -->
        @if ($errors->any())
            <div class="alert alert-danger border-0 rounded-3 mb-4">
                <ul class="mb-0 ps-3 fs-6">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- الاسم الكامل -->
            <div class="mb-3">
                <label for="name" class="form-label text-dark fw-bold small">الاسم الكامل</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="form-control" placeholder="أدخل اسمك الكامل">
            </div>

            <!-- البريد الإلكتروني -->
            <div class="mb-3">
                <label for="email" class="form-label text-dark fw-bold small">البريد الإلكتروني</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" class="form-control" placeholder="example@mail.com">
            </div>

            <!-- كلمة المرور -->
            <div class="mb-3">
                <label for="password" class="form-label text-dark fw-bold small">كلمة المرور</label>
                <input id="password" type="password" name="password" required autocomplete="new-password" class="form-control" placeholder="••••••••">
            </div>

            <!-- تأكيد كلمة المرور -->
            <div class="mb-4">
                <label for="password_confirmation" class="form-label text-dark fw-bold small">تأكيد كلمة المرور</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="form-control" placeholder="••••••••">
            </div>

            <!-- زر التسجيل -->
            <button type="submit" class="btn btn-custom-primary w-100 mb-4">
                إنشاء الحساب
            </button>

            <!-- رابط تسجيل الدخول -->
            <div class="text-center">
                <span class="text-muted small">لديك حساب بالفعل؟</span>
                <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none small ms-1">تسجيل الدخول</a>
            </div>
        </form>

    </div>

</body>
</html>
