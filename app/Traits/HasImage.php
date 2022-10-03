<?php

namespace App\Traits;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;


trait HasImage
{

    public function getThumb()
    {
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
        $imgWidth = empty($this->image_width) ? 1000 : $this->image_width;
        $destination_path = empty($this->destination_path) ? 'uploads/misc' : $this->destination_path;
        $diskName = empty($this->image_disk) ? 'public' : $this->image_disk;
        $attrName = empty($this->image_attribute_name) ? 'image' : $this->image_attribute_name;
        $has_thumbs = empty($this->has_thumbs) ? false : $this->has_thumbs;
        $small_thumb_width = empty($this->small_thumb_width) ? $imgWidth / 10 : $this->small_thumb_width;
        $large_thumb_width = empty($this->large_thumb_width) ? $imgWidth / 4 : $this->large_thumb_width;
        $ratio = empty($this->ratio) ? 1 : $this->ratio;
        // if the image was erased
        $disk = Storage::disk($diskName);
        if ($value == null) {
            $this->deleteImageFromDisk($attrName, $disk);
        }
        //  else if (starts_with($value, 'data:image')) {

        $this->deleteImageFromDisk($attrName, $disk);
        $image = Image::make($value);

        $filename = md5($value . random_bytes(10));
        $filenameWithExtension = $filename . '.jpg';
        $fileFullPath = $destination_path . '/' . $filenameWithExtension;
        $image->resize($imgWidth, $imgWidth * (1 / $ratio));
        $disk->put($fileFullPath, $image->stream('jpg', 100));
        $this->attributes[$attrName] = $fileFullPath;
        if ($has_thumbs) {
            $this->MakeThumb($image, $disk, $destination_path, $filename, $ratio, $small_thumb_width, $large_thumb_width);
        }
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

        $filenameWithExtension = $filename . '.thumb.large.jpg';
        $fileFullPath = $destination_path . '/' . $filenameWithExtension;
        $image->resize($large_width, $large_width * (1 / $ratio));
        $disk->put($fileFullPath, $image->stream('jpg', 100));


        $filenameWithExtension = $filename . '.thumb.small.jpg';
        $fileFullPath = $destination_path . '/' . $filenameWithExtension;
        $image->resize($small_width, $small_width * (1 / $ratio));
        $disk->put($fileFullPath, $image->stream('jpg', 100));
    }
}
