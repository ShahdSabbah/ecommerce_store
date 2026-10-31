@extends('layouts.master')

@section('title', __('messages.order_details') . ' #' . $order->id)

@section('content')
<!-- تنبيه عند نجاح تحديث الحالة -->
@if(session('success'))
    <div class="alert alert-success d-flex align-items-center p-4 mb-5 rounded-3">
        <i class="bi bi-check-circle-fill fs-3 me-3 text-success"></i>
        <div class="fw-bolder">{{ session('success') }}</div>
    </div>
@endif

<div class="d-flex flex-column flex-xl-row gap-7 gap-lg-10">
    
    <!-- الجانب: بيانات العميل وتغيير الحالة -->
    <div class="w-100 w-xl-350px">
        <div class="card card-flush py-4 mb-5 shadow-sm">
            <div class="card-header">
                <div class="card-title">
                    <h3 class="fw-bolder m-0">{{ __('messages.delivery_details') }}</h3>
                </div>
            </div>
            <div class="card-body pt-0 fs-6">
                <div class="mb-4">
                    <span class="text-gray-400 fw-bold d-block">{{ __('messages.customer_name') }}:</span>
                    <span class="text-gray-800 fw-bolder fs-5">{{ $order->customer_name }}</span>
                </div>
                <div class="mb-4">
                    <span class="text-gray-400 fw-bold d-block">{{ __('messages.customer_email') }}:</span>
                    <span class="text-gray-800 fw-bolder">{{ $order->customer_email }}</span>
                </div>
                <div class="mb-4">
                    <span class="text-gray-400 fw-bold d-block">{{ __('messages.customer_phone') }}:</span>
                    <span class="text-gray-800 fw-bolder">{{ $order->customer_phone }}</span>
                </div>
                <div class="mb-4">
                    <span class="text-gray-400 fw-bold d-block">{{ __('messages.shipping_address') }}:</span>
                    <span class="text-gray-800 fw-bolder">{{ $order->address }}</span>
                </div>
                
                <div class="separator separator-dashed my-5"></div>
                
                <!-- نموذج تغيير حالة الطلب -->
                <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <label class="form-label fw-bolder text-gray-700 mb-3">{{ __('messages.update_status') }}:</label>
                    <select name="status" class="form-select form-select-solid fw-bold" onchange="this.form.submit()">
                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>{{ __('messages.status_pending') }}</option>
                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>{{ __('messages.status_processing') }}</option>
                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>{{ __('messages.status_completed') }}</option>
                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>{{ __('messages.status_cancelled') }}</option>
                    </select>
                </form>

                <div class="mt-5">
                    <a href="{{ route('orders.index') }}" class="btn btn-light-secondary w-100 fw-bolder">
                        {{ __('messages.back_to_list') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- الجانب الآخر: جدول المنتجات والمجموع -->
    <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
        <div class="card card-flush py-4 flex-row-fluid shadow-sm">
            <div class="card-header">
                <div class="card-title">
                    <h3 class="fw-bolder m-0">{{ __('messages.order_items') }} (#{{ $order->id }})</h3>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                        <thead>
                            <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                <th class="min-w-175px">{{ __('messages.product_name') }}</th>
                                <th class="min-w-100px text-end">{{ __('messages.unit_price') }}</th>
                                <th class="min-w-70px text-end">{{ __('messages.quantity') }}</th>
                                <th class="min-w-100px text-end">{{ __('messages.item_total') }}</th>
                            </tr>
                        </thead>
                        <tbody class="fw-bold text-gray-600">
                            @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <span class="text-gray-800 fw-bolder">{{ $item->product->name ?? '-' }}</span>
                                </td>
                                <td class="text-end">{{ number_format($item->price, 2) }} {{ __('messages.currency') }}</td>
                                <td class="text-end">{{ $item->quantity }}</td>
                                <td class="text-end text-dark fw-bolder">{{ number_format($item->price * $item->quantity, 2) }} {{ __('messages.currency') }}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="3" class="fs-4 fw-bolder text-end text-dark pt-6">{{ __('messages.total') }}:</td>
                                <td class="text-end fs-3 fw-bolder text-primary pt-6">{{ number_format($order->total_price, 2) }} {{ __('messages.currency') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection