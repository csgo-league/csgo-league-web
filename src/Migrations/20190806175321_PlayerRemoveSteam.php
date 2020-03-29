<?php
// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

use Medoo\Medoo;
use Phpmig\Migration\Migration;

class PlayerRemoveSteam extends Migration
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
            ALTER TABLE `players` DROP COLUMN `steam64`;
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
            ALTER TABLE `players` ADD COLUMN `steam64` varchar(100) NOT NULL;
        ');

        return $query->execute();
    }
}
