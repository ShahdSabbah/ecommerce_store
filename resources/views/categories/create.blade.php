@extends('layouts.master')

@section('title', 'إضافة قسم جديد')

@section('content')
<div class="card card-flush">
    <div class="card-header">
        <h3 class="card-title fw-bold">إضافة قسم جديد</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf
            
            <div class="mb-5">
                <label class="form-label required fw-bold">اسم القسم</label>
                <input type="text" name="name" class="form-control" placeholder="أدخلي اسم القسم..." required />
            </div>

            <div class="mb-5">
                <label class="form-label fw-bold">الوصف</label>
                <textarea name="description" class="form-control" rows="4" placeholder="وصف قصير للقسم..."></textarea>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('categories.index') }}" class="btn btn-light me-3">إلغاء</a>
                <button type="submit" class="btn btn-primary">حفظ القسم</button>
            </div>
        </form>
    </div>
</div>
@endsection