<?php
namespace App\View\Helper;

use Cake\View\Helper;

class PhotoHelper extends Helper
{
    public $path = "photos/";

    public function showPhoto($photo, $isThumbnail = false)
    {
        if (file_exists($this->getAbsolutePath($photo, $isThumbnail))) {
            return $this->_View->Html->image($this->getWebPath($photo, $isThumbnail), ["alt" => $photo->title]);
        }
        return "";
    }

    private function getAbsolutePath($photo, $isThumbnail)
    {
        return WWW_ROOT . "img" . DS . $this->path . ($isThumbnail ?  "thumbs/" : "") . $photo->name;
    }

    private function getWebPath($photo, $isThumbnail)
    {
        return $this->path . ($isThumbnail ?  "thumbs/" : "") . $photo->name;
    }


}