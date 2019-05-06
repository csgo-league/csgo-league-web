<?php

namespace Redline\League\Models;

class PlayerModel
{
    protected $steam;
    protected $name;
    protected $lastIP;
    protected $score;
    protected $kills;
    protected $deaths;
    protected $assists;
    protected $suicides;
    protected $tk;
    protected $shots;
    protected $hits;
    protected $headshots;
    protected $connected;
    protected $rounds_tr;
    protected $rounds_ct;
    protected $lastconnect;
    protected $knife;
    protected $glock;
    protected $hkp2000;
    protected $usp_silencer;
    protected $p250;
    protected $deagle;
    protected $elite;
    protected $fiveseven;
    protected $tec9;
    protected $cz75a;
    protected $revolver;
    protected $nova;
    protected $xm1014;
    protected $mag7;
    protected $sawedoff;
    protected $bizon;
    protected $mac10;
    protected $mp9;
    protected $mp7;
    protected $ump45;
    protected $p90;
    protected $galilar;
    protected $ak47;
    protected $scar20;
    protected $famas;
    protected $m4a1;
    protected $m4a1_silencer;
    protected $aug;
    protected $ssg08;
    protected $sg556;
    protected $awp;
    protected $g3sg1;
    protected $m249;
    protected $negev;
    protected $hegrenade;
    protected $flashbang;
    protected $smokegrenade;
    protected $inferno;
    protected $decoy;
    protected $taser;
    protected $head;
    protected $chest;
    protected $stomach;
    protected $left_arm;
    protected $right_arm;
    protected $left_leg;
    protected $right_leg;
    protected $c4_planted;
    protected $c4_exploded;
    protected $c4_defused;
    protected $ct_win;
    protected $tr_win;
    protected $hostages_rescued;
    protected $vip_killed;
    protected $vip_escaped;
    protected $vip_played;
    protected $mvp;
    protected $damage;
    protected $steamid64;

    /**
     * PlayerModel Constructor
     * @param array $item
     */
    public function __construct(array $item)
    {
        $this->steam = $item['steam'];
        $this->name = $item['name'];
        $this->lastIP = $item['lastip'];
        $this->score = $item['score'];
        $this->kills = $item['kills'];
        $this->deaths = $item['deaths'];
        $this->assists = $item['assists'];
        $this->suicides = $item['suicides'];
        $this->tk = $item['tk'];
        $this->shots = $item['shots'];
        $this->hits = $item['hits'];
        $this->headshots = $item['headshots'];
        $this->connected = $item['connected'];
        $this->rounds_tr = $item['rounds_tr'];
        $this->rounds_ct = $item['rounds_ct'];
        $this->lastconnect = $item['lastconnect'];
        $this->knife = $item['knife'];
        $this->glock = $item['glock'];
        $this->hkp2000 = $item['hkp2000'];
        $this->usp_silencer = $item['usp_silencer'];
        $this->p250 = $item['p250'];
        $this->deagle = $item['deagle'];
        $this->elite = $item['elite'];
        $this->fiveseven = $item['fiveseven'];
        $this->tec9 = $item['tec9'];
        $this->cz75a = $item['cz75a'];
        $this->revolver = $item['revolver'];
        $this->nova = $item['nova'];
        $this->xm1014 = $item['xm1014'];
        $this->mag7 = $item['mag7'];
        $this->sawedoff = $item['sawedoff'];
        $this->bizon = $item['bizon'];
        $this->mac10 = $item['mac10'];
        $this->mp9 = $item['mp9'];
        $this->mp7 = $item['mp7'];
        $this->ump45 = $item['ump45'];
        $this->p90 = $item['p90'];
        $this->galilar = $item['galilar'];
        $this->ak47 = $item['ak47'];
        $this->scar20 = $item['scar20'];
        $this->famas = $item['famas'];
        $this->m4a1 = $item['m4a1'];
        $this->m4a1_silencer = $item['m4a1_silencer'];
        $this->aug = $item['aug'];
        $this->ssg08 = $item['ssg08'];
        $this->sg556 = $item['sg556'];
        $this->awp = $item['awp'];
        $this->g3sg1 = $item['g3sg1'];
        $this->m249 = $item['m249'];
        $this->negev = $item['negev'];
        $this->hegrenade = $item['hegrenade'];
        $this->flashbang = $item['flashbang'];
        $this->smokegrenade = $item['smokegrenade'];
        $this->inferno = $item['inferno'];
        $this->decoy = $item['decoy'];
        $this->taser = $item['taser'];
        $this->head = $item['head'];
        $this->chest = $item['chest'];
        $this->stomach = $item['stomach'];
        $this->left_arm = $item['left_arm'];
        $this->right_arm = $item['right_arm'];
        $this->left_leg = $item['left_leg'];
        $this->right_leg = $item['right_leg'];
        $this->c4_planted = $item['c4_planted'];
        $this->c4_exploded = $item['c4_exploded'];
        $this->c4_defused = $item['c4_defused'];
        $this->ct_win = $item['ct_win'];
        $this->tr_win = $item['tr_win'];
        $this->hostages_rescued = $item['hostages_rescued'];
        $this->vip_killed = $item['vip_killed'];
        $this->vip_escaped = $item['vip_escaped'];
        $this->vip_played = $item['vip_played'];
        $this->mvp = $item['mvp'];
        $this->damage = $item['damage'];
        $this->steamid64 = $item['steamid64'];
    }

    /**
     * @param $name
     * @return mixed
     */
    public function get(string $name)
    {
        return $this->{$name};
    }

    /**
     * @return float
     */
    public function getKDR(): float
    {
        $deaths = $this->get('deaths');

        if ($deaths == 0) {
            $deaths = 1;
        }

        // Force 2dp
        return $this->get('kills') / $deaths;
    }

    /**
     * @return float
     */
    public function getADR(): float
    {
        $roundsTotal = $this->get('rounds_ct');
        $roundsTotal += $this->get('rounds_tr');
        $damageTotal = $this->get('damage');

        return round($damageTotal / $roundsTotal, 2);
    }

    /**
     * @return float
     */
    public function getAccuracy(): float
    {
        $shots = $this->get('shots');
        $hits = $this->get('hits');
        $accuracy = ceil(($hits / $shots) * 100);

        return $accuracy;
    }

    /**
     * @return float
     */
    public function getHeadShotPercent(): float
    {
        $headshots = $this->get('headshots');
        $kills = $this->get('kills');

        return round(($headshots / $kills) * 100, 2);
    }

    /**
     * @param int $weaponKills
     * @return float
     */
    public function getPercentTotalKill(int $weaponKills): float
    {
        $kills = $this->get('kills');

        return round(($weaponKills / $kills) * 100, 2);
    }

    /**
     * @return false|int|string
     */
    public function getFavoriteWeapon()
    {
        $weapons = [
            'AK-47' => $this->get('ak47'),
            'M4A1-S' => $this->get('m4a1_silencer'),
            'M4A4' => $this->get('m4a1'),
            'AWP' => $this->get('awp'),
            'USP-S' => $this->get('usp_silencer'),
            'P2000' => $this->get('hkp2000'),
            'Glock' => $this->get('glock'),
            'P250' => $this->get('p250'),
            'Desert Eagle' => $this->get('deagle'),
            'Five Seven' => $this->get('fiveseven'),
            'Tec-9' => $this->get('tec9'),
            'SG556' => $this->get('sg556'),
            'SSG-08' => $this->get('ssg08'),
            'Aug' => $this->get('aug'),
            'Famas' => $this->get('famas'),
            'Galil' => $this->get('galilar')
        ];

        return array_search(max($weapons), $weapons);
    }
}
