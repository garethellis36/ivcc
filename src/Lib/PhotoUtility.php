<?php
/**
 * Created by PhpStorm.
 * User: garethellis
 * Date: 06/07/15
 * Time: 19:08
 */

namespace App\Lib;
use Cake\Error\Debugger;
use Intervention\Image\ImageManagerStatic as Image;


class PhotoUtility
{
    private $source;

    private $imgWidth;

    private $imgHeight;

    private $defaultThumbnailWidth = 300;

    private $defaultPhotoWidth = 980;

    private $savePath;

    private $name;

    private $ext;

    public function __construct($photo, $ext = "jpg")
    {
        if (!is_readable($photo)) {
            throw new Exception("File not readable");
        }
        $this->source = $photo;

        $info = getimagesize($this->source);
        $this->imgWidth = $info[0];
        $this->imgHeight = $info[1];

        $this->savePath = WWW_ROOT . "img" . DS . "photos";

        $this->ext = $ext;

        $this->setName();
    }


    public function resize()
    {
        $img = Image::make($this->source);

        $img->resize($this->defaultPhotoWidth, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        return $img->save($this->savePath . DS . $this->getName());
    }

    public function createThumbnail()
    {
        $img = Image::make($this->source);

        $img->resize($this->defaultThumbnailWidth, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        return $img->save($this->savePath . DS . "thumbs" . DS . $this->getName());
    }

    private function setName()
    {
        $this->name = rand(1,9999) . "_" . time() . "." . $this->ext;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDate()
    {
        $exif = exif_read_data($this->source);
        if (isset($exif["DateTimeOriginal"]) && !empty($exif["DateTimeOriginal"])) {
            return $exif["DateTimeOriginal"];
        }
        if (isset($exif["FileDateTime"]) && !empty($exit["FileDateTime"])) {
            return date("Y-m-d H:i:s", $exif["FileDateTime"]);
        }
        return date("Y-m-d H:i:s");
    }


}