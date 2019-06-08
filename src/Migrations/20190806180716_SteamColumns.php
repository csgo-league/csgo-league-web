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
            ALTER TABLE `sql_matches` RENAME COLUMN steam64 TO steam;
            ALTER TABLE `players` RENAME COLUMN steam64 TO steam;
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
            ALTER TABLE `sql_matches` RENAME COLUMN steam64 TO steam;
            ALTER TABLE `players` RENAME COLUMN steam64 TO steam;
        ');

        return $query->execute();
    }
}
