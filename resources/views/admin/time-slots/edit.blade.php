@extends('layouts.admin')
@section('title','Sửa khung giờ')
@section('content')<h1 class="pp-section-title">Chỉnh sửa khung giờ</h1><p class="text-muted">{{ $timeSlot->label }}</p><form class="pp-card p-4" method="POST" action="{{ route('admin.time-slots.update',$timeSlot) }}">@csrf @method('PUT') @include('admin.time-slots._form',['submitLabel'=>'Lưu thay đổi'])</form>@endsection
