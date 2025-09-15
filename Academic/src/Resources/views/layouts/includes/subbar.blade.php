<ul class="metismenu list-unstyled" id="side-menu">
    <li class="nav-main-item"> <a class="nav-main-link" href="{{ route('academic::home') }}"><i class="nav-icon mdi mdi-speedometer"></i> Beranda</a> </li>
    

    <li class="menu-title">
        Kelas dan Asrama
    </li>

    <li class="nav-main-item"> <a class="nav-main-link" href="{{ route('academic::classroom.index') }}"><i class="nav-icon bx bx-dock-bottom"></i> Kelas Saya</a> </li>
    <li class="nav-main-item"> <a class="nav-main-link" href="{{ route('academic::boarding.index') }}"><i class="nav-icon bx bxs-building-house"></i> Asrama</a> </li>

    @if (session('login_as_nik'))
        <li class="menu-title" key="t-menu">Pengelolaan</li>
        <li class="nav-main-item"> <a class="nav-main-link" href="{{ route('academic::leave.manage.index') }}"><i class="nav-icon  bx bx-band-aid"></i> Perizinan</a> </li>
    @endif

    <li class="menu-title" key="t-menu">Laporan</li>

    <li class="nav-main-item"> <a class="nav-main-link" href="{{ route('academic::activity.index') }}"><i class="nav-icon bx bx-run"></i> Aktivitas</a></li>
    <li class="nav-main-item"> <a class="nav-main-link" href="{{ route('academic::counselings.index') }}"><i class="nav-icon bx bxs-user-voice"></i> Konseling</a> </li>
    <li class="nav-main-item"> <a class="nav-main-link" href="{{ route('academic::report') }}"><i class="nav-icon bx bxs-report"></i> Raport</a> </li>
</ul>

@push('script')
    <script>
        $(() => {
            let u = window.location.href;
            $('#subbar').find('a').each((i, e) => {
                if (u.includes($(e).attr('href'))) {
                    $(e).hasClass('dropdown-item') ?
                        $(e).parents('.dropdown').find('.dropdown-toggle').addClass('text-white') :
                        $(e).addClass('text-white');
                }
            })
        })
    </script>
@endpush
