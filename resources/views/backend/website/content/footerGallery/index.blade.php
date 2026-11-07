@extends('layouts.backend.app')

@section('title')
    Galeri Footer
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
                <h1 class="km-page-title">Galeri foto footer</h1>
                <p class="km-page-sub">{{ $gallery->count() }} foto tersimpan. Enam foto aktif pertama tampil pada blok galeri di footer situs.</p>
            </div>
        </header>

        <div class="row">
            <div class="col-xl-7 col-12 mb-2">
                <section class="km-panel">
                    <div class="km-panel-head">
                        <h2 class="km-panel-title">Foto terpasang</h2>
                    </div>

                    @if ($gallery->isEmpty())
                        <div class="km-empty">
                            <p class="km-empty-title">Belum ada foto</p>
                            <p class="km-empty-text">Footer masih memakai penanda galeri kosong. Unggah foto lewat form di samping.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="dt-responsive table">
                                <thead>
                                    <tr>
                                        <th scope="col">Foto</th>
                                        <th scope="col">Urutan</th>
                                        <th scope="col">Status</th>
                                        <th scope="col" class="text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($gallery as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ asset('storage/images/footer/' . $item->image) }}"
                                                         alt="{{ $item->caption ?: 'Foto footer' }}" class="km-thumb mr-2">
                                                    <span>{{ $item->caption ?: 'Tanpa keterangan' }}</span>
                                                </div>
                                            </td>
                                            <td>{{ $item->urutan }}</td>
                                            <td>
                                                <span class="km-status {{ $item->is_active === '0' ? 'km-status--live' : 'km-status--draft' }}">
                                                    {{ $item->is_active === '0' ? 'Aktif' : 'Tidak aktif' }}
                                                </span>
                                            </td>
                                            <td class="text-right">
                                                <div class="km-row-actions">
                                                    <a href="{{ route('backend-footer-gallery.edit', $item->id) }}" class="btn btn-sm btn-success">Edit</a>
                                                    <form action="{{ route('backend-footer-gallery.destroy', $item->id) }}" method="post"
                                                          onsubmit="return confirm('Hapus foto ini dari footer?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm km-btn-danger">Hapus</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </section>
            </div>

            <div class="col-xl-5 col-12 mb-2">
                <section class="km-panel">
                    <div class="km-panel-head">
                        <h2 class="km-panel-title">Unggah foto</h2>
                    </div>
                    <form action="{{ route('backend-footer-gallery.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="fotoFooter">Foto <span class="text-danger">*</span></label>
                            <input type="file" id="fotoFooter" name="image" accept="image/*"
                                   class="form-control @error('image') is-invalid @enderror">
                            <span class="km-form-hint">JPG/PNG maksimal 2 MB. Rasio kotak paling rapi.</span>
                            @error('image')
                                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="captionFooter">Keterangan</label>
                            <input type="text" id="captionFooter" name="caption" value="{{ old('caption') }}"
                                   class="form-control @error('caption') is-invalid @enderror" placeholder="Contoh: Kegiatan Pramuka">
                            @error('caption')
                                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="urutanFooter">Urutan</label>
                                    <input type="number" id="urutanFooter" name="urutan" min="0" value="{{ old('urutan', 0) }}"
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
                                        <option value="0" {{ old('is_active') === '0' ? 'selected' : '' }}>Aktif</option>
                                        <option value="1" {{ old('is_active') === '1' ? 'selected' : '' }}>Tidak aktif</option>
                                    </select>
                                    @error('is_active')
                                        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="km-form-actions">
                            <button class="btn btn-primary" type="submit">Unggah foto</button>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </div>
@endsection
