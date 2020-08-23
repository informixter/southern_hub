<?php

require ('lib/Sinonims.php');
use App\Sins\Sinonims;
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
            'name' => "Классификатор текста",
            'status' => 'markup',
            'type' => 'classification',
        ]);

        DB::table('models')->insert([
            'name' => "Анализатор синонимов",
            'status' => 'markup',
            'type' => 'classification',
        ]);

        DB::table('models')->insert([
            'name' => "Исходные топики",
            'status' => 'markup',
            'type' => 'classification',
        ]);

        $tags = [
          'проблема',
          'интернет',
          'модем',
          'ip-телефония',
          'видеонаблюдение',
          'приветствие',
          'кабельное телевидение',
          'недовольство',
          'личный кабинет',
          'мобильная связь',
          'мобильная связь',
          'тарифы',
          'личный кабинет',
        ];

        $lines = [];
        $lines[] = implode("\t", array_merge([''], $tags));

        foreach ($tags as $tag)
        {
            DB::table('labels')->insert(['name' => $tag, 'model_id' => 1]);
        }

        foreach ($tags as $tag)
        {
            DB::table('labels')->insert(['name' => $tag, 'model_id' => 3]);
        }

       /* DB::table('labels')->insert(['name' => "проблема", 'model_id' => 1,]);  //1
        DB::table('labels')->insert(['name' => "интернет", 'model_id' => 1,]);  //2
        DB::table('labels')->insert(['name' => "модем", 'model_id' => 1,]);     //3
        DB::table('labels')->insert(['name' => "ip-телефония", 'model_id' => 1,]);//4
        DB::table('labels')->insert(['name' => "видеонаблюдение", 'model_id' => 1,]);//5
        DB::table('labels')->insert(['name' => "приветствие", 'model_id' => 1,]);//6
        DB::table('labels')->insert(['name' => "кабельное телевидение", 'model_id' => 1,]);//7
        DB::table('labels')->insert(['name' => "недовольство", 'model_id' => 1,]);//8
        DB::table('labels')->insert(['name' => "личный кабинет", 'model_id' => 1,]);//9
        DB::table('labels')->insert(['name' => "мобильная связь", 'model_id' => 1,]);//10
        DB::table('labels')->insert(['name' => "мобильная связь", 'model_id' => 1,]);//11
        DB::table('labels')->insert(['name' => "тарифы", 'model_id' => 1,]);//12
        DB::table('labels')->insert(['name' => "личный кабинет", 'model_id' => 1,]);//13*/

        $texts = [
            ['tags' => [1, 2], 'text' => 'У меня проблемы с интернетом, ничего не грузит, вы можете что-нибудь сделать?',
            ], ['tags' => [1, 2, 3], 'text' => 'ютуб не грузит, лампочка горит',
            ], ['tags' => [4, 6], 'text' => 'привет, у вас есть айпи-телефония?',
            ], ['tags' => [5], 'text' => 'вы камеры ставите? я бы хотел поставить себе камеры и с телефона контролировать',
            ], ['tags' => [1, 2], 'text' => 'алло, что с инетрнетом? постоянные сбои!',
            ], ['tags' => [6], 'text' => 'привет, есть кто?',
            ], ['tags' => [6], 'text' => 'привет',
            ], ['tags' => [1, 10], 'text' => 'не рабоатет телефон',
            ], ['tags' => [10], 'text' => 'подключить телефон',
            ], ['tags' => [5], 'text' => 'поставить камеру дома',
            ], ['tags' => [6, 7], 'text' => 'Здравтвуйте! сколько у вас стоит кабельное телевиднеие? в какую цену?',
            ], ['tags' => [1, 2], 'text' => 'шпд у вас какое? есть 15мб в секунду или меньше?',
            ], ['tags' => [6, 4], 'text' => 'Добрый вечер. Я представитель кампании ООО Техстрой Инвест. Мы хотим для своей кампании подключить ip-телефонию, чтобы сотрудники техподдержки по ней смогли принимать вызовы',
            ], ['tags' => [1, 2], 'text' => 'тупит инернет',
            ], ['tags' => [1, 10], 'text' => 'телефон не звонит',
            ], ['tags' => [1, 2, 3], 'text' => 'сайты не грузятся, я wifi раздал, пытался открыть сайт, но пишет что инета нет, что делать?',
            ], ['tags' => [6, 2, 3], 'text' => 'добрый вечер, на роутере лампочки не горят, хотя одна только красным горит и все, почему так?!',
            ], ['tags' => [6, 1], 'text' => 'Привет. вы на связи? проблема возникла',
            ], ['tags' => [2, 11], 'text' => 'вопрос, а мой трафик виден провайдеру? ну то что я посещаю, какие сайты и т.д?',
            ], ['tags' => [1, 2], 'text' => 'сегодня в 9 вечера инет упал, у вас тех. работы какие-то?',
            ], ['tags' => [6, 12], 'text' => 'Вечер добрый, какие тарифы у вас есть?',
            ], ['tags' => [6, 13, 2], 'text' => 'Здравствуйте, есть вопрос технического характера. В лс отметил, чтобы у меня интерент работал для отедльных айпишников (мой, жены). Но не работает эта настройки',
            ], ['tags' => [6, 1, 2], 'text' => 'Привет, я из-зза вас Игру престолов не могу смотреть, перезагрузите там инет у себя',
            ], ['tags' => [6, 5], 'text' => 'Здравствуйте, почем тарифы для камер? Сколько камер можете поставить?',
            ], ['tags' => [6, 5], 'text' => 'Привет, хочу поставить камеру в гараж, чтобы следить за машиной своей, сколько стоит?',
            ], ['tags' => [2, 12], 'text' => 'почем инет у вас',
            ], ['tags' => [6], 'text' => 'доброго вренеи суток. хочу себе быстрый шбд, чтобы все очень быстро грузилось',
            ], ['tags' => [1, 2], 'text' => 'youtube не грузит, у вас техработы какие-то?',
            ], ['tags' => [1, 2], 'text' => 'сколько у вас максимальна скорость? потому что у меня вот тупит',
            ], ['tags' => [6, ], 'text' => 'привет, я недоволен качеством услуг, можно вернуть деньги?',
            ], ['tags' => [6], 'text' => 'приветствую. у меня проблемы с подклчение к сети. можете помочь?']
        ];

        foreach ($texts as $text) {
            $id = DB::table('texts')->insertGetId([
                'text' => $text['text'],
                'model_id' => 1,
            ]);

            $textId = $id;
            foreach ($text['tags'] as $tagId)
            {
                DB::table('labels_texts')->insert(['model_id' => $tagId, 'text_id' => $textId]);
            }

            $line = [];
            $line[] = $text['text'];
            foreach ($tags as $key => $tag)
            {
                if (in_array($key, $text['tags']))
                {
                    $line[] = "1";
                }
                else
                {
                    $line[] = "0";
                }
            }

            $lines[] = implode("\t", $line);

        }

        $f = fopen("input.txt", "wt+");
        fwrite($f, implode("\n", $lines));
        fclose($f);

        DB::table('texts') -> insert([
            'text' => 'веб | сеть | инет | паутина | сетка | интернет | подсоединение | соединение | присоединение | включение | введение | шбд | подключение',
            'model_id' => 2
        ]);

        DB::table('texts') -> insert([
            'text' => 'проблемы | вопрос | беда | задача | тема | дело | положение | заморочка | засада | переделка | материя | геморрой | рана | переплет | трудность | затруднение | препятствие | осложнение | загвоздка | закавыка | дилемма | загадка | проблема',
            'model_id' => 2
        ]);

        DB::table('texts') -> insert([
            'text' => 'мало | бедно | хреново | нехорошо | дурно | неудачно | скверно | паршиво | некачественно | неудовлетворительно | погано | неладно | нездорово | недобросовестно | предосудительно | недостаточно | неважно | тонко | низко | понаслышке | тяжело | тяжко | нелегко | отвратительно | отвратно | неприглядно | наперекосяк | двойка | пара | неуд | два | страшный | жуткий | кошмарный | чудовищный | жестокий | дикий | плохой | зверский | страшнейший | злой | катастрофический | грозный | убийственный | тяжкий | смертный | собачий | бедственный | чрезвычайный | тугой | страховитый | отвратительный | мерзкий | гнусный | омерзительный | уродливый | безобразный | мрачный | зловещий | жутковатый | черный | адский | дьявольский | адов | бесовский | невыносимый | мучительный | отчаянный | безнадежный | панический | лихой | пугающий | устрашающий | трагический | трагичный | душераздирающий | страшенный | ужасный | ужаснейший | скверный | жутчайший | ужасающий | чертовский | смертельный | бешеный | отталкивающий | вялый | вялотекущий | неповоротливый | тихоходный | ленивый | нерасторопный | ползучий | замедленный | неторопливый | неспешный | постепенный | тихий | небыстрый | долгий | продолжительный | долговременный | кропотливый | тягучий | протяжный | долго | глупый | бестолковый | дурацкий | немой | бездарный | глухой | безмозглый | бессмысленный | тупоголовый | придурковатый | приглушенный | безответный | тупиковый | малоумный | грубый | прямолинейный | откровенный | твердолобый | унылый | скучный | непонятливый | невыразительный | отупевший | низкий | ограниченный | плохо | медленный | тупой | недовольство',
            'model_id' => 2
        ]);

        DB::table('texts') -> insert([
            'text' => 'здравствуйте | здравствуй | здорово | салют | поклон | чао | привет | хелло | приветствие',
            'model_id' => 2
        ]);

        DB::table('labels')->insert(['name' => 'интернет', 'model_id' => 2]);
        DB::table('labels')->insert(['name' => 'проблема', 'model_id' => 2]);
        DB::table('labels')->insert(['name' => 'недовольство', 'model_id' => 2]);
        DB::table('labels')->insert(['name' => 'приветствие', 'model_id' => 2]);


    }
}
