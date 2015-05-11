<?php
/**
 * Created by PhpStorm.
 * User: garethellis
 * Date: 12/05/15
 * Time: 14:47
 */

namespace App\Controller;
use App\Controller\AppController;

class NewsController extends AppController {

    public $paginate = [
        "limit" => 3,
        "order" => [
            "News.created DESC"
        ],
        "finder" => "all",
        "contain" => [
            "Users"
        ]
    ];

    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow('index');
        $this->loadComponent('Paginator');
    }

    public function index()
    {
        $query = $this->paginate($this->News);

        $news = $query->toArray();

        $showSplash = true;

        $this->set(compact("news", "showSplash"));

    }

}