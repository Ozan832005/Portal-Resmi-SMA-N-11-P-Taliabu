<?php

namespace App\Http\Controllers\Backend\Website;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use App\Http\Requests\KegiatanRequest;
use App\Support\ImageStorage;
use ErrorException;
use Session;
use DB;

class KegiatanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kegiatan = Kegiatan::all();
        return view('backend.website.kegiatan.index', compact('kegiatan'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.website.kegiatan.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(KegiatanRequest $request)
    {
        try {
            $nama_img = ImageStorage::store($request->file('image'), 'kegiatan');

            $url = \Str::slug($request->nama);
            $kegiatan = new Kegiatan();
            $kegiatan->nama     = $request->nama;
            $kegiatan->slug     = $url;
            $kegiatan->image    = $nama_img ?? NULL;
            $kegiatan->content  = $request->content;
            $kegiatan->save();

            Session::flash('success','Kegiatan Berhasil ditambah !');
            return redirect()->route('backend-kegiatan.index');
        } catch (ErrorException $e) {
            throw new ErrorException($e->getMessage());
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $kegiatan = Kegiatan::find($id);
        return view('backend.website.kegiatan.edit', compact('kegiatan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'      => 'required|string|max:255|unique:kegiatans,nama,'.$id,
            'content'   => 'required|string',
            'is_active' => 'required|in:0,1',
            'image'     => 'nullable|image|max:2048',
        ]);

        try {
            $nama_img = ImageStorage::store($request->file('image'), 'kegiatan');

            $url = \Str::slug($request->nama);
            $kegiatan = Kegiatan::findOrFail($id);
            $old_image = $kegiatan->image;
            $kegiatan->nama         = $request->nama;
            $kegiatan->slug         = $url;
            $kegiatan->image        = $nama_img ?? $kegiatan->image;
            $kegiatan->is_active    = $request->is_active;
            $kegiatan->content      = $request->content;
            $kegiatan->save();

            if ($nama_img) {
                ImageStorage::delete($old_image, 'kegiatan');
            }

            Session::flash('success','Kegiatan Berhasil diupdate !');
            return redirect()->route('backend-kegiatan.index');
        } catch (ErrorException $e) {
            throw new ErrorException($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);

        ImageStorage::delete($kegiatan->image, 'kegiatan');
        $kegiatan->delete();

        Session::flash('success','Kegiatan Berhasil dihapus !');

        return redirect()->route('backend-kegiatan.index');
    }
}
