<?php
/**
 * Created by PhpStorm.
 * User: garethellis
 * Date: 14/05/15
 * Time: 13:14
 */

namespace App\Controller\Admin;
use App\Controller\AppController;
use Cake\ORM\TableRegistry;

class PlayersController extends AppController
{

    public function add()
    {
        $player = $this->Players->newEntity();

        if ($this->request->is('post')) {

            $player = $this->Players->patchEntity($player, $this->request->data, [
                "associated" => [
                    "Roles"
                ]
            ]);

            if ($this->Players->save($player)) {
                $this->Flash->success('Player saved.');
                return $this->redirect("/players");
            }

            $this->Flash->error('The player could not be saved. Please, try again.');
        }

        $roles = TableRegistry::get("roles");
        $roles = $roles->getList();

        $this->set(compact('player', 'roles'));
        $this->set('_serialize', ['player']);

        $this->render('form');
    }

    public function delete($player_id)
    {
        $player = $this->Players->get($player_id);
        if ($this->Players->delete($player)) {
            $this->Flash->success('Player deleted.');
        } else {
            $this->Flash->error('The player could not be deleted. Please, try again.');
        }
        return $this->redirect("/players");
    }

    public function edit($player_id)
    {
        $player = $this->Players->get($player_id, [
            'contain' => ["Roles"]
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {


            // TODO - make this less bit (i.e. handling of file validation) less shite

            $continue = true;

            if (isset($this->request->data["photo"]) && !empty($this->request->data["photo"]["name"])) {
                if (!$this->validPhoto()) {
                    $this->Flash->error("The photo you uploaded is not valid. Please make sure you choose a JPG file no larger than 50kb, with maximum dimensions of 190px x 190px");
                    $continue = false;
                } else {
                    $target = WWW_ROOT . DS . "img" . DS . "players" . DS . $this->request->data["photo"]["name"];
                    if (!move_uploaded_file($this->request->data["photo"]["tmp_name"], $target)) {
                        $continue = false;
                        $this->Flash->error("Photo upload failed");
                    } else {
                        $this->request->data["photo"] = $this->request->data["photo"]["name"];
                    }
                }
            } else {
                unset($this->request->data["photo"]);
            }

            if ($continue) {

                $player = $this->Players->patchEntity($player, $this->request->data, [
                    'associated' => ["Roles"]
                ]);
                if ($this->Players->save($player)) {
                    $this->Flash->success('Player saved.');
                    return $this->redirect("/players");
                } else {
                    $this->Flash->error('The player could not be saved. Please, try again.');
                }
            }
        }

        $roles = TableRegistry::get("roles");
        $roles = $roles->getList();

        $this->set(compact('player', 'roles'));
        $this->set('_serialize', ['player']);

        $this->render('form');
    }

    private function validPhoto()
    {
        if ($this->request->data["photo"]["size"] > 50000) {
            return false;
        }

        if ($this->request->data["photo"]["type"] != "image/jpeg") {
            return false;
        }

        $size = getimagesize($this->request->data["photo"]["tmp_name"]);
        if ($size[0] > 190 || $size[0] > 190) {
            return false;
        }

        return true;
    }


}