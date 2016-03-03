<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();
        DB::table('types')->insert([
            ['title' => '工作', 'created_at' => $now, 'updated_at' => $now],
            ['title' => '吃饭', 'created_at' => $now, 'updated_at' => $now],
            ['title' => '交通', 'created_at' => $now, 'updated_at' => $now],
            ['title' => '睡觉', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
