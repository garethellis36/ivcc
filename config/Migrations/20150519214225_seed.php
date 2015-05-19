<?php

use Phinx\Migration\AbstractMigration;

class Seed extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     *
     * Uncomment this method if you would like to use it.
     *
    public function change()
    {
    }
    */
    
    /**
     * Migrate Up.
     */
    public function up()
    {
        $this->query("INSERT INTO `formats` VALUES (1,'40 overs'),(2,'Twenty20')");
        $this->query("INSERT INTO `modes_of_dismissal` VALUES (1,'Bowled',0),(2,'Caught',0),(3,'LBW',0),(4,'Stumped',0),(5,'Run out',0),(6,'Hit wicket',0),(7,'Handled ball',0),(8,'Timed out',0),(9,'Retired hurt',1),(10,'Retired out',0),(11,'Hit ball twice',0),(12,'Obstructing the field',0),(13,'Not out',1)");
        $this->query("INSERT INTO `roles` VALUES (1,'Right-handed batsman'),(2,'Left-handed batsman'),(3,'Right-arm fast'),(4,'Left-arm fast'),(5,'Right-arm medium'),(6,'Left-arm medium'),(7,'Off-break'),(8,'Leg-spin'),(9,'Slow left-arm'),(10,'Left-arm chinaman'),(11,'Wicketkeeper')");
        $this->query('INSERT INTO `users` VALUES (2,"admin@admin.admin","$2y$10$R0WLEHTJWlowU5S6Iw9La.EywYXthUaNJGb5v9BfkhhI62.56nING","admin",1,1)');
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->query("TRUNCATE TABLE formats");
        $this->query("TRUNCATE TABLE modes_of_dismissal");
        $this->query("TRUNCATE TABLE roles");
        $this->query("TRUNCATE TABLE users");
    }
}