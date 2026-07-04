@extends('layouts.admin')
@section('title','Thêm sân bóng')
@section('content')<h1 class="pp-section-title">Thêm sân bóng</h1><p class="text-muted">Nhập thông tin sân, giá, thời gian hoạt động và hình ảnh.</p><form class="pp-card p-4" method="POST" action="{{ route('admin.fields.store') }}" enctype="multipart/form-data">@csrf @include('admin.fields._form',['submitLabel'=>'Tạo sân'])</form>@endsection
