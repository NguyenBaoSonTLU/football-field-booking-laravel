<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TimeSlotStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTimeSlotRequest;
use App\Http\Requests\Admin\UpdateTimeSlotRequest;
use App\Models\TimeSlot;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TimeSlotController extends Controller
{
    public function index(): View
    {
        $timeSlots = TimeSlot::query()->withCount('bookings')->orderBy('start_time')->paginate(20);

        return view('admin.time-slots.index', compact('timeSlots'));
    }

    public function create(): View
    {
        $statuses = TimeSlotStatus::cases();

        return view('admin.time-slots.create', compact('statuses'));
    }

    public function store(StoreTimeSlotRequest $request): RedirectResponse
    {
        try {
            TimeSlot::query()->create($request->validated());
        } catch (QueryException $exception) {
            if ((string) $exception->getCode() === '23000') {
                return back()->withErrors(['start_time' => 'Khung giờ này đã tồn tại.'])->withInput();
            }

            throw $exception;
        }

        return redirect()->route('admin.time-slots.index')->with('success', 'Đã thêm khung giờ.');
    }

    public function edit(TimeSlot $timeSlot): View
    {
        $statuses = TimeSlotStatus::cases();

        return view('admin.time-slots.edit', compact('timeSlot', 'statuses'));
    }

    public function update(UpdateTimeSlotRequest $request, TimeSlot $timeSlot): RedirectResponse
    {
        try {
            $timeSlot->update($request->validated());
        } catch (QueryException $exception) {
            if ((string) $exception->getCode() === '23000') {
                return back()->withErrors(['start_time' => 'Khung giờ này đã tồn tại.'])->withInput();
            }

            throw $exception;
        }

        return back()->with('success', 'Khung giờ đã được cập nhật.');
    }

    public function destroy(TimeSlot $timeSlot): RedirectResponse
    {
        if ($timeSlot->bookings()->exists()) {
            $timeSlot->update(['status' => TimeSlotStatus::INACTIVE]);

            return back()->with('success', 'Khung giờ đã có lịch sử đặt nên được chuyển sang tạm ngừng.');
        }

        $timeSlot->delete();

        return back()->with('success', 'Đã xóa khung giờ.');
    }
}
