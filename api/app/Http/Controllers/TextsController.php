<?php

namespace App\Http\Controllers;

use App\Models;
use App\Texts;
use Illuminate\Http\Request;

class TextsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param int $id
     * @return void
     */
    public function index(int $id)
    {
        $model = Models::with('Labels')->first();
        $texts = Texts::where('model_id', $id)->orderBy('id')->get()->toArray();
        return ['model' => $model, 'text' => $texts];
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Texts $texts
     * @return \Illuminate\Http\Response
     */
    public function show(Texts $texts)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Texts $texts
     * @return \Illuminate\Http\Response
     */
    public function edit(Texts $texts)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Texts $texts
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Texts $texts)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Texts $texts
     * @return \Illuminate\Http\Response
     */
    public function destroy(Texts $texts)
    {
        //
    }
}
