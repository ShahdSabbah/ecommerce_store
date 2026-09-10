<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سلة المشتريات 🛒</title>
    <!-- الخطوط ومكتبة Bootstrap 5 -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        body { font-family: 'Tajawal', sans-serif; background-color: #f8fafc; }
        .cart-card { border: none; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.04); background: #fff; overflow: hidden; }
        .table img { width: 65px; height: 65px; object-fit: cover; border-radius: 12px; }
        .btn-checkout { background-color: #4f46e5; border: none; font-weight: 700; padding: 12px 28px; border-radius: 12px; transition: all 0.2s ease; }
        .btn-checkout:hover { background-color: #4338ca; }
    </style>
</head>
<body>

    <!-- هيدر بسيط للعودة للمتجر -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom py-3 sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary fs-4" href="{{ route('home') }}">
                <i class="bi bi-bag-heart-fill me-1"></i> متجرنا
            </a>
            <a href="{{ route('home') }}" class="btn btn-outline-secondary rounded-pill px-3 fw-bold">
                ← العودة للمتجر
            </a>
        </div>
    </nav>

    <div class="container py-5">
        <h3 class="fw-bold mb-4 text-dark">سلة المشتريات 🛒</h3>

        {{-- تنبيه رسالة النجاح عند الحذف أو التعديل --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(count($cart) > 0)
            <div class="card cart-card mb-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="py-3 px-4">المنتج</th>
                                    <th class="py-3">السعر</th>
                                    <th class="py-3 text-center">الكمية</th>
                                    <th class="py-3">المجموع</th>
                                    <th class="py-3 text-center">إجراء</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0; @endphp
                                @foreach($cart as $id => $details)
                                    @php $subtotal = $details['price'] * $details['quantity']; @endphp
                                    @php $total += $subtotal; @endphp
                                    <tr>
                                        <td class="px-4">
                                            <div class="d-flex align-items-center gap-3">
                                                @if(!empty($details['image']))
                                                    <img src="{{ asset('storage/' . $details['image']) }}" alt="{{ $details['name'] }}" class="border">
                                                @else
                                                    <div class="bg-light rounded-3 d-flex align-items-center justify-content-center text-muted border" style="width:65px; height:65px;">
                                                        <i class="bi bi-image fs-3"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <span class="fw-bold text-dark fs-6 d-block">{{ $details['name'] }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="fw-semibold text-secondary">{{ number_format($details['price'], 2) }} شيكل</td>

                                        <!-- هنا أزرار تحكم الكمية (+ و -) -->
                                        <td class="text-center">
                                            <div class="d-flex align-items-center justify-content-center gap-2">
                                                <!-- زر إنقاص الكمية -->
                                                <form action="{{ route('cart.update', $id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="type" value="decrease">
                                                    <button type="submit" class="btn btn-sm btn-outline-secondary rounded-circle p-0 d-flex align-items-center justify-content-center" 
                                                            style="width: 28px; height: 28px;" {{ $details['quantity'] <= 1 ? 'disabled' : '' }}>
                                                        <i class="bi bi-dash"></i>
                                                    </button>
                                                </form>

                                                <!-- رقم الكمية الحالي -->
                                                <span class="badge bg-light text-dark border px-3 py-2 rounded-pill fs-6 fw-bold">
                                                    {{ $details['quantity'] }}
                                                </span>

                                                <!-- زر زيادة الكمية -->
                                                <form action="{{ route('cart.update', $id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="type" value="increase">
                                                    <button type="submit" class="btn btn-sm btn-outline-secondary rounded-circle p-0 d-flex align-items-center justify-content-center" 
                                                            style="width: 28px; height: 28px;">
                                                        <i class="bi bi-plus"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>

                                        <td class="fw-bold text-primary fs-6">{{ number_format($subtotal, 2) }} شيكل</td>
                                        <td class="text-center">
                                            <form action="{{ route('cart.remove', $id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-bold">
                                                    <i class="bi bi-trash me-1"></i> حذف
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- شريط المجموع الكلي وإتمام الشراء -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center bg-white p-4 rounded-4 shadow-sm border">
                <h4 class="mb-3 mb-md-0 fw-bold text-dark">
                    إجمالي الحساب: <span class="text-success fw-bolder">{{ number_format($total, 2) }} شيكل</span>
                </h4>
                <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-checkout text-white shadow-sm">
                    متابعة الشراء (Checkout) ➔
                </a>
            </div>
        @else
            <!-- حالة السلة الفارغة -->
            <div class="text-center py-5 bg-white rounded-4 shadow-sm p-5 border">
                <i class="bi bi-cart-x text-muted display-1"></i>
                <h4 class="mt-3 fw-bold text-dark">السلة فارغة حالياً! 🛒</h4>
                <p class="text-muted">لم تقمي بإضافة أي منتجات للسلة بعد.</p>
                <a href="{{ route('home') }}" class="btn btn-primary rounded-pill px-4 py-2 mt-2 fw-bold">
                    تصفح المنتجات الآن
                </a>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>