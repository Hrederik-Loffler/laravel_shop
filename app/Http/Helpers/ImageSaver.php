<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageSaver
{
    /**
     * Save image when you try to add or edit category/product;
     *
     * @param \Illuminate\Http\Request $request — object HTTP-request
     * @param \App\Models\Item $item — model of CAtegory/Product
     * @param string $dir — place where to save
     * @return string|null — name of file
     **/
    public function upload($request, $item, $dir) {
        $name = $item->image ?? null;
        if ($item && $request->remove) { // if need to remove image
            $this->remove($item, $dir);
            $name = null;
        }
        $source = $request->file('image');
        if ($source) { // if image was upload
            // before upload a new one image remove old
            if ($item && $item->image) {
                $this->remove($item, $dir);
            }
            $ext = $source->extension();
            // save without change
            $path = $source->store('catalog/'.$dir.'/source', 'public');
            $path = Storage::disk('public')->path($path); // full path
            $name = basename($path); // имя файла
            $dst = 'catalog/'.$dir.'/image/';
            $this->resize($path, $dst, 600, 300, $ext);
            $dst = 'catalog/'.$dir.'/thumb/';
            $this->resize($path, $dst, 300, 150, $ext);
        }
        return $name;
    }

    /**
     * Creqte small version of image
     *
     * @param string $src — path to original image
     * @param string $dst — path where need to save
     * @param integer $width
     * @param integer $height
     * @param string $ext — jpeg/png/svg...
     */
    private function resize($src, $dst, $width, $height, $ext) {
        // create small version width x height
        $image = Image::make($src)
            ->heighten($height)
            ->resizeCanvas($width, $height, 'center', false, 'eeeeee')
            ->encode($ext, 100);
        // save image with basic name
        $name = basename($src);
        Storage::disk('public')->put($dst . $name, $image);
        $image->destroy();
    }

    /**
     * Remove image
     *
     * @param \App\Models\Item $item — model
     * @param string $dir — place where image
     */
    public function remove($item, $dir) {
        $old = $item->image;
        if ($old) {
            Storage::disk('public')->delete('catalog/'.$dir.'/source/' . $old);
            Storage::disk('public')->delete('catalog/'.$dir.'/image/' . $old);
            Storage::disk('public')->delete('catalog/'.$dir.'/thumb/' . $old);
        }
    }
}
