@extends('layouts.master')

@section('title', 'إدارة الطلبات')

@section('content')
<!-- كارد ميتورنيك الرئيسي -->
<div class="card card-flush shadow-sm">
    
    <!-- هيدر الكارد -->
    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
        <div class="card-title">
            <h3 class="fw-bolder m-0 text-dark">جميع الطلبات</h3>
        </div>
    </div>

    <!-- جدول الطلبات -->
    <div class="card-body pt-0">
        <div class="table-responsive">
            <table class="table align-middle table-row-dashed fs-6 gy-5">
                <thead>
                    <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                        <th class="min-w-80px"># الطلب</th>
                        <th class="min-w-150px">اسم الزبون</th>
                        <th class="min-w-120px">رقم الهاتف</th>
                        <th class="min-w-100px">الإجمالي</th>
                        <th class="min-w-100px">الحالة</th>
                        <th class="min-w-120px">التاريخ</th>
                        <th class="text-end min-w-100px">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="fw-bold text-gray-600">
                    @forelse($orders as $order)
                    <tr>
                        <td>
                            <span class="text-dark fw-bolder">#{{ $order->id }}</span>
                        </td>
                        <td>{{ $order->customer_name }}</td>
                        <td>{{ $order->customer_phone }}</td>
                        <td>
                            <span class="text-dark fw-bolder">${{ number_format($order->total_price, 2) }}</span>
                        </td>
                        <td>
                            @if($order->status == 'pending')
                                <span class="badge badge-light-warning fw-bolder px-3 py-2">قيد الانتظار</span>
                            @elseif($order->status == 'processing')
                                <span class="badge badge-light-info fw-bolder px-3 py-2">جاري المعالجة</span>
                            @elseif($order->status == 'completed')
                                <span class="badge badge-light-success fw-bolder px-3 py-2">مكتمل</span>
                            @else
                                <span class="badge badge-light-danger fw-bolder px-3 py-2">ملغي</span>
                            @endif
                        </td>
                        <td>{{ $order->created_at->format('Y-m-d') }}</td>
                        <td class="text-end">
                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-light-primary fw-bolder px-4">
                                معاينة
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-10 text-gray-500 fs-6">
                            لا توجد طلبات متوفرة حالياً.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- أزرار الصفحات -->
        <div class="d-flex justify-content-end mt-4">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection