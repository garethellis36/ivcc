<?php

namespace App\Controller;
use App\Controller\AppController;

class PhotosController extends AppController
{

    public $helpers = ["Photo"];

    public $paginate = [
        "limit" => 12,
        "order" => [
            "Photos.created DESC"
        ],
        "finder" => "all",
    ];

    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['index', 'view']);
        $this->loadComponent('Paginator');
    }

    public function index()
    {
        $query = $this->paginate($this->Photos);

        $photos = $query->toArray();

        $this->set(compact("photos"));

    }

    public function view($photoId)
    {
        $photo = $this->Photos->findById($photoId)->first();

        $prev = $this->Photos->find("all")
            ->where([
                "Photos.id < " . $photoId
            ])
            ->order([
                "Photos.id DESC"
            ])
            ->limit("1")
            ->first();

        $next = $this->Photos->find("all")
            ->where([
                "Photos.id > " . $photoId
            ])
            ->order([
                "Photos.id ASC"
            ])
            ->limit("1")
            ->first();

        $this->set(compact("photo", "prev", "next"));
    }

}