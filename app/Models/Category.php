<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    /**
     * ترجمة اسم القسم تلقائياً بناءً على لغة التطبيق الحالية
     */
    public function getNameAttribute($value)
    {
        $locale = app()->getLocale();

        if ($locale === 'en') {
            if (\Illuminate\Support\Facades\Lang::has('messages.' . $value, 'en')) {
                return __('messages.' . $value, [], 'en');
            }
        } elseif ($locale === 'ar') {
            $translations = [
                'Perfumes'    => 'عطور',
                'Clothing'    => 'ملابس',
                'Accessories' => 'إكسسوارات',
                'Electronics' => 'إلكترونيات',
                'Furniture'   => 'أثاث',
                'Shoes'       => 'أحذية',
            ];
            if (isset($translations[$value])) {
                return $translations[$value];
            }
        }

        return $value;
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
