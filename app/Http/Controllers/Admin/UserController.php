<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserStatusRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $keyword = $request->string('keyword')->trim()->toString();
        $status = $request->string('status')->toString();

        $users = User::query()
            ->withCount('bookings')
            ->when($keyword, fn (Builder $query) => $query->where(function (Builder $builder) use ($keyword): void {
                $builder->where('name', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%")
                    ->orWhere('phone', 'like', "%{$keyword}%");
            }))
            ->when(in_array($status, ['active', 'locked'], true), fn (Builder $query) => $query->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'keyword', 'status'));
    }

    public function show(User $user): View
    {
        $user->load(['bookings.footballField', 'bookings.timeSlot']);
        $statuses = UserStatus::cases();

        return view('admin.users.show', compact('user', 'statuses'));
    }

    public function update(UpdateUserStatusRequest $request, User $user): RedirectResponse
    {
        if ($user->email === config('booking.primary_admin_email')) {
            return back()->withErrors(['status' => 'Không thể khóa tài khoản quản trị viên chính.']);
        }

        if ($user->id === $request->user()->id) {
            return back()->withErrors(['status' => 'Bạn không thể tự khóa tài khoản đang đăng nhập.']);
        }

        $user->update(['status' => UserStatus::from($request->validated('status'))]);

        return back()->with('success', 'Trạng thái tài khoản đã được cập nhật.');
    }
}
