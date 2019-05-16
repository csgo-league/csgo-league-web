<?php
// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

use Medoo\Medoo;
use Phpmig\Migration\Migration;

class CreatePlayerLinkCodes extends Migration
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
            CREATE TABLE IF NOT EXISTS player_link_codes (
              `discord` varchar(100) NOT NULL,
              `code` varchar(100) NOT NULL,
              `expires` int(11) DEFAULT NULL
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

        $query = $db->query('DROP TABLE IF EXISTS player_link_codes;');

        return $query->execute();
    }
}
