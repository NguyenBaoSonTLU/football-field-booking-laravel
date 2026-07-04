@extends('layouts.admin')
@section('title','Gửi thông báo')
@section('content')
<div class="mb-4"><h1 class="pp-section-title">Gửi thông báo</h1><p class="text-muted">Gửi thông tin chung hoặc thông báo riêng cho một khách hàng.</p></div>
<form class="pp-card p-4" method="POST" action="{{ route('admin.notifications.store') }}">@csrf
    <div class="row g-3">
        <div class="col-md-4"><label class="form-label" for="target">Đối tượng nhận</label><select class="form-select" id="target" name="target"><option value="all" @selected(old('target')==='all')>Tất cả khách hàng</option><option value="user" @selected(old('target')==='user')>Một khách hàng</option></select></div>
        <div class="col-md-8"><label class="form-label" for="user_id">Khách hàng cụ thể</label><select class="form-select" id="user_id" name="user_id"><option value="">-- Chọn người dùng khi gửi riêng --</option>@foreach($users as $user)<option value="{{ $user->id }}" @selected(old('user_id')==$user->id)>{{ $user->name }} - {{ $user->email }}</option>@endforeach</select></div>
        <div class="col-12"><label class="form-label" for="title">Tiêu đề</label><input class="form-control" id="title" name="title" value="{{ old('title') }}" required></div>
        <div class="col-12"><label class="form-label" for="message">Nội dung</label><textarea class="form-control" rows="7" id="message" name="message" required>{{ old('message') }}</textarea></div>
    </div>
    <button class="btn btn-pp mt-4" type="submit">Gửi thông báo</button>
</form>
@endsection
