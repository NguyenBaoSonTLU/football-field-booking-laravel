@extends('layouts.admin')
@section('title','Thêm khung giờ')
@section('content')<h1 class="pp-section-title">Thêm khung giờ</h1><p class="text-muted">Giờ kết thúc phải sau giờ bắt đầu.</p><form class="pp-card p-4" method="POST" action="{{ route('admin.time-slots.store') }}">@csrf @include('admin.time-slots._form',['submitLabel'=>'Tạo khung giờ'])</form>@endsection
