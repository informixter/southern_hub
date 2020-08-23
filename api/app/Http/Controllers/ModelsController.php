<?php

namespace App\Http\Controllers;

use App\Labels;
use App\LabelsTexts;
use App\Models;
use App\Texts;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use mysql_xdevapi\Exception;

class ModelsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $text = $request->get("text");
        $words = explode(" ", mb_strtoupper($text));
        $lemms = $this->normalizeMyStem($words);
        array_walk($words, function (&$word) use ($lemms) {
            $this->normalizWord($word, $lemms);
        });

        $tags = [];
        $texts = Texts::where('model_id', 2)->orderBy('id')->get()->toArray();
        for ($i = 0, $max = sizeof($texts); $i < $max; $i++) {
            $texts[$i]['tags'] = LabelsTexts::where('text_id', $texts[$i]['id'])->get()->toArray();
            for ($j = 0, $max2 = sizeof($texts[$i]['tags']); $j < $max2; $j++) {
                $texts[$i]['tags'][$j] = Labels::where('id', $texts[$i]['tags'][$j]['model_id'])->get()->toArray()[0]['name'];
            }

            $wordsTemp = explode(' | ', $texts[$i]['text']);
            if (sizeof(array_intersect($words, $wordsTemp)) !== 0) {
                $tags = array_merge($tags, $texts[$i]['tags']);
            }
        }

        return $this->findResultForTags($tags);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search2(Request $request)
    {
        $text = 'плохой интернет';
        $words = explode(" ", mb_strtoupper($text));
        $lemms = $this->normalizeMyStem($words);
        array_walk($words, function (&$word) use ($lemms) {
            $this->normalizWord($word, $lemms);
        });

        $tags = [];
        $texts = Texts::where('model_id', 2)->orderBy('id')->get()->toArray();
        for ($i = 0, $max = sizeof($texts); $i < $max; $i++) {
            $texts[$i]['tags'] = LabelsTexts::where('text_id', $texts[$i]['id'])->get()->toArray();
            for ($j = 0, $max2 = sizeof($texts[$i]['tags']); $j < $max2; $j++) {
                $texts[$i]['tags'][$j] = Labels::where('id', $texts[$i]['tags'][$j]['model_id'])->get()->toArray()[0]['name'];
            }

            $wordsTemp = explode(' | ', $texts[$i]['text']);
            if (sizeof(array_intersect($words, $wordsTemp)) !== 0) {
                $tags = array_merge($tags, $texts[$i]['tags']);
            }
        }

        return $this->findResultForTags($tags);
    }

    /**
     * @param $tags
     */
    public function findResultForTags($tags)
    {
        $texts = Texts::where('model_id', 3)->orderBy('id')->get()->toArray();
        $result = [];
        for ($i = 0, $max = sizeof($texts); $i < $max; $i++) {
            $texts[$i]['tags'] = LabelsTexts::where('text_id', $texts[$i]['id'])->get()->toArray();
            for ($j = 0, $max2 = sizeof($texts[$i]['tags']); $j < $max2; $j++) {
                $texts[$i]['tags'][$j] = Labels::where('id', $texts[$i]['tags'][$j]['model_id'])->get()->toArray()[0]['name'];
            }

            if (sizeof(array_intersect($texts[$i]['tags'], $tags)) === sizeof($tags) and sizeof($tags) !== 0) {
                $result[] = $texts[$i]['text'];
            }
        }

        return $result;
    }

    /**
     * нормализация с использованием yandex mystem
     * @param array $words - массив слов
     * @return array
     */
    public function normalizeMyStem($words)
    {
        $words = array_values(array_unique($words));
        $file_name = '../lib/' . time() . rand(0, 1000);
        $file = fopen($file_name, 'wt+');
        fwrite($file, implode("\n", $words));
        fclose($file);
        $result = `../lib/mystem -n $file_name`;
        $result = mb_strtoupper($result, 'utf-8');
        unlink($file_name);
        $result = explode("\n", $result);
        $lemms = [];
        for ($i = 0, $max = sizeof($result); $i < $max; $i++) {
            $result[$i] = preg_replace("/\?/", '', $result[$i]);
            $result[$i] = preg_match("/([^{]*){([^}]*)}/", $result[$i], $matches);
            if ($matches) {
                $lemms[$matches[1]] = explode('|', $matches[2]);
            }
        }
        return $lemms;
    }

    /**
     * нормализация слова
     * @param string $word
     * @param array $lemms
     * @return mixed
     */
    public function normalizWord(&$word, &$lemms)
    {
        $normWord = $word;
        if (isset($lemms[$word]) and $lemms[$word]) //если есть нормальная форма для слова (без спец. символов)
        {
            $normWord = $lemms[$word][0];
        }
        $word = mb_strtolower($normWord, 'utf-8');
        return true;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Models::with('Labels')->where('id', '<>', 4)->get()->toArray();
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
            return response()->json($model->toArray(), 200);
        }

        return response()->json(['status' => 'cant save model'], 500);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $model_id
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function edit(int $model_id, \Illuminate\Http\Request $request)
    {
        $model = Models::find($model_id);
        $model->name = $request->get("name");
        if ($model->save()) {
            return response()->json(['status' => 'ok'], 200);
        }
        return response()->json(['status' => 'cant save model'], 500);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $model_id
     * @param \App\Models $models
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $model_id, Models $models)
    {
        Models::find($model_id)->delete();
        return response()->json(['status' => 'ok'], 200);
    }

    /**
     * @param int $model_id
     * @param Request $request
     */
    public function test(int $model_id, Request $request)
    {
        $texts = [
            'Домашний интернет — стабильный доступ к глобальной сети по удобному тарифу',
            'Телевидение — каналы на любой вкус за скромные деньги',
        ];

        $client = new Client();
        $headers = ['ContentType' => 'application/json'];
        $response = $client->post("http://auto-ml:8080/predict/" + $model_id, ['headers' => $headers, 'json' => ['text'->$request->get()]);
    }

    /**
     * Запуск обучения модели
     * @param int $model_id
     */
    public function run(int $model_id)
    {
        $client = new Client();
        try {
            $response = $client->get("http://auto-ml:8080/train/" + $model_id);
            $content = json_decode($response->getBody()->getContents(), true);
            return $content;
        } catch (RequestException $e) {
            return response()->json(["status" => "err", "msg" => $e->getResponse()->getBody()], 500);
        }
    }

    /**
     * Получение информации о процессе обучения модели
     */
    public function info(int $model_id)
    {
    }

    /**
     * Получение информации о процессе обучения модели
     */
    public function testRequest(int $model_id)
    {
        $phrase = 'Интернет говно';
        //$tags = магияML($phrase);
        //return $tags;
    }
}
