<?php

namespace Database\Seeders;

use App\Models\category;
use App\Models\DefinitionProperty;
use App\Models\DefinitionPropertyOption;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class property extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $car = category::query()->where("name","cars")->first();
        $car_brand = DefinitionProperty::query()->create(["owner_id"=>$car->id,"label"=>"Brand","type"=>"Multi value","required"=>true]);
        DefinitionProperty::query()->create(["owner_id"=>$car->id,"label"=>"Number of doors","type"=>"Number","required"=>true]);
        DefinitionProperty::query()->create(["owner_id"=>$car->id,"label"=>"KM","type"=>"Number","required"=>true,"unit"=>"km"]);
        DefinitionProperty::query()->create(["owner_id"=>$car->id,"label"=>"Engine power","type"=>"Number float","required"=>true]);
        DefinitionProperty::query()->create(["owner_id"=>$car->id,"label"=>"Fule Type","type"=>"String","required"=>true]);
        DefinitionProperty::query()->create(["owner_id"=>$car->id,"label"=>"Addition info","type"=>"Text","required"=>false]);

        DefinitionPropertyOption::query()->create(["property_id"=>$car_brand->id,"value"=>"Renult"]);
        DefinitionPropertyOption::query()->create(["property_id"=>$car_brand->id,"value"=>"BMW"]);
        DefinitionPropertyOption::query()->create(["property_id"=>$car_brand->id,"value"=>"KIA"]);
        DefinitionPropertyOption::query()->create(["property_id"=>$car_brand->id,"value"=>"Mersides"]);


        $car = category::query()->where("name","buildings")->first();
        $building_type = DefinitionProperty::query()->create(["owner_id"=>$car->id,"label"=>"Type","type"=>"Multi value","required"=>true]);
        DefinitionProperty::query()->create(["owner_id"=>$car->id,"label"=>"Number of Rooms","type"=>"Number","required"=>true]);
        DefinitionProperty::query()->create(["owner_id"=>$car->id,"label"=>"Direction","type"=>"String","required"=>true]);
        DefinitionProperty::query()->create(["owner_id"=>$car->id,"label"=>"Floor","type"=>"Number","required"=>true]);
        DefinitionProperty::query()->create(["owner_id"=>$car->id,"label"=>"String","type"=>"String","required"=>true]);
        DefinitionProperty::query()->create(["owner_id"=>$car->id,"label"=>"Addition info","type"=>"Text","required"=>false]);

        DefinitionPropertyOption::query()->create(["property_id"=>$building_type->id,"value"=>"Villa"]);
        DefinitionPropertyOption::query()->create(["property_id"=>$building_type->id,"value"=>"Apartment"]);
        DefinitionPropertyOption::query()->create(["property_id"=>$building_type->id,"value"=>"studio"]);
    }
}
