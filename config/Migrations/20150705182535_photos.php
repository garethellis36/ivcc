<?php

use Phinx\Migration\AbstractMigration;

class Photos extends AbstractMigration
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
        $tbl = $this->table("photos");
        $tbl->addColumn("name", "string")
            ->addColumn("title", "string")
            ->addColumn("date", "datetime")
            ->create();
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