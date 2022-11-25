
CREATE TABLE IF NOT EXISTS `#__djl_tournaments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `description` text,
  `checked_out` int(11) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `params` text,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__djl_seasons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `description` text,
  `checked_out` int(11) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `params` text,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__djl_leagues` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tournament_id` int(11) NOT NULL,
  `season_id` int(11) NOT NULL,
  `description` text,
  `checked_out` int(11) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `params` text,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__djl_games` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `league_id` int(11) NOT NULL,
  `round` tinyint(4) NOT NULL DEFAULT '0',
  `team_home` int(11) NOT NULL,
  `team_away` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `city` varchar(255) NOT NULL,
  `venue` varchar(255) NOT NULL,
  `score_home` smallint(6) NOT NULL DEFAULT '0',
  `score_away` smallint(6) NOT NULL DEFAULT '0',
  `score_desc` varchar(100) NOT NULL,
  `winner` tinyint(4) NOT NULL DEFAULT '0',
  `points_home` decimal(8,2) NOT NULL DEFAULT '0.00',
  `points_away` decimal(8,2) NOT NULL DEFAULT '0.00',
  `status` tinyint(4) NOT NULL DEFAULT '-1',
  `checked_out` int(11) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `params` text,
  PRIMARY KEY (`id`),
  KEY `league` (`league_id`),
  KEY `league_id` (`league_id`,`status`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__djl_tables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `league_id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `extra_points` tinyint(4) NOT NULL default 0,
  `ordering` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `league_id` (`league_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__djl_teams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `venue` varchar(255) NOT NULL,
  `checked_out` int(11) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `params` text,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;
