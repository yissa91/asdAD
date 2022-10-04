<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdRequest;
use App\Models\Ad;
use App\Models\category;
use App\Models\DefinitionPropertyLookupValue;
use App\Models\DefinitionPropertyValue;
use App\Models\ImageItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AdApiController extends Controller
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

    public function __construct()
    {
        $this->middleware(['auth:sanctum'],//, 'active', EnsurePhoneIsVerified::class],
            ['only' => [
                'store', 'update', 'destroy']]);
    }

    public function show($id)
    {
        Log::debug("id" . $id);
        $ad = Ad::query()->with(['user'
            , 'images', "category", 'propertiesValues', 'propertiesLookupValues.lookupValue'
        ])->findOrFail($id);
        $ad->view_number += 1;
        $ad->save();
        Log::debug("ad object", $ad->toArray());
        return $this->sendResponse($ad);
    }

    public function index(Request $request)
    {
        $query = Ad::query();

        if ($request->input('name') != null) {
            $name = $request->input('name');
            $query = $query->where(function ($query) use ($name) {
                $query->where('title', "like", "%$name%")
                    ->orWhere('description', "like", "%$name%");
            });
        }

        if ($request->input('min_price') != null)
            $query = $query->where('price', '>=', $request->input('min_price'));
        //dd($request->input());
        if ($request->input('max_price') != null) {

            $query = $query->where('price', '<=', $request->input('max_price'));

        }
        if ($request->input('date_min') != null)
            $query = $query->where('created_at', '>=', $request->input('date_min'));

        if ($request->input('date_max') != null)
            $query = $query->where('created_at', '<=', $request->input('date_max'));

        // كانت تابع خاص وصارت هيك أفضل
        if ($request->input("user_id"))
            $query = $query->where("user_id", $request->input("user_id"));
        // dd($request->input("category_id"));
        if ($request->input("category_id"))
            $query = $query->where("category_id", $request->input("category_id"));

        $query = $query->where("available", true);

        $query = $query->with(['images']);

        $orderBy = $request->input('orderBy', 'id');
        $orderType = $request->input('orderType', 'desc');

        $query = $query->orderBy($orderBy, $orderType);

        $limit = $request->input('limit', 10);
        $offset = $request->input('offset', 0);

        $query->limit($limit)->offset($offset);




        $prefix = "p";

        if ($request->input("category_id") != null) {
            $definition = category::findOrFail($request->input("category_id"));

            foreach ($definition->property as $property) {
                $key = $prefix . $property->id;
                if ($property->type == "Date" || $property->type == "Number" || $property->type == "Number float") {
                    if ($request->input($key . "min") == null && $request->input($key . "max") == null)
                        continue;
                } else if ($request->input($key) == null)
                    continue;

                $value = $request->input($key);
                switch ($property->type) {
                    case "Date":
                    case "Number":
                    case "Number float":
                        Log::debug($request->input($key . "min"));
                        $valueMin = (int) $request->input($key . "min");
                        $valueMax = (int) $request->input($key . "max");
                        $query = $query->whereHas('propertiesValues', function ($query) use ($valueMax, $valueMin) {
                            if ($valueMax != null)
                                $query = $query->where('value', '<=', $valueMax);
                            if ($valueMin != null)
                                $query = $query->where('value', '>=', $valueMin);
                            return $query;
                        });
                        break;
                    case "String":
                    case "Text":
                        $query = $query->whereHas('propertiesValues', function ($query) use ($value) {
                            $query->where('value',"like", "%$value%");
                        });
                        break;
                    case "Bool":
                        $query = $query->whereHas('propertiesValues', function ($query) use ($value) {
                            $query->where('value', $value);
                        });
                        break;
                    case "Multi value":
                        $query = $query->whereHas('propertiesLookupValues', function ($query) use ($value) {
                            $query->where('value_id', $value);
                        });
                        break;
                }
            }


        }
        $res = $query->get();
        return $this->sendResponse($res);

    }

    public function store(AdRequest $request)
    {
        //$this->authorize('create', Ad::class);
        $obj = $request->validated();
        $obj['user_id'] = $request->user()->id;

        $images = $request->file('images');
        $ad = DB::transaction(function () use ($obj, $images) {
            $ad = Ad::query()->create($obj);
            $this->AddPropertyDetails($obj, $ad);
            foreach ($images as $img) {
                $img_item = new ImageItem();
                $img_item->image = $img;
                // $img_item->owner_type = Ad::class;
                // $img_item->owner_id = $ad->id;
                $img_item->owner()->associate($ad);
                $img_item->save();
            }
            return $ad;
        });

        return $this->show($ad->id);
    }

    public function destroy(Ad $ad)
    {
        // $this->authorize('delete', $ad);
        $destroy = DB::transaction(function () use ($ad) {
            //          $ad->propertiesValues()->delete();
            foreach ($ad->images as $image) {
                $image->image = null;
            }
            $ad->images()->delete();
            //        $ad->propertiesLookupValues()->delete();
            return $ad->delete();
        });
        //  return $ad->delete();
        return $this->sendResponse($destroy);
    }

    public function update(AdRequest $request, Ad $ad)
    {
        //$this->authorize('update', $ad);
        $obj = $request->validated();
        $images = $request->file('images');
        $del_images = $request->input('del_imgs') != null ?
            ImageItem::query()->findMany($request->input('del_imgs')) : [];
        $ad = DB::transaction(function () use ($obj, $images, $ad, $del_images) {
            $ad->update($obj);
            //$this->updatePropertyDetails($obj, $ad);

            if ($images != null)
                foreach ($images as $img) {
                    $img_item = new ImageItem();
                    $img_item->image = file_get_contents($img->path());
                    $img_item->owner()->associate($ad);
                    $img_item->save();
                }

            foreach ($del_images as $img) {
                $img->image = null;
                $img->delete();
            }

            return $ad;
        });

        $ad->update($obj);
        return $this->show($ad->id);

    }


    private function updatePropertyDetails($data, $material)
    {

        $prefix = "p";
        foreach (category::findOrFail($material->category_id)->property
                 as $property) {
            switch ($property->type) {
                case "Date":
                case "Text":
                case "String":
                case "Number":
                case "Number float":
                case "Color":
                case "Bool":
                    $propertyValue = $material->propertiesValues->where('property_id', $property->id)->first();
                    if ($propertyValue == null) {
                        $propertyValue = new DefinitionPropertyValue();
                        $propertyValue->definition()->associate($property);
                        $propertyValue->owner()->associate($material);
                    }

                    if ($property->type == "Boolean") {
                        $filled = isset($data[$prefix . $property->id]);
                        $propertyValue->value = $filled ? $data[$prefix . $property->id] : 0;

                    } else {
                        // a continue inside a switch doesn't work
                        if (!isset($data[$prefix . $property->id])) {
                            // delete it if exist
                            if ($propertyValue->exists)
                                $propertyValue->delete();
                            break;
                        }
                        $propertyValue->value = $data[$prefix . $property->id];
                    }
                    $propertyValue->save();
                    break;
                case "Multi value":
                case "Multi color":
                    $propertyLookupValues = $material->propertiesLookupValues->where('property_id', $property->id);
                    if (!isset($data[$prefix . $property->id])) {
                        $material->propertiesLookupValues()->where('property_id', $property->id)->delete();
                    } else {

                        $dataValue = $data[$prefix . $property->id];
                        if ($property->single) {
                            $propertyLookupValue = $propertyLookupValues->first();
                            if ($propertyLookupValue == null) {
                                $lookup_value = new DefinitionPropertyLookupValue();
                                $lookup_value->definition()->associate($property);
                                $lookup_value->value_id = $dataValue;
                                $lookup_value->owner()->associate($material);
                                $lookup_value->save();
                            } else {
                                $propertyLookupValue->value_id = $dataValue;
                                $propertyLookupValue->save();
                            }
                        } else {
                            $dataValue = $data[$prefix . $property->id];
                            foreach ($propertyLookupValues as $plv) {
                                if (!in_array($plv->value_id, $dataValue)) {
                                    $plv->delete();
                                } else {
                                    // remove to not add later
                                    $dataValue = array_diff($dataValue, [$plv->value_id]);
                                }
                            }
                            // now input is all clear of old values, we can add new ones.
                            foreach ($dataValue as $value_id) {
                                $lvalue = new DefinitionPropertyLookupValue();
                                $lvalue->definition()->associate($property);
                                $lvalue->value_id = $value_id;
                                $lvalue->owner()->associate($material);
                                $lvalue->save();
                            }
                        }
                    }
            }
        }
    }

    /**
     * @param $data
     */
    private function AddPropertyDetails($data, $ad)
    {
        $data = Request()->all();
        $prefix = "p";
        $category = category::findOrFail($ad->category_id);
        $properties = $category->property;
        Log::debug($properties);

        foreach ($properties as $property) {

            if (!isset($data[$prefix . $property->id]))
                continue;
            $value = $data[$prefix . $property->id];
            switch ($property->type) {
                case "Date":
                case "Text":
                case "String":
                case "Number":
                case "Number float":
                case "Color":
                case "Bool":
                    Log::debug($property->type);
                    $propertyValue = new DefinitionPropertyValue();
                    $propertyValue->property_id = $property->id;
                    $propertyValue->owner()->associate($ad);
                    $propertyValue->value = $value;
                    $propertyValue->save();
                    break;
                case "Multi value":
                case "Multi color":
                    Log::debug($property->type);
                    $lookup_value = new DefinitionPropertyLookupValue();
                    $lookup_value->definition()->associate($property);
                    $lookup_value->value_id = $value;
                    $lookup_value->owner()->associate($ad);
                    $lookup_value->save();

                    break;
            }
        }
    }

    public function toggleHideShow(Request $request, Ad $ad)
    {
        if ($ad->user_id == $request->user()->id) { // user policy
            $ad->avialable = !$ad->avialable;
            $ad->save();
            return $this->sendResponse('done');
        }
    }


//
//
//    public function store(AdRequest $request)
//    {
//        $validated = $request->validated();
//        $validated['user_id'] = $request->user()->id;
//        $res = Ad::query()->create($validated);
//        $image = $request->file("image")->store("ad_image/$res->id", "public");;
//        $res->image = $image;
//        $res->save();
//        return Ad::query()->findOrFail($res->id);
//    }
//
//    public function index(Request $request)
//    {
//        $ads = Ad::query()->get();
//        return $ads;
//    }
//
//    public function show(Request $request, $id)
//    {
//        echo "hello";
//        $ad = Ad::query()->where("id", $id)->get()->first();
//        if ($ad != null)
//            echo "$ad->title -- $ad->description <br> ";
//        else
//            echo "not found";
//    }
//
//
//    public function update(AdRequest $request, $id)
//    {
//        $ad = Ad::query()->findOrFail($id);
//        $ad->update($request->validated());
//        if (request->file("image")) {
//            $image = $request->file("image")->store("ad_image/$res->id", "public");;
//            $res->image = $image;
//            $res->save();
//        }
//
//        return $ad->fresh();
//    }
//
//    public function destroy(Request $request, $id)
//    {
//        $ad = Ad::query()->findOrFail($id);
//        return $ad->delete();
//    }
}

