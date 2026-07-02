<?php

namespace App\Services;

use App\Enums\BookingStatus;
use App\Enums\FootballFieldStatus;
use App\Enums\TimeSlotStatus;
use App\Models\Booking;
use App\Models\FootballField;
use App\Models\TimeSlot;
use App\Models\User;
use App\Notifications\BookingStatusChanged;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BookingService
{
    public function __construct(private readonly AvailabilityService $availabilityService)
    {
    }

    public function create(User $user, array $data): Booking
    {
        try {
            return DB::transaction(function () use ($user, $data): Booking {
                $field = FootballField::query()
                    ->whereKey($data['football_field_id'])
                    ->where('status', FootballFieldStatus::ACTIVE->value)
                    ->firstOrFail();

                $slot = TimeSlot::query()
                    ->whereKey($data['time_slot_id'])
                    ->where('status', TimeSlotStatus::ACTIVE->value)
                    ->firstOrFail();

                $date = Carbon::createFromFormat('Y-m-d', $data['booking_date'])->format('Y-m-d');

                if (Carbon::parse($date)->lt(today())) {
                    throw ValidationException::withMessages([
                        'booking_date' => 'Ngày đặt sân không được nhỏ hơn ngày hiện tại.',
                    ]);
                }

                if ($slot->start_time < $field->open_time || $slot->end_time > $field->close_time) {
                    throw ValidationException::withMessages([
                        'time_slot_id' => 'Khung giờ không nằm trong thời gian hoạt động của sân.',
                    ]);
                }

                $lockKey = Booking::makeLockKey($field->id, $date, $slot->id);

                $alreadyBooked = Booking::query()
                    ->where('slot_lock_key', $lockKey)
                    ->whereIn('status', [BookingStatus::PENDING->value, BookingStatus::CONFIRMED->value])
                    ->exists();

                if ($alreadyBooked) {
                    throw ValidationException::withMessages([
                        'time_slot_id' => 'Khung giờ này vừa có người đặt. Vui lòng chọn khung giờ khác.',
                    ]);
                }

                return Booking::query()->create([
                    'user_id' => $user->id,
                    'football_field_id' => $field->id,
                    'time_slot_id' => $slot->id,
                    'booking_date' => $date,
                    'total_price' => $this->availabilityService->calculatePrice($field, $slot),
                    'status' => BookingStatus::PENDING,
                    'note' => $data['note'] ?? null,
                    'slot_lock_key' => $lockKey,
                ])->load(['footballField.mainImage', 'timeSlot', 'user']);
            }, 3);
        } catch (QueryException $exception) {
            if ((string) $exception->getCode() === '23000') {
                throw ValidationException::withMessages([
                    'time_slot_id' => 'Khung giờ này vừa có người đặt. Vui lòng chọn khung giờ khác.',
                ]);
            }

            throw $exception;
        }
    }

    public function cancel(Booking $booking, User $actor): Booking
    {
        return DB::transaction(function () use ($booking, $actor): Booking {
            $lockedBooking = Booking::query()
                ->with(['timeSlot', 'user', 'footballField'])
                ->lockForUpdate()
                ->findOrFail($booking->id);

            if (! $lockedBooking->canBeCancelledBy($actor)) {
                throw ValidationException::withMessages([
                    'booking' => 'Đơn đặt sân không còn đủ điều kiện để hủy.',
                ]);
            }

            $lockedBooking->update([
                'status' => BookingStatus::CANCELLED,
                'slot_lock_key' => null,
            ]);

            if ($actor->id !== $lockedBooking->user_id) {
                $lockedBooking->user->notify(new BookingStatusChanged($lockedBooking->fresh(), 'Đơn đặt sân đã được quản trị viên hủy.'));
            }

            return $lockedBooking->fresh(['footballField.mainImage', 'timeSlot', 'user']);
        }, 3);
    }

    public function updateStatus(Booking $booking, BookingStatus $newStatus): Booking
    {
        return DB::transaction(function () use ($booking, $newStatus): Booking {
            $lockedBooking = Booking::query()
                ->with(['user', 'footballField', 'timeSlot'])
                ->lockForUpdate()
                ->findOrFail($booking->id);

            if ($lockedBooking->status === $newStatus) {
                return $lockedBooking;
            }

            $allowedTransitions = [
                BookingStatus::PENDING->value => [BookingStatus::CONFIRMED, BookingStatus::CANCELLED],
                BookingStatus::CONFIRMED->value => [BookingStatus::COMPLETED, BookingStatus::CANCELLED],
                BookingStatus::CANCELLED->value => [],
                BookingStatus::COMPLETED->value => [],
            ];

            if (! in_array($newStatus, $allowedTransitions[$lockedBooking->status->value] ?? [], true)) {
                throw ValidationException::withMessages([
                    'status' => 'Không thể chuyển trạng thái đơn theo yêu cầu.',
                ]);
            }

            $lockKey = $lockedBooking->slot_lock_key;

            if ($newStatus === BookingStatus::CONFIRMED && ! $lockKey) {
                $lockKey = Booking::makeLockKey(
                    $lockedBooking->football_field_id,
                    $lockedBooking->booking_date->format('Y-m-d'),
                    $lockedBooking->time_slot_id
                );
            }

            if (in_array($newStatus, [BookingStatus::CANCELLED, BookingStatus::COMPLETED], true)) {
                $lockKey = null;
            }

            $lockedBooking->update([
                'status' => $newStatus,
                'slot_lock_key' => $lockKey,
            ]);

            $message = match ($newStatus) {
                BookingStatus::CONFIRMED => 'Đơn đặt sân của bạn đã được xác nhận.',
                BookingStatus::CANCELLED => 'Đơn đặt sân của bạn đã bị hủy.',
                BookingStatus::COMPLETED => 'Đơn đặt sân đã được đánh dấu hoàn thành.',
                default => 'Trạng thái đơn đặt sân đã được cập nhật.',
            };

            $lockedBooking->user->notify(new BookingStatusChanged($lockedBooking->fresh(), $message));

            return $lockedBooking->fresh(['footballField.mainImage', 'timeSlot', 'user']);
        }, 3);
    }
}
