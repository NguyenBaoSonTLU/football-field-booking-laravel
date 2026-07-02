@extends('layouts.admin')
@section('title','Thống kê và báo cáo')
@section('content')
<div class="mb-4"><h1 class="pp-section-title">Thống kê và báo cáo</h1><p class="text-muted">Theo dõi số lượng đơn và doanh thu dự kiến theo khoảng thời gian.</p></div>
<form class="pp-card p-3 mb-4" method="GET"><div class="row g-2 align-items-end"><div class="col-md-4"><label class="form-label">Từ ngày</label><input class="form-control" type="date" name="from" value="{{ $from->format('Y-m-d') }}"></div><div class="col-md-4"><label class="form-label">Đến ngày</label><input class="form-control" type="date" name="to" value="{{ $to->format('Y-m-d') }}"></div><div class="col-md-auto"><button class="btn btn-pp">Xem báo cáo</button></div></div></form>
<div class="row g-4 mb-4">
    @foreach([['Tổng đơn',$summary['total']],['Đã xác nhận',$summary['confirmed']],['Hoàn thành',$summary['completed']],['Đã hủy',$summary['cancelled']],['Doanh thu',number_format($summary['revenue'],0,',','.').' ₫']] as [$label,$value])
        <div class="col-sm-6 col-xl"><div class="pp-card stat-card h-100"><div class="small text-muted text-uppercase">{{ $label }}</div><div class="stat-value">{{ $value }}</div></div></div>
    @endforeach
</div>
<div class="pp-card overflow-hidden"><div class="p-4"><h2 class="h5 fw-bold mb-0">Hiệu quả theo sân</h2></div><div class="table-responsive"><table class="table table-pp mb-0"><thead><tr><th>Sân bóng</th><th>Số đơn</th><th>Doanh thu</th></tr></thead><tbody>@forelse($byField as $row)<tr><td>{{ $row->footballField?->name ?? 'Sân đã ngừng' }}</td><td>{{ $row->total_bookings }}</td><td>{{ number_format((float)$row->revenue,0,',','.') }} ₫</td></tr>@empty<tr><td colspan="3" class="text-center py-4">Chưa có dữ liệu trong khoảng thời gian này.</td></tr>@endforelse</tbody></table></div></div>
@endsection
