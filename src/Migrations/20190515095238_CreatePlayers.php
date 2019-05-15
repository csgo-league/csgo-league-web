<?php
// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

use Medoo\Medoo;
use Phpmig\Migration\Migration;

class CreatePlayers extends Migration
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
            CREATE TABLE `players` (
              `steam` varchar(100) NOT NULL,
              `steam64` varchar(100) NOT NULL,
              `discord` varchar(100)
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

        $query = $db->query('DROP TABLE IF EXISTS players;');

        return $query->execute();
    }
}
