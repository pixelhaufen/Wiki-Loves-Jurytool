CREATE TABLE IF NOT EXISTS `wlx_fotos` (
      `name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
      `user` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
      `date` text COLLATE utf8_bin NOT NULL,
      `time` text COLLATE utf8_bin NOT NULL,
      `size` int(11) NOT NULL DEFAULT '0',
      `width` int(11) NOT NULL DEFAULT '0',
      `height` int(11) NOT NULL DEFAULT '0',
      `pixel` int(11) NOT NULL DEFAULT '0',
      `license` text COLLATE utf8_bin NOT NULL,
      `url` text COLLATE utf8_bin NOT NULL,
	  `thumburl` text COLLATE utf8_bin NOT NULL,
      `descriptionurl` text COLLATE utf8_bin NOT NULL,
      `exclude` int(11) NOT NULL DEFAULT '0',
      `vote` int(11) NOT NULL DEFAULT '0',
      `jury` int(11) NOT NULL DEFAULT '0',
      `online` int(11) NOT NULL DEFAULT '0'
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS `wlx_v_jury` (
      `lname` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
      `pw` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
      `width` int(11) NOT NULL DEFAULT '500',
      `info` int(11) NOT NULL DEFAULT '0',
      `userlevel` int(11) NOT NULL DEFAULT '0',
      `time` int(11) NOT NULL
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS `wlx_v_votes` (
      `name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
      `user` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
      `vote` int(11) NOT NULL DEFAULT '0',
      `time` int(11) NOT NULL,
      `online` int(11) NOT NULL
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS `wlx_jury` (
      `lname` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
      `pw` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
      `width` int(11) NOT NULL DEFAULT '500',
      `info` int(11) NOT NULL DEFAULT '0',
      `userlevel` int(11) NOT NULL DEFAULT '0',
      `time` int(11) NOT NULL
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS `wlx_votes` (
      `name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
      `user` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
      `vote` int(11) NOT NULL DEFAULT '0',
      `time` int(11) NOT NULL,
      `online` int(11) NOT NULL
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `wlx_points` (
      `name` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
	  `round` int(11) NOT NULL,
	  `sum` int(11) NOT NULL DEFAULT '0'
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `wlx_jury`(`lname`,`pw`,`userlevel`) VALUES ('admin','e74c3726b3eae1420c351f3a1978dd47d03d5cbb','1');
INSERT INTO `wlx_v_jury`(`lname`,`pw`,`userlevel`) VALUES ('admin','e74c3726b3eae1420c351f3a1978dd47d03d5cbb','1');

ALTER TABLE `wlx_fotos`
		ADD KEY `vote` (`vote`),
		ADD KEY `name` (`name`);

ALTER TABLE `wlx_v_votes`
		ADD KEY `vote` (`vote`),
		ADD KEY `name` (`name`);

ALTER TABLE `wlx_votes`
		ADD KEY `vote` (`vote`),
		ADD KEY `name` (`name`);

CREATE TABLE IF NOT EXISTS `wlx_fotos_commons` (
		`name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
		`commons` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
		`online` int(11) NOT NULL,
		KEY `name` (`name`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
