@php
    use Carbon\Carbon;

    $__employee = [
        'NIP' => $employee->nip ?? '-',
        'Bergabung sejak' => $employee && $employee->entered_at ? Carbon::parse($employee->entered_at)->diffForHumans() : '-',
    ];
@endphp

<div class="card">
    <div class="card-header">
        <i class="mdi mdi-account-question-outline"></i>
        Informasi {{ $employee->name ?? '-' }}
    </div>
    <div class="card-body">
        @foreach ($__employee as $k => $v)
            <p @if ($loop->last) class="mb-0" @endif>
                {{ $k }}<br> <strong>{{ $v }}</strong>
            </p>
        @endforeach
    </div>
</div>
