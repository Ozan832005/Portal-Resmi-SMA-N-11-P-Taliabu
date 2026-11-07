@extends('layouts.backend.app')

@section('title')
    Edit Foto Footer
@endsection

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success" role="alert">
            <div class="alert-body">
                <strong>{{ $message }}</strong>
                <button type="button" class="close" data-dismiss="alert">×</button>
            </div>
        </div>
    @elseif($message = Session::get('error'))
        <div class="alert alert-danger" role="alert">
            <div class="alert-body">
                <strong>{{ $message }}</strong>
                <button type="button" class="close" data-dismiss="alert">×</button>
            </div>
        </div>
    @endif

    <div class="km-page">
        <header class="km-page-head">
            <div>
                <h1 class="km-page-title">Edit foto footer</h1>
                <p class="km-page-sub">{{ $semua->count() }} foto terdaftar pada galeri footer.</p>
            </div>
            <a href="{{ route('backend-footer-gallery.index') }}" class="btn btn-outline-secondary">Kembali</a>
        </header>

        <div class="row">
            <div class="col-xl-7 col-12 mb-2">
                <section class="km-panel">
                    <div class="km-panel-head">
                        <h2 class="km-panel-title">{{ $galeri->caption ?: 'Foto footer' }}</h2>
                    </div>
                    <form action="{{ route('backend-footer-gallery.update', $galeri->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="fotoFooter">Ganti foto</label>
                            <input type="file" id="fotoFooter" name="image" accept="image/*"
                                   class="form-control @error('image') is-invalid @enderror">
                            <span class="km-form-hint">Kosongkan jika tidak ingin mengubah.</span>
                            @error('image')
                                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="captionFooter">Keterangan</label>
                            <input type="text" id="captionFooter" name="caption" value="{{ old('caption', $galeri->caption) }}"
                                   class="form-control @error('caption') is-invalid @enderror">
                            @error('caption')
                                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="urutanFooter">Urutan</label>
                                    <input type="number" id="urutanFooter" name="urutan" min="0"
                                           value="{{ old('urutan', $galeri->urutan) }}"
                                           class="form-control @error('urutan') is-invalid @enderror">
                                    @error('urutan')
                                        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="statusFooter">Status <span class="text-danger">*</span></label>
                                    <select id="statusFooter" name="is_active" class="form-control @error('is_active') is-invalid @enderror">
                                        <option value="0" {{ old('is_active', $galeri->is_active) === '0' ? 'selected' : '' }}>Aktif</option>
                                        <option value="1" {{ old('is_active', $galeri->is_active) === '1' ? 'selected' : '' }}>Tidak aktif</option>
                                    </select>
                                    @error('is_active')
                                        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="km-form-actions">
                            <button class="btn btn-primary" type="submit">Simpan perubahan</button>
                            <a href="{{ route('backend-footer-gallery.index') }}" class="btn btn-warning">Batal</a>
                        </div>
                    </form>
                </section>
            </div>

            <div class="col-xl-5 col-12 mb-2">
                <section class="km-panel">
                    <div class="km-panel-head">
                        <h2 class="km-panel-title">Pratinjau</h2>
                    </div>
                    <img src="{{ asset('storage/images/footer/' . $galeri->image) }}" class="img-fluid rounded" alt="Foto footer">
                </section>
            </div>
        </div>
    </div>
@endsection
