<nav class="navbar navbar-expand-lg pp-navbar">
    <div class="container">
        <a class="navbar-brand pp-brand" href="{{ route('home') }}"><span class="pp-brand-mark">⚽</span>PitchPerfect</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Mở menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div id="mainNav" class="collapse navbar-collapse">
            <ul class="navbar-nav mx-auto gap-lg-2">
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Trang chủ</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('fields.*') ? 'active' : '' }}" href="{{ route('fields.index') }}">Sân bóng</a></li>
                @auth
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('bookings.*') ? 'active' : '' }}" href="{{ route('bookings.index') }}">Lịch sử</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('profile.edit') }}">Tài khoản</a></li>
                @endauth
            </ul>
            <div class="d-flex align-items-center gap-2 mt-3 mt-lg-0">
                @guest
                    <a class="btn btn-link text-dark fw-semibold" href="{{ route('login') }}">Đăng nhập</a>
                    <a class="btn btn-pp px-4" href="{{ route('register') }}">Đăng ký</a>
                @else
                    <a class="btn btn-light position-relative" href="{{ route('notifications.index') }}" aria-label="Thông báo">
                        🔔
                        @if(auth()->user()->unreadNotifications()->count() > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{ auth()->user()->unreadNotifications()->count() }}</span>
                        @endif
                    </a>
                    @if(auth()->user()->isAdmin())
                        <a class="btn btn-outline-pp" href="{{ route('admin.dashboard') }}">Quản trị</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn btn-light" type="submit">Đăng xuất</button></form>
                @endguest
            </div>
        </div>
    </div>
</nav>
