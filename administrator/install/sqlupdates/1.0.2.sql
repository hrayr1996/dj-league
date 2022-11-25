ALTER TABLE `#__djl_games` 
CHANGE `points_home` `points_home` DECIMAL(8,2) NOT NULL DEFAULT '0.0', 
CHANGE `points_away` `points_away` DECIMAL(8,2) NOT NULL DEFAULT '0.0';
