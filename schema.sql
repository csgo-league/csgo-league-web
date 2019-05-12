SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE TABLE `players` (
  `steam` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `steam64` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discord` varchar(100) COLLATE utf8mb4_unicode_ci,
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

CREATE TABLE `player_link_codes` (
  `steam64` varchar(100) NOT NULL,
  `discord` varchar(100) NOT NULL,
  `code` varchar(100) NOT NULL,
  `expires` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `rankme` (
  `id` int(11) NOT NULL,
  `steam` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` text COLLATE utf8mb4_unicode_ci,
  `lastip` text COLLATE utf8mb4_unicode_ci,
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

CREATE TABLE `sql_matches` (
  `match_id` bigint(20) NOT NULL,
  `name` varchar(65) CHARACTER SET utf8 NOT NULL,
  `steam64` varchar(64) CHARACTER SET utf8 NOT NULL,
  `team` int(11) NOT NULL,
  `alive` int(11) NOT NULL,
  `ping` int(11) NOT NULL,
  `account` int(11) NOT NULL,
  `kills` int(11) NOT NULL,
  `assists` int(11) NOT NULL,
  `deaths` int(11) NOT NULL,
  `mvps` int(11) NOT NULL,
  `score` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

CREATE TABLE `sql_matches_scoretotal` (
  `match_id` bigint(20) UNSIGNED NOT NULL,
  `timestamp` int(11) NOT NULL,
  `team_0` int(11) NOT NULL,
  `team_1` int(11) NOT NULL,
  `team_2` int(11) NOT NULL,
  `team_3` int(11) NOT NULL,
  `teamname_1` varchar(64) NOT NULL,
  `teamname_2` varchar(64) NOT NULL,
  `map` varchar(128) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

ALTER TABLE `rankme`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `steam` (`steam`);

ALTER TABLE `sql_matches_scoretotal`
  ADD PRIMARY KEY (`match_id`),
  ADD UNIQUE KEY `match_id` (`match_id`);

ALTER TABLE `rankme`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

ALTER TABLE `sql_matches_scoretotal`
  MODIFY `match_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
