<?php

namespace App\Http\Controllers\Backend\Website;

use App\Http\Controllers\Controller;
use App\Models\Visimisi;
use Illuminate\Http\Request;
use App\Http\Requests\VisidanMisiRequest;
use App\Support\ImageStorage;
use ErrorException;
use Session;

class VisidanMisiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $visimisi = Visimisi::first();
        return view('backend.website.tentang.visiMisi', compact('visimisi'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VisidanMisiRequest $request)
    {
        try {
            $nama_img = ImageStorage::store($request->file('image'), 'visimisi');

            $visimisi = new Visimisi();
            $visimisi->visi     = $request->visi;
            $visimisi->misi     = $request->misi;
            $visimisi->image    = $nama_img;
            $visimisi->save();

            Session::flash('success','Visi dan Misi Berhasil dibuat!');
            return redirect()->route('backend-visimisi.index');

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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(VisidanMisiRequest $request, $id)
    {
        try {
            $nama_img = ImageStorage::store($request->file('image'), 'visimisi');

            $visimisi = Visimisi::findOrFail($id);
            $old_image = $visimisi->image;
            $visimisi->visi     = $request->visi;
            $visimisi->misi     = $request->misi;
            $visimisi->image    = $nama_img ?? $visimisi->image;
            $visimisi->save();

            if ($nama_img) {
                ImageStorage::delete($old_image, 'visimisi');
            }

            Session::flash('success','Visi dan Misi Berhasil diupdate!');
            return redirect()->route('backend-visimisi.index');

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
        $visimisi = Visimisi::findOrFail($id);

        ImageStorage::delete($visimisi->image, 'visimisi');
        $visimisi->delete();

        Session::flash('success','Visi dan Misi Berhasil dihapus!');

        return redirect()->route('backend-visimisi.index');
    }
}
