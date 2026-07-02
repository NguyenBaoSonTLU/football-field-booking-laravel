@extends('layouts.app')

@section('title', 'Lịch sử đặt sân')

@section('content')
<section class="pp-section pt-4">
    <div class="container">
        <h1 class="display-5 fw-bold mb-2">Lịch sử <span class="text-pp">đặt sân</span></h1>
        <p class="text-muted mb-4">Theo dõi các trận đã đặt và trạng thái xử lý của từng đơn.</p>

        <form method="GET" class="pp-card p-3 mb-4">
            <div class="row g-3 align-items-center">
                <div class="col-lg"><input class="form-control" name="keyword" value="{{ $filters['keyword'] ?? '' }}" placeholder="Tìm theo tên sân hoặc địa chỉ..."></div>
                <div class="col-lg-auto"><div class="btn-group flex-wrap" role="group">
                    @foreach([''=>'Tất cả','pending'=>'Chờ xác nhận','confirmed'=>'Đã xác nhận','completed'=>'Hoàn thành','cancelled'=>'Đã hủy'] as $value=>$label)
                        <a class="btn {{ ($filters['status'] ?? '') === $value ? 'btn-pp' : 'btn-light' }}" href="{{ route('bookings.index', array_filter(['keyword'=>$filters['keyword'] ?? null,'status'=>$value])) }}">{{ $label }}</a>
                    @endforeach
                </div></div>
            </div>
        </form>

        <div class="d-grid gap-3">
            @forelse($bookings as $booking)
                <article class="pp-card booking-card">
                    <img src="{{ $booking->footballField->main_image_url }}" alt="{{ $booking->footballField->name }}">
                    <div>
                        <div class="d-flex flex-wrap align-items-center gap-2"><h2 class="h5 fw-bold mb-0">{{ $booking->footballField->name }}</h2><x-status-badge :status="$booking->status" /></div>
                        <div class="text-muted mt-2">📅 {{ $booking->booking_date->format('d/m/Y') }}, {{ $booking->timeSlot->label }}</div>
                        <div class="fw-semibold mt-1">{{ $booking->formatted_price }} · Thanh toán tại sân</div>
                    </div>
                    <div class="booking-card-actions d-grid gap-2">
                        <a class="btn btn-pp" href="{{ route('bookings.rebook', $booking) }}">Đặt lại sân này</a>
                        <a class="btn btn-light" href="{{ route('bookings.show', $booking) }}">Xem chi tiết</a>
                    </div>
                </article>
            @empty
                <x-empty-state title="Bạn chưa có đơn đặt sân" message="Hãy chọn một sân và khung giờ phù hợp để bắt đầu."><a class="btn btn-pp mt-3" href="{{ route('fields.index') }}">Tìm sân ngay</a></x-empty-state>
            @endforelse
        </div>
        <div class="mt-4">{{ $bookings->links() }}</div>
    </div>
</section>
@endsection
