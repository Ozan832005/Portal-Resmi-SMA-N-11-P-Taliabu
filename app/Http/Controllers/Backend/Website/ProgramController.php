<?php

namespace App\Http\Controllers\Backend\Website;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use Illuminate\Http\Request;
use App\Http\Requests\ProgramRequest;
use App\Models\DataJurusan;
use App\Support\ImageStorage;
use ErrorException;
use Session;
use DB;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jurusan = Jurusan::all();
        return view('backend.website.program.index', compact('jurusan'));
    }

    public function create()
    {
        return view('backend.website.program.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProgramRequest $request)
    {
        try {
            DB::beginTransaction();
            $url                = \Str::slug($request->nama);
            $jurusan = new Jurusan;
            $jurusan->nama      = $request->nama;
            $jurusan->slug      = $url;
            $jurusan->singkatan = $request->singkatan;
            $jurusan->is_active = '0';
            $jurusan->save();

            if ($jurusan) {
                $nama_foto = ImageStorage::store($request->file('image'), 'jurusan');

                $dataJurusan = new DataJurusan;
                $dataJurusan->jurusan_id    = $jurusan->id;
                $dataJurusan->content       = $request->content;
                $dataJurusan->image         = $nama_foto;
                $dataJurusan->save();
            }

            DB::commit();
            Session::flash('success','Program Studi Berhasil ditambah !');
            return redirect()->route('program-studi.index');

        } catch (ErrorException $e) {
            DB::rollback();
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
        $jurusan = Jurusan::with('dataJurusan')->findOrFail($id);
        return view('backend.website.program.edit', compact('jurusan'));
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
            'nama'      => 'required|string|max:255|unique:jurusans,nama,'.$id,
            'singkatan' => 'required|string|max:20|unique:jurusans,singkatan,'.$id,
            'content'   => 'required|string',
            'is_active' => 'required|in:0,1',
            'image'     => 'nullable|image|max:2048',
        ]);

        try {
            DB::beginTransaction();
            $jurusan = Jurusan::findOrFail($id);
            $jurusan->nama      = $request->nama;
            $jurusan->slug      = \Str::slug($request->nama);
            $jurusan->singkatan = $request->singkatan;
            $jurusan->is_active = $request->is_active;
            $jurusan->save();

            $dataJurusan = DataJurusan::firstOrNew(['jurusan_id' => $jurusan->id]);
            $old_image = $dataJurusan->image;
            $nama_foto = ImageStorage::store($request->file('image'), 'jurusan');

            $dataJurusan->content = $request->content;

            if ($nama_foto) {
                $dataJurusan->image = $nama_foto;
            }

            $dataJurusan->save();

            if ($nama_foto) {
                ImageStorage::delete($old_image, 'jurusan');
            }

            DB::commit();
            Session::flash('success','Program Studi Berhasil diupdate !');
            return redirect()->route('program-studi.index');
            
        } catch (ErrorException $e) {
            DB::rollback();
            Session::flash('error','Program Studi Gagal diupdate !');
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
        $jurusan = Jurusan::findOrFail($id);
        $dataJurusan = $jurusan->dataJurusan;

        if ($dataJurusan) {
            ImageStorage::delete($dataJurusan->image, 'jurusan');
            $dataJurusan->delete();
        }

        $jurusan->delete();

        Session::flash('success','Program Studi Berhasil dihapus !');

        return redirect()->route('program-studi.index');
    }
}
