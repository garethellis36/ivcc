<?php
/**
 * Created by PhpStorm.
 * User: garethellis
 * Date: 14/05/15
 * Time: 13:14
 */

namespace App\Controller\Admin;
use App\Controller\AppController;
use App\Lib\PhotoUtility;
use Cake\ORM\TableRegistry;
use Cake\Filesystem\File;

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

            //we need to know the file name of the existing photo if it's being deleted
            //$player->photo will be nulled by patchEntity so store it here
            $existingPhoto = $player->photo;

            $player = $this->Players->patchEntity($player, $this->request->data, [
                'associated' => ["Roles"]
            ]);

            //delete photo image from disk
            if ($existingPhoto && empty($this->request->data["photo"]["name"])
                && isset($this->request->data["delete_photo"]) && $this->request->data["delete_photo"] == 1) {
                unlink(WWW_ROOT . DS . "img" . DS . "players" . DS . $existingPhoto);
            }

            /*
             * set file name to save in database
             * if you don't do this it tries to save the data array
             * and if you set the name before patchEntity returns (e.g. in patchEntity itself), validation will fail
             */
            $errors = $player->errors();
            if (empty($errors) && !empty($this->request->data["photo"]["name"])) {
                $file = new File($this->request->data["photo"]["tmp_name"]);
                $player->photo = strtolower($file->safe($player->initials . $player->last_name)) . ".jpg";
            }

            if ($this->Players->save($player)) {

                if (!empty($this->request->data["photo"]["name"])) {
                    try {
                        $photo = new PhotoUtility($this->request->data["photo"]["tmp_name"]);
                        $photo->resizePlayerPhoto($player->photo);
                    } catch (Exception $e) {
                        $this->Flash->error("FYI: file failed to upload");
                    }

                }

                $this->Flash->success('Player saved.');
                return $this->redirect("/players");
            } else {
                $this->Flash->error('The player could not be saved. Please, try again.');
            }

        }

        $roles = TableRegistry::get("roles");
        $roles = $roles->getList();

        $this->set(compact('player', 'roles'));
        $this->set('_serialize', ['player']);

        $this->render('form');
    }

    public function delete_photo($player_id)
    {
        $player = $this->Player->get($player_id);

        $this->redirect("/players/edit/" . $player_id);
    }

}