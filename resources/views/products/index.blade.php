@extends('layouts.master')

@section('title', 'إدارة المنتجات')

@section('content')
<div class="card card-flush">
    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
        <h3 class="card-title fw-bold">جميع المنتجات</h3>
        <a href="{{ route('products.create') }}" class="btn btn-primary">إضافة منتج جديد +</a>
    </div>
    
    <div class="card-body pt-0">
        @if(session('success'))
            <div class="alert alert-success mt-2">{{ session('success') }}</div>
        @endif

        <table class="table align-middle table-row-dashed fs-6 gy-5">
            <thead>
                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                    <th>الصورة</th>
                    <th>اسم المنتج</th>
                    <th>القسم</th>
                    <th>السعر</th>
                    <th>الكمية (Stock)</th>
                    <th class="text-end">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="fw-semibold text-gray-600">
                @forelse($products as $product)
                <tr>
                    <td>
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" width="50" height="50" class="rounded object-fit-cover" alt="{{ $product->name }}">
                        @else
                            <span class="badge badge-light-secondary">لا توجد صورة</span>
                        @endif
                    </td>
                    <td>{{ $product->name }}</td>
                    <td><span class="badge badge-light-primary fs-7">{{ $product->category->name ?? 'بدون قسم' }}</span></td>
                    <td>{{ $product->price }} $</td>
                    <td>{{ $product->stock }}</td>
                    <td class="text-end">
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-light-warning">تعديل</a>
                        
                        @can('delete-products')
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-light-danger" onclick="return confirm('هل أنتِ متأكدة من الحذف؟')">حذف</button>
                        </form>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">لا توجد منتجات مضافة حتى الآن.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{ $products->links() }}
    </div>
</div>
@endsection