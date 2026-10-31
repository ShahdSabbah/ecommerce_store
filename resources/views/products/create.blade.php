@extends('layouts.master')

@section('title', __('messages.add_new_product'))

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card card-flush shadow-sm">
            <div class="card-header py-5 border-bottom">
                <h3 class="card-title fw-bold text-dark m-0">{{ __('messages.add_new_product') }}</h3>
                <div>
                    <a href="{{ route('products.index') }}" class="btn btn-sm btn-light fw-bold">
                        {{ __('messages.back_to_list') }}
                    </a>
                </div>
            </div>
            <div class="card-body py-6">
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <label class="form-label required fw-bold">{{ __('messages.product_name') }}</label>
                            <input type="text" name="name" class="form-control" placeholder="{{ __('messages.product_name') }}..." value="{{ old('name') }}" required />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required fw-bold">{{ __('messages.categories') }}</label>
                            <select name="category_id" class="form-select" required>
                                <option value="">{{ __('messages.select_category') }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-5">
                        <div class="col-md-6">
                            <label class="form-label required fw-bold">{{ __('messages.product_price') }}</label>
                            <input type="number" step="0.01" name="price" class="form-control" placeholder="0.00" value="{{ old('price') }}" required />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required fw-bold">{{ __('messages.product_stock') }}</label>
                            <input type="number" name="stock" class="form-control" placeholder="0" value="{{ old('stock') }}" required />
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="form-label fw-bold">{{ __('messages.product_image') }}</label>
                        <input type="file" name="image" class="form-control" accept="image/*" />
                    </div>

                    <div class="mb-5">
                        <label class="form-label fw-bold">{{ __('messages.product_description') }}</label>
                        <textarea name="description" class="form-control" rows="4" placeholder="{{ __('messages.product_description') }}...">{{ old('description') }}</textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-3 border-top pt-4">
                        <a href="{{ route('products.index') }}" class="btn btn-light fw-bold">{{ __('messages.cancel') }}</a>
                        <button type="submit" class="btn btn-primary fw-bold px-6">{{ __('messages.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection