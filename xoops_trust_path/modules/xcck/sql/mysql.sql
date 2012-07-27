CREATE TABLE `{prefix}_{dirname}_page` (
  `page_id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `category_id` mediumint(8) unsigned NOT NULL,
  `maintable_id` int(11) unsigned NOT NULL,
  `p_id` int(11) unsigned NOT NULL,
  `descendant` smallint(5) unsigned NOT NULL,
  `uid` mediumint(8) unsigned NOT NULL,
  `weight` smallint(5) unsigned NOT NULL,
  `status` tinyint(2) unsigned NOT NULL,
  `posttime` int(11) unsigned NOT NULL,
  `updatetime` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`page_id`),
  KEY `category_id` (`category_id`, `status`),
  KEY `maintable_id` (`maintable_id`, `status`),
  KEY `updatetime` (`updatetime`, `status`),
  KEY `weight` (`weight`, `status`)
) ENGINE=MyISAM;

CREATE TABLE `{prefix}_{dirname}_definition` (
  `definition_id` smallint(5) unsigned NOT NULL auto_increment,
  `field_name` varchar(32) NOT NULL,
  `label` varchar(255) NOT NULL,
  `field_type` varchar(16) NOT NULL,
  `validation` varchar(255) NOT NULL,
  `required` tinyint(1) unsigned NOT NULL,
  `weight` tinyint(3) unsigned NOT NULL,
  `show_list` tinyint(1) unsigned NOT NULL,
  `search_flag` tinyint(1) unsigned NOT NULL,
  `description` text NOT NULL,
  `options` text NOT NULL,
  PRIMARY KEY  (`definition_id`),
  KEY `weight` (`weight`)
) ENGINE=MyISAM AUTO_INCREMENT=10;

CREATE TABLE `{prefix}_{dirname}_revision` (
  `revision_id` int(11) unsigned NOT NULL auto_increment,
  `page_id` int(11) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `category_id` mediumint(8) unsigned NOT NULL,
  `maintable_id` int(11) unsigned NOT NULL,
  `p_id` int(11) unsigned NOT NULL,
  `descendant` smallint(5) unsigned NOT NULL,
  `uid` mediumint(8) unsigned NOT NULL,
  `weight` smallint(5) unsigned NOT NULL,
  `status` tinyint(2) unsigned NOT NULL,
  `posttime` int(11) unsigned NOT NULL,
  `updatetime` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`revision_id`),
  KEY `page` (`page_id`),
  KEY `category_id` (`category_id`, `status`),
  KEY `maintable_id` (`maintable_id`, `status`),
  KEY `updatetime` (`updatetime`, `status`),
  KEY `weight` (`weight`, `status`)
) ENGINE=MyISAM;

