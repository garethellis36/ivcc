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
            ->addColumn("type", "string")
            ->addColumn("title", "string")
            ->addColumn("date", "datetime")
            ->addColumn("created", "datetime", ["null" => true])
            ->addColumn("modified", "datetime", ["null" => true])
            ->addColumn("user_id", "integer")
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