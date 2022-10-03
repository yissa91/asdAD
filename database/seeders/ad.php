<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ad extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Ad::factory(1)->create(["title"=>"yara"]);
        \App\Models\Ad::factory(1)->create(["title"=>"tat"]);
        \App\Models\Ad::factory(1)->create(["title"=>"asdasd"]);
        \App\Models\Ad::factory(1)->create(["title"=>"324146"]);

    }
}
