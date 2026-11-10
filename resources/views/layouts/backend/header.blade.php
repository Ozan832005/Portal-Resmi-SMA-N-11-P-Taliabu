<nav class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-light navbar-shadow container-xxl">
    <div class="navbar-container d-flex content">
        <button class="nav-link menu-toggle km-backend-menu-toggle" type="button" aria-label="Buka atau tutup menu navigasi" aria-controls="main-menu-sidebar" title="Buka atau tutup menu navigasi">
            <i data-feather="menu" aria-hidden="true"></i>
        </button>

        <ul class="nav navbar-nav align-items-center ml-auto">

            <li class="nav-item">
                <a class="nav-link nav-link-style km-theme-toggle" href="javascript:void(0);" role="button"
                   aria-label="Aktifkan mode gelap" aria-pressed="false" title="Ganti tema">
                    <i class="ficon" data-feather="moon" aria-hidden="true"></i>
                    <span class="km-theme-toggle__label">Mode gelap</span>
                </a>
            </li>

            @php
                $headerNotifications = collect([
                    ['message' => session('success'), 'type' => 'success'],
                    ['message' => session('error'), 'type' => 'error'],
                ])->filter(fn (array $notification): bool => filled($notification['message']));
            @endphp
            <li class="nav-item dropdown dropdown-notification km-notification">
                <button
                    class="nav-link km-notification__toggle"
                    id="header-notifications"
                    type="button"
                    data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                    aria-label="Pemberitahuan{{ $headerNotifications->isNotEmpty() ? ', '.$headerNotifications->count().' baru' : '' }}"
                >
                    <i data-feather="bell" aria-hidden="true"></i>
                    <span class="km-notification__label">Pemberitahuan</span>
                    @if ($headerNotifications->isNotEmpty())
                        <span class="km-notification__badge" aria-hidden="true">{{ $headerNotifications->count() }}</span>
                    @endif
                </button>
                <div class="dropdown-menu dropdown-menu-right km-notification__menu" aria-labelledby="header-notifications">
                    <div class="km-notification__header">
                        <div>
                            <strong>Pemberitahuan</strong>
                            <span>{{ $headerNotifications->isNotEmpty() ? 'Informasi terbaru untuk Anda' : 'Pembaruan aktivitas akun' }}</span>
                        </div>
                        @if ($headerNotifications->isNotEmpty())
                            <span class="km-notification__count">{{ $headerNotifications->count() }} baru</span>
                        @endif
                    </div>

                    @forelse ($headerNotifications as $notification)
                        <div class="km-notification__item km-notification__item--{{ $notification['type'] }}" role="status">
                            <span class="km-notification__item-icon" aria-hidden="true">
                                <i data-feather="{{ $notification['type'] === 'success' ? 'check' : 'alert-circle' }}"></i>
                            </span>
                            <div class="km-notification__item-body">
                                <strong>{{ $notification['type'] === 'success' ? 'Berhasil' : 'Perlu perhatian' }}</strong>
                                <p>{{ $notification['message'] }}</p>
                                <small>Baru saja</small>
                            </div>
                        </div>
                    @empty
                        <div class="km-notification__empty">
                            <span class="km-notification__empty-icon" aria-hidden="true">
                                <i data-feather="bell-off"></i>
                            </span>
                            <strong>Belum ada pemberitahuan</strong>
                            <p>Pembaruan penting akan muncul di sini.</p>
                        </div>
                    @endforelse
                </div>
            </li>

            <li class="nav-item dropdown dropdown-user">
                <a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="user-nav d-sm-flex d-none">
                        <span class="user-name font-weight-bolder">{{Auth::user()->name}}</span>
                        <span class="user-status">{{Auth::user()->role}}</span>
                    </div>
                    <span class="avatar">
                        @if (Auth::user()->foto_profile == NULL)
                            <img class="round" src="{{asset('Assets/Backend/images/user.png')}}" alt="avatar" height="40" width="40">
                        @else
                            <img class="round" src="{{asset('storage/images/profile/' .Auth::user()->foto_profile)}}" alt="avatar" height="40" width="40">
                        @endif
                        <span class="avatar-status-online"></span>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-user">
                    <a class="dropdown-item" href="{{route('profile-settings.index')}}"><i class="mr-50" data-feather="user"></i> Profile</a>
                    @role('Admin')
                    <a class="dropdown-item" href="{{route('settings')}}"><i class="mr-50" data-feather="settings"></i> Settings</a>
                    @endrole
                    <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                         <i class="mr-50" data-feather="power"></i> Logout
                          <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                              @csrf
                          </form>
                        </a>
                </div>
            </li>
        </ul>
    </div>
</nav>