<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileSettingsRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Support\ImageStorage;
use ErrorException;
use Illuminate\Support\Facades\Session;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $profile = User::whereId(Auth::id())->first();
        return view('backend.profile.index', compact('profile'));
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
    public function store(Request $request)
    {
        //
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
    public function update(ProfileSettingsRequest $request, $id)
    {
        try {
            // Profil hanya boleh mengubah akun milik pengguna yang sedang login.
            $profile = User::findOrFail(Auth::id());
            $emailBerubah = $profile->email !== $request->email;

            $nama_image = ImageStorage::store($request->file('foto_profile'), 'profile');
            $old_image = $profile->foto_profile;

            $profile->name          = $request->name;
            $profile->username      = $request->username;
            $profile->email         = $request->email;
            $profile->foto_profile  = $nama_image ?? $profile->foto_profile;
            if ($emailBerubah) {
                $profile->email_verified_at  = NULL;
            }
            $profile->save();

            if ($nama_image) {
                ImageStorage::delete($old_image, 'profile');
            }

            Session::flash('success','Profile Berhasil diupdate !');
            return back();

        } catch (ErrorException $e) {
            throw new ErrorException($e->getMessage());
        }
    }

    // Ubah Password
    public function changePassword(ChangePasswordRequest $request, $id)
    {
       try {
            // Password hanya boleh diubah untuk akun milik pengguna yang sedang login.
            $profile = User::findOrFail(Auth::id());
            $profile->password   = bcrypt($request->password);
            $profile->save();

            Session::flash('success','Password Berhasil diupdate !');
            return back();

       } catch (ErrorException $e) {
           throw new ErrorException($e->getMessage());
       }
    }

    /**
     * Akun milik sendiri tidak dihapus dari halaman profil.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Session::flash('error','Akun tidak dapat dihapus dari halaman profil.');

        return back();
    }
}
