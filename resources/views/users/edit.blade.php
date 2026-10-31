@extends('layouts.master')

@section('title', __('messages.edit_user') . ' - Super Admin')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-flush shadow-sm">
            <div class="card-header py-5 border-bottom">
                <div class="card-title">
                    <h3 class="fw-bolder m-0 text-dark">
                        <i class="bi bi-pencil-square text-primary me-2"></i> {{ __('messages.edit_user') }}: {{ $user->name }}
                    </h3>
                </div>
                <div>
                    <a href="{{ route('users.index') }}" class="btn btn-sm btn-light fw-bold">
                        {{ __('messages.back_to_list') }}
                    </a>
                </div>
            </div>

            <div class="card-body py-6">
                {{-- رسائل الأخطاء والتنبيهات --}}
                @if(session('error'))
                    <div class="alert alert-danger d-flex align-items-center p-4 mb-5 rounded-3">
                        <i class="bi bi-exclamation-triangle-fill fs-3 me-3 text-danger"></i>
                        <div class="fw-bold">{{ session('error') }}</div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger p-4 mb-5 rounded-3">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold required">{{ __('messages.full_name') }}</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold required">{{ __('messages.email') }}</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('messages.new_password_optional') }}</label>
                            <input type="password" name="password" class="form-control" placeholder="••••••••">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('messages.password_confirmation') }}</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold required">{{ __('messages.user_role') }}</label>
                        <select name="role" class="form-select">
                            <option value="customer" {{ old('role', $user->role) === 'customer' ? 'selected' : '' }}>{{ __('messages.customer') }}</option>
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>{{ __('messages.admin') }}</option>
                        </select>
                    </div>

                    <!-- خيار السوبر أدمن -->
                    <div class="card bg-light-warning border border-warning border-dashed p-4 rounded-4 mb-6">
                        <div class="form-check form-switch d-flex align-items-center gap-3">
                            <input class="form-check-input ms-0" type="checkbox" name="is_super_admin" value="1" id="superAdminSwitch" 
                                   {{ old('is_super_admin', $user->is_super_admin) ? 'checked' : '' }} 
                                   {{ auth()->id() === $user->id ? 'checked disabled' : '' }}
                                   style="width: 2.5em; height: 1.3em;">
                            <label class="form-check-label fw-bolder text-dark fs-6" for="superAdminSwitch">
                                {{ __('messages.super_admin_switch') }}
                            </label>
                        </div>
                        @if(auth()->id() === $user->id)
                            <input type="hidden" name="is_super_admin" value="1">
                            <div class="text-danger fw-bold fs-7 mt-2">
                                * {{ __('messages.cannot_remove_self') }}
                            </div>
                        @else
                            <div class="text-muted fs-7 mt-2 ms-8">
                                {{ __('messages.super_admin_note') }}
                            </div>
                        @endif
                    </div>

                    <div class="d-flex justify-content-end gap-3 border-top pt-4">
                        <a href="{{ route('users.index') }}" class="btn btn-light fw-bold">{{ __('messages.cancel') }}</a>
                        <button type="submit" class="btn btn-primary fw-bold px-6">
                            {{ __('messages.save_changes') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
