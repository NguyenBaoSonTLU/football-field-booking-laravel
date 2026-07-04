@extends('layouts.app')
@section('title','Không tìm thấy trang')
@section('content')<section class="pp-section"><div class="container"><x-empty-state title="404 - Không tìm thấy trang" message="Đường dẫn không tồn tại hoặc dữ liệu đã ngừng hoạt động."><a class="btn btn-pp mt-3" href="{{ route('home') }}">Về trang chủ</a></x-empty-state></div></section>@endsection
