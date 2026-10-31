<?php

namespace App\Observers;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class CategoryObserver
{
    public function created(Category $category): void
    {
        $this->clearCategoryCache($category->id);
    }

    public function updated(Category $category): void
    {
        $this->clearCategoryCache($category->id);
    }

    public function deleted(Category $category): void
    {
        $this->clearCategoryCache($category->id);
    }

    private function clearCategoryCache(?int $categoryId = null): void
    {
        if ($categoryId) {
            Cache::forget("category_detail_{$categoryId}_lang_ar");
            Cache::forget("category_detail_{$categoryId}_lang_en");
        }

        Cache::forget('categories_all_lang_ar');
        Cache::forget('categories_all_lang_en');
        
        Cache::flush();
    }
}