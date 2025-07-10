@extends('adminlte::page')
@section('title', 'Semua Notifikasi')
@section('content_header')
    <h1>Semua Notifikasi</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Riwayat Notifikasi</h3>
            <div class="card-tools">
                <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-primary">Tandai Semua Sudah Dibaca</button>
                </form>
            </div>
        </div>
        <div class="card-body p-0">
            <ul class="list-group list-group-flush">
                @forelse($notifications as $notification)
                    <li class="list-group-item {{ $notification->read_at ? '' : 'bg-light font-weight-bold' }}">
                        <div class="d-flex w-100 justify-content-between">
                            <p class="mb-1">
                                <i class="fas fa-info-circle mr-2"></i>
                                {{ $notification->data['message'] }}
                            </p>
                            <small>{{ $notification->created_at->diffForHumans() }}</small>
                        </div>
                        @if(!$notification->read_at)
                            <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-xs btn-link p-0">Tandai sudah dibaca</button>
                            </form>
                        @endif
                    </li>
                @empty
                    <li class="list-group-item text-center">Tidak ada notifikasi.</li>
                @endforelse
            </ul>
        </div>
        <div class="card-footer">
            {{ $notifications->links() }}
        </div>
    </div>
@stop