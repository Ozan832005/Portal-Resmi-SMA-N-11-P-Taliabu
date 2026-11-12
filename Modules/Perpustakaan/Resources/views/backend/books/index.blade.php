@extends('layouts.backend.app')

@section('title')
    Data Buku
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
<div class="content-wrapper container-xxl p-0">
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2> Data Buku</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="content-body">
        <div class="row">
            <div class="col-12">
                <section>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header border-bottom">
                                    <h4 class="card-title">Data Buku <a href=" {{route('books.create')}} " class="btn btn-primary">Tambah</a></h4>
                                </div>
                                <div class="card-datatable">
                                    <table class="dt-responsive table">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>No</th>
                                                <th>Kode Buku</th>
                                                <th>Judul</th>
                                                <th>Penerbit</th>
                                                <th>Penulis</th>
                                                <th>Kategori</th>
                                                <th>File PDF</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($book as $key => $books)
                                                <tr>
                                                    <td></td>
                                                    <td> {{$key+1}} </td>
                                                    <td> {{$books->book_code}} </td>
                                                    <td> {{$books->name}} </td>
                                                    <td> {{$books->publisher->name}} </td>
                                                    <td> {{$books->author->name}} </td>
                                                    <td> {{$books->category->name}} </td>
                                                    <td>
                                                        @if ($books->pdf_path)
                                                            <a href="{{ route('katalog.perpustakaan.download', $books) }}">Unduh PDF</a>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="d-flex flex-wrap" style="gap: 6px;">
                                                            <a href="{{ route('books.edit', $books->id) }}" class="btn btn-success btn-sm">Edit</a>
                                                            @if ($books->borrowings_count > 0)
                                                                <button type="button" class="btn btn-secondary btn-sm" disabled title="Buku memiliki riwayat peminjaman">Tidak dapat dihapus</button>
                                                            @else
                                                                <form action="{{ route('books.destroy', $books->id) }}" method="post"
                                                                      data-delete-message="{{ 'Buku “'.$books->name.'” akan dihapus permanen.' }}">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection
