<?php
/**
 * Created by PhpStorm.
 * User: garethellis
 * Date: 15/05/15
 * Time: 17:34
 */

namespace App\Controller;
use App\Controller\AppController;

class UsersController extends AppController 
{

    public function login()
    {
        if ($this->Auth->isAuthorized()) {
            $this->redirect("/");
        }

        if ($this->request->is('post')) {

            $user = $this->Auth->identify();

            if ($user) {
                $this->Auth->setUser($user);
                return $this->redirect($this->Auth->redirectUrl());
            }

            $this->Flash->error(
                __('Email or password is incorrect'),
                'default',
                [],
                'auth'
            );

        }
    }

    public function logout()
    {
        return $this->redirect($this->Auth->logout());
    }

}