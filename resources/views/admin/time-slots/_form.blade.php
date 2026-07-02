@php($slot = $timeSlot ?? null)
<div class="row g-3">
    <div class="col-md-4"><label class="form-label" for="start_time">Giờ bắt đầu</label><input class="form-control" type="time" id="start_time" name="start_time" value="{{ old('start_time',$slot ? substr($slot->start_time,0,5) : '') }}" required></div>
    <div class="col-md-4"><label class="form-label" for="end_time">Giờ kết thúc</label><input class="form-control" type="time" id="end_time" name="end_time" value="{{ old('end_time',$slot ? substr($slot->end_time,0,5) : '') }}" required></div>
    <div class="col-md-4"><label class="form-label" for="status">Trạng thái</label><select class="form-select" name="status" id="status">@foreach($statuses as $status)<option value="{{ $status->value }}" @selected(old('status',$slot?->status?->value ?? 'active')===$status->value)>{{ $status->label() }}</option>@endforeach</select></div>
</div><div class="mt-4 d-flex gap-2"><button class="btn btn-pp" type="submit">{{ $submitLabel }}</button><a class="btn btn-light" href="{{ route('admin.time-slots.index') }}">Hủy</a></div>
