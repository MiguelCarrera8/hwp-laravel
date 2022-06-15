<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Activity;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $date = Carbon::now();

        $date = $date->format('Y-m-d');

        $user_id = Auth::id();

        $activity = Activity::whereNotIn('organizer_id', [$user_id])
            ->where('date', '>=', $date)
            ->orderBy('date', 'asc')
            ->with('user')
            ->get();

        // $articulos = $activity->orderBy('date', 'desc')->paginate(5);

        return $activity;
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
        // dd($request);

        $user_id = Auth::id();

        // dd($user_id);

        $activity = new Activity;
        $activity->name = $request->title;
        $activity->description = $request->description;
        $activity->location = $request->location;
        $activity->city = $request->city;
        $activity->hour = $request->hour;
        $activity->date = $request->date;
        $activity->organizer_id = $user_id;

        $activity->save();

        return response()->json([
            'message' => 'Successfully created activity!'
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user_id = Auth::id();

        $activity = Activity::where('id', $id)
            ->whereNotIn('organizer_id', [$user_id])
            ->with('user')
            ->get();

        return $activity;
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
        //
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
