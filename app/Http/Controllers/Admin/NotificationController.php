<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SendNotificationRequest;
use App\Models\User;
use App\Notifications\SystemAnnouncement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function create(): View
    {
        $users = User::query()->where('role', UserRole::CUSTOMER->value)->orderBy('name')->get(['id', 'name', 'email']);

        return view('admin.notifications.create', compact('users'));
    }

    public function store(SendNotificationRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $notification = new SystemAnnouncement($data['title'], $data['message']);

        if ($data['target'] === 'all') {
            $users = User::query()->where('role', UserRole::CUSTOMER->value)->where('status', 'active')->get();
            Notification::send($users, $notification);
        } else {
            User::query()->findOrFail($data['user_id'])->notify($notification);
        }

        return back()->with('success', 'Thông báo đã được gửi.');
    }
}
