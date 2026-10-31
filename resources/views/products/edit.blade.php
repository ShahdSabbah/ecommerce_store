@extends('layouts.master')

@section('title', __('messages.edit_product'))

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card card-flush shadow-sm">
            <div class="card-header py-5 border-bottom">
                <h3 class="card-title fw-bold text-dark m-0">{{ __('messages.edit_product') }}: {{ $product->name }}</h3>
                <div>
                    <a href="{{ route('products.index') }}" class="btn btn-sm btn-light fw-bold">
                        {{ __('messages.back_to_list') }}
                    </a>
                </div>
            </div>
            <div class="card-body py-6">
                <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <label class="form-label required fw-bold">{{ __('messages.product_name') }}</label>
                            <input type="text" name="name" value="{{ old('name', $product->name) }}" class="form-control" required />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required fw-bold">{{ __('messages.categories') }}</label>
                            <select name="category_id" class="form-select" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-5">
                        <div class="col-md-6">
                            <label class="form-label required fw-bold">{{ __('messages.product_price') }}</label>
                            <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" class="form-control" required />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required fw-bold">{{ __('messages.product_stock') }}</label>
                            <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" class="form-control" required />
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="form-label fw-bold">{{ __('messages.product_image') }}</label>
                        <input type="file" name="image" class="form-control" accept="image/*" />
                        @if($product->image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $product->image) }}" width="70" class="rounded mt-1 border shadow-sm">
                            </div>
                        @endif
                    </div>

                    <div class="mb-5">
                        <label class="form-label fw-bold">{{ __('messages.product_description') }}</label>
                        <textarea name="description" class="form-control" rows="4">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-3 border-top pt-4">
                        <a href="{{ route('products.index') }}" class="btn btn-light fw-bold">{{ __('messages.cancel') }}</a>
                        <button type="submit" class="btn btn-primary fw-bold px-6">{{ __('messages.save_changes') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection