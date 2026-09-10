@extends('layouts.master')

@section('title', 'إدارة الأقسام')

@section('content')
<div class="card card-flush">
    <!-- رأس البطاقة: العنوان وزر الإضافة -->
    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
        <h3 class="card-title fw-bold">جميع الأقسام</h3>
        <a href="{{ route('categories.create') }}" class="btn btn-primary">إضافة قسم جديد +</a>
    </div>
    
    <!-- جسم البطاقة: الجدول -->
    <div class="card-body pt-0">
        <!-- إشعار النجاح عند الحفظ أو التعديل -->
        @if(session('success'))
            <div class="alert alert-success mt-2">{{ session('success') }}</div>
        @endif

        <table class="table align-middle table-row-dashed fs-6 gy-5">
            <thead>
                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                    <th>#</th>
                    <th>اسم القسم</th>
                    <th>الوصف</th>
                    <th>تاريخ الإنشاء</th>
                    <th class="text-end">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="fw-semibold text-gray-600">
                <!-- الدوران على الأقسام الجلب من الداتابيز -->
                @forelse($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->description ?? 'لا يوجد وصف' }}</td>
                    <td>{{ $category->created_at->format('Y-m-d') }}</td>
                    <td class="text-end">
                        <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-light-warning">تعديل</a>
                        
                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-light-danger" onclick="return confirm('هل أنتِ متأكدة من الحذف؟')">حذف</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">لا توجد أقسام مضافة حتى الآن.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- أزرار التنقل بين الصفحات (Pagination) -->
        {{ $categories->links() }}
    </div>
</div>
@endsection