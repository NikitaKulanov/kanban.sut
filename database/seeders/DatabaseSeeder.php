<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('statuses')->insert([
            [
                'id' => 1,
                'title' => 'К ВЫПОЛНЕНИЮ'
            ],
            [
                'id' => 2,
                'title' => 'В РАБОТЕ'
            ],
            [
                'id' => 3,
                'title' => 'НА ОБСУЖДЕНИЕ'
            ],
            [
                'id' => 4,
                'title' => 'НА ПРОВЕРКЕ'
            ],
            [
                'id' => 5,
                'title' => 'ГОТОВО'
            ],
        ]);
    }
}
