@extends('layouts.master')

@section('title', __('messages.private_documents') . ' - Super Admin')

@section('content')

<!-- تنبيه أمني توضيحي -->
<div class="alert alert-dismissible bg-light-danger border border-danger border-dashed d-flex flex-column flex-sm-row p-5 mb-7 rounded-4">
    <i class="bi bi-shield-lock-fill fs-2hx text-danger me-4 mb-5 mb-sm-0"></i>
    <div class="d-flex flex-column pe-0 pe-sm-10">
        <h5 class="fw-bolder text-danger mb-1">{{ __('messages.private_storage_title') }}</h5>
        <span class="text-gray-700 fs-7">
            {{ __('messages.private_storage_notice') }}
        </span>
    </div>
</div>

<!-- بطاقات الإحصائيات -->
<div class="row g-5 g-xl-8 mb-7">
    <div class="col-md-4">
        <div class="card card-flush shadow-sm bg-light-primary border-0">
            <div class="card-body d-flex justify-content-between align-items-center py-5">
                <div>
                    <span class="fs-2hx fw-bolder text-primary d-block">{{ $totalDocs }}</span>
                    <span class="text-gray-600 fw-bold fs-6">{{ __('messages.all') }} {{ __('messages.private_documents') }}</span>
                </div>
                <div class="p-3 bg-white rounded-circle shadow-sm">
                    <i class="bi bi-folder-check fs-2 text-primary"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-flush shadow-sm bg-light-info border-0">
            <div class="card-body d-flex justify-content-between align-items-center py-5">
                <div>
                    <span class="fs-2hx fw-bolder text-info d-block">{{ $userDocsCount }}</span>
                    <span class="text-gray-600 fw-bold fs-6">{{ __('messages.type_user_document') }}</span>
                </div>
                <div class="p-3 bg-white rounded-circle shadow-sm">
                    <i class="bi bi-person-badge fs-2 text-info"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-flush shadow-sm bg-light-warning border-0">
            <div class="card-body d-flex justify-content-between align-items-center py-5">
                <div>
                    <span class="fs-2hx fw-bolder text-warning d-block">{{ $invoicesCount }}</span>
                    <span class="text-gray-600 fw-bold fs-6">{{ __('messages.type_private_invoice') }}</span>
                </div>
                <div class="p-3 bg-white rounded-circle shadow-sm">
                    <i class="bi bi-receipt-cutoff fs-2 text-warning"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- كرت التحكم والجدول -->
<div class="card card-flush shadow-sm">
    <!-- رأس الكرت -->
    <div class="card-header align-items-center py-5 gap-3 flex-wrap">
        <div>
            <h3 class="card-title fw-bold text-dark m-0">{{ __('messages.private_documents') }}</h3>
            <span class="text-muted fs-7">{{ __('messages.super_admin') }}</span>
        </div>

        <div class="d-flex align-items-center gap-3 ms-auto">
            <a href="{{ route('private-documents.create') }}" class="btn btn-primary fw-bold">
                <i class="bi bi-cloud-arrow-up-fill me-1"></i> {{ __('messages.upload_document') }}
            </a>
        </div>
    </div>

    <!-- شريط البحث والفلترة -->
    <div class="card-body border-top border-bottom py-4 bg-light-subtle">
        <form method="GET" action="{{ route('private-documents.index') }}" class="row g-3 align-items-center">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="{{ __('messages.search') }}..." value="{{ request('search') }}">
                </div>
            </div>

            <div class="col-md-3">
                <select name="type" class="form-select">
                    <option value="">-- {{ __('messages.all') }} {{ __('messages.document_type') }} --</option>
                    <option value="user_document" {{ request('type') === 'user_document' ? 'selected' : '' }}>{{ __('messages.type_user_document') }}</option>
                    <option value="private_invoice" {{ request('type') === 'private_invoice' ? 'selected' : '' }}>{{ __('messages.type_private_invoice') }}</option>
                    <option value="other" {{ request('type') === 'other' ? 'selected' : '' }}>{{ __('messages.type_other') }}</option>
                </select>
            </div>

            <div class="col-md-3">
                <select name="user_id" class="form-select">
                    <option value="">-- {{ __('messages.linked_user') }} --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-dark fw-bold flex-grow-1">{{ __('messages.filter') }}</button>
                @if(request()->hasAny(['search', 'type', 'user_id']))
                    <a href="{{ route('private-documents.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
                @endif
            </div>
        </form>
    </div>

    <!-- جسم الكرت: عرض الملفات -->
    <div class="card-body pt-5">
        {{-- رسائل التنبيه والنجاح --}}
        @if(session('success'))
            <div class="alert alert-success d-flex align-items-center p-4 mb-5 rounded-3">
                <i class="bi bi-check-circle-fill fs-3 me-3 text-success"></i>
                <div class="fw-bold">{{ session('success') }}</div>
            </div>
        @endif

        @if($documents->count() > 0)
            <div class="row g-4">
                @foreach($documents as $doc)
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="card h-100 border border-secondary border-opacity-25 rounded-4 overflow-hidden shadow-sm hover-elevate-up transition">
                            
                            <!-- معاينة الصورة أو أيقونة المستند -->
                            <div class="position-relative bg-light d-flex align-items-center justify-content-center" style="height: 160px; overflow: hidden;">
                                @if($doc->isImage())
                                    <img src="{{ route('private-documents.view', $doc->id) }}" 
                                         alt="{{ $doc->title }}" 
                                         class="w-100 h-100" 
                                         style="object-fit: cover;"
                                         loading="lazy">
                                @else
                                    <div class="text-center p-4">
                                        <i class="bi bi-file-earmark-pdf-fill text-danger fs-1"></i>
                                        <div class="text-muted fw-bold small mt-1">PDF</div>
                                    </div>
                                @endif

                                <!-- شارة نوع المستند -->
                                <span class="position-absolute top-0 end-0 m-3 badge {{ $doc->getTypeBadgeClass() }} rounded-pill px-3 py-2 shadow-sm">
                                    {{ $doc->type === 'user_document' ? __('messages.type_user_document') : ($doc->type === 'private_invoice' ? __('messages.type_private_invoice') : __('messages.type_other')) }}
                                </span>
                            </div>

                            <!-- تفاصيل المستند -->
                            <div class="card-body p-4 d-flex flex-column">
                                <h5 class="fw-bold text-dark text-truncate mb-2" title="{{ $doc->title }}">{{ $doc->title }}</h5>

                                <div class="fs-7 text-muted mb-2">
                                    <i class="bi bi-person me-1 text-primary"></i> 
                                    @if($doc->user)
                                        <span class="text-gray-800 fw-semibold">{{ $doc->user->name }}</span>
                                    @else
                                        <span class="text-muted">{{ __('messages.unlinked_general') }}</span>
                                    @endif
                                </div>

                                <div class="d-flex justify-content-between text-muted fs-8 mb-3 pb-2 border-bottom">
                                    <span><i class="bi bi-hdd me-1"></i> {{ $doc->getFormattedSize() }}</span>
                                    <span><i class="bi bi-calendar3 me-1"></i> {{ $doc->created_at->format('Y-m-d') }}</span>
                                </div>

                                @if($doc->notes)
                                    <p class="text-gray-600 fs-8 text-truncate mb-3" title="{{ $doc->notes }}">
                                        <i class="bi bi-chat-left-text me-1"></i> {{ $doc->notes }}
                                    </p>
                                @endif

                                <!-- أزرار التحكم -->
                                <div class="mt-auto d-flex gap-2">
                                    <a href="{{ route('private-documents.view', $doc->id) }}" target="_blank" class="btn btn-sm btn-light-primary fw-bold flex-grow-1" title="{{ __('messages.view') }}">
                                        <i class="bi bi-eye"></i> {{ __('messages.view') }}
                                    </a>

                                    <a href="{{ route('private-documents.download', $doc->id) }}" class="btn btn-sm btn-light-success fw-bold" title="{{ __('messages.download') }}">
                                        <i class="bi bi-download"></i>
                                    </a>

                                    <form action="{{ route('private-documents.destroy', $doc->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light-danger fw-bold" title="{{ __('messages.delete') }}" onclick="return confirm('{{ __('messages.confirm_delete') }}')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-5">
                {{ $documents->links() }}
            </div>
        @else
            <div class="text-center py-10">
                <i class="bi bi-shield-x text-muted" style="font-size: 3.5rem;"></i>
                <h5 class="text-muted fw-bold mt-3">{{ __('messages.no_documents_yet') }}</h5>
                <a href="{{ route('private-documents.create') }}" class="btn btn-primary fw-bold mt-2">
                    <i class="bi bi-cloud-arrow-up-fill me-1"></i> {{ __('messages.upload_document') }}
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
