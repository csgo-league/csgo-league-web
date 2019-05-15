<?php

namespace B3none\League\Migrations;

use Medoo\Medoo;
use Phpmig\Migration\Migration;

class InitialProject extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        /**
         * @var $db Medoo
         */
        $db = $this->get('db');

        $db->exec(file_get_contents(__DIR__ . '/../../schema.sql'));
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        /**
         * @var $db Medoo
         */
        $db = $this->get('db');

        $db->exec("
            DROP TABLE IF EXISTS players;
            DROP TABLE IF EXISTS player_link_codes;
            DROP TABLE IF EXISTS rankme;
            DROP TABLE IF EXISTS sql_matches;
            DROP TABLE IF EXISTS sql_matches_scoretotal;
        ");
    }
}
