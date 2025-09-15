@extends('layouts.dompdf')

@section('title', $title)

@section('content')
    <section>
        <div class="center" style="position: absolute; top: 0; right: 0; width: 80px;">
            @include('x-docs::qr', ['qr' => $document->qr, 'link' => route('docs::verify', ['qr' => $document->qr]), 'withText' => true])
        </div>

        <p>Sleman, {{ strftime('%d %B %Y') }}</p>
        <p>Kepada Yth.,</p>
        <p class="bold">
            @isset($to)
                {{ $to->employee->user->name }} <br>
                {{ $to->position->name }} <br>
                @endif
                PT PEMAD INTERNATIONAL TRANSEARCH
            </p>
            <p>Dengan hormat, <br> Saya yang bertanda tangan di bawah ini:</p>
            <table width="100%">
                @foreach ([
                'Nama Karyawan' => $leave->employee->user->name,
                'Nomor Induk Karyawan' => $leave->employee->kd,
                'Jabatan' => $leave->employee->position->position->name,
                'Departemen' => $leave->employee->position->position->department->name,
                'Atasan' => $leave->employee->position->position->parents->last()?->employees->first()->user->name ?? '-',
            ] as $key => $value)
                    <tr>
                        <td width="28%">{{ $key }}</td>
                        <td width="2%">:</td>
                        <td>{{ $value }}</td>
                    </tr>
                @endforeach
            </table>
            <p>Dengan ini mengajukan permohonan izin sebagai berikut:</p>
            <table width="100%">
                @foreach (array_filter([
                'Waktu pengajuan' => $leave->created_at->isoFormat('LLLL'),
                'Kategori izin' => $leave->category->name,
                'Tanggal izin' => $leave->dates->map(fn($date) => strftime('%d %B %Y', strtotime($date['d'])) . (isset($date['t_s']) ? ' pukul ' . $date['t_s'] : '') . (isset($date['t_e']) ? ' s.d. ' . $date['t_e'] : ''))->join(', '),
                'Alasan izin' => $leave->description ?: '-',
            ]) as $key => $value)
                    <tr>
                        <td width="28%">{{ $key }}</td>
                        <td width="2%">:</td>
                        <td>{{ $value }}</td>
                    </tr>
                @endforeach
            </table>
            <p>Demikian surat permohonan ini saya sampaikan, atas perhatiannya saya mengucapkan terima kasih.</p>
            <br>
            @php
                $signatures = [
                    [
                        'position' => 'Karyawan',
                        'qr' => $document->signatures->firstWhere('user_id', $leave->employee->user->id)?->qr,
                        'name' => $leave->employee->user->name,
                    ],
                ];
                
                foreach ($leave->approvables as $approvable) {
                    array_push($signatures, [
                        'position' => $approvable->userable->position->level->label(),
                        'qr' => $document->signatures->firstWhere('user_id', $approvable->userable->employee->user->id)?->qr,
                        'name' => $approvable->userable->employee->user->name,
                    ]);
                }
            @endphp
            <table width="100%">
                <tr>
                    @foreach ($signatures as $value)
                        <td class="center" width="{{ round(100 / count($signatures), 2) }}%">
                            <p>{{ $value['position'] }}</p>
                            <div style="height: 90px;">
                                @include('x-docs::qr', ['qr' => $value['qr'], 'link' => route('docs::verify', ['qr' => $value['qr'], 'type' => 'signature']), 'small' => true, 'withText' => true])
                            </div>
                            <p class="bold">{{ $value['name'] }}</p>
                        </td>
                    @endforeach
                </tr>
            </table>
        </section>
    @endsection
