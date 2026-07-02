<aside class="admin-sidebar">
    <div class="mb-4">
        <a class="pp-brand" href="{{ route('admin.dashboard') }}"><span class="pp-brand-mark">⚽</span>Admin Console</a>
        <div class="small text-muted mt-2">Management Suite</div>
    </div>
    <nav class="admin-nav flex-grow-1">
        <a class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">▦ Dashboard</a>
        <a class="{{ request()->routeIs('admin.fields.*') ? 'active' : '' }}" href="{{ route('admin.fields.index') }}">⚽ Quản lý sân</a>
        <a class="{{ request()->routeIs('admin.time-slots.*') ? 'active' : '' }}" href="{{ route('admin.time-slots.index') }}">◷ Khung giờ</a>
        <a class="{{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}" href="{{ route('admin.bookings.index') }}">▣ Đơn đặt sân</a>
        <a class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">♙ Người dùng</a>
        <a class="{{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}">▥ Thống kê</a>
        <a class="{{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}" href="{{ route('admin.notifications.create') }}">🔔 Thông báo</a>
    </nav>
    <div class="mt-4 d-grid gap-2">
        <a class="btn btn-pp" href="{{ route('admin.fields.create') }}">+ Thêm sân</a>
        <a class="btn btn-light" href="{{ route('home') }}">← Về website</a>
        <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn btn-outline-secondary w-100" type="submit">Đăng xuất</button></form>
    </div>
</aside>
