<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="curriculum-label">Label <span class="text-danger">*</span></label>
            <input id="curriculum-label" name="label" type="text" maxlength="50" required
                   value="{{ old('label', $curriculum->label ?? '') }}"
                   class="form-control @error('label') is-invalid @enderror">
            @error('label')
                <span class="invalid-feedback" role="alert">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="curriculum-title">Judul <span class="text-danger">*</span></label>
            <input id="curriculum-title" name="title" type="text" maxlength="255" required
                   value="{{ old('title', $curriculum->title ?? '') }}"
                   class="form-control @error('title') is-invalid @enderror">
            @error('title')
                <span class="invalid-feedback" role="alert">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="curriculum-sort-order">Urutan tampil <span class="text-danger">*</span></label>
            <input id="curriculum-sort-order" name="sort_order" type="number" min="0" max="65535" required
                   value="{{ old('sort_order', $curriculum->sort_order ?? 0) }}"
                   class="form-control @error('sort_order') is-invalid @enderror">
            @error('sort_order')
                <span class="invalid-feedback" role="alert">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="curriculum-status">Status <span class="text-danger">*</span></label>
            <select id="curriculum-status" name="is_active" required
                    class="form-control @error('is_active') is-invalid @enderror">
                <option value="1" @selected(old('is_active', (int) ($curriculum->is_active ?? true)) == 1)>Aktif</option>
                <option value="0" @selected(old('is_active', (int) ($curriculum->is_active ?? true)) == 0)>Tidak aktif</option>
            </select>
            @error('is_active')
                <span class="invalid-feedback" role="alert">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            <label for="curriculum-description">Deskripsi <span class="text-danger">*</span></label>
            <textarea id="curriculum-description" name="description" rows="5" required
                      class="form-control @error('description') is-invalid @enderror">{{ old('description', $curriculum->description ?? '') }}</textarea>
            @error('description')
                <span class="invalid-feedback" role="alert">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

<div class="d-flex align-items-center">
    <button class="btn btn-primary mr-1" type="submit">{{ $submitLabel }}</button>
    <a class="btn btn-outline-primary" href="{{ route('backend-kurikulum.index') }}">Batal</a>
</div>
