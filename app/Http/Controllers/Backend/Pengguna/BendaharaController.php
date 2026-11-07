<?php

namespace App\Http\Controllers\Backend\Pengguna;

use App\Http\Controllers\Controller;
use App\Http\Requests\BendaharaRequest;
use App\Models\User;
use App\Models\UsersDetail;
use App\Support\ImageStorage;
use ErrorException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class BendaharaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $bendahara = User::with('userDetail')->where('role','Bendahara')->get();
      return view('backend.pengguna.bendahara.index', compact('bendahara'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('backend.pengguna.bendahara.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BendaharaRequest $request)
    {
      try {
            DB::beginTransaction();

            $nama_img = ImageStorage::store($request->file('foto_profile'), 'profile');

            // Pilih kalimat
            $kalimatKe  = "1";
            $username   = implode(" ", array_slice(explode(" ", $request->name), 0, $kalimatKe)); // ambil kalimat

            $user = new User;
            $user->name             = $request->name;
            $user->email            = $request->email;
            $user->username         = strtolower($username).date("s");
            $user->role             = 'Bendahara';
            $user->status           = 'Aktif';
            $user->foto_profile      = $nama_img;
            $user->password         = bcrypt('12345678');
            $user->save();

            if ($user) {
                $userDetail = new UsersDetail();
                $userDetail->user_id      = $user->id;
                $userDetail->role         = $user->role;
                $userDetail->nip          = $request->nip;
                $userDetail->email        = $request->email;
                $userDetail->linkedln     = $request->linkedln;
                $userDetail->instagram    = $request->instagram;
                $userDetail->website      = $request->website;
                $userDetail->facebook     = $request->facebook;
                $userDetail->twitter      = $request->twitter;
                $userDetail->youtube      = $request->youtube;
                $userDetail->save();
            }

            $user->assignRole($user->role);
            DB::commit();
            Session::flash('success','Bendahara Berhasil ditambah !');
            return redirect()->route('backend-pengguna-bendahara.index');

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
      $bendahara = User::with('userDetail')->where('role','Bendahara')->findOrFail($id);
      return view('backend.pengguna.bendahara.edit', compact('bendahara'));
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
      try {
            DB::beginTransaction();

            $nama_img = ImageStorage::store($request->file('foto_profile'), 'profile');

            $user = User::findOrFail($id);
            $old_image = $user->foto_profile;
            $user->name             = $request->name;
            $user->email            = $request->email;
            $user->status           = $request->status;
            $user->foto_profile      = $nama_img ?? $user->foto_profile;
            $user->save();

            if ($nama_img) {
                ImageStorage::delete($old_image, 'profile');
            }

            if ($user) {
                $userDetail = UsersDetail::firstOrNew(['user_id' => $user->id]);
                $userDetail->user_id      = $user->id;
                $userDetail->is_active    = $user->status == 'Aktif' ? '0' : '1';
                $userDetail->nip          = $request->nip;
                $userDetail->email        = $request->email;
                $userDetail->linkedln     = $request->linkedln;
                $userDetail->instagram    = $request->instagram;
                $userDetail->website      = $request->website;
                $userDetail->facebook     = $request->facebook;
                $userDetail->twitter      = $request->twitter;
                $userDetail->youtube      = $request->youtube;
                $userDetail->save();
            }

            DB::commit();
            Session::flash('success','Bendahara Berhasil diubah !');
            return redirect()->route('backend-pengguna-bendahara.index');

        } catch (ErrorException $e) {
            DB::rollback();
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
        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);
            $old_image = $user->foto_profile;

            UsersDetail::where('user_id', $user->id)->delete();
            $user->syncRoles([]);
            $user->delete();

            ImageStorage::delete($old_image, 'profile');

            DB::commit();
            Session::flash('success','Bendahara Berhasil dihapus !');
        } catch (ErrorException $e) {
            DB::rollback();
            Session::flash('error','Bendahara Gagal dihapus !');
        }

        return redirect()->route('backend-pengguna-bendahara.index');
    }
}
