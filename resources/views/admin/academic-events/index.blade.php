@extends('adminlte::page')

@section('title', 'Kalender Akademik')

@section('content_header')
    <h1>Kalender Akademik</h1>
@stop

@section('content')
    <livewire:admin.academic-event.actions />
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div x-data="{ calendar: null }" x-init="const calendarEl = document.getElementById('calendar');
    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
        },
        events: '{{ route('api.academic-events.feed') }}',
        eventClick: function(info) {
            info.jsEvent.preventDefault();
            // Hanya kirim satu sinyal sederhana ke Livewire berisi ID event
            Livewire.dispatch('show-event-detail', { eventId: info.event.id });
        }
    });
    calendar.render();" @academic-event-updated.window="calendar.refetchEvents()">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Kegiatan Akademik</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.academic-events.create') }}" class="btn btn-primary btn-sm"
                        wire:navigate>Tambah Event Baru</a>
                </div>
            </div>
            <div class="card-body">
                {{-- {{ $dataTable->table() }} --}}
                <div id="calendar"></div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="eventDetailModal" tabindex="-1" role="dialog" aria-labelledby="eventDetailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventDetailModalLabel">Detail Kegiatan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h6 id="eventTitle"></h6>
                    <p><strong>Tanggal:</strong> <span id="eventDate"></span></p>
                    <p><strong>Deskripsi:</strong><br><span id="eventDescription"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    {{-- Tombol Edit diletakkan di sini, link-nya akan diisi oleh JavaScript --}}
                    <a id="editEventButton" href="#" class="btn btn-primary" wire:navigate>Edit Event</a>
                    <button type="button" id="deleteEventButton" class="btn btn-danger">Hapus</button>
                </div>
            </div>
        </div>
    </div>
@stop

{{-- @push('js')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush --}}
{{-- @push('js')
<script>
    let calendar;
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        
        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth', // Tampilan per bulan
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listWeek'
            },
            events: '{{ route('api.academic-events.feed') }}', // Ambil data dari API kita

            // (Opsional) Aksi saat event di-klik
            eventClick: function(info) {
                // Mencegah browser mengikuti link (jika ada)
                info.jsEvent.preventDefault(); 
                
                // Ambil data dari event yang di-klik
                let event = info.event;
                
                // Buat URL untuk tombol edit
                // Kita perlu mengambil ID event. Kita akan modifikasi API kita sedikit.
                let editUrl = `/admin/academic-events/${event.id}/edit`;

                // Format tanggal untuk ditampilkan
                let startDate = event.start.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                let endDate = event.end ? new Date(event.end.valueOf() - 1).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) : null;
                let dateString = startDate;
                if (endDate && startDate !== endDate) {
                    dateString = `${startDate} - ${endDate}`;
                }

                // Isi konten modal dengan data event
                document.getElementById('eventTitle').innerText = event.title;
                document.getElementById('eventDate').innerText = dateString;
                document.getElementById('eventDescription').innerText = event.extendedProps.description || 'Tidak ada deskripsi.';
                document.getElementById('editEventButton').href = editUrl;

                // Tampilkan modal
                $('#eventDetailModal').modal('show');
            }
        });
        calendar.render();
    });
</script>
@endpush --}}
