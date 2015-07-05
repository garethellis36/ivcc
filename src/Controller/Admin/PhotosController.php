<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class PhotosController extends AppController
{

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $photo = $this->Photos->newEntity();

        if ($this->request->is('post')) {

            $photo = $this->Photos->patchEntity($photo, $this->request->data);
            $photo->user_id = $this->Auth->user("id");

            if ($this->Photos->save($photo)) {
                $this->Flash->success('Photo saved.');
                return $this->redirect("/photos");
            }

            $this->Flash->error('The photo could not be saved. Please, try again.');
        }
        $this->set(compact('photo'));
        $this->set('_serialize', ['photo']);

        $this->render("form");
    }

    /**
     * Delete method
     *
     * @param string|null $id News id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $photo = $this->Photos->get($id);
        if ($this->Photos->delete($photo)) {
            $this->Flash->success('Photo deleted.');
        } else {
            $this->Flash->error('The photo could not be deleted. Please, try again.');
        }
        return $this->redirect("/photos");
    }

}
