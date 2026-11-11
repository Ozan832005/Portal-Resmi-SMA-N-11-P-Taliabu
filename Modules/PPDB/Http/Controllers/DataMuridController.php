<?php

namespace Modules\PPDB\Http\Controllers;

use App\Models\dataMurid;
use App\Models\User;
use ErrorException;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Modules\PPDB\Entities\BerkasMurid;
use Modules\SPP\Entities\DetailPaymentSpp;
use Modules\SPP\Entities\PaymentSpp;
use Modules\SPP\Entities\SppSetting;

class DataMuridController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index()
    {
        $murid = User::with('muridDetail')->where('role', 'Guest')->get();

        return view('ppdb::backend.dataMurid.index', compact('murid'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create()
    {
        return view('ppdb::create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show($id)
    {
        $murid = User::with('muridDetail', 'dataOrtu', 'berkas')->where('role', 'Guest')->find($id);
        if (! $murid->muridDetail->agama || ! $murid->dataOrtu->nama_ayah || ! $murid->berkas->kartu_keluarga) {
            Session::flash('error', 'Calon Siswa Belum Input Biodata Diri !');

            return redirect('/ppdb/data-murid');
        }

        return view('ppdb::backend.dataMurid.show', compact('murid'));

    }

    public function viewBerkas(string $id, string $field)
    {
        $allowedFields = [
            'kartu_keluarga',
            'akte_kelahiran',
            'surat_kelakuan_baik',
            'surat_sehat',
            'surat_tidak_buta_warna',
            'rapor',
            'foto',
            'ijazah',
        ];

        abort_unless(in_array($field, $allowedFields, true), 404);

        $applicant = User::where('role', 'Guest')->findOrFail($id);
        $berkas = BerkasMurid::where('user_id', $applicant->id)->firstOrFail();
        $filename = $berkas->{$field};

        abort_unless(is_string($filename) && $filename !== '', 404);

        $filename = basename(str_replace('\\', '/', $filename));
        $publicPath = 'images/berkas_murid/'.$filename;
        $legacyPath = 'public/'.$publicPath;
        $legacyDisk = config('filesystems.default');

        foreach ([
            ['local', $publicPath],
            ['public', $publicPath],
            [$legacyDisk, $legacyPath],
            ['local', $legacyPath],
            ['public', $legacyPath],
        ] as [$diskName, $path]) {
            $disk = Storage::disk($diskName);

            if ($disk->exists($path)) {
                return $disk->response($path, $filename, [], 'inline');
            }
        }

        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('ppdb::edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $validator = FacadesValidator::make($request->all(), [
                'nis' => 'required|numeric|unique:data_murids',
                'nisn' => 'required|numeric|unique:data_murids',
            ],
                [
                    'nis.required' => 'NIS tidak boleh kosong.',
                    'nisn.required' => 'NISN tidak boleh kosong.',
                    'nis.numeric' => 'NIS hanya mendukung angka.',
                    'nis.unique' => 'NIS sudah pernah digunakan.',
                    'nisn.numeric' => 'NISN hanya mendukung angka.',
                    'nisn.unique' => 'NISN sudah pernah digunakan.',
                ]
            );

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $murid = User::find($id);
            $murid->role = 'Murid';
            $murid->update();

            if ($murid) {
                $data = dataMurid::where('user_id', $id)->first();
                $data->nis = $request->nis;
                $data->nisn = $request->nisn;
                $data->proses = $murid->role;
                $data->update();

                // create data payment
                $this->payment($murid->id);
            }

            DB::table('model_has_roles')->where('model_id', $id)->delete();
            $murid->assignApplicationRole($murid->role);

            DB::commit();
            Session::flash('success', 'Success, Data Berhasil diupdate !');

            return redirect()->route('data-murid.index');
        } catch (ErrorException $e) {
            DB::rollback();
            throw new ErrorException($e->getMessage());
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    // Create Data Payment
    public function payment($murid)
    {
        try {
            DB::beginTransaction();
            $payment = PaymentSpp::create([
                'user_id' => $murid,
                'year' => date('Y'),
                'is_active' => 1,
            ]);

            if ($payment) {
                $spp = SppSetting::first();
                DetailPaymentSpp::create([
                    'payment_id' => $payment->id,
                    'user_id' => $murid,
                    'month' => date('F'),
                    'amount' => $spp->amount,
                    'status' => 'unpaid',
                    'file' => null,
                ]);
            }
            DB::commit();
        } catch (ErrorException $e) {
            DB::rollBack();
            throw new ErrorException($e->getMessage());
        }
    }
}
