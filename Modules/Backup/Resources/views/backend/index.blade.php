@extends('layouts.backend.app')

@section('title')
    Backup
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
                        <h2> Daftar File Backup</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="row">
                <div class="col-12">
                    <section>
                        <div class="row">
                            <div class="col-8">
                                <div class="card">
                                    <div class="card-datatable">
                                        <table class="dt-responsive table" id="backups-table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Nama File</th>
                                                    <th>Ukuran File</th>
                                                    <th>Dibuat Pada</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($backups as $key => $backup)
                                                    <tr>
                                                        <td></td>
                                                        <td class="p-2">{{ $backup['filename'] }}</td>
                                                        <td class="p-2">{{ $backup['formatted_size'] }}</td>
                                                        <td class="p-2" data-order="{{ $backup['modified_at'] }}" >{{ $backup['formatted_date'] }}</td>
                                                        <td class="p-2">
                                                            <div class="btn-group btn-group-sm" role="group"
                                                                aria-label="Basic example">
                                                                <!-- Restore -->
                                                                <a href="{{ route('backup.restore', $backup['filename']) }}"
                                                                    onclick="return confirm('Yakin ingin merestore database? Data saat ini akan ditimpa!')"
                                                                    class="btn btn-warning" title="Restore">
                                                                    <i data-feather="refresh-cw"></i>
                                                                </a>

                                                                <!-- Download -->
                                                                <a href="{{ route('backup.download', $backup['filename']) }}"
                                                                    class="btn btn-success" title="Download">
                                                                    <i data-feather="download"></i>
                                                                </a>

                                                                <!-- Delete -->
                                                                <a href="{{ route('backup.delete', $backup['filename']) }}"
                                                                    onclick="return confirm('Yakin ingin menghapus file ini?')"
                                                                    class="btn btn-danger" title="Hapus">
                                                                    <i data-feather="x"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="col-4">
                                @include('backup::backend.form')
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
@endsection