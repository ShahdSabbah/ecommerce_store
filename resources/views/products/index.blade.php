@extends('layouts.master')

@section('title', __('messages.products_management'))

@section('content')
<div class="card card-flush shadow-sm">
    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
        <h3 class="card-title fw-bold text-dark m-0">{{ __('messages.all_products_title') }}</h3>
        <a href="{{ route('products.create') }}" class="btn btn-primary fw-bold">
            <i class="bi bi-plus-circle me-1"></i> {{ __('messages.add_new_product') }}
        </a>
    </div>
    
    <div class="card-body pt-0">
        @if(session('success'))
            <div class="alert alert-success d-flex align-items-center p-4 my-3 rounded-3">
                <i class="bi bi-check-circle-fill fs-3 me-3 text-success"></i>
                <div class="fw-bold">{{ session('success') }}</div>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table align-middle table-row-dashed fs-6 gy-5">
                <thead>
                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                        <th>{{ __('messages.product_image') }}</th>
                        <th>{{ __('messages.product_name') }}</th>
                        <th>{{ __('messages.categories') }}</th>
                        <th>{{ __('messages.price') }}</th>
                        <th>{{ __('messages.product_stock') }}</th>
                        <th class="text-end">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    @forelse($products as $product)
                    <tr>
                        <td>
                            <img src="{{ $product->image_url }}" width="50" height="50" class="rounded object-fit-cover shadow-sm border" alt="{{ $product->name }}" loading="lazy">
                        </td>
                        <td class="text-dark fw-bold">{{ $product->name }}</td>
                        <td><span class="badge bg-light-primary text-primary fs-7">{{ $product->category->name ?? '-' }}</span></td>
                        <td class="fw-bold text-dark">{{ number_format($product->price, 2) }} {{ __('messages.currency') }}</td>
                        <td>
                            <span class="badge {{ $product->stock > 0 ? 'bg-light-success text-success' : 'bg-light-danger text-danger' }} px-3 py-1">
                                {{ $product->stock }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-light-warning fw-bold me-1">
                                <i class="bi bi-pencil me-1"></i> {{ __('messages.edit') }}
                            </a>
                            
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light-danger fw-bold" onclick="return confirm('{{ __('messages.confirm_delete') }}')">
                                    <i class="bi bi-trash me-1"></i> {{ __('messages.delete') }}
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">{{ __('messages.no_data') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection