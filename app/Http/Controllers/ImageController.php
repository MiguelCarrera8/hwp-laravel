<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Image;
use Carbon\Carbon;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $image = Image::wherenotIn('user_id', [Auth::id()])
            ->orderBy('created_at', 'desc')
            ->with('user')
            ->get();
        return $image;
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
        $date = Carbon::now();

        $date = $date->format('Y-m-d');

        $user_id = Auth::id();

        $img = $request->get('image');
        $img = str_replace('data:image/jpeg;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $img = base64_decode($img);
        $imageName = date('mdYHis') . uniqid() . '.jpeg';
        Storage::disk('public')->put('pictures/' . $imageName, $img);
        $path = "pictures/" . $imageName;
        $request->merge(['image' => $path]);

        $request->image = $path;

        $image = Image::create([
            'user_id' => $user_id,
            'date' => $date,
            'image' => $request->image,
        ]);

        return response()->json([
            'message' => 'Image created successfully',
            'image' => $image,
        ]);
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
    public function update(Request $request, $id)
    {
        $image = Image::find($id);

        $image->like_count = $request->like_count;

        $image->save();

        return response()->json([
            'message' => 'Image updated successfully',
            'image' => $image,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
