<?php
// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

use Medoo\Medoo;
use Phpmig\Migration\Migration;

class CreateRankme extends Migration
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
            CREATE TABLE rankme (
              `id` int(11) NOT NULL,
              `steam` varchar(100) DEFAULT NULL,
              `name` text,
              `lastip` text,
              `score` decimal(10,0) DEFAULT NULL,
              `kills` decimal(10,0) DEFAULT NULL,
              `deaths` decimal(10,0) DEFAULT NULL,
              `assists` decimal(10,0) DEFAULT NULL,
              `suicides` decimal(10,0) DEFAULT NULL,
              `tk` decimal(10,0) DEFAULT NULL,
              `shots` decimal(10,0) DEFAULT NULL,
              `hits` decimal(10,0) DEFAULT NULL,
              `headshots` decimal(10,0) DEFAULT NULL,
              `connected` decimal(10,0) DEFAULT NULL,
              `rounds_tr` decimal(10,0) DEFAULT NULL,
              `rounds_ct` decimal(10,0) DEFAULT NULL,
              `lastconnect` decimal(10,0) DEFAULT NULL,
              `knife` decimal(10,0) DEFAULT NULL,
              `glock` decimal(10,0) DEFAULT NULL,
              `hkp2000` decimal(10,0) DEFAULT NULL,
              `usp_silencer` decimal(10,0) DEFAULT NULL,
              `p250` decimal(10,0) DEFAULT NULL,
              `deagle` decimal(10,0) DEFAULT NULL,
              `elite` decimal(10,0) DEFAULT NULL,
              `fiveseven` decimal(10,0) DEFAULT NULL,
              `tec9` decimal(10,0) DEFAULT NULL,
              `cz75a` decimal(10,0) DEFAULT NULL,
              `revolver` decimal(10,0) DEFAULT NULL,
              `nova` decimal(10,0) DEFAULT NULL,
              `xm1014` decimal(10,0) DEFAULT NULL,
              `mag7` decimal(10,0) DEFAULT NULL,
              `sawedoff` decimal(10,0) DEFAULT NULL,
              `bizon` decimal(10,0) DEFAULT NULL,
              `mac10` decimal(10,0) DEFAULT NULL,
              `mp9` decimal(10,0) DEFAULT NULL,
              `mp7` decimal(10,0) DEFAULT NULL,
              `ump45` decimal(10,0) DEFAULT NULL,
              `p90` decimal(10,0) DEFAULT NULL,
              `galilar` decimal(10,0) DEFAULT NULL,
              `ak47` decimal(10,0) DEFAULT NULL,
              `scar20` decimal(10,0) DEFAULT NULL,
              `famas` decimal(10,0) DEFAULT NULL,
              `m4a1` decimal(10,0) DEFAULT NULL,
              `m4a1_silencer` decimal(10,0) DEFAULT NULL,
              `aug` decimal(10,0) DEFAULT NULL,
              `ssg08` decimal(10,0) DEFAULT NULL,
              `sg556` decimal(10,0) DEFAULT NULL,
              `awp` decimal(10,0) DEFAULT NULL,
              `g3sg1` decimal(10,0) DEFAULT NULL,
              `m249` decimal(10,0) DEFAULT NULL,
              `negev` decimal(10,0) DEFAULT NULL,
              `hegrenade` decimal(10,0) DEFAULT NULL,
              `flashbang` decimal(10,0) DEFAULT NULL,
              `smokegrenade` decimal(10,0) DEFAULT NULL,
              `inferno` decimal(10,0) DEFAULT NULL,
              `decoy` decimal(10,0) DEFAULT NULL,
              `taser` decimal(10,0) DEFAULT NULL,
              `mp5sd` decimal(10,0) DEFAULT NULL,
              `breachcharge` decimal(10,0) DEFAULT NULL,
              `head` decimal(10,0) DEFAULT NULL,
              `chest` decimal(10,0) DEFAULT NULL,
              `stomach` decimal(10,0) DEFAULT NULL,
              `left_arm` decimal(10,0) DEFAULT NULL,
              `right_arm` decimal(10,0) DEFAULT NULL,
              `left_leg` decimal(10,0) DEFAULT NULL,
              `right_leg` decimal(10,0) DEFAULT NULL,
              `c4_planted` decimal(10,0) DEFAULT NULL,
              `c4_exploded` decimal(10,0) DEFAULT NULL,
              `c4_defused` decimal(10,0) DEFAULT NULL,
              `ct_win` decimal(10,0) DEFAULT NULL,
              `tr_win` decimal(10,0) DEFAULT NULL,
              `hostages_rescued` decimal(10,0) DEFAULT NULL,
              `vip_killed` decimal(10,0) DEFAULT NULL,
              `vip_escaped` decimal(10,0) DEFAULT NULL,
              `vip_played` decimal(10,0) DEFAULT NULL,
              `mvp` decimal(10,0) DEFAULT NULL,
              `damage` decimal(10,0) DEFAULT NULL,
              `match_win` decimal(10,0) DEFAULT NULL,
              `match_draw` decimal(10,0) DEFAULT NULL,
              `match_lose` decimal(10,0) DEFAULT NULL,
              `first_blood` decimal(10,0) DEFAULT NULL,
              `no_scope` decimal(10,0) DEFAULT NULL,
              `no_scope_dis` decimal(10,0) DEFAULT NULL
            );
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

        $query = $db->query('DROP TABLE IF EXISTS rankme;');

        return $query->execute();
    }
}
