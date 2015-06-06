<?php

use Phinx\Migration\AbstractMigration;

class PlayerFines extends AbstractMigration
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
        $tbl = $this->table("players");
        $tbl->addColumn("fines_owed", "string", ["null" => true])->save();
    }

    
    /**
     * Migrate Up.
     */
    public function up()
    {
    
    }

    /**
     * Migrate Down.
     */
    public function down()
    {

    }
}