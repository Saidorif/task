<?php

use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $regions = array(
            "result" => array(
                array(
                    "name" => "Қорақалпоғистон Республикаси",
                ),
                array(
                    "name" => "Сирдарё",
                ),
                array(
                    "name" => "Қашқадарё",
                ),
                array(
                    "name" => "Тошкент вилояти",
                ),
                array(
                    "name" => "Навоий",
                ),
                array(
                    "name" => "Сурхондарё",
                ),
                array(
                    "name" => "Андижон",
                ),
                array(
                    "name" => "Жиззах",
                ),
                array(
                    "name" => "Наманган",
                ),
                array(
                    "name" => "Хоразм",
                ),
                array(
                    "name" => "Бухоро",
                ),
                array(
                    "name" => "Фарғона",
                ),
                array(
                    "name" => "Тошкент шаҳар",
                ),
                array(
                    "name" => "Самарқанд",
                )
            )
        );
        foreach($regions['result'] as $key => $item){
            $region = DB::table('regions')->insert($item);
        }
    }
}
