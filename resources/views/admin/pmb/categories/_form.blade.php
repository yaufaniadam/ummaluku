@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="form-group">
    <label for="name">Nama Jalur</label>
    <input type="text" name="name" class="form-control" id="name" value="{{ old('name', $category->name ?? '') }}" required>
</div>
<div class="form-group">
    <label for="description">Deskripsi</label>
    <textarea name="description" class="form-control" id="description">{{ old('description', $category->description ?? '') }}</textarea>
</div>
<div class="form-group">
    <label for="price">Biaya Pendaftaran (Rp)<small class="text-muted"> (isi dengan '0' jika gratis)</small></label>
    <input type="text" name="price" class="form-control" id="price" value="{{ old('price', $category->price ?? '') }}" required>
</div>
<div class="form-group">
    <label for="display_group">Grup Tampilan</label>
    <input type="text" name="display_group" class="form-control" id="display_group" value="{{ old('display_group', $category->display_group ?? '') }}" placeholder="e.g., beasiswa, reguler">
</div>
<div class="form-group">
    <label for="is_active">Status</label>
    <select name="is_active" class="form-control" id="is_active" required>
        <option value="1" {{ old('is_active', $category->is_active ?? 1) == 1 ? 'selected' : '' }}>Aktif</option>
        <option value="0" {{ old('is_active', $category->is_active ?? 1) == 0 ? 'selected' : '' }}>Tidak Aktif</option>
    </select>
</div>

<div class="form-group">
    <label>Dokumen Persyaratan</label>
    <div class="card card-outline card-secondary">
        <div class="card-body">
            <div class="row">
                @foreach($documents as $doc)
                    <div class="col-md-6 mb-2">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="doc_{{ $doc->id }}" name="documents[]" value="{{ $doc->id }}"
                            @if(is_array(old('documents')))
                                {{ in_array($doc->id, old('documents')) ? 'checked' : '' }}
                            @elseif(isset($category) && $category->documentRequirements->contains($doc->id))
                                checked
                            @endif
                            >
                            <label class="custom-control-label font-weight-normal" for="doc_{{ $doc->id }}">
                                {{ $doc->name }}
                            </label>
                            <div class="text-muted small ml-1">{{ $doc->description }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
            @if($documents->isEmpty())
                <p class="text-muted">Belum ada data dokumen persyaratan. Silakan tambahkan di database.</p>
            @endif
        </div>
    </div>
</div>
