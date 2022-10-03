<?php
/**
 * Created by PhpStorm.
 * User: DH
 * Date: 9/20/2018
 * Time: 12:04 PM
 */

namespace App\Traits;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

trait HasImage
{
    protected $destination_path = "uploads/imageItem";
    protected $image_ext = "jpg";

    function withDestination($destination)
    {
        $this->destination_path = "uploads/$destination";
    }

    public function getThumb()
    {
        // todo test
        $attrName = empty($this->image_attribute_name) ? 'image' : $this->image_attribute_name;
        if ($this->attributes[$attrName] == null) {
            return null;
        } else {
            $strs = explode('.', $this->attributes[$attrName]);
            array_splice($strs, 1, 0, 'thumb');
            return implode('.', $strs);
        }
    }

    public function setImageAttribute($value)
    {
        $this->setAnImageFiled($value, 'image');
    }


    /**
     * @param string $imgAttr
     * @param Filesystem $disk
     */
    public function deleteImageFromDisk($imgAttr, $disk)
    {
        if (!array_key_exists($imgAttr, $this->attributes)) {
            return;
        }
        $imgPath = $this->attributes[$imgAttr];
        if ($imgPath != null) {
            if ($disk->exists($imgPath)) {
                $disk->delete($imgPath);
            }
            if ($disk->exists(substr($imgPath, 0, -3) . "thumb.large.$this->image_ext")) {
                $disk->delete(substr($imgPath, 0, -3) . "thumb.large.$this->image_ext");
            }
            if ($disk->exists(substr($imgPath, 0, -3) . "thumb.small.$this->image_ext")) {
                $disk->delete(substr($imgPath, 0, -3) . "thumb.small.$this->image_ext");
            }
        }
        $this->attributes[$imgAttr] = null;

    }

    /**
     * @param \Intervention\Image\Image $image
     * @param Filesystem $disk
     * @param string $destination_path
     * @param string $filename
     * @param float $ratio
     */
    public function MakeThumb($image, $disk, $destination_path, $filename, $ratio, $small_width, $large_width)
    {


        $filenameWithExtension = $filename . ".thumb.large.$this->image_ext";
        $fileFullPath = $destination_path . '/' . $filenameWithExtension;
        $image->resize($large_width, $large_width * (1 / $ratio));
        $disk->put($fileFullPath, $image->stream($this->image_ext, 100));


        $filenameWithExtension = $filename . ".thumb.small.$this->image_ext";
        $fileFullPath = $destination_path . '/' . $filenameWithExtension;
        $image->resize($small_width, $small_width * (1 / $ratio));
        $disk->put($fileFullPath, $image->stream($this->image_ext, 100));
    }


    /**
     * @param $value
     * @param $imageFieldName
     * @throws \Exception
     */
    private function setAnImageFiled($value, $imageFieldName, $ratio = null, $width = null): void
    {
        $ratio = $ratio == null ? empty($this->ratio) ? 1 : $this->ratio : $ratio;
        $imgWidth = $width == null ? empty($this->image_width) ? 1000 : $this->image_width : $width;
        $destination_path = empty($this->destination_path) ? 'uploads/misc' : $this->destination_path;
        $diskName = empty($this->image_disk) ? 'public' : $this->image_disk;
        $attrName = empty($imageFieldName) ? 'image' : $imageFieldName;
        $has_thumbs = empty($this->has_thumbs) ? false : $this->has_thumbs;
        $small_thumb_width = empty($this->small_thumb_width) ? $imgWidth / 8 : $this->small_thumb_width;
        $large_thumb_width = empty($this->large_thumb_width) ? $imgWidth / 2 : $this->large_thumb_width;
        // if the image was erased
        $disk = Storage::disk($diskName);
        if ($value == null) {
            $this->deleteImageFromDisk($attrName, $disk);
        } else if (Str::startsWith($value, 'data:image') || is_object($value)) {
            $this->deleteImageFromDisk($attrName, $disk);

            $image = Image::make($value);
            if (!property_exists($this, 'storeWithoutResizing')) {
                $image->resize($imgWidth, $imgWidth * (1 / $ratio));
            } else if ($this->storeWithoutResizing == false) {
                $image->resize($imgWidth, $imgWidth * (1 / $ratio));
            }


            if ($this->image_ext === "jpg")
                $image->interlace(true);
            $filename = md5($value . random_bytes(10));
            $filenameWithExtension = $filename . ".$this->image_ext";
            $fileFullPath = $destination_path . '/' . $filenameWithExtension;
            $disk->put($fileFullPath, $image->stream($this->image_ext, 100));
            $this->attributes[$attrName] = $fileFullPath;
            if ($has_thumbs) {
                $this->MakeThumb($image, $disk, $destination_path, $filename, $ratio, $small_thumb_width, $large_thumb_width);
            }
        } else if ($value == "test" || str_starts_with($value, 'upload')) {
            $this->attributes[$attrName] = $value;
        }
    }
}
