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
    private $photo;

    private $imgWidth;

    private $imgHeight;

    private $defaultThumbnailWidth = 300;

    private $defaultThumbnailHeight = 300;

    private $savePath;

    private $name;

    private $ext;

    private $type;

    public function __construct($photo, $ext = "jpg")
    {
        if (!is_readable($photo)) {
            throw new Exception("File not readable");
        }
        $this->photo = $photo;

        $info = getimagesize($this->photo);
        $this->imgWidth = $info[0];
        $this->imgHeight = $info[1];

        $this->savePath = WWW_ROOT . "img" . DS . "photos";

        $this->setName();
Debugger::log($ext);
        $this->ext = $ext;

        $this->setType();
    }

    private function setType()
    {
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $this->type = $finfo->file($this->photo);
    }

    public function getType()
    {
        return $this->type;
    }

    public function resize($x, $y)
    {

    }

    public function createThumbnail()
    {
        $img = Image::make($this->photo);

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

}