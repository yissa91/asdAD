<?php

namespace Database\Seeders;

use App\Models\category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        category::factory()->create(["name" => "Buildings", "description" => "", "image" => "category/building.png"]);
        category::factory()->create(["name" => "Cars", "description" => "", "image" => "category/car.png"]);
        category::factory()->create(["name" => "Car Parts", "description" => "", "image" => "category/car_part.png"]);
        category::factory()->create(["name" => "Furniture", "description" => "", "image" => "category/furniture.png"]);
        category::factory()->create(["name" => "Electrical Devices", "description" => "", "image" => "category/electrical_device.png"]);
        category::factory()->create(["name" => "Smart Devices", "description" => "", "image" => "category/smart_device.png"]);
        category::factory()->create(["name" => "Beauty Tools", "description" => "", "image" => "category/beauty_tool.png"]);
        category::factory()->create(["name" => "Toys", "description" => "", "image" => "category/toy.jpg"]);
        category::factory()->create(["name" => "Industrial Tools", "description" => "", "image" => "category/industrial_tool.jpg"]);
        category::factory()->create(["name" => "Sport Equipments", "description" => "", "image" => "category/sport_equipment.jpg"]);
        category::factory()->create(["name" => "Job Applications", "description" => "", "image" => "category/job_application.png"]);
    }
}
