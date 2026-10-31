@extends('layouts.master')

@section('title', 'الإشعارات')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">
                <i class="bi bi-bell-fill text-primary me-2"></i> مركز الإشعارات
            </h2>
            <p class="text-muted mb-0">عرض وإدارة جميع الإشعارات والتنبيهات الخاصة بحسابك</p>
        </div>

        @if(auth()->user()->unreadNotifications->count() > 0)
            <form action="{{ route('notifications.markAllRead') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-primary rounded-pill px-4 fw-bold">
                    <i class="bi bi-check2-all me-1"></i> تعليم الكل كمقروء
                </button>
            </form>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-body p-0">
            @forelse($notifications as $notification)
                @php
                    $isUnread = is_null($notification->read_at);
                    $data = $notification->data;
                    $msg = $data['message'] ?? 'إشعار جديد';
                    $actionUrl = $data['action_url'] ?? null;
                    
                    // تحديد الأيقونة واللون حسب نوع الإشعار
                    $icon = 'bi-bell-fill';
                    $bgClass = 'bg-light-primary text-primary';
                    if (str_contains($notification->type, 'OutOfStock')) {
                        $icon = 'bi-exclamation-triangle-fill';
                        $bgClass = 'bg-light-danger text-danger';
                    } elseif (str_contains($notification->type, 'OrderStatus')) {
                        $icon = 'bi-arrow-repeat';
                        $bgClass = 'bg-light-warning text-warning';
                    } elseif (str_contains($notification->type, 'OrderPlaced')) {
                        $icon = 'bi-bag-check-fill';
                        $bgClass = 'bg-light-success text-success';
                    }
                @endphp

                <div class="p-4 border-bottom d-flex align-items-center justify-content-between transition-all {{ $isUnread ? 'bg-light bg-opacity-75' : '' }}" style="border-color: #f1f1f4 !important;">
                    <div class="d-flex align-items-center gap-3 flex-grow-1">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; background-color: {{ $isUnread ? '#e8fff3' : '#f4f4f7' }};">
                            <i class="bi {{ $icon }} fs-4" style="color: {{ $isUnread ? '#198754' : '#6c757d' }};"></i>
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <h6 class="mb-0 fw-bold {{ $isUnread ? 'text-dark' : 'text-secondary' }}">
                                    {{ $msg }}
                                </h6>
                                @if($isUnread)
                                    <span class="badge bg-primary rounded-pill px-2 py-1" style="font-size: 11px;">جديد</span>
                                @endif
                            </div>
                            <span class="text-muted small">
                                <i class="bi bi-clock me-1"></i> {{ $notification->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        @if($actionUrl)
                            <a href="{{ route('notifications.read', $notification->id) }}" class="btn btn-sm btn-primary rounded-pill px-3 fw-bold">
                                عرض التفاصيل <i class="bi bi-arrow-left ms-1"></i>
                            </a>
                        @elseif($isUnread)
                            <a href="{{ route('notifications.read', $notification->id) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                تعليم كمقروء
                            </a>
                        @endif

                        <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الإشعار؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-light text-danger rounded-circle p-2" title="حذف">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <div class="mb-3 text-muted">
                        <i class="bi bi-bell-slash fs-1 text-secondary opacity-50"></i>
                    </div>
                    <h5 class="fw-bold text-secondary">لا توجد إشعارات حالياً</h5>
                    <p class="text-muted">ستظهر هنا جميع الإشعارات والتنبيهات الخاصة بطلباتك ومخزونك.</p>
                </div>
            @endforelse
        </div>

        @if($notifications->hasPages())
            <div class="card-footer bg-white border-0 py-3 d-flex justify-content-center">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
