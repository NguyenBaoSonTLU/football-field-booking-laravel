@extends('layouts.app')

@section('title', 'Chi tiết đơn #'.$booking->id)

@section('content')
<section class="pp-section pt-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4"><div><a href="{{ route('bookings.index') }}">← Quay lại lịch sử</a><h1 class="pp-section-title mt-2">Chi tiết đơn #{{ $booking->id }}</h1></div><x-status-badge :status="$booking->status" /></div>
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="pp-card p-4">
                    <div class="row g-4 align-items-center"><div class="col-md-5"><img class="rounded-4" src="{{ $booking->footballField->main_image_url }}" alt="{{ $booking->footballField->name }}"></div><div class="col-md-7"><h2 class="h3 fw-bold">{{ $booking->footballField->name }}</h2><p class="text-muted">📍 {{ $booking->footballField->address }}</p><a href="{{ route('fields.show', $booking->footballField) }}">Xem thông tin sân →</a></div></div>
                    <hr class="my-4">
                    <div class="row g-4"><div class="col-sm-6"><div class="small text-muted text-uppercase">Ngày sử dụng</div><strong>{{ $booking->booking_date->format('d/m/Y') }}</strong></div><div class="col-sm-6"><div class="small text-muted text-uppercase">Khung giờ</div><strong>{{ $booking->timeSlot->label }}</strong></div><div class="col-sm-6"><div class="small text-muted text-uppercase">Thời điểm tạo</div><strong>{{ $booking->created_at->format('d/m/Y H:i') }}</strong></div><div class="col-sm-6"><div class="small text-muted text-uppercase">Ghi chú</div><strong>{{ $booking->note ?: 'Không có' }}</strong></div></div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="pp-card p-4"><h2 class="h5 fw-bold">Tổng thanh toán</h2><div class="display-6 fw-bold text-pp my-3">{{ $booking->formatted_price }}</div><p class="text-muted">Phương thức: Thanh toán trực tiếp tại sân.</p><a class="btn btn-outline-pp w-100" href="{{ route('bookings.rebook', $booking) }}">Đặt lại sân</a>
                    @if($booking->canBeCancelledBy(auth()->user()))
                        <form data-confirm="Bạn chắc chắn muốn hủy lịch đặt sân này?" method="POST" action="{{ route('bookings.cancel', $booking) }}" class="mt-2">@csrf @method('PATCH')<button class="btn btn-outline-danger w-100" type="submit">Hủy lịch đặt sân</button></form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
