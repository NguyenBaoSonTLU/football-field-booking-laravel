<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BookingStatusChanged extends Notification
{
    use Queueable;

    public function __construct(
        private readonly Booking $booking,
        private readonly string $message
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Cập nhật đơn đặt sân',
            'message' => $this->message,
            'booking_id' => $this->booking->id,
            'status' => $this->booking->status->value,
            'field_name' => $this->booking->footballField?->name,
            'booking_date' => $this->booking->booking_date?->format('d/m/Y'),
        ];
    }
}
