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