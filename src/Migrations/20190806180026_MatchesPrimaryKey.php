<?php
// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

use Medoo\Medoo;
use Phpmig\Migration\Migration;

class MatchesPrimaryKey extends Migration
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
            ALTER TABLE `sql_matches_scoretotal` MODIFY COLUMN `match_id` INT(11) UNSIGNED PRIMARY KEY AUTO_INCREMENT;
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
            ALTER TABLE `sql_matches_scoretotal` MODIFY COLUMN `match_id` INT(11);
        ');

        return $query->execute();
    }
}
