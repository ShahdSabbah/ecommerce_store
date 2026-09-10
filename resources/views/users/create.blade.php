@extends('layouts.master')

@section('title', 'إضافة مستخدم جديد - Super Admin')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-flush shadow-sm">
            <div class="card-header py-5 border-bottom">
                <div class="card-title">
                    <h3 class="fw-bolder m-0 text-dark">
                        <i class="bi bi-person-plus text-primary me-2"></i> إضافة مستخدم / مدير جديد
                    </h3>
                </div>
                <div>
                    <a href="{{ route('users.index') }}" class="btn btn-sm btn-light fw-bold">
                        <i class="bi bi-arrow-right me-1"></i> العودة للقائمة
                    </a>
                </div>
            </div>

            <div class="card-body py-6">
                @if($errors->any())
                    <div class="alert alert-danger p-4 mb-5">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('users.store') }}" method="POST">
                    @csrf

                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold required">الاسم الكامل</label>
                            <input type="text" name="name" class="form-control" placeholder="أدخل اسم المستخدم" value="{{ old('name') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold required">البريد الإلكتروني</label>
                            <input type="email" name="email" class="form-control" placeholder="example@domain.com" value="{{ old('email') }}" required>
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold required">كلمة المرور</label>
                            <input type="password" name="password" class="form-control" placeholder="8 أحرف على الأقل" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold required">تأكيد كلمة المرور</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="أعد كتابة كلمة المرور" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold required">نوع الحساب (الرتبة)</label>
                        <select name="role" id="roleSelect" class="form-select">
                            <option value="customer" {{ old('role') === 'customer' ? 'selected' : '' }}>زبون (Customer) - يتسوق فقط</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>مدير (Admin) - يدخل للوحة التحكم</option>
                        </select>
                    </div>

                    <!-- خيار السوبر أدمن -->
                    <div class="card bg-light-warning border border-warning border-dashed p-4 rounded-4 mb-6">
                        <div class="form-check form-switch d-flex align-items-center gap-3">
                            <input class="form-check-input ms-0" type="checkbox" name="is_super_admin" value="1" id="superAdminSwitch" {{ old('is_super_admin') ? 'checked' : '' }} style="width: 2.5em; height: 1.3em;">
                            <label class="form-check-label fw-bolder text-dark fs-6" for="superAdminSwitch">
                                👑 منح صلاحية السوبر أدمن (Super Admin)
                            </label>
                        </div>
                        <div class="text-muted fs-7 mt-2 ms-8">
                            عند تفعيل هذا الخيار، سيتمكن هذا المستخدم من الدخول لشاشة إدارة المستخدمين وتعديل صلاحيات باقي المدراء.
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3 border-top pt-4">
                        <a href="{{ route('users.index') }}" class="btn btn-light fw-bold">إلغاء</a>
                        <button type="submit" class="btn btn-primary fw-bold px-6">
                            <i class="bi bi-check2-circle me-1"></i> حفظ المستخدم
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
