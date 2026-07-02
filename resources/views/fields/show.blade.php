@extends('layouts.app')

@section('title', $footballField->name)

@section('content')
<section class="pp-section pt-4">
    <div class="container">
        <div class="row g-3 mb-4">
            <div class="col-lg-8"><img class="gallery-main" src="{{ $footballField->images->first()?->url ?? $footballField->main_image_url }}" alt="{{ $footballField->name }}"></div>
            <div class="col-lg-4 d-grid gap-3">
                <img class="gallery-thumb" src="{{ $footballField->images->get(1)?->url ?? asset('images/field-detail.svg') }}" alt="Ảnh sân bổ sung">
                <img class="gallery-thumb" src="{{ $footballField->images->get(2)?->url ?? asset('images/field-default.svg') }}" alt="Ảnh sân bổ sung">
            </div>
        </div>

        <div class="row g-4 align-items-start">
            <div class="col-lg-7">
                <div class="text-pp small fw-bold text-uppercase">Sân bóng 7 người</div>
                <h1 class="display-6 fw-bold mt-2">{{ $footballField->name }}</h1>
                <p class="text-muted">📍 {{ $footballField->address }}</p>
                <div class="pp-card p-4 my-4 d-flex justify-content-between align-items-center"><span class="small text-uppercase text-muted fw-bold">Giá thuê theo giờ</span><strong class="display-6 text-pp">{{ number_format((float)$footballField->price_per_hour,0,',','.') }} <small class="fs-6">VND</small></strong></div>

                <h2 class="h4 fw-bold">Giới thiệu sân</h2>
                <p class="text-muted lh-lg">{{ $footballField->description ?: 'Sân bóng 7 người chất lượng cao, phù hợp luyện tập và thi đấu phong trào.' }}</p>

                <h2 class="h4 fw-bold mt-4">Tiện ích</h2>
                <div class="row g-3">
                    @forelse($footballField->amenities ?? [] as $amenity)<div class="col-6 col-md-3"><div class="amenity"><span class="amenity-icon">✓</span><span>{{ $amenity }}</span></div></div>@empty<div class="text-muted">Chưa cập nhật tiện ích.</div>@endforelse
                </div>
            </div>
            <div class="col-lg-5">
                <div class="pp-card p-4 booking-summary">
                    <h2 class="h5 fw-bold">Chọn lịch đặt sân</h2>
                    <form method="GET" action="{{ route('fields.show', $footballField) }}" class="mb-3">
                        <label class="form-label" for="booking_date">Ngày sử dụng sân</label>
                        <div class="input-group"><input type="date" min="{{ today()->format('Y-m-d') }}" class="form-control" name="booking_date" id="booking_date" value="{{ $date }}"><button class="btn btn-outline-pp">Xem lịch</button></div>
                    </form>
                    <div class="small fw-bold text-uppercase text-muted mb-2">Khung giờ còn trống</div>
                    <div class="row g-2">
                        @foreach($slots as $slot)
                            <div class="col-6">
                                @if($slot->getAttribute('available'))
                                    <a class="slot-chip d-block" href="{{ route('bookings.create', ['footballField' => $footballField, 'booking_date' => $date, 'time_slot_id' => $slot->id]) }}">{{ $slot->label }}<small class="d-block mt-1">{{ number_format($slot->getAttribute('calculated_price'),0,',','.') }} ₫</small></a>
                                @else
                                    <div class="slot-chip disabled">{{ $slot->label }}<small class="d-block mt-1">Đã đặt</small></div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    @auth
                        <a class="btn btn-pp w-100 mt-4" href="{{ route('bookings.create', ['footballField' => $footballField, 'booking_date' => $date]) }}">Đặt sân ngay →</a>
                    @else
                        <a class="btn btn-pp w-100 mt-4" href="{{ route('login') }}">Đăng nhập để đặt sân</a>
                    @endauth
                    <div class="small text-muted text-center mt-3">Hủy miễn phí trước giờ thi đấu tối thiểu {{ config('booking.cancellation_hours') }} giờ.</div>
                </div>
            </div>
        </div>
    </div>
</section>

@if($relatedFields->isNotEmpty())
<section class="pp-section bg-soft-gray"><div class="container"><h2 class="pp-section-title mb-4">Sân liên quan</h2><div class="row g-4">@foreach($relatedFields as $field)<div class="col-md-6 col-lg-3"><x-field-card :field="$field" /></div>@endforeach</div></div></section>
@endif
@endsection
