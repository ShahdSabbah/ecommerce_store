@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h3 class="fw-bold mb-4 text-center">{{ __('messages.checkout') }}</h3>

    <div class="row g-4">
        {{-- نموذج بيانات التوصيل --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm p-4 rounded-4">
                <h5 class="fw-bold mb-3 border-bottom pb-2 text-dark">{{ __('messages.delivery_details') }}</h5>

                @if($errors->any())
                    <div class="alert alert-danger rounded-3">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('checkout.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">{{ __('messages.customer_name') }}</label>
                            <input type="text" class="form-control bg-light" value="{{ auth()->user()->name }}" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">{{ __('messages.customer_email') }}</label>
                            <input type="email" class="form-control bg-light" value="{{ auth()->user()->email }}" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label fw-semibold">{{ __('messages.customer_phone') }} <span class="text-danger">*</span></label>
                        <input type="text" name="phone" id="phone" class="form-control" placeholder="059XXXXXXX" required value="{{ old('phone') }}">
                    </div>

                    <div class="mb-4">
                        <label for="shipping_address" class="form-label fw-semibold">{{ __('messages.shipping_address') }} <span class="text-danger">*</span></label>
                        <textarea name="shipping_address" id="shipping_address" rows="3" class="form-control" placeholder="{{ __('messages.address_placeholder') }}" required>{{ old('shipping_address') }}</textarea>
                    </div>

                    <h6 class="fw-bold mb-2">{{ __('messages.payment_method') }}</h6>
                    <div class="p-3 border rounded-3 bg-light mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" checked>
                            <label class="form-check-label fw-bold">{{ __('messages.cash_on_delivery') }}</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success btn-lg w-100 fw-bold shadow-sm rounded-pill">
                        {{ __('messages.confirm_order') }}
                    </button>
                </form>
            </div>
        </div>

        {{-- ملخص السلة --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm p-4 rounded-4">
                <h5 class="fw-bold mb-3 border-bottom pb-2 text-dark">{{ __('messages.order_summary') }}</h5>
                
                <div class="mb-3" style="max-height: 300px; overflow-y: auto;">
                    @foreach($cart as $id => $item)
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                            <div>
                                <h6 class="mb-1 fw-bold">{{ $item['name'] }}</h6>
                                <small class="text-muted">{{ __('messages.quantity') }}: {{ $item['quantity'] }} × {{ number_format($item['price'], 2) }} {{ __('messages.currency') }}</small>
                            </div>
                            <span class="fw-bold text-dark">{{ number_format($item['price'] * $item['quantity'], 2) }} {{ __('messages.currency') }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">{{ __('messages.subtotal') }}:</span>
                    <strong>{{ number_format($total, 2) }} {{ __('messages.currency') }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">{{ __('messages.shipping') }}:</span>
                    <span class="text-success fw-bold">{{ __('messages.free_shipping') }}</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">{{ __('messages.total') }}:</h5>
                    <h4 class="fw-bold text-primary mb-0">{{ number_format($total, 2) }} {{ __('messages.currency') }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection