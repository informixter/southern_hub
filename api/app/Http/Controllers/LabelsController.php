<?php

namespace App\Http\Controllers;

use App\Labels;
use Illuminate\Http\Request;

class LabelsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param int $id
     * @return
     */
    public function index(int $id)
    {
        return Labels::where('model_id', $id)->get()->pluck('name', 'id')->toArray();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param int $model_id ID модели
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(int $model_id, Request $request)
    {
        $label = new Labels;
        $label->name = $request->get("name");
        $label->model_id = $model_id;

        if ($label->save()) {
            return response()->json(['status' => 'ok'], 200);
        }

        return response()->json(['status' => 'cant save model'], 500);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $model_id
     * @param int $label_id
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function edit(int $model_id, int $label_id, Request $request)
    {
        $label = Labels::where('id', $label_id);
        $label->name = $request->get("name");
        if ($label->save()) {
            return response()->json(['status' => 'ok'], 200);
        }
        return response()->json(['status' => 'cant save model'], 500);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $label_id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $label_id)
    {
        Labels::find($label_id)->delete();
        return response()->json(['status' => 'ok'], 200);
    }
}
