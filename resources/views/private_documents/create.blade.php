@extends('layouts.master')

@section('title', 'رفع وثيقة أو فاتورة خاصة - Super Admin')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-flush shadow-sm">
            <div class="card-header py-5 border-bottom">
                <div class="card-title">
                    <h3 class="fw-bolder m-0 text-dark">
                        <i class="bi bi-cloud-arrow-up text-primary me-2"></i> رفع وثيقة أو فاتورة خاصة (Private Storage)
                    </h3>
                </div>
                <div>
                    <a href="{{ route('private-documents.index') }}" class="btn btn-sm btn-light fw-bold">
                        <i class="bi bi-arrow-right me-1"></i> العودة للقائمة
                    </a>
                </div>
            </div>

            <div class="card-body py-6">
                <!-- رسالة توضيحية -->
                <div class="alert alert-light-warning border border-warning border-dashed p-4 mb-6 rounded-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-info-circle-fill text-warning fs-3 me-3"></i>
                        <span class="fs-7 text-gray-700">
                            سيتم تشفير وتخزين الملف المرفوع في مجلد محمي معزول، ولن يتمكن أي شخص من فتحه سوى <strong>Super Admin</strong>.
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
                        <label class="form-label fw-bold required">عنوان الوثيقة أو الفاتورة</label>
                        <input type="text" name="title" class="form-control" placeholder="مثال: هوية العميل أحمد / فاتورة توريد رقم #402" value="{{ old('title') }}" required>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold required">نوع المستند</label>
                            <select name="type" class="form-select" required>
                                <option value="user_document" {{ old('type') === 'user_document' ? 'selected' : '' }}>وثيقة مستخدم (هوية، جواز سفر، عقد...)</option>
                                <option value="private_invoice" {{ old('type') === 'private_invoice' ? 'selected' : '' }}>فاتورة خاصة (مشتريات، كشف حساب، إيصال...)</option>
                                <option value="other" {{ old('type') === 'other' ? 'selected' : '' }}>مستند آخر</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">المستخدم التابع له المستند (اختياري)</label>
                            <select name="user_id" class="form-select">
                                <option value="">-- عام (بدون ربط بمستخدم) --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold required">الملف أو الصورة</label>
                        <input type="file" name="file" class="form-control" accept="image/*,application/pdf" required>
                        <div class="form-text text-muted fs-8 mt-1">
                            الصيغ المدعومة: صور (JPG, PNG, WEBP, GIF) أو ملفات PDF (الحد الأقصى للحجم: 10 ميغابايت).
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="form-label fw-bold">ملاحظات إضافية (اختياري)</label>
                        <textarea name="notes" rows="3" class="form-control" placeholder="أي تفاصيل أو ملاحظات حول هذه الوثيقة...">{{ old('notes') }}</textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-3 border-top pt-4">
                        <a href="{{ route('private-documents.index') }}" class="btn btn-light fw-bold">إلغاء</a>
                        <button type="submit" class="btn btn-primary fw-bold px-6">
                            <i class="bi bi-cloud-arrow-up-fill me-1"></i> رفع وحفظ المستند
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
