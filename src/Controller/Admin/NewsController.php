<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class NewsController extends AppController
{

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $newsItem = $this->News->newEntity();

        if ($this->request->is('post')) {

            $newsItem = $this->News->patchEntity($newsItem, $this->request->data);
            $newsItem->created = date("Y-m-d H:i:s");
            $newsItem->user_id = $this->Auth->user("id");

            if ($this->News->save($newsItem)) {
                $this->Flash->success('News item saved.');
                return $this->redirect("/");
            }

            $this->Flash->error('The news item could not be saved. Please, try again.');
        }
        $this->set(compact('newsItem'));
        $this->set('_serialize', ['newsItem']);
    }

    /**
     * Edit method
     *
     * @param string|null $id News id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $newsItem = $this->News->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {

            if (empty($this->request->data["password"])) {
                unset($this->request->data["password"]);
            }

            $newsItem = $this->News->patchEntity($newsItem, $this->request->data);
            if ($this->News->save($newsItem)) {
                $this->Flash->success('News item saved.');
                return $this->redirect("/");
            } else {
                $this->Flash->error('The news item could not be saved. Please, try again.');
            }
        }
        $this->set(compact('newsItem'));
        $this->set('_serialize', ['newsItem']);
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
        $newsItem = $this->News->get($id);
        if ($this->News->delete($newsItem)) {
            $this->Flash->success('News item deleted.');
        } else {
            $this->Flash->error('The news item could not be deleted. Please, try again.');
        }
        return $this->redirect("/");
    }

}
