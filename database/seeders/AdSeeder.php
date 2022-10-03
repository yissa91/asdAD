<?php

namespace Database\Seeders;

use App\Models\category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user=User::query()->first();
        $category = category::query()->first();
        \App\Models\Ad::factory(10)->create(["category_id"=>$category->id,"user_id"=> $user->id]);


    }
}
