@extends('layouts.master')

@section('title', 'تعديل منتج')

@section('content')
<div class="card card-flush">
    <div class="card-header">
        <h3 class="card-title fw-bold">تعديل البيانات للمنتج: {{ $product->name }}</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row mb-5">
                <div class="col-md-6">
                    <label class="form-label required fw-bold">اسم المنتج</label>
                    <input type="text" name="name" value="{{ $product->name }}" class="form-control" required />
                </div>
                <div class="col-md-6">
                    <label class="form-label required fw-bold">القسم</label>
                    <select name="category_id" class="form-select" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-5">
                <div class="col-md-6">
                    <label class="form-label required fw-bold">السعر</label>
                    <input type="number" step="0.01" name="price" value="{{ $product->price }}" class="form-control" required />
                </div>
                <div class="col-md-6">
                    <label class="form-label required fw-bold">الكمية (Stock)</label>
                    <input type="number" name="stock" value="{{ $product->stock }}" class="form-control" required />
                </div>
            </div>

            <div class="mb-5">
                <label class="form-label fw-bold">صورة المنتج</label>
                <input type="file" name="image" class="form-control" accept="image/*" />
                @if($product->image)
                    <div class="mt-2">
                        <small class="text-muted d-block">الصورة الحالية:</small>
                        <img src="{{ asset('storage/' . $product->image) }}" width="70" class="rounded mt-1">
                    </div>
                @endif
            </div>

            <div class="mb-5">
                <label class="form-label fw-bold">الوصف</label>
                <textarea name="description" class="form-control" rows="4">{{ $product->description }}</textarea>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('products.index') }}" class="btn btn-light me-3">إلغاء</a>
                <button type="submit" class="btn btn-primary">تحديث بيانات المنتج</button>
            </div>
        </form>
    </div>
</div>
@endsection