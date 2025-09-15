@extends('academic::layouts.default')

@section('title', 'Notifikasi | ')

@php($notifications = Auth::user()->notifications()->paginate(request('limit', 10)))
@php($grouped = $notifications->groupBy(fn($n) => \Carbon\Carbon::parse($n->created_at)->ISOFormat('MMMM YYYY')))

@section('content')
    <div class="card card-body d-flex flex-sm-row align-items-center gap-sm-4 rounded border-0 p-4" style="background: linear-gradient(to bottom, #fcf1f1, transparent);">
        <i class="mdi mdi-bell-outline mdi-48px text-danger mx-2"></i>
        <div class="text-sm-start text-center">
            <h2 class="mb-1">Notifications</h2>
            <div class="text-muted">Shows activity and information related to yout account.</div>
        </div>
    </div>
    <div class="card mb-4 border-0 bg-light">
        <div class="card-body">
            @forelse($grouped as $day => $_notifications)
                <p class="fw-bold">{{ $day }}</p>
                <div class="list-group mb-4">
                    @foreach ($_notifications as $notification)
                        <a class="list-group-item list-group-item-action d-flex align-items-center @if (!$notification->read_at) bg-light @else bg-white @endif py-3" href="{{ isset($notification->data['link']) ? route('account::notifications.read', ['id' => $notification->id, 'next' => $notification->data['link'] ?? null]) : 'javascript:;' }}">
                            <div class="me-3">
                                @if (!$notification->read_at)
                                    <span class="float-end bg-primary rounded-circle ms-n3 border" style="width: 12px; height: 12px;"></span>
                                @endif
                                <div class="rounded-circle d-flex align-items-center justify-content-center bg-{{ $notification->data['color'] ?? 'primary' }}" style="width: 2.5rem; height: 2.5rem;">
                                    <i class="{{ $notification->data['icon'] ?? 'mdi mdi-bell-outline' }} m-0 text-white"></i>
                                </div>
                            </div>
                            <div>
                                <span class="text-wrap text-dark">{!! $notification->data['message'] !!}</span>
                                <div class="small text-gray-600">{{ optional($notification->created_at)->diffForHumans() }}</div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @empty
                <div class="py-5 text-center">
                    <div class="mb-5">
                        <img class="img-fluid" src="{{ asset('img/undraw/undraw_Done_re_oak4.svg') }}" alt="" style="max-height: 240px;">
                    </div>
                    <div class="text-muted lead">There are no notifications here, have a nice day!</div>
                </div>
            @endforelse
            @if (count($grouped))
                <p class="text-muted">Showing {{ $notifications->firstItem() }} - {{ $notifications->lastItem() }} from {{ $notifications->total() }} items</p>
                {{ $notifications->appends(request()->all())->links() }}
            @endif
        </div>
    </div>
@endsection
