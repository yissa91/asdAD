<?php

namespace App\Models;

use App\Traits\HasImage;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\ImageItem
 *
 * @property int $id
 * @property string $description
 * @property string $image
 * @property int $related_id
 * @property string $related_type
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ImageItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ImageItem whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ImageItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ImageItem whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ImageItem whereRelatedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ImageItem whereRelatedType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ImageItem whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $owner_id
 * @property string $owner_type
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $owner
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ImageItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ImageItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ImageItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ImageItem whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ImageItem whereOwnerType($value)
 * @property-read int|null $audits_count
 */
class ImageItem extends Model // implements Auditable
{
    // use \OwenIt\Auditing\Auditable;
    //use CrudTrait;
    use HasImage;

    /*
   |--------------------------------------------------------------------------
   | GLOBAL VARIABLES
   |--------------------------------------------------------------------------
   */

    protected $table = 'image_item';
    protected $fillable = ['image', 'owner_id', 'owner_type'];
    protected $image_disk = "public";
    protected $destination_path = "uploads/imageItem";
    protected $has_thumbs = true;
    protected $ratio = 16 / 9;
    protected $image_width = 1500;
    protected $large_thumb_width = 1500 / 4;
    protected $small_thumb_width = 1500 / 10;

    public function owner()
    {
        return $this->morphTo();
    }

}
