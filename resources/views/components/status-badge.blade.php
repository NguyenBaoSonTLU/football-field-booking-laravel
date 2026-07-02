@props(['status'])
@php($enum = $status instanceof \App\Enums\BookingStatus ? $status : \App\Enums\BookingStatus::from($status))
<span {{ $attributes->class(['status-badge', $enum->cssClass()]) }}>{{ $enum->label() }}</span>
