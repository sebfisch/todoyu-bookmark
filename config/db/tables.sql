--
-- Table structure for table `ext_bookmark_bookmark`
--

CREATE TABLE `ext_bookmark_bookmark` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user_create` int(10) unsigned NOT NULL,
  `date_create` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) NOT NULL,
  `deleted` tinyint(1) NOT NULL,
  `id_item` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;