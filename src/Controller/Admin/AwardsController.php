<?php
/**
 * Created by PhpStorm.
 * User: garethellis
 * Date: 21/11/2016
 * Time: 19:17
 */

namespace App\Controller\Admin;


use App\Controller\AppController;
use App\Model\Table\AwardsTable;

/**
 * @property AwardsTable $Awards
 */
class AwardsController extends AppController
{
    public function index()
    {
        $awards = $this->Awards->find('all', ['order' => 'Awards.order_number ASC']);
        $this->set(compact('awards'));
    }

    public function add()
    {
        $award = $this->Awards->newEntity();

        if ($this->request->is('post')) {

            $award = $this->Awards->patchEntity($award, $this->request->data);
            if ($this->Awards->save($award)) {
                $this->Flash->success('Award saved.');
                return $this->redirect("/admin/awards");
            }

            $this->Flash->error('The award could not be saved. Please, try again.');
        }
        $this->set(compact('award'));
        $this->set('_serialize', ['award']);

        $this->render("form");
    }

    public function edit($awardId)
    {
        $award = $this->Awards->get($awardId);

        if ($this->request->is(['patch', 'post', 'put'])) {

            $award = $this->Awards->patchEntity($award, $this->request->data);
            if ($this->Awards->save($award)) {
                $this->Flash->success('Award saved.');
                return $this->redirect("/admin/awards");
            }

            $this->Flash->error('The award could not be saved. Please, try again.');
        }
        $this->set(compact('award'));
        $this->set('_serialize', ['award']);

        $this->render("form");
    }

    public function delete($awardId)
    {
        $this->Awards->delete($this->Awards->get($awardId));
        $this->Flash->success('Award deleted.');
        return $this->redirect("/admin/awards");
    }
}