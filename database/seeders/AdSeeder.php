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
        $building = category::query()->where("name","Buildings")->first();
        $car = category::query()->where("name","Cars")->first();

        \App\Models\Ad::factory()->create(["category_id"=>$building->id,"user_id"=> $user->id,"image"=>"ad/1.jpg"]);
        \App\Models\Ad::factory()->create(["category_id"=>$car->id,"user_id"=> $user->id,"image"=>"ad/2.jpg"]);
        \App\Models\Ad::factory()->create(["category_id"=>$building->id,"user_id"=> $user->id,"image"=>"ad/3.jpg"]);
        \App\Models\Ad::factory()->create(["category_id"=>$car->id,"user_id"=> $user->id,"image"=>"ad/4.jpg"]);
        \App\Models\Ad::factory()->create(["category_id"=>$building->id,"user_id"=> $user->id,"image"=>"ad/5.jpg"]);
        \App\Models\Ad::factory()->create(["category_id"=>$car->id,"user_id"=> $user->id,"image"=>"ad/6.jpg"]);
        \App\Models\Ad::factory()->create(["category_id"=>$building->id,"user_id"=> $user->id,"image"=>"ad/7.jpg"]);
        \App\Models\Ad::factory()->create(["category_id"=>$car->id,"user_id"=> $user->id,"image"=>"ad/8.jpg"]);
        \App\Models\Ad::factory()->create(["category_id"=>$building->id,"user_id"=> $user->id,"image"=>"ad/9.jpg"]);





    }
}
