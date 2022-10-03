<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
         return [
            "specifications" => "json",
            "price" => "numeric|min:0|required",
            "description"=>"between:10,1000|required",
            "title"=>"between:10,80|required",
            "available"=>"boolean|required",
             'category_id'=>"numeric|required"
        ];
    }
}
