<?php
// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

use Medoo\Medoo;
use Phpmig\Migration\Migration;

class AddMatchServerInfo extends Migration
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
            ALTER TABLE matches ADD COLUMN `server_ip` varchar(255);
            ALTER TABLE matches ADD COLUMN `server_port` int(11) DEFAULT 27015;
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
            ALTER TABLE matches DROP COLUMN `server_ip`;
            ALTER TABLE matches DROP COLUMN `server_port`;
        ');

        return $query->execute();
    }
}
