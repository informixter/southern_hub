<?php

namespace App\Http\Controllers;

use App\Models;
use Illuminate\Http\Request;

class ModelsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Models::with('Labels')->get()->toArray();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $model = new Models;
        $model->name = $request->get("name");

        if ($model->save()) {
            return response()->json(['status' => 'ok'], 200);
        }

        return response()->json(['status' => 'cant save model'], 500);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id, \Illuminate\Http\Request $request)
    {
        $model = Models::find($id);
        $model->name = $request->get("name");
        if ($model->save()) {
            return response()->json(['status' => 'ok'], 200);
        }
        return response()->json(['status' => 'cant save model'], 500);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models $models
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id, Models $models)
    {
        Models::find($id)->delete();
        return response()->json(['status' => 'ok'], 200);
    }
}
