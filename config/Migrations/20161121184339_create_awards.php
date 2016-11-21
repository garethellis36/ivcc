<?php

use Phinx\Migration\AbstractMigration;

class CreateAwards extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     *
     * Uncomment this method if you would like to use it.
     */
    public function change()
    {
        $this->table('awards')
            ->addColumn('name', 'string')
            ->addColumn('order_number', 'integer')
            ->addColumn('deleted', 'datetime', ['null' => true])
            ->create();

        $this->table('awards')
            ->insert([
                [
                    'name'         => 'Batsman of the year',
                    'order_number' => '10',
                ],
                [
                    'name'         => 'Bowler of the year',
                    'order_number' => '20',
                ],
                [
                    'name'         => 'Spirit of Iffley Village',
                    'order_number' => '30',
                ],
                [
                    'name'         => 'Clubman of the year',
                    'order_number' => '40',
                ],
                [
                    'name'         => 'Gareth Ellis "Chase It!" award for fielding endeavour',
                    'order_number' => '50',
                ],
            ])
            ->update();

        $this->table('award_winners')
            ->addColumn('award_id', 'integer')
            ->addColumn('player_id', 'integer')
            ->addColumn('year', 'integer')
            ->addColumn('comments', 'text')
            ->addIndex(['award_id', 'player_id'])
            ->create();
    }
}