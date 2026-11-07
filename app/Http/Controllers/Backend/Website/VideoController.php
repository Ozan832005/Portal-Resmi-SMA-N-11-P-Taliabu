<?php

namespace App\Http\Controllers\Backend\Website;

use App\Http\Controllers\Controller;
use App\Http\Requests\VideoRequest;
use App\Models\Video;
use ErrorException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $video = Video::all();

        return view('backend.website.content.vidio.index', compact('video'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(VideoRequest $request)
    {
        try {
            $video = new Video;
            $video->title = $request->title;
            $video->desc = $request->desc;
            $video->url = $request->url;
            $video->is_active = $request->is_active;
            $video->save();

            Session::flash('success', 'Video Berhasil ditambah !');

            return redirect()->route('backend-video.index');
        } catch (ErrorException $e) {
            throw new ErrorException($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $video = Video::findOrFail($id);

        return view('backend.website.content.vidio.edit', compact('video'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'is_active' => 'required|in:0,1',
        ]);

        try {
            $video = Video::findOrFail($id);
            $video->title = $request->title ?? $video->title;
            $video->desc = $request->desc ?? $video->desc;
            $video->url = $request->url ?? $video->url;
            $video->is_active = $request->is_active;
            $video->save();

            Session::flash('success', 'Video Berhasil diupdate !');

            return redirect()->route('backend-video.index');
        } catch (ErrorException $e) {
            throw new ErrorException($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $video = Video::findOrFail($id);
        $video->delete();

        Session::flash('success', 'Video Berhasil dihapus !');

        return redirect()->route('backend-video.index');
    }
}
