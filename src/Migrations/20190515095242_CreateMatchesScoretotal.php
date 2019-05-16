<?php
// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

use Medoo\Medoo;
use Phpmig\Migration\Migration;

class CreateMatchesScoretotal extends Migration
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

        $query = $db->query('
            CREATE TABLE IF NOT EXISTS sql_matches_scoretotal (
              `match_id` bigint(20) UNSIGNED NOT NULL,
              `timestamp` int(11) NOT NULL,
              `team_0` int(11) NOT NULL,
              `team_1` int(11) NOT NULL,
              `team_2` int(11) NOT NULL,
              `team_3` int(11) NOT NULL,
              `teamname_1` varchar(64) NOT NULL,
              `teamname_2` varchar(64) NOT NULL,
              `map` varchar(128) NOT NULL
            );
        ');

        return $query->execute();
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

        $query = $db->query('DROP TABLE IF EXISTS sql_matches_scoretotal;');

        return $query->execute();
    }
}
