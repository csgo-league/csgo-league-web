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

        $query = $db->query("
            CREATE TABLE `matches` (
              `matchid` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `start_time` datetime NOT NULL,
              `end_time` datetime,
              `winner` varchar(16) NOT NULL DEFAULT '',
              `series_type` varchar(64) NOT NULL DEFAULT '',
              `team1_name` varchar(64) NOT NULL DEFAULT '',
              `team1_score` smallint(5) unsigned NOT NULL DEFAULT '0',
              `team2_name` varchar(64) NOT NULL DEFAULT '',
              `team2_score` smallint(5) unsigned NOT NULL DEFAULT '0',
              PRIMARY KEY (`matchid`)
            );
            
            CREATE TABLE `matches_maps` (
              `matchid` int(10) unsigned NOT NULL,
              `mapnumber` smallint(5) unsigned NOT NULL,
              `start_time` datetime NOT NULL,
              `end_time` datetime,
              `winner` varchar(16) NOT NULL DEFAULT '',
              `mapname` varchar(64) NOT NULL DEFAULT '',
              `team1_score` smallint(5) unsigned NOT NULL DEFAULT '0',
              `team2_score` smallint(5) unsigned NOT NULL DEFAULT '0',
              PRIMARY KEY (`matchid`,`mapnumber`)
            );
            
            CREATE TABLE `matches_players` (
              `matchid` int(10) unsigned NOT NULL,
              `mapnumber` smallint(5) unsigned NOT NULL,
              `steam` varchar(32) NOT NULL,
              `team` varchar(16) NOT NULL DEFAULT '',
              `rounds_played` smallint(5) unsigned NOT NULL,
              `name` varchar(64) NOT NULL,
              `kills` smallint(5) unsigned NOT NULL,
              `deaths` smallint(5) unsigned NOT NULL,
              `assists` smallint(5) unsigned NOT NULL,
              `flashbang_assists` smallint(5) unsigned NOT NULL,
              `teamkills` smallint(5) unsigned NOT NULL,
              `headshot_kills` smallint(5) unsigned NOT NULL,
              `damage` int(10) unsigned NOT NULL,
              `bomb_plants` smallint(5) unsigned NOT NULL,
              `bomb_defuses` smallint(5) unsigned NOT NULL,
              `v1` smallint(5) unsigned NOT NULL,
              `v2` smallint(5) unsigned NOT NULL,
              `v3` smallint(5) unsigned NOT NULL,
              `v4` smallint(5) unsigned NOT NULL,
              `v5` smallint(5) unsigned NOT NULL,
              `2k` smallint(5) unsigned NOT NULL,
              `3k` smallint(5) unsigned NOT NULL,
              `4k` smallint(5) unsigned NOT NULL,
              `5k` smallint(5) unsigned NOT NULL,
              `firstkill_t` smallint(5) unsigned NOT NULL,
              `firstkill_ct` smallint(5) unsigned NOT NULL,
              `firstdeath_t` smallint(5) unsigned NOT NULL,
              `firstdeath_ct` smallint(5) unsigned NOT NULL,
              PRIMARY KEY (`matchid`,`mapnumber`,`steam`)
            );
        ");

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
            DROP TABLE `matches`;
            DROP TABLE `matches_players`;
            DROP TABLE `matches_maps`;
        ');

        return $query->execute();
    }
}
