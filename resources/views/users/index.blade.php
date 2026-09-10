@extends('layouts.master')

@section('title', 'إدارة المستخدمين - Super Admin')

@section('content')

<!-- كروت إحصائيات المستخدمين -->
<div class="row g-5 g-xl-8 mb-6">
    <div class="col-xl-3 col-md-6">
        <div class="card card-flush shadow-sm bg-light-primary border-0">
            <div class="card-body d-flex justify-content-between align-items-center py-5">
                <div>
                    <span class="fs-2hx fw-bolder text-primary d-block">{{ $totalUsers }}</span>
                    <span class="text-gray-600 fw-bold fs-6">إجمالي المستخدمين</span>
                </div>
                <div class="p-3 bg-white rounded-circle shadow-sm">
                    <i class="bi bi-people-fill fs-2 text-primary"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-flush shadow-sm bg-light-warning border-0">
            <div class="card-body d-flex justify-content-between align-items-center py-5">
                <div>
                    <span class="fs-2hx fw-bolder text-warning d-block">{{ $totalSuperAdmins }}</span>
                    <span class="text-gray-600 fw-bold fs-6">سوبر أدمن 👑</span>
                </div>
                <div class="p-3 bg-white rounded-circle shadow-sm">
                    <i class="bi bi-shield-fill-check fs-2 text-warning"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-flush shadow-sm bg-light-info border-0">
            <div class="card-body d-flex justify-content-between align-items-center py-5">
                <div>
                    <span class="fs-2hx fw-bolder text-info d-block">{{ $totalAdmins }}</span>
                    <span class="text-gray-600 fw-bold fs-6">المدراء (Admins)</span>
                </div>
                <div class="p-3 bg-white rounded-circle shadow-sm">
                    <i class="bi bi-person-gear fs-2 text-info"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-flush shadow-sm bg-light-success border-0">
            <div class="card-body d-flex justify-content-between align-items-center py-5">
                <div>
                    <span class="fs-2hx fw-bolder text-success d-block">{{ $totalCustomers }}</span>
                    <span class="text-gray-600 fw-bold fs-6">الزبائن (Customers)</span>
                </div>
                <div class="p-3 bg-white rounded-circle shadow-sm">
                    <i class="bi bi-person-check fs-2 text-success"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- بطاقة جدول المستخدمين -->
<div class="card card-flush shadow-sm">
    <!-- رأس البطاقة -->
    <div class="card-header align-items-center py-5 gap-3 flex-wrap">
        <div>
            <h3 class="card-title fw-bold text-dark m-0">قائمة المستخدمين والصلاحيات</h3>
            <span class="text-muted fs-7">لوحة تحكم السوبر أدمن لإدارة الحسابات</span>
        </div>

        <div class="d-flex align-items-center gap-3 ms-auto">
            <a href="{{ route('users.create') }}" class="btn btn-primary fw-bold">
                <i class="bi bi-person-plus-fill me-1"></i> إضافة مستخدم جديد +
            </a>
        </div>
    </div>

    <!-- شريط البحث والفلترة -->
    <div class="card-body border-top border-bottom py-4 bg-light-subtle">
        <form method="GET" action="{{ route('users.index') }}" class="row g-3 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="بحث بالاسم أو البريد الإلكتروني..." value="{{ request('search') }}">
                </div>
            </div>

            <div class="col-md-4">
                <select name="role" class="form-select">
                    <option value="">-- جميع الرتب والصلاحيات --</option>
                    <option value="super_admin" {{ request('role') === 'super_admin' ? 'selected' : '' }}>👑 سوبر أدمن فقط</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>مدير (Admin)</option>
                    <option value="customer" {{ request('role') === 'customer' ? 'selected' : '' }}>زبون (Customer)</option>
                </select>
            </div>

            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-dark fw-bold flex-grow-1">تصفية</button>
                @if(request()->hasAny(['search', 'role']))
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">إلغاء</a>
                @endif
            </div>
        </form>
    </div>

    <!-- جسم البطاقة: الجدول -->
    <div class="card-body pt-4">
        {{-- رسائل التنبيه والنجاح --}}
        @if(session('success'))
            <div class="alert alert-success d-flex align-items-center p-4 mb-4">
                <i class="bi bi-check-circle-fill fs-3 me-3 text-success"></i>
                <div class="fw-bold">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger d-flex align-items-center p-4 mb-4">
                <i class="bi bi-exclamation-triangle-fill fs-3 me-3 text-danger"></i>
                <div class="fw-bold">{{ session('error') }}</div>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table align-middle table-row-dashed fs-6 gy-5">
                <thead>
                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                        <th>#</th>
                        <th>المستخدم</th>
                        <th>البريد الإلكتروني</th>
                        <th>الرتبة</th>
                        <th>سوبر أدمن</th>
                        <th>تاريخ الانضمام</th>
                        <th class="text-end">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    @forelse($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="symbol symbol-40px bg-light-primary rounded-circle d-flex align-items-center justify-content-center fw-bolder text-primary" style="width:40px; height:40px;">
                                    {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <span class="text-dark fw-bold d-block">{{ $user->name }}</span>
                                    @if(auth()->id() === $user->id)
                                        <span class="badge bg-light text-primary border fs-9">أنت (الحساب الحالي)</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->role === 'admin')
                                <span class="badge bg-light-info text-info border border-info px-3 py-2">مدير (Admin)</span>
                            @else
                                <span class="badge bg-light-secondary text-secondary border px-3 py-2">زبون (Customer)</span>
                            @endif
                        </td>
                        <td>
                            @if($user->is_super_admin)
                                <span class="badge bg-warning text-dark fw-bold px-3 py-2">
                                    <i class="bi bi-patch-check-fill text-dark me-1"></i> نعم (Super Admin)
                                </span>
                            @else
                                <span class="badge bg-light text-muted border px-3 py-2">لا</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at ? $user->created_at->format('Y-m-d') : '-' }}</td>
                        <td class="text-end">
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-light-primary fw-bold me-1">
                                <i class="bi bi-pencil me-1"></i> تعديل
                            </a>

                            @if(auth()->id() !== $user->id)
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light-danger fw-bold" onclick="return confirm('هل أنت متأكد من رغبتك في حذف هذا المستخدم نهائياً؟')">
                                        <i class="bi bi-trash me-1"></i> حذف
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-search fs-2 text-muted d-block mb-2"></i>
                            لا يوجد مستخدمين مطابقين لخيارات البحث.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- أزرار التنقل بين الصفحات -->
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>

@endsection
