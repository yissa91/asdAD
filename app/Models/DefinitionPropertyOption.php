<?php

namespace App\Models;

use App\Traits\HasImage;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DefinitionPropertyOption extends Model
{

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'definition_property_options';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['value', 'property_id','parent_id','image'];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function lookupValues()
    {
        return $this->hasMany(DefinitionPropertyLookupValue::class, 'value_id', 'id');
    }

    public function getFullnameAttribute()
    {
        return $this->value . " " . $this->type->unit;
    }

    public function definition()
    {
        return $this->belongsTo(DefinitionProperty::class, 'property_id');
    }

    public function parent()
    {
        return $this->belongsTo(DefinitionPropertyOption::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(DefinitionPropertyOption::class, 'parent_id');
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */


    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

}
