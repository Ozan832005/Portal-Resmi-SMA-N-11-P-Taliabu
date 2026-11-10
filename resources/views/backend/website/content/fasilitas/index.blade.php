@extends('layouts.backend.app')

@section('title')
    Fasilitas Sekolah
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
                <h1 class="km-page-title">Fasilitas sekolah</h1>
                <p class="km-page-sub">{{ $fasilitas->count() }} fasilitas tersimpan. Fasilitas aktif ikut tampil pada ringkasan halaman depan.</p>
            </div>
        </header>

        <div class="row">
            <div class="col-xl-7 col-12 mb-2">
                <section class="km-panel">
                    <div class="km-panel-head">
                        <h2 class="km-panel-title">Daftar fasilitas</h2>
                    </div>

                    @if ($fasilitas->isEmpty())
                        <div class="km-empty">
                            <p class="km-empty-title">Belum ada fasilitas</p>
                            <p class="km-empty-text">Tambahkan fasilitas pertama lewat form di samping.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="dt-responsive table">
                                <thead>
                                    <tr>
                                        <th scope="col">Fasilitas</th>
                                        <th scope="col">Urutan</th>
                                        <th scope="col">Status</th>
                                        <th scope="col" class="text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($fasilitas as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if ($item->image)
                                                        <img src="{{ asset('storage/images/fasilitas/' . $item->image) }}"
                                                             alt="Foto {{ $item->nama }}" class="km-thumb mr-2">
                                                    @else
                                                        <span class="km-thumb km-thumb--empty mr-2">Tanpa foto</span>
                                                    @endif
                                                    <span>
                                                        <strong>{{ $item->nama }}</strong>
                                                        @if ($item->desc)
                                                            <div class="text-muted small">{{ \Illuminate\Support\Str::limit($item->desc, 48) }}</div>
                                                        @endif
                                                    </span>
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
                                                    <a href="{{ route('backend-fasilitas.edit', $item->id) }}" class="btn btn-sm btn-success">Edit</a>
                                                    <form action="{{ route('backend-fasilitas.destroy', $item->id) }}" method="post"
                                                          data-delete-message="{{ 'Fasilitas “'.$item->nama.'” akan dihapus permanen.' }}">
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
                        <h2 class="km-panel-title">Tambah fasilitas</h2>
                    </div>
                    <form action="{{ route('backend-fasilitas.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="namaFasilitas">Nama fasilitas <span class="text-danger">*</span></label>
                            <input type="text" id="namaFasilitas" name="nama" value="{{ old('nama') }}"
                                   class="form-control @error('nama') is-invalid @enderror" placeholder="Contoh: Laboratorium Komputer">
                            @error('nama')
                                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="descFasilitas">Keterangan</label>
                            <textarea id="descFasilitas" name="desc" rows="3" class="form-control @error('desc') is-invalid @enderror"
                                      placeholder="Jumlah ruang, kapasitas, atau catatan singkat">{{ old('desc') }}</textarea>
                            @error('desc')
                                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="fotoFasilitas">Foto</label>
                            <input type="file" id="fotoFasilitas" name="image" class="form-control @error('image') is-invalid @enderror">
                            <span class="km-form-hint">Opsional, maksimal 2 MB.</span>
                            @error('image')
                                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="urutanFasilitas">Urutan</label>
                                    <input type="number" id="urutanFasilitas" name="urutan" min="0" value="{{ old('urutan', 0) }}"
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
                            <button class="btn btn-primary" type="submit">Tambah fasilitas</button>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </div>
@endsection
