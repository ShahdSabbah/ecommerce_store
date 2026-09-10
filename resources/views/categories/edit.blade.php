@extends('layouts.master')

@section('title', 'تعديل قسم')

@section('content')
<div class="card card-flush">
    <div class="card-header">
        <h3 class="card-title fw-bold">تعديل بيانات القسم</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('categories.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-5">
                <label class="form-label required fw-bold">اسم القسم</label>
                <input type="text" name="name" value="{{ $category->name }}" class="form-control" required />
            </div>

            <div class="mb-5">
                <label class="form-label fw-bold">الوصف</label>
                <textarea name="description" class="form-control" rows="4">{{ $category->description }}</textarea>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('categories.index') }}" class="btn btn-light me-3">إلغاء</a>
                <button type="submit" class="btn btn-primary">تحديث البيانات</button>
            </div>
        </form>
    </div>
</div>
@endsection