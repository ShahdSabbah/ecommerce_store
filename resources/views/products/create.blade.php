@extends('layouts.master')

@section('title', 'إضافة منتج جديد')

@section('content')
<div class="card card-flush">
    <div class="card-header">
        <h3 class="card-title fw-bold">إضافة منتج جديد</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row mb-5">
                <div class="col-md-6">
                    <label class="form-label required fw-bold">اسم المنتج</label>
                    <input type="text" name="name" class="form-control" placeholder="أدخلي اسم المنتج..." required />
                </div>
                <div class="col-md-6">
                    <label class="form-label required fw-bold">القسم</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">اختاري القسم...</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-5">
                <div class="col-md-6">
                    <label class="form-label required fw-bold">السعر</label>
                    <input type="number" step="0.01" name="price" class="form-control" placeholder="0.00" required />
                </div>
                <div class="col-md-6">
                    <label class="form-label required fw-bold">الكمية في المخزن (Stock)</label>
                    <input type="number" name="stock" class="form-control" placeholder="0" required />
                </div>
            </div>

            <div class="mb-5">
                <label class="form-label fw-bold">صورة المنتج</label>
                <input type="file" name="image" class="form-control" accept="image/*" />
            </div>

            <div class="mb-5">
                <label class="form-label fw-bold">الوصف</label>
                <textarea name="description" class="form-control" rows="4" placeholder="وصف المنتج..."></textarea>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('products.index') }}" class="btn btn-light me-3">إلغاء</a>
                <button type="submit" class="btn btn-primary">حفظ المنتج</button>
            </div>
        </form>
    </div>
</div>
@endsection