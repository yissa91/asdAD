<?php

namespace App\Http\Controllers;

use App\Models\category;
use App\Http\Requests\StorecategoryRequest;
use App\Http\Requests\UpdatecategoryRequest;

class CategoryController extends Controller
{
    private function sendResponse($result = null, $count = null)
    {
        $response = [
            'success' => true,
            'data' => $result == null ? '' : $result,
        ];
        if ($count != null)
            $response['count'] = $count;
        return $response;
    }

    public function index()
    {
        $query = category::query()->with(["ads"=>function($q){
            $q->where("available",true)->limit(10);
        }])->withCount("ads");
        //$query->whereHas("ads");
        $categories = $query->get();
        return $this->sendResponse($categories);

    }

    public function show($id)
    {
        $res = category::query()->where("id",$id)->with(["property","property.options"])->get()->first();
        return $this->sendResponse($res);

    }
}
