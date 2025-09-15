<header class="app-header navbar border-bottom">
    <button class="navbar-toggler sidebar-toggler d-lg-none" type="button" data-toggle="sidebar-show">
        <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand d-md-down-none" href="{{ route('administration::index') }}">
        <img class="navbar-brand-full" src="{{ asset('img/logo/rounded-bw-128.png') }}" height="25" alt="">
        <small class="text-dark pl-2"><strong>ADMIN</strong></small>
        <img class="navbar-brand-minimized" src="{{ asset('img/logo/rounded-bw-128.png') }}" height="30" alt="">
    </a>
    <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button" data-toggle="sidebar-lg-show">
        <span class="navbar-toggler-icon"></span>
    </button>
    <ul class="nav navbar-nav">
        <li class="nav-item d-sm-down-none px-2">
            <span class="text-muted">{{ config('account.admin.name') }}</span>
        </li>
        <li class="nav-item d-inline d-md-none px-2">
            <img class="navbar-brand-minimized" src="{{ asset('img/logo/rounded-bw-128.png') }}" height="30" alt="">
        </li>
    </ul>
    <ul class="nav navbar-nav ml-auto">
        <li class="nav-item">
            <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                <i class="mdi mdi-bell"></i>
                {{-- <span class="badge badge-pill badge-danger">!</span> --}}
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-item">Tidak ada pemberitahuan</div>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="javascript:;" role="button" data-toggle="modal" data-target="#navbar-apps"> <i class="mdi mdi-apps"></i> </a>
            @include('web::layouts.includes.navbar-apps')
        </li>
        <li class="nav-item dropdown mr-3">
            <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                <img class="img-avatar" src="" alt="" style="height: 32px;">
            </a>
            {{-- {{{{ auth()->user()->profile->avatar_path }}}} --}}
            <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-header text-center">
                    {{ auth()->user()->name }}
                </div>
                <a class="dropdown-item" href="{{ route('account::index') }}"><i class="mdi mdi-account-outline"></i> Akun saya</a>
                <a class="dropdown-item" href="{{ route('account::user.password', ['next' => url()->full()]) }}"><i class="mdi mdi-lock-outline"></i> Ubah password</a>
                <div class="dropdown-divider"></div>
                {{-- <a class="dropdown-item" href="{{ route('account::auth.logout') }}" onclick="event.preventDefault(); $('#logout-form').submit();"><i class="mdi mdi-logout"></i> Logout</a> --}}
            </div>
        </li>
    </ul>
</header>
