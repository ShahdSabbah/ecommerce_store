@extends('layouts.master') <!-- نفس اسم القالب الرئيسي عندك -->

@section('title', 'تفاصيل الطلب #' . $order->id)

@section('content')
<!-- تنبيه عند نجاح تحديث الحالة -->
@if(session('success'))
    <div class="alert alert-success d-flex align-items-center p-4 mb-5">
        <i class="bi bi-check-circle-fill fs-3 me-3 text-success"></i>
        <div class="d-flex flex-column">
            <span class="fw-bolder">{{ session('success') }}</span>
        </div>
    </div>
@endif

<div class="d-flex flex-column flex-xl-row gap-7 gap-lg-10">
    
    <!-- الجانب الأيمن: بيانات العميل وتغيير الحالة -->
    <div class="w-100 w-xl-350px">
        <div class="card card-flush py-4 mb-5 shadow-sm">
            <div class="card-header">
                <div class="card-title">
                    <h3 class="fw-bolder m-0">بيانات العميل</h3>
                </div>
            </div>
            <div class="card-body pt-0 fs-6">
                <div class="mb-4">
                    <span class="text-gray-400 fw-bold d-block">اسم العميل:</span>
                    <span class="text-gray-800 fw-bolder fs-5">{{ $order->customer_name }}</span>
                </div>
                <div class="mb-4">
                    <span class="text-gray-400 fw-bold d-block">البريد الإلكتروني:</span>
                    <span class="text-gray-800 fw-bolder">{{ $order->customer_email }}</span>
                </div>
                <div class="mb-4">
                    <span class="text-gray-400 fw-bold d-block">رقم الهاتف:</span>
                    <span class="text-gray-800 fw-bolder">{{ $order->customer_phone }}</span>
                </div>
                <div class="mb-4">
                    <span class="text-gray-400 fw-bold d-block">عنوان التوصيل:</span>
                    <span class="text-gray-800 fw-bolder">{{ $order->address }}</span>
                </div>
                
                <div class="separator separator-dashed my-5"></div>
                
                <!-- نموذج تغيير حالة الطلب -->
                <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <label class="form-label fw-bolder text-gray-700 mb-3">تحديث حالة الطلب:</label>
                    <select name="status" class="form-select form-select-solid fw-bold" onchange="this.form.submit()">
                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>جاري المعالجة</option>
                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>مكتمل</option>
                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                    </select>
                </form>

                <div class="mt-5">
                    <a href="{{ route('orders.index') }}" class="btn btn-light-secondary w-100 fw-bolder">
                        العودة لقائمة الطلبات
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- الجانب الأيسر: جدول المنتجات والمجموع -->
    <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
        <div class="card card-flush py-4 flex-row-fluid shadow-sm">
            <div class="card-header">
                <div class="card-title">
                    <h3 class="fw-bolder m-0">تفاصيل عناصر الطلب (#{{ $order->id }})</h3>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                        <thead>
                            <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                <th class="min-w-175px">اسم المنتج</th>
                                <th class="min-w-100px text-end">سعر القطعة</th>
                                <th class="min-w-70px text-end">الكمية</th>
                                <th class="min-w-100px text-end">الإجمالي</th>
                            </tr>
                        </thead>
                        <tbody class="fw-bold text-gray-600">
                            @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <span class="text-gray-800 fw-bolder">{{ $item->product->name ?? 'منتج محذوف' }}</span>
                                </td>
                                <td class="text-end">${{ number_format($item->price, 2) }}</td>
                                <td class="text-end">{{ $item->quantity }}</td>
                                <td class="text-end text-dark fw-bolder">${{ number_format($item->price * $item->quantity, 2) }}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="3" class="fs-4 fw-bolder text-end text-dark pt-6">المجموع الكلي للفاتورة:</td>
                                <td class="text-end fs-3 fw-bolder text-primary pt-6">${{ number_format($order->total_price, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection