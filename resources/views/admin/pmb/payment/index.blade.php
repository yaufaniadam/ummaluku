@extends('adminlte::page')
@section('title', 'Verifikasi Pembayaran')
@section('content_header')
    <h1>Verifikasi Pembayaran Registrasi Ulang</h1>
@stop
@section('content')

    <div class="card mb-4">     
        <div class="card-body">
            <div class="row">

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Status Pembayaran</label>
                        <select id="payment-status-filter" class="form-control">
                            <option value="">Semua Status</option>
                            @foreach ($statuses as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ... Card tabel data ... --}}
    <div class="card">
        <div class="card-body">
            {!! $dataTable->table() !!}
        </div>
    </div>
@stop
@push('js')
    {!! $dataTable->scripts(attributes: ['type' => 'module']) !!}


    <script>
        $(function() {
            // Ambil instance tabel
            const table = window.LaravelDataTables['reregistrationinvoices-table'];

            // Tambahkan ID filter baru ke event listener
            $('#payment-status-filter').on('change', function() {
                table.draw();
            });
           
        });
    </script>

@endpush
