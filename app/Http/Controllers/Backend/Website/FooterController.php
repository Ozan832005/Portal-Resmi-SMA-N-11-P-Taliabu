<?php

namespace App\Http\Controllers\Backend\Website;

use App\Http\Controllers\Controller;
use App\Models\Footer;
use Illuminate\Http\Request;
use App\Http\Requests\FooterRequest;
use App\Support\ImageStorage;
use ErrorException;
use Session;

class FooterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $footer = Footer::first();
        return view('backend.website.content.footer.index',compact('footer'));
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
    public function store(FooterRequest $request)
    {
        try {
            $nama_image = ImageStorage::store($request->file('logo'), 'logo');

            $footer = new Footer;
            $footer->facebook   = $request->facebook;
            $footer->instagram  = $request->instagram;
            $footer->twitter    = $request->twitter;
            $footer->youtube    = $request->youtube;
            $footer->logo       = $nama_image;
            $footer->email      = $request->email;
            $footer->telp       = $request->telp;
            $footer->whatsapp   = $request->whatsapp;
            $footer->desc       = $request->desc;
            $footer->save();

            Session::flash('success','Data Berhasil dibuat !');
            return redirect()->route('backend-footer.index');
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
        // Data footer bersifat tunggal, pengubahan dilakukan langsung pada halaman index.
        return redirect()->route('backend-footer.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FooterRequest $request, $id)
    {
        try {
            $nama_image = ImageStorage::store($request->file('logo'), 'logo');

            $footer = Footer::findOrFail($id);
            $old_image = $footer->logo;
            $footer->facebook   = $request->facebook;
            $footer->instagram  = $request->instagram;
            $footer->twitter    = $request->twitter;
            $footer->youtube    = $request->youtube;
            $footer->logo       = $nama_image ?? $footer->logo;
            $footer->email      = $request->email;
            $footer->telp       = $request->telp;
            $footer->whatsapp   = $request->whatsapp;
            $footer->desc       = $request->desc;
            $footer->save();

            if ($nama_image) {
                ImageStorage::delete($old_image, 'logo');
            }

            Session::flash('success','Data Berhasil diupdate !');
            return redirect()->route('backend-footer.index');
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
        $footer = Footer::findOrFail($id);

        ImageStorage::delete($footer->logo, 'logo');
        $footer->delete();

        Session::flash('success','Data Berhasil dihapus !');

        return redirect()->route('backend-footer.index');
    }
}
