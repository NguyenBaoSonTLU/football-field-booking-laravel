@extends('layouts.admin')
@section('title','Sửa '.$field->name)
@section('content')<h1 class="pp-section-title">Chỉnh sửa sân bóng</h1><p class="text-muted">{{ $field->name }}</p><form class="pp-card p-4" method="POST" action="{{ route('admin.fields.update',$field) }}" enctype="multipart/form-data">@csrf @method('PUT') @include('admin.fields._form',['submitLabel'=>'Lưu thay đổi'])</form>@endsection
