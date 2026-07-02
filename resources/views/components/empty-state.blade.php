@props(['title' => 'Chưa có dữ liệu', 'message' => 'Không tìm thấy dữ liệu phù hợp.'])
<div class="empty-state pp-card">
    <div class="display-5 mb-3">⚽</div>
    <h3 class="h5 fw-bold">{{ $title }}</h3>
    <p class="mb-0">{{ $message }}</p>
    {{ $slot }}
</div>
