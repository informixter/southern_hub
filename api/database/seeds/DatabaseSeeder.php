<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('models')->insert([
            'name' => "Первая модель",
            'status' => 'markup',
            'type' => 'classification',
        ]);

        DB::table('models')->insert([
            'name' => "Вторая модель",
            'status' => 'markup',
            'type' => 'classification',
        ]);

        DB::table('labels')->insert([
            'name' => "first_model_label_1",
            'model_id' => 1,
        ]);

        DB::table('labels')->insert([
            'name' => "first_model_label_2",
            'model_id' => 1,
        ]);

        $texts = [
            'Какие категории вопросов и задач можно решать в рамках технической поддержки?',
            'Как обратиться в службу технической поддержки, если не получается войти в консоль управления?',
            'Как быстро служба технической поддержки отреагирует на обращение?',
            'Как быстро служба технической поддержки решит выявленную проблему?',
            'Сколько раз можно обращаться в техническую поддержку?',
            'Как получить помощь в решении архитектурных задач?',
            'На какое программное обеспечение сторонних производителей распространяется техническая поддержка Яндекс.Облака?',
        ];

        foreach ($texts as $text) {
            DB::table('texts')->insert([
                'text' => $text,
                'model_id' => 1,
            ]);
        }


    }
}
