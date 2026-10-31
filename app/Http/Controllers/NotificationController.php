<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * عرض صفحة كل الإشعارات للمستخدم الحالي
     */
    public function index()
    {
        $notifications = auth()->user()->notifications()->paginate(15);
        return view('notifications.index', compact('notifications'));
    }

    /**
     * تعليم إشعار محدد كمقروء والتحويل لصفحة الحدث
     */
    public function markAsRead(string $id)
    {
        $notification = auth()->user()->notifications()->where('id', $id)->firstOrFail();
        $notification->markAsRead();

        $actionUrl = $notification->data['action_url'] ?? null;
        if ($actionUrl) {
            return redirect($actionUrl);
        }

        return redirect()->back()->with('success', __('messages.notification_read', [], app()->getLocale()) ?: 'تم قراءة الإشعار');
    }

    /**
     * تعليم جميع الإشعارات كمقروءة
     */
    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return redirect()->back()->with('success', 'تم تعليم جميع الإشعارات كمقروءة بنجاح');
    }

    /**
     * حذف إشعار
     */
    public function destroy(string $id)
    {
        $notification = auth()->user()->notifications()->where('id', $id)->firstOrFail();
        $notification->delete();

        return redirect()->back()->with('success', 'تم حذف الإشعار بنجاح');
    }
}
