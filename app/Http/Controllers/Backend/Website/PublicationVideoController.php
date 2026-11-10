<?php

namespace App\Http\Controllers\Backend\Website;

use App\Http\Controllers\Controller;
use App\Http\Requests\PublicationVideoRequest;
use App\Models\PublicationVideo;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PublicationVideoController extends Controller
{
    public function index(): View
    {
        $videos = PublicationVideo::query()->orderByDesc('id')->get();

        return view('backend.website.publikasi-video.index', compact('videos'));
    }

    public function create(): View
    {
        return view('backend.website.publikasi-video.create');
    }

    public function store(PublicationVideoRequest $request): RedirectResponse
    {
        PublicationVideo::create($request->validated());

        return redirect()
            ->route('backend-video-publikasi.index')
            ->with('success', 'Video publikasi berhasil ditambahkan.');
    }

    public function edit(PublicationVideo $publicationVideo): View
    {
        return view('backend.website.publikasi-video.edit', compact('publicationVideo'));
    }

    public function update(PublicationVideoRequest $request, PublicationVideo $publicationVideo): RedirectResponse
    {
        $publicationVideo->update($request->validated());

        return redirect()
            ->route('backend-video-publikasi.index')
            ->with('success', 'Video publikasi berhasil diperbarui.');
    }

    public function destroy(PublicationVideo $publicationVideo): RedirectResponse
    {
        $publicationVideo->delete();

        return redirect()
            ->route('backend-video-publikasi.index')
            ->with('success', 'Video publikasi berhasil dihapus.');
    }
}
