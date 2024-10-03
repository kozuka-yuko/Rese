<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AreashopTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            ['area_id' => '1'],
            ['area_id' => '2'],
            ['area_id' => '3'],
        ];
        DB::table('area_shop')->insert($param);
        $param = [
            ['shop_id' => '2'],
            ['shop_id' => '3'],
            ['shop_id' => '4'],
            ['shop_id' => '5'],
            ['shop_id' => '6'],
            ['shop_id' => '7'],
            ['shop_id' => '8'],
            ['shop_id' => '9'],
            ['shop_id' => '10'],
            ['shop_id' => '11'],
            ['shop_id' => '12'],
            ['shop_id' => '13'],
            ['shop_id' => '14'],
            ['shop_id' => '15'],
            ['shop_id' => '16'],
            ['shop_id' => '17'],
            ['shop_id' => '18'],
            ['shop_id' => '19'],
            ['shop_id' => '20'],
            ['shop_id' => '21'],
        ];
        DB::table('area_shop')->insert($param);
    }
}
