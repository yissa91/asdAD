<?php

namespace App\Http\Controllers;

use App\Models\category;
use App\Http\Requests\StorecategoryRequest;
use App\Http\Requests\UpdatecategoryRequest;

class CategoryController extends Controller
{

    public function index()
    {
        $query = category::query()->with(["ads"=>function($q){
            $q->where("available",true)->limit(10);
        }]);
        $categories = $query->get();
        return $categories;
    }

}
