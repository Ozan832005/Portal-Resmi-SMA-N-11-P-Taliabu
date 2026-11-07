<?php

namespace App\Http\Controllers\Backend\Website;

use App\Http\Controllers\Controller;
use App\Http\Requests\FasilitasRequest;
use App\Models\Fasilitas;
use App\Support\ImageStorage;
use Illuminate\Support\Facades\DB;
use Session;

class FasilitasController extends Controller
{
    /**
     * Daftar fasilitas + form tambah pada satu halaman.
     */
    public function index()
    {
        $fasilitas = Fasilitas::orderBy('urutan')->orderBy('id')->get();

        return view('backend.website.content.fasilitas.index', compact('fasilitas'));
    }

    public function create()
    {
        return redirect()->route('backend-fasilitas.index');
    }

    public function store(FasilitasRequest $request)
    {
        try {
            DB::beginTransaction();

            $fasilitas = new Fasilitas;
            $fasilitas->nama = $request->nama;
            $fasilitas->desc = $request->desc;
            $fasilitas->urutan = $request->urutan ?? 0;
            $fasilitas->is_active = $request->is_active;
            $fasilitas->image = ImageStorage::store($request->file('image'), 'fasilitas');
            $fasilitas->save();

            DB::commit();
            Session::flash('success', 'Fasilitas Berhasil ditambah !');
        } catch (\Throwable $e) {
            DB::rollback();

            return back()->withInput()->with('error', 'Fasilitas gagal disimpan: '.$e->getMessage());
        }

        return redirect()->route('backend-fasilitas.index');
    }

    public function show($id)
    {
        return redirect()->route('backend-fasilitas.index');
    }

    public function edit($id)
    {
        $fasilitas = Fasilitas::findOrFail($id);
        $semua = Fasilitas::orderBy('urutan')->orderBy('id')->get();

        return view('backend.website.content.fasilitas.edit', [
            'fasilitas' => $fasilitas,
            'semua' => $semua,
        ]);
    }

    public function update(FasilitasRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $fasilitas = Fasilitas::findOrFail($id);
            $gambarBaru = ImageStorage::store($request->file('image'), 'fasilitas');
            $gambarLama = $fasilitas->image;

            $fasilitas->nama = $request->nama;
            $fasilitas->desc = $request->desc;
            $fasilitas->urutan = $request->urutan ?? 0;
            $fasilitas->is_active = $request->is_active;
            $fasilitas->image = $gambarBaru ?? $gambarLama;
            $fasilitas->save();

            if ($gambarBaru) {
                ImageStorage::delete($gambarLama, 'fasilitas');
            }

            DB::commit();
            Session::flash('success', 'Fasilitas Berhasil diupdate !');
        } catch (\Throwable $e) {
            DB::rollback();

            return back()->withInput()->with('error', 'Fasilitas gagal diupdate: '.$e->getMessage());
        }

        return redirect()->route('backend-fasilitas.index');
    }

    public function destroy($id)
    {
        $fasilitas = Fasilitas::findOrFail($id);

        ImageStorage::delete($fasilitas->image, 'fasilitas');
        $fasilitas->delete();

        Session::flash('success', 'Fasilitas Berhasil dihapus !');

        return redirect()->route('backend-fasilitas.index');
    }
}
