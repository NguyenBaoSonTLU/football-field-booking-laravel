@extends('layouts.app')
@section('title','Không có quyền truy cập')
@section('content')<section class="pp-section"><div class="container"><x-empty-state title="403 - Không có quyền truy cập" message="Tài khoản của bạn không được phép truy cập nội dung này."><a class="btn btn-pp mt-3" href="{{ route('home') }}">Về trang chủ</a></x-empty-state></div></section>@endsection
