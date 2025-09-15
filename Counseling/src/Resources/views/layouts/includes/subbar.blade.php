<ul class="metismenu list-unstyled" id="side-menu">
    <li class="nav-main-item"> <a class="nav-main-link" href="{{ route('counseling::home') }}"><i class="nav-icon mdi mdi-speedometer"></i> Beranda</a> </li>

    <li class="menu-title" key="t-menu">Pengelolaan</li>
    <li class="nav-main-item">
        <a class="has-arrow waves-effect" href="javascript:;"><i class="nav-icon bx bxs-detail"></i>Presensi</a>
        <ul class="sub-menu mm-collapse">
            <li>
                <a class="nav-main-link" href="{{ route('counseling::presences.index') }}"><i class="nav-icon bx bxs-duplicate"></i> presensi</a>
            </li>

            <li>
                <a class="nav-main-link" href="{{ route('counseling::presences.create') }}"><i class="nav-icon bx bxs-duplicate"></i> presensi baru</a>
            </li>
        </ul>
    </li>

    <li class="nav-main-item">
        <a class="has-arrow waves-effect" href="javascript:;"><i class="nav-icon bx bx-clipboard"></i> Kasus</a>
        <ul class="sub-menu mm-collapse">
            <li>
                <a class="nav-main-link" href="{{ route('counseling::cases.index') }}"><i class="nav-icon bx bx-list-plus"></i> Data kasus</a>
            </li>

            <li>
                <a class="nav-main-link" href="{{ route('counseling::cases.create') }}"><i class="nav-icon bx bx-list-plus"></i> Input kasus baru</a>
            </li>

            <li>
                <a class="nav-main-link" href="{{ route('counseling::manage.cases.categories.index') }}"><i class="nav-icon bx bx-list-plus"></i> Kelola kategori</a>
            </li>

            <li>
                <a class="nav-main-link" href="{{ route('counseling::manage.cases.descriptions.index') }}"><i class="nav-icon bx bx-list-plus"></i> Kelola deskripsi</a>
            </li>
        </ul>
    </li>
    <li class="nav-main-item">
        <a class="has-arrow waves-effect" href="javascript:;"><i class="nav-icon bx bx-user-voice"></i> Konseling</a>
        <ul class="sub-menu mm-collapse">
            <li>
                <a class="nav-main-link" href="{{ route('counseling::counselings.index') }}"><i class="nav-icon bx bx-user-pin"></i> Data konseling</a>
            </li>

            <li>
                <a class="nav-main-link" href="{{ route('counseling::counselings.create') }}"><i class="nav-icon bx bx-user-pin"></i> Input konseling baru</a>
            </li>

            <li>
                <a class="nav-main-link" href="{{ route('counseling::manage.counseling.categories.index') }}"><i class="nav-icon bx bx-user-pin"></i> Kelola kategori</a>
            </li>
        </ul>
    </li>

    <li class="menu-title" key="t-menu">Siswa</li>
    <li class="nav-main-item"> <a class="nav-main-link" href="{{ route('counseling::leave.manage.index') }}"><i class="nav-icon  bx bx-band-aid"></i> Perizinan</a> </li>
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
