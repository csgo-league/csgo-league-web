<?php
// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

use Medoo\Medoo;
use Phpmig\Migration\Migration;

class MatchesNew extends Migration
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
            
        ');

        return $query->execute();
    }
}
