<div wire:init="loadProvinces">
    {{-- Baris Dropdown Pencarian Wilayah --}}
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label>Provinsi</label>
                <div wire:loading wire:target="loadProvinces" class="text-muted small">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Memuat Data Provinsi...
                </div>
                <select wire:model.live="selectedProvinsi" class="form-control" wire:loading.attr="disabled" wire:target="loadProvinces">
                    <option value="">-- Pilih Provinsi --</option>
                    @foreach ($provinsis as $provinsi)
                        <option value="{{ $provinsi['district']['kodeWilayah'] }}">{{ $provinsi['district']['namaWilayah'] }}
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Kabupaten/Kota</label>
                <div wire:loading wire:target="selectedProvinsi" class="text-muted small">Memuat...</div>
                <select wire:model.live="selectedKabupaten" class="form-control" @if(!$selectedProvinsi) disabled @endif>
                    <option value="">-- Pilih Kabupaten/Kota --</option>
                    @foreach ($kabupatens as $kabupaten)
                         <option value="{{ $kabupaten['district']['kodeWilayah'] }}">
                        {{ $kabupaten['district']['namaWilayah'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Kecamatan</label>
                <div wire:loading wire:target="selectedKabupaten" class="text-muted small">Memuat...</div>
                <select wire:model.live="selectedKecamatan" class="form-control" @if(!$selectedKabupaten) disabled @endif>
                    <option value="">-- Pilih Kecamatan --</option>
                    @foreach ($kecamatans as $kecamatan)
                        <option value="{{ $kecamatan['district']['kodeWilayah'] }}">
                        {{ $kecamatan['district']['namaWilayah'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    
    <hr>

    {{-- Tabel Hasil Pencarian Sekolah --}}
    <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
        <div wire:loading wire:target="selectedKecamatan" class="text-center p-4">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Mencari sekolah...</span>
            </div>
            <p class="mt-2">Mencari sekolah...</p>
        </div>

        <table class="table table-hover table-striped" wire:loading.remove wire:target="selectedKecamatan">
            <thead>
                <tr>
        
                    <th>Nama Sekolah</th>
                    <th style="width: 100px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sekolahs as $sekolah)
                    <tr>
             
                        <td>{{ $sekolah['nama'] }}</td>
                  
                        <td>
                           <button class="btn btn-sm btn-primary"
                            wire:click="pilihSekolah('{{ $sekolah['satuanPendidikanId'] }}', '{{ $sekolah['npsn'] }}', '{{ $sekolah['nama'] }}', '{{ $sekolah['alamatJalan'] }}', '{{ $sekolah['namaDesa'] }}')">
                            Pilih
                        </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">
                            @if($selectedKecamatan)
                                Tidak ada data sekolah ditemukan di kecamatan ini.
                            @else
                                Silakan pilih provinsi, kabupaten, dan kecamatan terlebih dahulu.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>