@if (request('action') == null)
    <div class="card">
        <div class="card-body">
            {{-- form this backup store --}}
            <form action="{{ route('backup.create') }}" method="post">
                @csrf
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="basicInput">Buat Backup Baru</label></span>
                            <input type="text" class="form-control" name="filename" placeholder="{{ date('Y-m-d_Hi') }}">
                        </div>
                    </div>
                </div>
                <button class="btn btn-success" type="submit">Buat Backup Baru</button>
            </form>
            {{-- form this backup upload --}}
            <form action="{{ route('backup.upload') }}" method="post" enctype="multipart/form-data">
                @csrf
                <hr>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="basicInput">Upload File Backup</label> <span class="text-danger">*</span>
                            <input type="file" class="form-control" name="backupFile">
                        </div>
                    </div>
                </div>
                <button class="btn btn-primary" type="submit">Upload File Backup</button>
            </form>
        </div>
    </div>
@endif
