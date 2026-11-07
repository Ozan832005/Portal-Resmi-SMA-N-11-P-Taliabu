<?php

namespace App\Http\Controllers\Backend\Website;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImageSliderRequest;
use App\Support\ImageStorage;
use ErrorException;
use App\Models\ImageSlider;
use Illuminate\Http\Request;
use Session;

class ImageSliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $image = ImageSlider::all();
        return view('backend.website.content.imageSlider.index', compact('image'));
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
    public function store(ImageSliderRequest $request)
    {
        try {
            $nama_image = ImageStorage::store($request->file('image'), 'slider');

            $imageSlider = new ImageSlider;
            $imageSlider->image     = $nama_image;
            $imageSlider->desc      = $request->desc;
            $imageSlider->title     = $request->title;
            $imageSlider->urutan    = $request->urutan;
            $imageSlider->save();

            Session::flash('success','Gambar Slider Berhasil ditambah !');
            return redirect()->route('backend-imageslider.index');

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
        $image = ImageSlider::find($id);
        return view('backend.website.content.imageSlider.edit', compact('image'));
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
            $request->validate([
                'is_active' => 'required|in:0,1',
                'urutan'    => 'required|integer|min:1',
                'image'     => 'nullable|image|max:2048',
            ]);

            $nama_image = ImageStorage::store($request->file('image'), 'slider');

            $imageSlider = ImageSlider::findOrFail($id);
            $old_image = $imageSlider->image;
            $imageSlider->image     = $nama_image ?? $imageSlider->image;
            $imageSlider->title     = $request->title;
            $imageSlider->desc      = $request->desc;
            $imageSlider->urutan    = $request->urutan;
            $imageSlider->is_active = $request->is_active;
            $imageSlider->save();

            if ($nama_image) {
                ImageStorage::delete($old_image, 'slider');
            }

            Session::flash('success','Gambar Slider Berhasil diupdate !');
            return redirect()->route('backend-imageslider.index');

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
        $imageSlider = ImageSlider::findOrFail($id);

        ImageStorage::delete($imageSlider->image, 'slider');
        $imageSlider->delete();

        Session::flash('success','Gambar Slider Berhasil dihapus !');

        return redirect()->route('backend-imageslider.index');
    }
}
