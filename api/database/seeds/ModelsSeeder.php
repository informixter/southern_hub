<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ModelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
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
    }
}
