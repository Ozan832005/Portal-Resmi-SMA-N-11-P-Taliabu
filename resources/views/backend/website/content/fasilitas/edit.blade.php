@extends('layouts.backend.app')

@section('title')
    Edit Fasilitas
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
                <h1 class="km-page-title">Edit fasilitas</h1>
                <p class="km-page-sub">{{ $semua->count() }} fasilitas terdaftar pada sistem.</p>
            </div>
            <a href="{{ route('backend-fasilitas.index') }}" class="btn btn-outline-secondary">Kembali</a>
        </header>

        <div class="row">
            <div class="col-xl-7 col-12 mb-2">
                <section class="km-panel">
                    <div class="km-panel-head">
                        <h2 class="km-panel-title">{{ $fasilitas->nama }}</h2>
                    </div>
                    <form action="{{ route('backend-fasilitas.update', $fasilitas->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="namaFasilitas">Nama fasilitas <span class="text-danger">*</span></label>
                            <input type="text" id="namaFasilitas" name="nama" value="{{ old('nama', $fasilitas->nama) }}"
                                   class="form-control @error('nama') is-invalid @enderror">
                            @error('nama')
                                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="descFasilitas">Keterangan</label>
                            <textarea id="descFasilitas" name="desc" rows="4" class="form-control @error('desc') is-invalid @enderror">{{ old('desc', $fasilitas->desc) }}</textarea>
                            @error('desc')
                                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="fotoFasilitas">Ganti foto</label>
                            <input type="file" id="fotoFasilitas" name="image" class="form-control @error('image') is-invalid @enderror">
                            <span class="km-form-hint">Kosongkan jika tidak ingin mengubah.</span>
                            @error('image')
                                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="urutanFasilitas">Urutan</label>
                                    <input type="number" id="urutanFasilitas" name="urutan" min="0"
                                           value="{{ old('urutan', $fasilitas->urutan) }}"
                                           class="form-control @error('urutan') is-invalid @enderror">
                                    @error('urutan')
                                        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="statusFasilitas">Status <span class="text-danger">*</span></label>
                                    <select id="statusFasilitas" name="is_active" class="form-control @error('is_active') is-invalid @enderror">
                                        <option value="0" {{ old('is_active', $fasilitas->is_active) === '0' ? 'selected' : '' }}>Aktif</option>
                                        <option value="1" {{ old('is_active', $fasilitas->is_active) === '1' ? 'selected' : '' }}>Tidak aktif</option>
                                    </select>
                                    @error('is_active')
                                        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="km-form-actions">
                            <button class="btn btn-primary" type="submit">Simpan perubahan</button>
                            <a href="{{ route('backend-fasilitas.index') }}" class="btn btn-warning">Batal</a>
                        </div>
                    </form>
                </section>
            </div>

            <div class="col-xl-5 col-12 mb-2">
                <section class="km-panel">
                    <div class="km-panel-head">
                        <h2 class="km-panel-title">Pratinjau</h2>
                    </div>
                    @if ($fasilitas->image)
                        <img src="{{ asset('storage/images/fasilitas/' . $fasilitas->image) }}" class="img-fluid rounded"
                             alt="Foto {{ $fasilitas->nama }}">
                    @else
                        <div class="km-empty">
                            <p class="km-empty-title">Belum ada foto</p>
                            <p class="km-empty-text">Unggah foto agar fasilitas tampil lebih menarik di halaman depan.</p>
                        </div>
                    @endif
                </section>
            </div>
        </div>
    </div>
@endsection
