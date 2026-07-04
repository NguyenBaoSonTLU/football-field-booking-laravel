@props(['field'])
<article class="pp-card field-card position-relative">
    <span class="field-status">Còn trống</span>
    <img src="{{ $field->main_image_url }}" alt="{{ $field->name }}">
    <div class="card-body">
        <div class="d-flex justify-content-between gap-3">
            <h3 class="h5 fw-bold mb-1">{{ $field->name }}</h3>
            <span class="text-pp small fw-bold">7 người</span>
        </div>
        <p class="text-muted small mb-3">📍 {{ $field->address }}</p>
        <div class="small text-uppercase text-muted">Giá thuê theo giờ</div>
        <div class="field-price mb-3">{{ $field->formatted_price }}</div>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-pp flex-fill" href="{{ route('fields.show', $field) }}">Xem chi tiết</a>
            <a class="btn btn-pp flex-fill" href="{{ route('bookings.create', $field) }}">Đặt sân</a>
        </div>
    </div>
</article>
