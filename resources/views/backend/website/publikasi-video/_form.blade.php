<div class="row">
    <div class="col-12">
        <div class="form-group">
            <label for="publication-video-title">Judul <span class="text-danger">*</span></label>
            <input id="publication-video-title" name="title" type="text" maxlength="255" required
                   value="{{ old('title', $publicationVideo->title ?? '') }}"
                   class="form-control @error('title') is-invalid @enderror">
            @error('title')
                <span class="invalid-feedback" role="alert">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-md-8">
        <div class="form-group">
            <label for="publication-video-url">URL video <span class="text-danger">*</span></label>
            <input id="publication-video-url" name="url" type="url" maxlength="2048" required
                   placeholder="https://www.youtube.com/watch?v=..."
                   value="{{ old('url', $publicationVideo->url ?? '') }}"
                   class="form-control @error('url') is-invalid @enderror">
            <small class="km-form-hint">Gunakan tautan YouTube atau berkas video MP4, WebM, dan OGG yang dapat diakses melalui HTTPS.</small>
            @error('url')
                <span class="invalid-feedback" role="alert">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="publication-video-status">Status <span class="text-danger">*</span></label>
            <select id="publication-video-status" name="is_active" required
                    class="form-control @error('is_active') is-invalid @enderror">
                <option value="0" @selected(old('is_active', $publicationVideo->is_active ?? '0') == '0')>Aktif</option>
                <option value="1" @selected(old('is_active', $publicationVideo->is_active ?? '0') == '1')>Tidak aktif</option>
            </select>
            @error('is_active')
                <span class="invalid-feedback" role="alert">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            <label for="publication-video-description">Deskripsi <span class="text-danger">*</span></label>
            <textarea id="publication-video-description" name="desc" rows="5" required
                      class="form-control @error('desc') is-invalid @enderror">{{ old('desc', $publicationVideo->desc ?? '') }}</textarea>
            @error('desc')
                <span class="invalid-feedback" role="alert">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

<div class="d-flex align-items-center">
    <button class="btn btn-primary mr-1" type="submit">{{ $submitLabel }}</button>
    <a class="btn btn-outline-primary" href="{{ route('backend-video-publikasi.index') }}">Batal</a>
</div>
