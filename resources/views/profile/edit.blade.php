@extends('layouts.app')

@section('title', 'Hồ sơ cá nhân')

@section('content')
<section class="pp-section pt-4">
    <div class="container">
        <div class="row g-4">
            <aside class="col-lg-3">
                <div class="pp-card p-3 profile-sidebar">
                    <div class="small text-muted text-uppercase fw-bold px-2 mb-2">Cài đặt tài khoản</div>
                    <nav class="nav flex-column"><a class="nav-link active" href="{{ route('profile.edit') }}">👤 Hồ sơ</a><a class="nav-link" href="{{ route('profile.password') }}">🔒 Bảo mật</a><a class="nav-link" href="{{ route('notifications.index') }}">🔔 Thông báo</a></nav>
                </div>
            </aside>
            <div class="col-lg-9">
                <h1 class="pp-section-title">Thông tin cá nhân</h1><p class="text-muted">Cập nhật ảnh đại diện và thông tin liên hệ.</p>
                <form class="pp-card p-4 p-lg-5" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">@csrf @method('PATCH')
                    <div class="d-flex flex-wrap align-items-center gap-4 mb-4"><img class="avatar-lg" src="{{ auth()->user()->avatar_url }}" alt="Ảnh đại diện"><div><h2 class="h5 fw-bold">Ảnh đại diện</h2><p class="text-muted small">Hỗ trợ JPG, PNG và WebP, tối đa 2MB.</p><input class="form-control" type="file" name="avatar" accept="image/jpeg,image/png,image/webp"><div class="form-check mt-2"><input class="form-check-input" type="checkbox" name="remove_avatar" value="1" id="remove_avatar"><label class="form-check-label" for="remove_avatar">Xóa ảnh hiện tại</label></div></div></div>
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label" for="name">Họ và tên</label><input class="form-control" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required></div>
                        <div class="col-md-6"><label class="form-label" for="email">Email</label><input class="form-control" type="email" id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required></div>
                        <div class="col-md-6"><label class="form-label" for="phone">Số điện thoại</label><input class="form-control" id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}" required></div>
                        <div class="col-12"><label class="form-label" for="address">Địa chỉ</label><input class="form-control" id="address" name="address" value="{{ old('address', auth()->user()->address) }}"></div>
                    </div>
                    <div class="d-flex gap-2 mt-4"><button class="btn btn-pp px-4" type="submit">Lưu thay đổi</button><a class="btn btn-light" href="{{ route('profile.password') }}">Đổi mật khẩu</a></div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
