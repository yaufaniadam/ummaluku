<div>
    <div class="row g-3">

        <div class="col-md-4">
            <label for="provinsi" class="form-label">Provinsi</label>

            <select wire:model.live="selectedProvinsi" id="provinsi" class="form-select">
                <option value="">-- Pilih Provinsi --</option>
                @foreach ($provinsis as $provinsi)
                    <option value="{{ $provinsi['district']['kodeWilayah'] }}">{{ $provinsi['district']['namaWilayah'] }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <label for="kabupaten" class="form-label">Kabupaten/Kota</label>
            <div wire:loading wire:target="selectedProvinsi" class="text-muted">Mencari kabupaten...
            </div>
            <select wire:model.live="selectedKabupaten" id="kabupaten" class="form-select">
                <option value="">-- Pilih Kabupaten/Kota --</option>
                @foreach ($kabupatens as $kabupaten)
                    <option value="{{ $kabupaten['district']['kodeWilayah'] }}">
                        {{ $kabupaten['district']['namaWilayah'] }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <label for="kecamatan" class="form-label">Kecamatan</label>
            <div wire:loading wire:target="selectedKabupaten" class="text-muted">Mencari kecamatan...
            </div>
            <select wire:model.live="selectedKecamatan" id="kecamatan" class="form-select">
                <option value="">-- Pilih Kecamatan --</option>
                @foreach ($kecamatans as $kecamatan)
                    <option value="{{ $kecamatan['district']['kodeWilayah'] }}">
                        {{ $kecamatan['district']['namaWilayah'] }}</option>
                @endforeach
            </select>
        </div>

        @if (count($sekolahs) > 0)
            <hr>
            <h5>Hasil Pencarian</h5>
            <ul class="list-group">
                @foreach ($sekolahs as $sekolah)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $sekolah['nama'] }} 
                        <button class="btn btn-sm btn-primary"
                            wire:click="pilihSekolah('{{ $sekolah['satuanPendidikanId'] }}', '{{ $sekolah['npsn'] }}', '{{ $sekolah['nama'] }}', '{{ $sekolah['alamatJalan'] }}', '{{ $sekolah['namaDesa'] }}')">
                            Pilih
                        </button>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
