<?php

namespace App\Http\Controllers\Backend\Website;

use App\Http\Controllers\Controller;
use App\Http\Requests\CurriculumRequest;
use App\Models\Curriculum;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class CurriculumController extends Controller
{
    public function index(): View
    {
        $curricula = Curriculum::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('backend.website.kurikulum.index', compact('curricula'));
    }

    public function create(): View
    {
        return view('backend.website.kurikulum.create');
    }

    public function store(CurriculumRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $moduleAjar = $validated['module_ajar'] ?? null;
        unset($validated['module_ajar']);

        $moduleAjarPath = null;

        if ($moduleAjar !== null) {
            $moduleAjarPath = $moduleAjar->store('kurikulum/modul-ajar', 'local');

            if ($moduleAjarPath === false) {
                throw new RuntimeException('File modul ajar tidak berhasil disimpan.');
            }

            $validated['module_ajar_path'] = $moduleAjarPath;
        }

        try {
            Curriculum::create($validated);
        } catch (Throwable $exception) {
            if ($moduleAjarPath !== null) {
                Storage::disk('local')->delete($moduleAjarPath);
            }

            throw $exception;
        }

        return redirect()
            ->route('backend-kurikulum.index')
            ->with('success', 'Kurikulum berhasil ditambahkan.');
    }

    public function edit(Curriculum $curriculum): View
    {
        return view('backend.website.kurikulum.edit', compact('curriculum'));
    }

    public function update(CurriculumRequest $request, Curriculum $curriculum): RedirectResponse
    {
        $validated = $request->validated();
        $moduleAjar = $validated['module_ajar'] ?? null;
        unset($validated['module_ajar']);

        $oldModuleAjarPath = $curriculum->module_ajar_path;
        $newModuleAjarPath = null;

        if ($moduleAjar !== null) {
            $newModuleAjarPath = $moduleAjar->store('kurikulum/modul-ajar', 'local');

            if ($newModuleAjarPath === false) {
                throw new RuntimeException('File modul ajar tidak berhasil disimpan.');
            }

            $validated['module_ajar_path'] = $newModuleAjarPath;
        }

        try {
            $curriculum->update($validated);
        } catch (Throwable $exception) {
            if ($newModuleAjarPath !== null) {
                Storage::disk('local')->delete($newModuleAjarPath);
            }

            throw $exception;
        }

        if ($newModuleAjarPath !== null && $oldModuleAjarPath !== null) {
            if (! Storage::disk('local')->delete($oldModuleAjarPath)) {
                throw new RuntimeException('File modul ajar sebelumnya tidak berhasil dihapus.');
            }
        }

        return redirect()
            ->route('backend-kurikulum.index')
            ->with('success', 'Kurikulum berhasil diperbarui.');
    }

    public function destroy(Curriculum $curriculum): RedirectResponse
    {
        $moduleAjarPath = $curriculum->module_ajar_path;
        $curriculum->delete();

        if ($moduleAjarPath !== null) {
            if (! Storage::disk('local')->delete($moduleAjarPath)) {
                throw new RuntimeException('File modul ajar tidak berhasil dihapus.');
            }
        }

        return redirect()
            ->route('backend-kurikulum.index')
            ->with('success', 'Kurikulum berhasil dihapus.');
    }

    public function downloadModuleAjar(Curriculum $curriculum): StreamedResponse
    {
        abort_unless(
            $curriculum->module_ajar_path !== null
                && Storage::disk('local')->exists($curriculum->module_ajar_path),
            404,
        );

        return Storage::disk('local')->download(
            $curriculum->module_ajar_path,
            Str::slug($curriculum->title).'.pdf',
        );
    }
}
