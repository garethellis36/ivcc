<?php
use Phinx\Migration\AbstractMigration;

class Initial extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('formats');
        $table
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => false,
            ])
            ->create();
        $table = $this->table('matches');
        $table
            ->addColumn('opposition', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('date', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('result', 'string', [
                'default' => null,
                'limit' => 15,
                'null' => true,
            ])
            ->addColumn('result_more', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => true,
            ])
            ->addColumn('venue', 'string', [
                'default' => 'A',
                'limit' => 5,
                'null' => false,
            ])
            ->addColumn('format_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('match_report', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('ivcc_total', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('ivcc_wickets', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('ivcc_overs', 'decimal', [
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('ivcc_extras', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('opposition_total', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('opposition_wickets', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('opposition_overs', 'decimal', [
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('ivcc_batted_first', 'integer', [
                'default' => 0,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('deleted', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->create();
        $table = $this->table('matches_players');
        $table
            ->addColumn('match_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('player_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('batting_order_no', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('did_not_bat', 'integer', [
                'default' => 0,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('batting_runs', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('modes_of_dismissal_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('bowling_overs', 'decimal', [
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('bowling_maidens', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('bowling_runs', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('bowling_wickets', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('bowling_order_no', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->create();
        $table = $this->table('modes_of_dismissal');
        $table
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 30,
                'null' => false,
            ])
            ->addColumn('not_out', 'integer', [
                'default' => 0,
                'limit' => 11,
                'null' => false,
            ])
            ->create();
        $table = $this->table('news');
        $table
            ->addColumn('title', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('body', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('user_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->create();
        $table = $this->table('players');
        $table
            ->addColumn('first_name', 'string', [
                'default' => null,
                'limit' => 25,
                'null' => false,
            ])
            ->addColumn('initials', 'string', [
                'default' => null,
                'limit' => 15,
                'null' => false,
            ])
            ->addColumn('last_name', 'string', [
                'default' => null,
                'limit' => 25,
                'null' => false,
            ])
            ->addColumn('previous_clubs', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('photo', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => true,
            ])
            ->addColumn('deleted', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->create();
        $table = $this->table('players_roles');
        $table
            ->addColumn('player_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('role_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->create();
        $table = $this->table('roles');
        $table
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 25,
                'null' => false,
            ])
            ->create();
        $table = $this->table('users');
        $table
            ->addColumn('email', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('password', 'string', [
                'default' => null,
                'limit' => 800,
                'null' => false,
            ])
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('is_admin', 'integer', [
                'default' => 0,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('enabled', 'integer', [
                'default' => 1,
                'limit' => 11,
                'null' => false,
            ])
            ->create();
    }

    public function down()
    {
        $this->dropTable('formats');
        $this->dropTable('matches');
        $this->dropTable('matches_players');
        $this->dropTable('modes_of_dismissal');
        $this->dropTable('news');
        $this->dropTable('players');
        $this->dropTable('players_roles');
        $this->dropTable('roles');
        $this->dropTable('users');
    }
}
