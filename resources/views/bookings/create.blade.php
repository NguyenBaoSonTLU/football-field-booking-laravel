@extends('layouts.app')

@section('title', 'Đặt sân '.$footballField->name)

@section('content')
<section class="pp-section pt-4" data-booking-picker data-availability-url="{{ route('fields.availability', $footballField) }}">
    <div class="container">
        <div class="pp-card overflow-hidden mb-4">
            <div class="row g-0 align-items-stretch">
                <div class="col-lg-8 p-4 p-lg-5">
                    <div class="text-pp small fw-bold text-uppercase">Sẵn sàng đặt sân</div>
                    <h1 class="display-6 fw-bold">{{ $footballField->name }}</h1>
                    <p class="text-muted mb-0">{{ $footballField->description }}</p>
                    <div class="d-flex flex-wrap gap-2 mt-3"><span class="badge text-bg-light p-2">📍 {{ $footballField->address }}</span><span class="badge text-bg-light p-2">⚽ Sân 7 người</span></div>
                </div>
                <div class="col-lg-4"><img class="w-100 h-100" style="object-fit:cover;min-height:220px" src="{{ $footballField->main_image_url }}" alt="{{ $footballField->name }}"></div>
            </div>
        </div>

        <form method="POST" action="{{ route('bookings.store') }}" class="booking-layout">
            @csrf
            <input type="hidden" name="football_field_id" value="{{ $footballField->id }}">
            <input type="hidden" name="time_slot_id" value="{{ old('time_slot_id', $selectedSlotId) }}">

            <div class="d-grid gap-4">
                <section>
                    <h2 class="h5 fw-bold"><span class="step-number">1</span>Chọn ngày</h2>
                    <div class="pp-card p-4 mt-3"><input data-booking-date type="date" class="form-control" name="booking_date" value="{{ old('booking_date', $date) }}" min="{{ today()->format('Y-m-d') }}" required></div>
                </section>

                <section>
                    <h2 class="h5 fw-bold"><span class="step-number">2</span>Chọn khung giờ</h2>
                    <div class="pp-card p-4 mt-3"><div data-slots class="row g-3">
                        @foreach($slots as $slot)
                            @php($isActive = old('time_slot_id', $selectedSlotId) == $slot->id)
                            <div class="col-6 col-md-3">
                                <button type="button" data-slot data-slot-id="{{ $slot->id }}" data-label="{{ $slot->label }}" data-price="{{ $slot->getAttribute('calculated_price') }}" class="slot-chip w-100 {{ $isActive ? 'active' : '' }} {{ $slot->getAttribute('available') ? '' : 'disabled' }}" @disabled(!$slot->getAttribute('available'))>
                                    {{ $slot->label }}
                                    <small class="d-block mt-1">{{ $slot->getAttribute('available') ? number_format($slot->getAttribute('calculated_price'),0,',','.').' ₫' : 'Đã có người đặt' }}</small>
                                </button>
                            </div>
                        @endforeach
                    </div></div>
                </section>

                <section>
                    <h2 class="h5 fw-bold"><span class="step-number">3</span>Thông tin liên hệ</h2>
                    <div class="pp-card p-4 mt-3">
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label">Họ tên</label><input class="form-control" value="{{ auth()->user()->name }}" disabled></div>
                            <div class="col-md-6"><label class="form-label">Số điện thoại</label><input class="form-control" value="{{ auth()->user()->phone }}" disabled></div>
                            <div class="col-12"><label class="form-label">Email</label><input class="form-control" value="{{ auth()->user()->email }}" disabled></div>
                            <div class="col-12"><label class="form-label" for="note">Ghi chú</label><textarea class="form-control" name="note" id="note" rows="4" maxlength="1000" placeholder="Ví dụ: đội đến sớm 15 phút...">{{ old('note') }}</textarea></div>
                        </div>
                    </div>
                </section>
            </div>

            <aside class="booking-summary">
                <div class="pp-card p-4">
                    <h2 class="h5 fw-bold mb-4">Thông tin đơn đặt</h2>
                    <div class="small text-muted text-uppercase fw-bold">Tên sân</div><div class="fw-bold text-pp mb-3">{{ $footballField->name }}</div>
                    <div class="small text-muted text-uppercase fw-bold">Ngày đặt</div><div data-summary-date class="fw-semibold mb-3">{{ \Carbon\Carbon::parse(old('booking_date', $date))->format('d/m/Y') }}</div>
                    <div class="small text-muted text-uppercase fw-bold">Khung giờ</div><div data-summary-time class="fw-semibold mb-3">{{ $slots->firstWhere('id', old('time_slot_id', $selectedSlotId))?->label ?? 'Chưa chọn' }}</div>
                    <hr>
                    @php($selectedSlot = $slots->firstWhere('id', old('time_slot_id', $selectedSlotId)))
                    <div class="d-flex justify-content-between align-items-end"><div><div class="small text-muted text-uppercase fw-bold">Tổng tiền</div><div data-summary-price class="h3 fw-bold mb-0">{{ $selectedSlot ? number_format($selectedSlot->getAttribute('calculated_price'),0,',','.').' ₫' : '0 ₫' }}</div></div><small class="text-muted">Thanh toán tại sân</small></div>
                    <button class="btn btn-pp w-100 mt-4 py-3" type="submit">Xác nhận đặt sân</button>
                    <p class="small text-muted text-center mt-3 mb-0">Bằng việc xác nhận, bạn đồng ý với chính sách đặt và hủy sân.</p>
                </div>
                <div class="alert alert-success mt-3 mb-0"><strong>Cần hỗ trợ?</strong><br><small>Liên hệ 1900 6789 để được hỗ trợ về lịch đặt.</small></div>
            </aside>
        </form>
    </div>
</section>
@endsection
