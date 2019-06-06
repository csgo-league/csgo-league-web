<?php
// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

use Medoo\Medoo;
use Phpmig\Migration\Migration;

class DiscordName extends Migration
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
            ALTER TABLE players ADD COLUMN `discord_name` varchar(100);
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
            ALTER TABLE players DROP COLUMN `discord_name`;
        ');

        return $query->execute();
    }
}
