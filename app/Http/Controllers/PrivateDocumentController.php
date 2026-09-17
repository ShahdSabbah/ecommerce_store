<?php

namespace App\Http\Controllers;

use App\Models\PrivateDocument;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PrivateDocumentController extends Controller
{
    /**
     * عرض قائمة الوثائق والفواتير الخاصة مع الفلترة والبحث
     */
    public function index(Request $request)
    {
        $query = PrivateDocument::with('user')->latest();

        // بحث بالعنوان أو اسم الملف
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('file_name', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        // فلترة بالنوع
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // فلترة بالمستخدم
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $documents = $query->paginate(12)->withQueryString();

        // إحصائيات سريعة للبطاقات
        $totalDocs     = PrivateDocument::count();
        $userDocsCount = PrivateDocument::where('type', 'user_document')->count();
        $invoicesCount = PrivateDocument::where('type', 'private_invoice')->count();

        // قائمة المستخدمين للفلترة
        $users = User::select('id', 'name', 'email')->orderBy('name')->get();

        return view('private_documents.index', compact(
            'documents',
            'totalDocs',
            'userDocsCount',
            'invoicesCount',
            'users'
        ));
    }

    /**
     * صفحة رفع وثيقة أو فاتورة جديدة
     */
    public function create()
    {
        $users = User::select('id', 'name', 'email')->orderBy('name')->get();
        return view('private_documents.create', compact('users'));
    }

    /**
     * معالجة وحفظ الملف في التخزين المحمي
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'   => ['required', 'string', 'max:255'],
            'type'    => ['required', 'in:user_document,private_invoice,other'],
            'user_id' => ['nullable', 'exists:users,id'],
            'file'    => ['required', 'file', 'mimes:jpeg,png,jpg,webp,gif,pdf', 'max:10240'],
            'notes'   => ['nullable', 'string', 'max:1000'],
        ]);

        $file = $request->file('file');

        // تخزين الملف بأمان داخل storage/app/private/documents
        $storedPath = $file->store('documents', 'private');

        PrivateDocument::create([
            'user_id'   => $request->user_id,
            'title'     => $request->title,
            'type'      => $request->type,
            'file_path' => $storedPath,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'mime_type' => $file->getClientMimeType(),
            'notes'     => $request->notes,
        ]);

        return redirect()->route('private-documents.index')->with('success', 'تم رفع الملف وحفظه بأمان في التخزين المحمي!');
    }

    /**
     * معاينة وعرض الملف المحمي مباشرة في المتصفح (Stream)
     * متاح حصرياً لمن يمر عبر وسيط super_admin
     */
    public function show(PrivateDocument $private_document)
    {
        // التحقق أن المسار غير فارغ قبل إرساله للـ Storage
        if (empty($private_document->file_path) || !Storage::disk('private')->exists($private_document->file_path)) {
            abort(404, 'الملف غير موجود في التخزين المحمي.');
        }

        $fullPath = Storage::disk('private')->path($private_document->file_path);

        return response()->file($fullPath, [
            'Content-Type' => $private_document->mime_type ?? 'application/octet-stream',
        ]);
    }

    /**
     * تحميل الملف الأصلي بأمان
     */
    public function download(PrivateDocument $private_document)
    {
        // التحقق أن المسار غير فارغ قبل إرساله للـ Storage
        if (empty($private_document->file_path) || !Storage::disk('private')->exists($private_document->file_path)) {
            abort(404, 'الملف غير موجود في التخزين المحمي.');
        }

        return Storage::disk('private')->download(
            $private_document->file_path,
            $private_document->file_name
        );
    }

    /**
     * حذف الملف من التخزين المحمي ومن قاعدة البيانات
     */
    public function destroy(PrivateDocument $private_document)
    {
        // حذف الملف الفعلي إذا كان المسار موجدوهاً
        if (!empty($private_document->file_path) && Storage::disk('private')->exists($private_document->file_path)) {
            Storage::disk('private')->delete($private_document->file_path);
        }

        // حذف السجل من قاعدة البيانات
        $private_document->delete();

        return redirect()->route('private-documents.index')->with('success', 'تم حذف المستند والملف بنجاح!');
    }
}