<?php
// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

use Medoo\Medoo;
use Phpmig\Migration\Migration;

class AddIdFields extends Migration
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
            ALTER TABLE players ADD COLUMN `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT;
            ALTER TABLE player_link_codes ADD COLUMN `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT;
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
            ALTER TABLE players DROP COLUMN `id`;
            ALTER TABLE player_link_codes DROP COLUMN `id`;
        ');

        return $query->execute();
    }
}
