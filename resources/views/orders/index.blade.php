@extends('layouts.master')

@section('title', __('messages.orders_management'))

@section('content')
<div class="card card-flush shadow-sm">
    
    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
        <div class="card-title">
            <h3 class="fw-bolder m-0 text-dark">{{ __('messages.all_orders_title') }}</h3>
        </div>
    </div>

    <div class="card-body pt-0">
        <div class="table-responsive">
            <table class="table align-middle table-row-dashed fs-6 gy-5">
                <thead>
                    <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                        <th class="min-w-80px">{{ __('messages.order_id') }}</th>
                        <th class="min-w-150px">{{ __('messages.customer_name') }}</th>
                        <th class="min-w-120px">{{ __('messages.customer_phone') }}</th>
                        <th class="min-w-100px">{{ __('messages.total') }}</th>
                        <th class="min-w-100px">{{ __('messages.status') }}</th>
                        <th class="min-w-120px">{{ __('messages.created_at') }}</th>
                        <th class="text-end min-w-100px">{{ __('messages.actions') }}</th>
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
                            <span class="text-dark fw-bolder">{{ number_format($order->total_price, 2) }} {{ __('messages.currency') }}</span>
                        </td>
                        <td>
                            @if($order->status == 'pending')
                                <span class="badge bg-light-warning text-warning fw-bolder px-3 py-2 border border-warning">{{ __('messages.status_pending') }}</span>
                            @elseif($order->status == 'processing')
                                <span class="badge bg-light-info text-info fw-bolder px-3 py-2 border border-info">{{ __('messages.status_processing') }}</span>
                            @elseif($order->status == 'completed')
                                <span class="badge bg-light-success text-success fw-bolder px-3 py-2 border border-success">{{ __('messages.status_completed') }}</span>
                            @else
                                <span class="badge bg-light-danger text-danger fw-bolder px-3 py-2 border border-danger">{{ __('messages.status_cancelled') }}</span>
                            @endif
                        </td>
                        <td>{{ $order->created_at->format('Y-m-d') }}</td>
                        <td class="text-end">
                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-light-primary fw-bolder px-4">
                                <i class="bi bi-eye me-1"></i> {{ __('messages.view') }}
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-10 text-gray-500 fs-6">
                            {{ __('messages.no_data') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end mt-4">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection