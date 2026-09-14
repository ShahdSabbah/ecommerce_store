<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. إعادة ضبط الكاش الخاص بالصلاحيات
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. إنشاء الصلاحيات (Permissions)
        $permissions = [
            'manage-products',
            'delete-products',
            'view-orders',
            'manage-users',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 3. إنشاء الأدوار (Roles) وربط الصلاحيات بها
        // أ. دور سوبر أدمن: يحصل على جميع الصلاحيات
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin']);
        $superAdminRole->syncPermissions(Permission::all());

        // ب. دور الأدمن العادي: يملك إدارة المنتجات ومشاهدة الطلبات فقط
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions([
            'manage-products',
            'view-orders',
        ]);

        // ج. دور الزبون العادي
        $customerRole = Role::firstOrCreate(['name' => 'customer']);

        // 4. تعيين الأدوار للمستخدمين الموجودين حالياً
        // المستخدم رقم 1 (شهد): سوبر أدمن
        $user1 = User::find(1);
        if ($user1) {
            $user1->assignRole('super-admin');
        }

        // المستخدم رقم 2 (أحمد): زبون عادي
        $user2 = User::find(2);
        if ($user2) {
            $user2->assignRole('customer');
        }

        // المستخدم رقم 3 (خالد): أدمن عادي
        $user3 = User::find(3);
        if ($user3) {
            $user3->assignRole('admin');
        }
    }
}
