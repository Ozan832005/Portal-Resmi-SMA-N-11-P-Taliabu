<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use ErrorException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Modules\SPP\Entities\BankAccount;

class SettingController extends Controller
{
    // setting
    public function index()
    {
        return view('backend.settings.index');
    }

    // Tambah Bank
    public function addBank(Request $request)
    {
      $validated = $request->validate([
        'account_number' => 'required|string|max:50',
        'account_name'   => 'required|string|max:100',
        'bank_name'      => 'required|string|max:100',
        'is_active'      => 'required|in:0,1',
      ]);

      try {
        BankAccount::create([
          'user_id'         => Auth::id(),
          'account_number'  => $validated['account_number'],
          'account_name'    => $validated['account_name'],
          'bank_name'       => $validated['bank_name'],
          'is_active'       => $validated['is_active'],
          'is_primary'      => BankAccount::where('user_id', Auth::id())->doesntExist() ? 1 : 0,
        ]);
        Session::flash('success','Akun Bank Berhasil Ditambah.');
        return back();
      } catch (\ErrorException $e) {
        throw new ErrorException($e->getMessage());
      }
    }

    // Setting Notification
    public function notifications(Request $request)
    {
        try {
            $setting = Setting::firstOrCreate(
                ['user_id' => Auth::id()],
                ['isEmail' => false]
            );
            $setting->isEmail  = $request->isEmail ?? 0;
            $setting->update();
            Session::flash('success','Setting Berhasil Diupdate.');
            return back();
        } catch (Exception $e) {
            throw new ErrorException($e->getMessage());
        }
    }
}
