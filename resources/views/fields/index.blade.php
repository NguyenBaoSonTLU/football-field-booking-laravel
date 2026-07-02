@extends('layouts.app')

@section('title', 'Danh sách sân bóng')

@section('content')
<section class="pp-section pt-4">
    <div class="container-fluid px-lg-5">
        <div class="row g-4">
            <aside class="col-lg-3 col-xl-2">
                <form class="pp-card p-4 filters-panel" method="GET" action="{{ route('fields.index') }}">
                    <div class="text-pp small fw-bold text-uppercase mb-3">Bộ lọc thông minh</div>
                    <label class="form-label fw-semibold" for="keyword">Tên sân hoặc khu vực</label>
                    <input class="form-control mb-3" id="keyword" name="keyword" value="{{ $filters['keyword'] ?? '' }}" placeholder="Ví dụ: Tây Hồ">

                    <label class="form-label fw-semibold">Khoảng giá (VND/giờ)</label>
                    <div class="row g-2 mb-3"><div class="col"><input type="number" class="form-control" name="min_price" value="{{ $filters['min_price'] ?? '' }}" placeholder="Từ"></div><div class="col"><input type="number" class="form-control" name="max_price" value="{{ $filters['max_price'] ?? '' }}" placeholder="Đến"></div></div>

                    <label class="form-label fw-semibold" for="booking_date">Ngày cần đặt</label>
                    <input type="date" class="form-control mb-3" id="booking_date" name="booking_date" min="{{ today()->format('Y-m-d') }}" value="{{ $filters['booking_date'] ?? '' }}">

                    <label class="form-label fw-semibold" for="time_slot_id">Khung giờ</label>
                    <select class="form-select mb-4" id="time_slot_id" name="time_slot_id">
                        <option value="">Tất cả khung giờ</option>
                        @foreach($timeSlots as $slot)<option value="{{ $slot->id }}" @selected(($filters['time_slot_id'] ?? null) == $slot->id)>{{ $slot->label }}</option>@endforeach
                    </select>
                    <button class="btn btn-pp w-100" type="submit">Áp dụng bộ lọc</button>
                    <a class="btn btn-light w-100 mt-2" href="{{ route('fields.index') }}">Xóa bộ lọc</a>
                </form>
            </aside>
            <div class="col-lg-9 col-xl-10">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                    <div><h1 class="pp-section-title mb-1">Tìm sân phù hợp</h1><p class="text-muted mb-0">Danh sách sân bóng 7 người đang hoạt động.</p></div>
                    <span class="badge text-bg-light p-3">{{ $fields->total() }} sân được tìm thấy</span>
                </div>
                @if($fields->isEmpty())
                    <x-empty-state title="Không tìm thấy sân" message="Hãy thay đổi từ khóa hoặc bộ lọc để xem kết quả khác."><a class="btn btn-pp mt-3" href="{{ route('fields.index') }}">Xem tất cả sân</a></x-empty-state>
                @else
                    <div class="row g-4">
                        @foreach($fields as $field)<div class="col-md-6 col-xl-4"><x-field-card :field="$field" /></div>@endforeach
                    </div>
                    <div class="mt-4">{{ $fields->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
