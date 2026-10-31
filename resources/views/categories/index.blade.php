@extends('layouts.master')

@section('title', __('messages.categories_management'))

@section('content')
<div class="card card-flush shadow-sm">
    <!-- رأس البطاقة: العنوان وزر الإضافة -->
    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
        <h3 class="card-title fw-bold text-dark m-0">{{ __('messages.all_categories_title') }}</h3>
        <a href="{{ route('categories.create') }}" class="btn btn-primary fw-bold">
            <i class="bi bi-plus-circle me-1"></i> {{ __('messages.add_new_category') }}
        </a>
    </div>
    
    <!-- جسم البطاقة: الجدول -->
    <div class="card-body pt-0">
        <!-- إشعار النجاح عند الحفظ أو التعديل -->
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
                        <th>#</th>
                        <th>{{ __('messages.category_name') }}</th>
                        <th>{{ __('messages.category_description') }}</th>
                        <th>{{ __('messages.created_at') }}</th>
                        <th class="text-end">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    @forelse($categories as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td class="text-dark fw-bold">{{ $category->name }}</td>
                        <td>{{ $category->description ?? '-' }}</td>
                        <td>{{ $category->created_at->format('Y-m-d') }}</td>
                        <td class="text-end">
                            <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-light-warning fw-bold me-1">
                                <i class="bi bi-pencil me-1"></i> {{ __('messages.edit') }}
                            </a>
                            
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline">
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
                        <td colspan="5" class="text-center py-5 text-muted">{{ __('messages.no_data') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- أزرار التنقل بين الصفحات -->
        <div class="mt-4">
            {{ $categories->links() }}
        </div>
    </div>
</div>
@endsection