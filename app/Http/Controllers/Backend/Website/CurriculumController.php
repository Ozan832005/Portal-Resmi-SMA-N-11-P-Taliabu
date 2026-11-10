<?php

namespace App\Http\Controllers\Backend\Website;

use App\Http\Controllers\Controller;
use App\Http\Requests\CurriculumRequest;
use App\Models\Curriculum;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

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
        Curriculum::create($request->validated());

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
        $curriculum->update($request->validated());

        return redirect()
            ->route('backend-kurikulum.index')
            ->with('success', 'Kurikulum berhasil diperbarui.');
    }

    public function destroy(Curriculum $curriculum): RedirectResponse
    {
        $curriculum->delete();

        return redirect()
            ->route('backend-kurikulum.index')
            ->with('success', 'Kurikulum berhasil dihapus.');
    }
}
