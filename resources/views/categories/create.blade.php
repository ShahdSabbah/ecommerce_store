@extends('layouts.master')

@section('title', __('messages.add_new_category'))

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-flush shadow-sm">
            <div class="card-header py-5 border-bottom">
                <h3 class="card-title fw-bold text-dark m-0">{{ __('messages.add_new_category') }}</h3>
                <div>
                    <a href="{{ route('categories.index') }}" class="btn btn-sm btn-light fw-bold">
                        {{ __('messages.back_to_list') }}
                    </a>
                </div>
            </div>
            <div class="card-body py-6">
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-5">
                        <label class="form-label required fw-bold">{{ __('messages.category_name') }}</label>
                        <input type="text" name="name" class="form-control" placeholder="{{ __('messages.category_name') }}..." value="{{ old('name') }}" required />
                    </div>

                    <div class="mb-5">
                        <label class="form-label fw-bold">{{ __('messages.category_description') }}</label>
                        <textarea name="description" class="form-control" rows="4" placeholder="{{ __('messages.category_description') }}...">{{ old('description') }}</textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-3 border-top pt-4">
                        <a href="{{ route('categories.index') }}" class="btn btn-light fw-bold">{{ __('messages.cancel') }}</a>
                        <button type="submit" class="btn btn-primary fw-bold px-6">{{ __('messages.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection