<?php

namespace Modules\PPDB\Http\Controllers;

use App\Models\dataMurid;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use DB;
use ErrorException;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Routing\Controller;
use Modules\PPDB\Http\Requests\RegisterRequest;
use Session;

class AuthController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest');
    }

    // Register View
    public function registerView()
    {
        return view('ppdb::auth.register');
    }

    // Register Store
    public function registerStore(RegisterRequest $request)
    {
        try {
            DB::beginTransaction();

            $register = new User;
            $register->name = $request->name;
            $register->username = User::generateUsername($request->name);
            $register->email = $request->email;
            $register->role = 'Guest';
            $register->status = 'Aktif';
            $register->password = bcrypt($request->password);
            $register->save();

            if ($register) {
                $murid = new dataMurid;
                $murid->user_id = $register->id;
                $murid->whatsapp = $request->whatsapp;
                $murid->asal_sekolah = $request->asal_sekolah;
                $murid->save();
            }

            $register->assignApplicationRole($register->role);

            DB::commit();
            Session::flash('success', 'Success, Data Berhasil dikirim !');

            return redirect()->route('login');
        } catch (ErrorException $e) {
            DB::rollback();
            throw new ErrorException($e->getMessage());
        }
    }
}
