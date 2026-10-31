@extends('layouts.master')

@section('title', __('messages.dashboard'))

@section('content')
<div class="row g-5 g-xl-8 mb-8">

    <!-- كرت إجمالي المبيعات -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-md-100 shadow-sm bg-light-success">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <span class="fs-2hx fw-bolder text-dark me-2 lh-1 ls-n2">{{ number_format($totalSales, 2) }} {{ __('messages.currency') }}</span>
                    <span class="text-gray-600 pt-1 fw-bold fs-6">{{ __('messages.kpi_total_sales') }}</span>
                </div>
            </div>
            <div class="card-body d-flex align-items-end pt-0 pb-5">
                <div class="d-flex align-items-center me-3">
                    <i class="bi bi-currency-dollar fs-1 text-success me-2"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- كرت إجمالي الطلبات -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-flush h-md-100 shadow-sm bg-light-primary">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <span class="fs-2hx fw-bolder text-dark me-2 lh-1 ls-n2">{{ $totalOrders }}</span>
                    <span class="text-gray-600 pt-1 fw-bold fs-6">{{ __('messages.kpi_total_orders') }}</span>
                </div>
            </div>
            <div class="card-body d-flex align-items-end pt-0 pb-5">
                <div class="d-flex align-items-center me-3">
                    <i class="bi bi-cart-check fs-1 text-primary me-2"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- كرت الطلبات قيد الانتظار -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-flush h-md-100 shadow-sm bg-light-warning">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <span class="fs-2hx fw-bolder text-dark me-2 lh-1 ls-n2">{{ $pendingOrders }}</span>
                    <span class="text-gray-600 pt-1 fw-bold fs-6">{{ __('messages.kpi_pending_orders') }}</span>
                </div>
            </div>
            <div class="card-body d-flex align-items-end pt-0 pb-5">
                <div class="d-flex align-items-center me-3">
                    <i class="bi bi-hourglass-split fs-1 text-warning me-2"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- كرت إجمالي المنتجات -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-flush h-md-100 shadow-sm bg-light-info">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <span class="fs-2hx fw-bolder text-dark me-2 lh-1 ls-n2">{{ $totalProducts }}</span>
                    <span class="text-gray-600 pt-1 fw-bold fs-6">{{ __('messages.kpi_total_products') }}</span>
                </div>
            </div>
            <div class="card-body d-flex align-items-end pt-0 pb-5">
                <div class="d-flex align-items-center me-3">
                    <i class="bi bi-box-seam fs-1 text-info me-2"></i>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
