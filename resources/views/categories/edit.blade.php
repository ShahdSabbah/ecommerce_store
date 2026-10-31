@extends('layouts.master')

@section('title', __('messages.edit_category'))

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-flush shadow-sm">
            <div class="card-header py-5 border-bottom">
                <h3 class="card-title fw-bold text-dark m-0">{{ __('messages.edit_category') }}</h3>
                <div>
                    <a href="{{ route('categories.index') }}" class="btn btn-sm btn-light fw-bold">
                        {{ __('messages.back_to_list') }}
                    </a>
                </div>
            </div>
            <div class="card-body py-6">
                <form action="{{ route('categories.update', $category->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-5">
                        <label class="form-label required fw-bold">{{ __('messages.category_name') }}</label>
                        <input type="text" name="name" value="{{ old('name', $category->name) }}" class="form-control" required />
                    </div>

                    <div class="mb-5">
                        <label class="form-label fw-bold">{{ __('messages.category_description') }}</label>
                        <textarea name="description" class="form-control" rows="4">{{ old('description', $category->description) }}</textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-3 border-top pt-4">
                        <a href="{{ route('categories.index') }}" class="btn btn-light fw-bold">{{ __('messages.cancel') }}</a>
                        <button type="submit" class="btn btn-primary fw-bold px-6">{{ __('messages.save_changes') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection