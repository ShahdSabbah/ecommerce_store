<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * عرض قائمة المستخدمين مع إحصائيات وبحث
     */
    public function index(Request $request)
    {
        $query = User::latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            if ($request->role === 'super_admin') {
                $query->where('is_super_admin', true);
            } else {
                $query->where('role', $request->role);
            }
        }

        $users = $query->paginate(10)->withQueryString();

        $totalUsers       = User::count();
        $totalAdmins      = User::where('role', 'admin')->count();
        $totalSuperAdmins = User::where('is_super_admin', true)->count();
        $totalCustomers   = User::where('role', 'customer')->count();

        return view('users.index', compact(
            'users',
            'totalUsers',
            'totalAdmins',
            'totalSuperAdmins',
            'totalCustomers'
        ));
    }

    /**
     * صفحة إضافة مستخدم جديد
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * حفظ مستخدم جديد
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'email'           => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password'        => ['required', 'confirmed', Rules\Password::defaults()],
            'role'            => ['required', 'in:admin,customer'],
            'is_super_admin'  => ['nullable', 'boolean'],
        ]);

        $isSuperAdmin = $request->boolean('is_super_admin');

        User::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'role'           => $isSuperAdmin ? 'admin' : $request->role,
            'is_super_admin' => $isSuperAdmin,
        ]);

        return redirect()->route('users.index')->with('success', __('messages.user_created'));
    }

    /**
     * صفحة تعديل بيانات المستخدم
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * تحديث بيانات وصلاحيات المستخدم
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'email'           => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password'        => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role'            => ['required', 'in:admin,customer'],
            'is_super_admin'  => ['nullable', 'boolean'],
        ]);

        $newIsSuperAdmin = $request->boolean('is_super_admin');

        if (auth()->id() === $user->id && !$newIsSuperAdmin) {
            return redirect()->back()->with('error', __('messages.cannot_remove_self'));
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $newIsSuperAdmin ? 'admin' : $request->role;
        $user->is_super_admin = $newIsSuperAdmin;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', __('messages.user_updated'));
    }

    /**
     * حذف مستخدم من النظام
     */
    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return redirect()->back()->with('error', __('messages.cannot_delete_self'));
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', __('messages.user_deleted'));
    }
}
