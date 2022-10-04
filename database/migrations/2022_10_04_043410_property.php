<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('definition_properties', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('label');
            $table->text('unit')->nullable()->default(null);
            $table->boolean('required')->default(false);
            $table->timestamps();
            $table->string('type'); // int - double - date - string - text - bool - multi option - multi color -
            $table->unsignedInteger('owner_id')->references('id')->on('category');
            $table->unsignedBigInteger('related')->nullable()->references('id')->on('definition_properties');
        });

        Schema::create('definition_property_options', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('value');
            $table->timestamps();
            $table->unsignedBigInteger('parent_id')->nullable()->references('id')->on('definition_property_options');
            $table->unsignedBigInteger('property_id')->references('id')->on('definition_properties');
        });

        Schema::create('definition_property_values', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('value')->nullable(false);
            $table->timestamps();
            $table->unsignedBigInteger('owner_id')->references('id')->on('ads');
            $table->unsignedBigInteger('property_id')->references('id')->on('definition_properties');
        });

        Schema::create('definition_property_lookup_values', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->unsignedBigInteger('property_id')->references('id')->on('definition_properties');
            $table->unsignedBigInteger('value_id')->references('id')->on('definition_property_options');
            $table->unsignedBigInteger('owner_id')->references('id')->on('ads');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
