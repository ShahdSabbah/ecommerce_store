@extends('layouts.master')

@section('title', __('messages.upload_document') . ' - Super Admin')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-flush shadow-sm">
            <div class="card-header py-5 border-bottom">
                <div class="card-title">
                    <h3 class="fw-bolder m-0 text-dark">
                        <i class="bi bi-cloud-arrow-up text-primary me-2"></i> {{ __('messages.upload_document') }}
                    </h3>
                </div>
                <div>
                    <a href="{{ route('private-documents.index') }}" class="btn btn-sm btn-light fw-bold">
                        {{ __('messages.back_to_list') }}
                    </a>
                </div>
            </div>

            <div class="card-body py-6">
                <!-- رسالة توضيحية -->
                <div class="alert alert-light-warning border border-warning border-dashed p-4 mb-6 rounded-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-info-circle-fill text-warning fs-3 me-3"></i>
                        <span class="fs-7 text-gray-700">
                            {{ __('messages.private_storage_notice') }}
                        </span>
                    </div>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger p-4 mb-5 rounded-3">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('private-documents.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label fw-bold required">{{ __('messages.document_title') }}</label>
                        <input type="text" name="title" class="form-control" placeholder="{{ __('messages.document_title') }}..." value="{{ old('title') }}" required>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold required">{{ __('messages.document_type') }}</label>
                            <select name="type" class="form-select" required>
                                <option value="user_document" {{ old('type') === 'user_document' ? 'selected' : '' }}>{{ __('messages.type_user_document') }}</option>
                                <option value="private_invoice" {{ old('type') === 'private_invoice' ? 'selected' : '' }}>{{ __('messages.type_private_invoice') }}</option>
                                <option value="other" {{ old('type') === 'other' ? 'selected' : '' }}>{{ __('messages.type_other') }}</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('messages.linked_user') }}</label>
                            <select name="user_id" class="form-select">
                                <option value="">-- {{ __('messages.unlinked_general') }} --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold required">{{ __('messages.file_or_image') }}</label>
                        <input type="file" name="file" class="form-control" accept="image/*,application/pdf" required>
                    </div>

                    <div class="mb-5">
                        <label class="form-label fw-bold">{{ __('messages.notes') }}</label>
                        <textarea name="notes" rows="3" class="form-control" placeholder="{{ __('messages.notes') }}...">{{ old('notes') }}</textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-3 border-top pt-4">
                        <a href="{{ route('private-documents.index') }}" class="btn btn-light fw-bold">{{ __('messages.cancel') }}</a>
                        <button type="submit" class="btn btn-primary fw-bold px-6">
                            <i class="bi bi-cloud-arrow-up-fill me-1"></i> {{ __('messages.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
