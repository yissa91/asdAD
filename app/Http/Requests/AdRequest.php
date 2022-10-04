<?php

namespace App\Http\Requests;

use App\Models\category;
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
        $rules = [
            "specifications" => "json",
            "price" => "numeric|min:0|required",
            "description" => "between:10,1000|required",
            "title" => "between:10,80|required",
            //"available"=>"boolean|required",
            'category_id' => "numeric|required"
        ];

        $definition_id = $this->input('category_id');
        if ($definition_id != null) {
            $prefix = "p";

            $this->definition = category::with("property.options")->findOrFail($definition_id);

            foreach ($this->definition->property as $property) {
                $fieldName = $prefix . $property->id;
                if ($property->required)
                    $rules[$fieldName] = 'required';
                else
                    $rules[$fieldName] = 'nullable';
                switch ($property->type) {
                    case "Date":
                        $rules[$fieldName] = $rules[$fieldName] . '|date';
                        break;
                    case "String":
                        $rules[$fieldName] = $rules[$fieldName] . '|max:100';
                        break;
                    case "Text":
                        $rules[$fieldName] = $rules[$fieldName] . '|max:400';
                        break;
                    case "Bool":
                        $rules[$fieldName] = $rules[$fieldName] . '|boolean';
                        break;
                    case "Number":
                    case "Number float":

                        $rules[$fieldName] = $rules[$fieldName] . '|numeric';
                        break;
                    case "Multi value":
                        $rules[$fieldName] = $rules[$fieldName] . "|in:" . $property->options->pluck('id')->implode(",");
                        break;
                }

            }
        }
        return $rules;
    }
}
