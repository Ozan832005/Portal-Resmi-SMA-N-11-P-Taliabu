@extends('layouts.backend.app')

@section('title')
    {{ isset($book) ? 'Edit Buku' : 'Tambah Buku' }}
@endsection

@section('content')
@php
    $isEditing = isset($book);
@endphp

<div class="content-wrapper container-xxl p-0">
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2> Buku</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="content-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header header-bottom">
                        <h4>{{ $isEditing ? 'Edit Buku' : 'Tambah Buku' }}</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ $isEditing ? route('books.update', $book->id) : route('books.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @if ($isEditing)
                                @method('PUT')
                            @endif
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="basicInput">Judul</label> <span class="text-danger">*</span>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $book->name ?? '') }}" placeholder="Nama" required />
                                        @error('name')
                                            <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="basicInput">Kategori</label> <span class="text-danger">*</span>
                                        <select name="category_id" class="form-control @error('category_id') is-invalid @enderror">
                                            <option value="">-- Pilih --</option>
                                            @foreach ($category as $categoryItem)
                                              <option value="{{ $categoryItem->id }}" @selected(old('category_id', $book->category_id ?? '') == $categoryItem->id)> {{ $categoryItem->name }} </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="basicInput">Penerbit</label> <span class="text-danger">*</span>
                                        <select name="publisher_id" class="form-control @error('publisher_id') is-invalid @enderror">
                                            <option value="">-- Pilih --</option>
                                            @foreach ($publisher as $publisherItem)
                                              <option value="{{ $publisherItem->id }}" @selected(old('publisher_id', $book->publisher_id ?? '') == $publisherItem->id)> {{ $publisherItem->name }} </option>
                                            @endforeach
                                        </select>
                                        @error('publisher_id')
                                            <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="basicInput">Penulis</label> <span class="text-danger">*</span>
                                        <select name="author_id" class="form-control @error('author_id') is-invalid @enderror">
                                            <option value="">-- Pilih --</option>
                                            @foreach ($author as $authorItem)
                                              <option value="{{ $authorItem->id }}" @selected(old('author_id', $book->author_id ?? '') == $authorItem->id)> {{ $authorItem->name }} </option>
                                            @endforeach
                                        </select>
                                        @error('author_id')
                                            <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="basicInput">Tahun Terbit</label>  <span class="text-danger">*</span>
                                        <input type="number" class="form-control @error('publication_year') is-invalid @enderror" name="publication_year" value="{{ old('publication_year', $book->publication_year ?? '') }}" required />
                                        @error('publication_year')
                                            <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="basicInput">Nomor ISBN</label>  <span class="text-danger">*</span>
                                        <input type="text" class="form-control @error('isbn') is-invalid @enderror" name="isbn" value="{{ old('isbn', $book->isbn ?? '') }}" required />
                                        @error('isbn')
                                            <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="basicInput">Stok</label>  <span class="text-danger">*</span>
                                        <input type="number" min="0" class="form-control @error('stock') is-invalid @enderror" name="stock" value="{{ old('stock', $book->stock ?? '') }}" required />
                                        @error('stock')
                                            <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="basicInput">Thumbnail @unless ($isEditing)<span class="text-danger">*</span>@endunless</label>
                                        @if ($isEditing)
                                            <div class="mb-1">
                                                @if ($book->thumbnail)
                                                    <img src="{{ route('katalog.perpustakaan.sampul', $book) }}" alt="Sampul {{ $book->name }}" style="max-width: 100px; max-height: 140px;">
                                                @else
                                                    <span class="text-muted">Belum ada sampul buku.</span>
                                                @endif
                                            </div>
                                            <small class="text-muted d-block mb-1">Kosongkan jika tidak ingin mengganti sampul.</small>
                                        @endif
                                        <input type="file" accept="image/jpeg,image/png" class="form-control @error('thumbnail') is-invalid @enderror" name="thumbnail" @unless ($isEditing) required @endunless />
                                        @error('thumbnail')
                                            <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="book-pdf">File Buku PDF <span class="text-muted">(Opsional)</span></label>
                                        @if ($isEditing && $book->pdf_path)
                                            <div class="mb-1">
                                                <a href="{{ route('katalog.perpustakaan.download', $book) }}">Unduh file PDF saat ini</a>
                                            </div>
                                            <small class="text-muted d-block mb-1">Kosongkan jika tidak ingin mengganti file.</small>
                                        @endif
                                        <input id="book-pdf" type="file" accept=".pdf,application/pdf"
                                               class="form-control @error('pdf_file') is-invalid @enderror" name="pdf_file" />
                                        @error('pdf_file')
                                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                        @enderror
                                        <small class="form-text text-muted">Format PDF, maksimal 20 MB.</small>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="basicInput">Deskripsi</label>  <span class="text-danger">*</span>
                                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3" required>{{ old('description', $book->description ?? '') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div

                            </div>
                            <button class="btn btn-primary" type="submit">{{ $isEditing ? 'Simpan Perubahan' : 'Tambah' }}</button>
                            <a href="{{route('books.index')}}" class="btn btn-warning">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection