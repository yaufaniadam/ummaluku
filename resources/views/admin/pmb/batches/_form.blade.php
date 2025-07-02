@if ($errors->any())
    <div class="alert alert-danger">
        <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif

<div class="row">
    <div class="col-md-6 form-group">
        <label for="name">Nama Gelombang</label>
        <input type="text" name="name" class="form-control" id="name" value="{{ old('name', $batch->name ?? '') }}" required>
    </div>
    <div class="col-md-6 form-group">
        <label for="year">Tahun Ajaran</label>
        <input type="number" name="year" class="form-control" id="year" value="{{ old('year', $batch->year ?? date('Y')) }}" required>
    </div>
</div>
<div class="row">
    <div class="col-md-6 form-group">
        <label for="start_date">Tanggal Mulai</label>
        <input type="date" name="start_date" class="form-control" id="start_date" value="{{ old('start_date', isset($batch) ? $batch->start_date->format('Y-m-d') : '') }}" required>
    </div>
    <div class="col-md-6 form-group">
        <label for="end_date">Tanggal Selesai</label>
        <input type="date" name="end_date" class="form-control" id="end_date" value="{{ old('end_date', isset($batch) ? $batch->end_date->format('Y-m-d') : '') }}" required>
    </div>
</div>
<div class="form-group">
    <label for="is_active">Status</label>
    <select name="is_active" class="form-control" id="is_active" required>
        <option value="1" {{ old('is_active', $batch->is_active ?? 1) == 1 ? 'selected' : '' }}>Aktif</option>
        <option value="0" {{ old('is_active', $batch->is_active ?? 1) == 0 ? 'selected' : '' }}>Tidak Aktif</option>
    </select>
</div>