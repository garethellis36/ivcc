<?php

namespace App\Controller;

use App\Model\Table\AwardWinnersTable;

/**
 * @property AwardWinnersTable $AwardWinners
 */
class AwardWinnersController extends AppController
{
    public function initialize()
    {
        parent::initialize();

        $this->Auth->allow('view');
    }


    public function view()
    {
        $awardWinnersQuery = $this->AwardWinners->find('all', [
            'contain' => ['Players', 'Awards'],
            'order'    => ['AwardWinners.year DESC', 'Awards.order_number ASC'],
        ]);

        $awardWinners = [];
        foreach ($awardWinnersQuery as $awardWinner) {
            if (!array_key_exists($awardWinner->year, $awardWinners)) {
                $awardWinners[$awardWinner->year] = [];
            }
            $awardWinners[$awardWinner->year][] = $awardWinner;
        }

        $this->set(compact('awardWinners'));
    }
}
