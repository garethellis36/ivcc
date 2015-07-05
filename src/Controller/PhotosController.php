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
        $this->Auth->allow('index');
        $this->loadComponent('Paginator');
    }

    public function index()
    {
        $query = $this->paginate($this->Photos);

        $photos = $query->toArray();

        $this->set(compact("photos"));

    }

}