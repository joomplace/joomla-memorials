CREATE TABLE IF NOT EXISTS `#__jp3_href` (
  `item_id` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL,
  KEY `item_id` (`item_id`),
  KEY `cat_id` (`cat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `portfolio_pictures`
--
CREATE TABLE IF NOT EXISTS `#__jp3_pictures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `full` varchar(255) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `is_default` enum('0','1') NOT NULL,
  `copyright` text NOT NULL,
  `description` text NOT NULL,
  `ordering` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `portfolio_items`
--

CREATE TABLE IF NOT EXISTS `#__jp3_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `published` tinyint(1) NOT NULL,
  `ordering` int(11) NOT NULL,
  `hits` int(11) NOT NULL,
  `asset_id` int(11) NOT NULL,
  `description_short` text NOT NULL,
  `description` text NOT NULL,
  `custom` text NOT NULL,
  `metakey` text NOT NULL,
  `metadesc` text NOT NULL,
  `metaauth` text NOT NULL,
  `date` VARCHAR(20) NOT NULL DEFAULT '0000-00-00 00:00:00',
  `def_image` VARCHAR( 220 ) NOT NULL,
  `mode` varchar(200) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `og_picture` text NOT NULL,
  `og_description` text NOT NULL,
  `custom_metatags` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `portfolio_rating`
--

CREATE TABLE IF NOT EXISTS `#__jp3_rating` (
  `item_id` int(11) NOT NULL,
  `lastip` varchar(15) NOT NULL,
  `sum` int(11) NOT NULL,
  `count` int(11) NOT NULL,
  KEY `item_id` (`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `portfolio_field`
--

CREATE TABLE IF NOT EXISTS `#__jp3_field` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `label` varchar(200) NOT NULL,
  `type` varchar(200) NOT NULL,
  `def` varchar(200) NOT NULL,
  `req` int(11) NOT NULL,
  `catview` int(11) NOT NULL,
  `format` varchar(50),
  `mode` varchar(200) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `portfolio_dashboard_items`
--

CREATE TABLE IF NOT EXISTS `#__jp3_dashboard_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `published` tinyint(1) NOT NULL,
  `mode` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `portfolio_pictures`
--
CREATE TABLE IF NOT EXISTS `#__jp3_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mode` varchar(255) NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `portfolio_item_content`
-- ----------------------------

CREATE TABLE IF NOT EXISTS `#__jp3_item_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `field_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `portfolio_item_pdf`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `#__jp3_pdf` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `full` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `portfolio_item_audio`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `#__jp3_audio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `full` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `portfolio_item_comments`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `#__jp3_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `date` date DEFAULT NULL,
  `item_id` int(11) NOT NULL,
  `comment` text,
  `mode` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  `published` int(2) NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `t_jp3_templates`
-- ----------------------------

CREATE TABLE IF NOT EXISTS `#__jp3_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `item_view` text NOT NULL,
  `cat_view` text NOT NULL,
  `mode` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `portfolio_item_video`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `#__jp3_video` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `full` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for `portfolio_jp3_condolence`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `#__jp3_condolence` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `portfolio_jp3_ornaments`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `#__jp3_ornaments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `condole_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `published` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;