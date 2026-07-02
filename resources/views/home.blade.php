@extends('layouts.app')

@section('title', 'Trang chủ')

@section('content')
<section class="hero">
    <div class="container">
        <div class="hero-copy">
            <span class="hero-kicker">Precision Motion</span>
            <h1>Click là có sân, lăn tăn là mất chỗ<br><span>Đặt sân nhanh chóng & dễ dàng</span></h1>
            <form class="hero-search d-flex gap-2" action="{{ route('fields.index') }}" method="GET">
                <input class="form-control border-0" name="keyword" placeholder="Tìm tên sân hoặc khu vực..." aria-label="Tìm sân">
                <button class="btn btn-pp px-4" type="submit">Tìm kiếm →</button>
            </form>
        </div>
    </div>
</section>

<section class="pp-section">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div><div class="text-pp small fw-bold text-uppercase">Top rated</div><h2 class="pp-section-title mb-0">Sân bóng nổi bật</h2></div>
            <a class="fw-bold" href="{{ route('fields.index') }}">Xem tất cả →</a>
        </div>
        @if($featuredFields->isEmpty())
            <x-empty-state title="Chưa có sân bóng" message="Quản trị viên chưa thêm dữ liệu sân bóng." />
        @else
            <div class="row g-4">
                @foreach($featuredFields as $field)
                    <div class="col-md-6 col-lg-4"><x-field-card :field="$field" /></div>
                @endforeach
            </div>
        @endif
    </div>
</section>

<section id="popular-slots" class="pp-section bg-soft-gray">
    <div class="container">
        <div class="text-center mb-5"><div class="text-pp small fw-bold text-uppercase">Match day HUD</div><h2 class="pp-section-title">Khung giờ phổ biến</h2><p class="text-muted">Chọn thời gian phù hợp cho đội của bạn. Khung giờ tối thường được đặt rất nhanh.</p></div>
        <div class="row g-4">
            @php
                $groups = [
                    'Buổi sáng' => $timeSlots->filter(fn($slot) => (int) substr($slot->start_time, 0, 2) < 12),
                    'Buổi chiều' => $timeSlots->filter(fn($slot) => (int) substr($slot->start_time, 0, 2) >= 12 && (int) substr($slot->start_time, 0, 2) < 18),
                    'Buổi tối' => $timeSlots->filter(fn($slot) => (int) substr($slot->start_time, 0, 2) >= 18),
                ];
            @endphp
            @foreach($groups as $label => $slots)
                <div class="col-lg-4">
                    <div class="pp-card p-4 h-100">
                        <h3 class="h5 fw-bold mb-3">{{ $label }}</h3>
                        <div class="d-grid gap-2">
                            @forelse($slots as $slot)
                                <a href="{{ route('fields.index', ['time_slot_id' => $slot->id, 'booking_date' => today()->addDay()->format('Y-m-d')]) }}" class="d-flex justify-content-between align-items-center border-bottom py-2">
                                    <span>{{ $slot->label }}</span><span class="text-pp small fw-bold">Tra cứu</span>
                                </a>
                            @empty
                                <span class="text-muted">Chưa có khung giờ.</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section id="why-us" class="pp-section">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6"><img class="rounded-4 shadow-lg" src="{{ asset('images/field-detail.svg') }}" alt="Trải nghiệm đặt sân"></div>
            <div class="col-lg-6">
                <div class="text-pp small fw-bold text-uppercase">Why PitchPerfect?</div>
                <h2 class="pp-section-title mb-4">Trải nghiệm đặt sân chưa bao giờ dễ dàng đến thế</h2>
                <div class="d-grid gap-3">
                    <div class="d-flex gap-3"><div class="amenity-icon">✓</div><div><h3 class="h6 fw-bold mb-1">Đặt sân cấp tốc</h3><p class="text-muted mb-0">Tra cứu và tạo đơn chỉ trong vài bước rõ ràng.</p></div></div>
                    <div class="d-flex gap-3"><div class="amenity-icon">▦</div><div><h3 class="h6 fw-bold mb-1">Quản lý thông minh</h3><p class="text-muted mb-0">Theo dõi trạng thái đơn và lịch sử đặt sân tập trung.</p></div></div>
                    <div class="d-flex gap-3"><div class="amenity-icon">☎</div><div><h3 class="h6 fw-bold mb-1">Hỗ trợ 24/7</h3><p class="text-muted mb-0">Thông tin sân, khung giờ và đơn đặt được cập nhật minh bạch.</p></div></div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="pp-section bg-soft-gray">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4"><h2 class="pp-section-title mb-0">Tiếng nói từ những đội trưởng</h2><div class="text-end"><div class="h3 text-pp fw-bold mb-0">4.9/5</div><small class="text-muted">Đánh giá trải nghiệm</small></div></div>
        <div class="row g-4">
            @foreach([
                ['Anh Tuấn','PitchPerfect giúp đội mình tìm sân nhanh, không phải gọi từng sân để hỏi giờ.'],
                ['Minh Anh','Giao diện rõ ràng, thông tin sân minh bạch và quy trình đặt dễ hiểu.'],
                ['Quốc Huy','Đặt xong có thể theo dõi trạng thái ngay trong lịch sử, rất thuận tiện.']
            ] as [$name,$quote])
                <div class="col-md-4"><div class="pp-card p-4 h-100"><div class="text-warning mb-2">★★★★★</div><p>“{{ $quote }}”</p><strong>{{ $name }}</strong><div class="small text-muted">Đội trưởng bóng đá phong trào</div></div></div>
            @endforeach
        </div>
    </div>
</section>
@endsection
