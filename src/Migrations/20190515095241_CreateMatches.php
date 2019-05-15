<?php
// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

use Medoo\Medoo;
use Phpmig\Migration\Migration;

class CreateMatches extends Migration
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
            CREATE TABLE sql_matches (
              `match_id` bigint(20) NOT NULL,
              `name` varchar(100) NOT NULL,
              `steam64` varchar(100) NOT NULL,
              `team` int(11) NOT NULL,
              `alive` int(11) NOT NULL,
              `ping` int(11) NOT NULL,
              `account` int(11) NOT NULL,
              `kills` int(11) NOT NULL,
              `assists` int(11) NOT NULL,
              `deaths` int(11) NOT NULL,
              `mvps` int(11) NOT NULL,
              `score` int(11) NOT NULL
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

        $query = $db->query('DROP TABLE IF EXISTS sql_matches;');

        return $query->execute();
    }
}
