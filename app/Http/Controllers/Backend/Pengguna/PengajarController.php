<?php

namespace App\Http\Controllers\Backend\Pengguna;

use App\Http\Controllers\Controller;
use App\Http\Requests\PengajarRequest;
use App\Models\User;
use App\Models\UsersDetail;
use App\Support\ImageStorage;
use ErrorException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Session;

class PengajarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $pengajar = User::with('userDetail')->where('role', 'Guru')->get();

        return view('backend.pengguna.pengajar.index', compact('pengajar'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('backend.pengguna.pengajar.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(PengajarRequest $request)
    {
        try {
            DB::beginTransaction();

            $nama_img = ImageStorage::store($request->file('foto_profile'), 'profile');

            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->username = User::generateUsername($request->name);
            $user->role = 'Guru';
            $user->status = 'Aktif';
            $user->foto_profile = $nama_img;
            $user->password = bcrypt('12345678');
            $user->save();

            if ($user) {
                $userDetail = new UsersDetail;
                $userDetail->user_id = $user->id;
                $userDetail->role = $user->role;
                $userDetail->mengajar = $request->mengajar;
                $userDetail->nip = $request->nip;
                $userDetail->email = $request->email;
                $userDetail->linkedln = $request->linkedln;
                $userDetail->instagram = $request->instagram;
                $userDetail->website = $request->website;
                $userDetail->facebook = $request->facebook;
                $userDetail->twitter = $request->twitter;
                $userDetail->youtube = $request->youtube;
                $userDetail->save();
            }

            $user->assignRole($user->role);
            DB::commit();
            Session::flash('success', 'Pengajar Berhasil ditambah !');

            return redirect()->route('backend-pengguna-pengajar.index');

        } catch (ErrorException $e) {
            DB::rollback();
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
        $pengajar = User::with('userDetail')->where('role', 'Guru')->findOrFail($id);

        return view('backend.pengguna.pengajar.edit', compact('pengajar'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $nama_img = ImageStorage::store($request->file('foto_profile'), 'profile');

            $user = User::findOrFail($id);
            $old_image = $user->foto_profile;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->status = $request->status;
            $user->foto_profile = $nama_img ?? $user->foto_profile;
            $user->save();

            if ($nama_img) {
                ImageStorage::delete($old_image, 'profile');
            }

            if ($user) {
                $userDetail = UsersDetail::firstOrNew(['user_id' => $user->id]);
                $userDetail->user_id = $user->id;
                $userDetail->is_active = $user->status == 'Aktif' ? '0' : '1';
                $userDetail->mengajar = $request->mengajar;
                $userDetail->nip = $request->nip;
                $userDetail->email = $request->email;
                $userDetail->linkedln = $request->linkedln;
                $userDetail->instagram = $request->instagram;
                $userDetail->website = $request->website;
                $userDetail->facebook = $request->facebook;
                $userDetail->twitter = $request->twitter;
                $userDetail->youtube = $request->youtube;
                $userDetail->save();
            }

            DB::commit();
            Session::flash('success', 'Pengajar Berhasil diubah !');

            return redirect()->route('backend-pengguna-pengajar.index');

        } catch (ErrorException $e) {
            DB::rollback();
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
        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);
            $old_image = $user->foto_profile;

            UsersDetail::where('user_id', $user->id)->delete();
            $user->syncRoles([]);
            $user->delete();

            ImageStorage::delete($old_image, 'profile');

            DB::commit();
            Session::flash('success', 'Pengajar Berhasil dihapus !');
        } catch (ErrorException $e) {
            DB::rollback();
            Session::flash('error', 'Pengajar Gagal dihapus !');
        }

        return redirect()->route('backend-pengguna-pengajar.index');
    }
}
