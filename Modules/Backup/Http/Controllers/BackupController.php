<?php

namespace Modules\Backup\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Support\Renderable;
use Modules\Backup\Services\BackupService;

class BackupController extends Controller
{
    protected $backupService;

    public function __construct(BackupService $backupService)
    {
        $this->backupService = $backupService;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $backupPath = storage_path('app/backup-db');

        if (!File::exists($backupPath)) {
            File::makeDirectory($backupPath, 0755, true);
        }

        $backups = collect(File::allFiles($backupPath))
            ->filter(fn ($file) => $file->getExtension() === 'zip')
            ->sortByDesc(fn ($file) => $file->getMTime())
            ->map(function ($file) {
                return [
                    'filename' => $file->getFilename(),
                    'path' => $file->getPathname(),
                    'size' => $file->getSize(),
                    'formatted_size' => BackupService::formatBytes($file->getSize()),
                    'modified_at' => $file->getMTime(),
                    'formatted_date' => \Carbon\Carbon::createFromTimestamp($file->getMTime())->format('Y-m-d H:i:s'),
                    'download_link' => route('backup.download', ['filename' => $file->getFilename()]),
                ];
            });

        return view('backup::backend.index', compact('backups'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(Request $request)
    {
        $filename = $request->input('filename');
        $result = $this->backupService->createDatabaseBackup($filename);

        if ($result['success']) {
            return redirect()->route('backup.index')->with('success', $result['message']);
        }

        return redirect()->route('backup.index')->with('error', $result['message']);
    }

    public function restore(string $filename)
    {
        $result = $this->backupService->restoreDatabase($filename);
        return $result['success']
            ? redirect()->route('backup.index')->with('success', $result['message'])
            : redirect()->route('backup.index')->with('error', $result['message']);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(string $filename)
    {
        $result = $this->backupService->deleteBackup($filename);
        return redirect()->route('backup.index')->with(
            $result['success'] ? 'success' : 'error',
            $result['message']
        );
    }

    public function download($filename)
    {
        $path = $this->backupService->getBackupFilePath($filename);
        
        if ($path) {
            return response()->download($path);
        }

        return redirect()->route('backup.index')->with('error', 'File backup tidak ditemukan.');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'backupFile' => 'required|file|mimes:zip',
        ]);

        $result = $this->backupService->uploadBackup($request->file('backupFile'));

        if ($result['success']) {
            return redirect()->route('backup.index')->with('success', $result['message']);
        }

        return redirect()->route('backup.index')->with('error', $result['message']);
    }
}
