<?php
// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

use Medoo\Medoo;
use Phpmig\Migration\Migration;

class SwitchTimestamps extends Migration
{
    /**
     * do the migration
     */
    public function up()
    {
        /**
         * @var $db Medoo
         */
        $db = $this->get('db');

        $query = $db->query('
            ALTER TABLE `matches` MODIFY COLUMN `start_time` INT(11) NOT NULL;
            ALTER TABLE `matches` MODIFY COLUMN `end_time` INT(11);
            ALTER TABLE `matches_maps` MODIFY COLUMN `start_time` INT(11) NOT NULL;
            ALTER TABLE `matches_maps` MODIFY COLUMN `end_time` INT(11);
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
            ALTER TABLE `matches` MODIFY COLUMN `start_time` datetime NOT NULL;
            ALTER TABLE `matches` MODIFY COLUMN `end_time` datetime;
            ALTER TABLE `matches_maps` MODIFY COLUMN `start_time` datetime NOT NULL;
            ALTER TABLE `matches_maps` MODIFY COLUMN `end_time` datetime;
        ');

        return $query->execute();
    }
}
