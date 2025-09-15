<nav class="navbar navbar-expand-sm navbar-light bg-white">
    <div class="container">
        <a class="navbar-brand" href="/">
            <img src="{{ asset('img/logo/rounded-bw-128.png') }}" width="30" height="30" class="mr-2">
            <small class="font-weight-bold pl-1">{{ config('teacher.navbar-brand') }}</small>
        </a>
        <ul class="navbar-nav align-items-center ml-auto flex-row">
            <li class="nav-item">
                <a class="nav-link pr-3" href="javascript:;" role="button" data-toggle="modal" data-target="#navbar-apps"> <i class="mdi mdi-apps mdi-24px"></i> </a>
                @include('web::layouts.includes.navbar-apps')
            </li>
            <li class="nav-item dropdown mr-0">
                <a class="nav-link dropdown-toggle no-caret" href="javascript:;" role="button" data-toggle="dropdown">
                    <img src="{{ auth()->user()->profile->avatar_path }}" alt="" height="32" class="rounded-circle">
                </a>
                <div class="dropdown-menu dropdown-menu-right position-absolute">
                    <a class="dropdown-item" href="{{ route('account::index') }}">Profil saya</a>
                    <a class="dropdown-item" href="{{ route('account::user.password', ['next' => url()->full()]) }}">Ubah password</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); $('#logout-form').submit();">Logout</a>
                </div>
            </li>
            <button class="navbar-toggler ml-2 border-0" type="button" data-toggle="collapse" data-target="#subbar">
                <span class="navbar-toggler-icon"></span>
            </button>
        </ul>
    </div>
</nav>
