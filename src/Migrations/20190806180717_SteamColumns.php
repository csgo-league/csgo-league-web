<?php
// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

use Medoo\Medoo;
use Phpmig\Migration\Migration;

class SteamColumns extends Migration
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
            ALTER TABLE `sql_matches` CHANGE COLUMN `steam64` `steam` varchar(100);
            ALTER TABLE `players` CHANGE COLUMN `steam64` `steam` varchar(100);
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

        $query = $db->query('
            ALTER TABLE `sql_matches` CHANGE COLUMN `steam` `steam64` varchar(100);
            ALTER TABLE `players` CHANGE COLUMN `steam` `steam64` varchar(100);
        ');

        return $query->execute();
    }
}
