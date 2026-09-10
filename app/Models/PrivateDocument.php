<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrivateDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'type',
        'file_path',
        'file_name',
        'file_size',
        'mime_type',
        'notes',
    ];

    /**
     * علاقة الوثيقة بالمستخدم المعني (إن وجد)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * التحقق مما إذا كان الملف صورة قابلة للمعاينة
     */
    public function isImage(): bool
    {
        return str_starts_with($this->mime_type ?? '', 'image/');
    }

    /**
     * نص نوع الوثيقة بالعربية
     */
    public function getTypeLabel(): string
    {
        return match ($this->type) {
            'user_document'   => 'وثيقة مستخدم',
            'private_invoice' => 'فاتورة خاصة',
            default           => 'مستند آخر',
        };
    }

    /**
     * شارة ملونة لنوع المستند
     */
    public function getTypeBadgeClass(): string
    {
        return match ($this->type) {
            'user_document'   => 'bg-light-primary text-primary border border-primary',
            'private_invoice' => 'bg-light-warning text-warning border border-warning',
            default           => 'bg-light-secondary text-secondary border',
        };
    }

    /**
     * تنسيق حجم الملف بشكل مقروء
     */
    public function getFormattedSize(): string
    {
        $bytes = $this->file_size ?? 0;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' B';
    }
}
