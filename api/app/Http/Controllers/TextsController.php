<?php

namespace App\Http\Controllers;

use App\Labels;
use App\LabelsTexts;
use App\Models;
use App\Texts;
use Faker\Provider\Text;
use Illuminate\Http\Request;

class TextsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param int $model_id
     * @return array
     */
    public function index(int $model_id)
    {
        $model = Models::where('id', $model_id)->get()->toArray()[0];
        $texts = Texts::where('model_id', $model_id)->orderBy('id')->get()->toArray();

        for ($i = 0, $max = sizeof($texts); $i < $max; $i++)
        {
            $texts[$i]['tags'] = LabelsTexts::where('text_id', $texts[$i]['id'])->get()->toArray();
            for ($j = 0, $max2 = sizeof($texts[$i]['tags']); $j < $max2; $j++)
            {
                $texts[$i]['tags'][$j] = Labels::where('id', $texts[$i]['tags'][$j]['model_id'])->get()->toArray()[0];
            }
        }

        return ['model' => $model, 'texts' => $texts];
    }

    /**
     * Display a listing of the resource.
     *
     * @param int $textId
     * @return array
     */
    public function byId(int $textId)
    {
        $text = Texts::where('id', $textId)->get()->toArray()[0];
        $text['tags'] = LabelsTexts::where('text_id', $textId)->get()->toArray();
        return $text;
    }

    public function addText (int $model_id, Request $request)
    {
        $text = new Texts();
        $text->text = $request->get("text");
        $text->model_id = $model_id;

        if ($text->save()) {
            return response()->json($text -> toArray(), 200);
        }
    }

    public function editText (int $textId, Request $request)
    {
        Texts::where('id', $textId)->update(['text' => $request->get("text")]);
        return true;
    }

    public function saveLabels (int $textId, Request $request)
    {
        $labelsIds = $request -> get('labelsIds');
        LabelsTexts::where('text_id', $textId) -> delete();
        for ($i = 0, $max = sizeof($labelsIds); $i < $max; $i++)
        {
            $label = new LabelsTexts;
            $label->model_id = $labelsIds[$i];
            $label->text_id = $textId;
            $label->save();
        }
        return true;
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
