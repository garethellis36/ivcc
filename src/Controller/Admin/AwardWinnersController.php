<?php
/**
 * Created by PhpStorm.
 * User: garethellis
 * Date: 21/11/2016
 * Time: 19:17
 */

namespace App\Controller\Admin;


use App\Controller\AppController;
use App\Model\Table\AwardWinnersTable;
use Cake\ORM\TableRegistry;

/**
 * @property AwardWinnersTable $AwardWinners
 */
class AwardWinnersController extends AppController
{
    public function add()
    {
        $awardWinner = $this->AwardWinners->newEntity();

        if ($this->request->is('post')) {

            $awardWinner = $this->AwardWinners->patchEntity($awardWinner, $this->request->data);
            if ($this->AwardWinners->save($awardWinner)) {
                $this->Flash->success('Award winner saved.');
                return $this->redirect("/awards");
            }

            $this->Flash->error('The award winner could not be saved. Please, try again.');
        }
        $this->set(compact('awardWinner'));
        $this->set('_serialize', ['awardWinner']);

        $this->populateDropdowns();

        $this->render("form");
    }

    public function edit($awardWinnerId)
    {
        $awardWinner = $this->AwardWinners->get($awardWinnerId);

        if ($this->request->is(['patch', 'post', 'put'])) {

            $awardWinner = $this->AwardWinners->patchEntity($awardWinner, $this->request->data);
            if ($this->AwardWinners->save($awardWinner)) {
                $this->Flash->success('Award winner saved.');
                return $this->redirect("/awards");
            }

            $this->Flash->error('The award winner could not be saved. Please, try again.');
        }
        $this->set(compact('awardWinner'));
        $this->set('_serialize', ['awardWinner']);

        $this->populateDropdowns();

        $this->render("form");
    }

    public function delete($awardWinnerId)
    {
        $this->AwardWinners->delete($this->AwardWinners->get($awardWinnerId));
        $this->Flash->success('Award winner deleted.');
        return $this->redirect("/awards");
    }

    private function populateDropdowns()
    {
        $years = range(2014, date('Y'));
        $this->set('years', array_combine($years, $years));
        $this->set('players', TableRegistry::get('Players')->getList());
        $this->set('awards', TableRegistry::get('Awards')->find('list'));
    }
}