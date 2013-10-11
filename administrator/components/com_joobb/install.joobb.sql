CREATE TABLE IF NOT EXISTS `#__joobb_attachments` (
  `id` int(11) NOT NULL auto_increment,
  `file_name` varchar(255) NOT NULL default '',
  `original_name` varchar(255) NOT NULL default '',
  `hits` int(11) NOT NULL default '0',
  `date_upload` datetime NOT NULL default '0000-00-00 00:00:00',
  `id_post` int(11) NOT NULL default '0',
  `id_user` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `id_post` (`id_post`),
  KEY `id_user` (`id_user`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__joobb_categories` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `published` tinyint(1) NOT NULL default '1',
  `ordering` int(11) NOT NULL default '0',
  `checked_out` int(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `ordering` (`ordering`)
) DEFAULT CHARSET=utf8;

INSERT INTO `#__joobb_categories` (`id`,`name`,`published`,`ordering`,`checked_out`,`checked_out_time`) VALUES 
(1,'Test Category',1,1,0,'0000-00-00 00:00:00');

CREATE TABLE IF NOT EXISTS `#__joobb_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template` varchar(255) NOT NULL DEFAULT '',
  `theme` varchar(255) NOT NULL DEFAULT '',
  `emotion_set` varchar(255) NOT NULL DEFAULT '',
  `icon_set` varchar(255) NOT NULL DEFAULT '',
  `editor` varchar(255) NOT NULL DEFAULT '',
  `topic_icon_function` varchar(255) NOT NULL DEFAULT '',
  `post_icon_function` varchar(255) NOT NULL DEFAULT '',
  `board_settings` text NOT NULL,
  `latestpost_settings` text NOT NULL,
  `feed_settings` text NOT NULL,
  `view_settings` text NOT NULL,
  `user_settings_defaults` text NOT NULL,
  `attachment_settings` text NOT NULL,
  `image_settings` text NOT NULL,
  `captcha_settings` text NOT NULL,
  `parse_settings` text NOT NULL,
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

INSERT INTO `#__joobb_configs` (`id`,`template`,`theme`,`emotion_set`,`icon_set`,`editor`,`topic_icon_function`,`post_icon_function`,`board_settings`,`latestpost_settings`,`feed_settings`,`view_settings`,`user_settings_defaults`,`attachment_settings`,`image_settings`,`captcha_settings`,`parse_settings`,`checked_out`,`checked_out_time`) VALUES 
 (1,'joobb.xml','joobb_black.xml','joobb_yellow.xml','joobb.xml','joobb','postPost','postPost','board_name=Joo!BB - Joomla Bulletin Board\nbreadcrumb_index=Board Index\ndescription=Joo!BB - Joomla! Bulletin Board\nkeywords=Joo!BB, Joomla! Bulletin Board\npublished=1\nflood_interval=30\ntopics_per_page=10\nposts_per_page=10\nsearch_results_per_page=10\nitems_per_page=10\nbreadcrumb_max_length=50\nguest_time=30\nlatest_items_count=5\nlatest_items_type=0\nenable_bbcode=1\nenable_emotions=1\nauto_subscription=0\nenable_guest_name=1\nguest_name_required=1\nlatest_members_count=3','enable_filter=1\nlatest_post_hours=8,12\nlatest_post_days=1,2,3\nlatest_post_weeks=1\nlatest_post_months=1,6\nlatest_post_years=1','enable_feeds=1\nfeed_items_count=10\nfeed_items_type=0\nfeed_desc_trunk_size=0\nfeed_desc_html_syndicate=0\nfeed_image_title=Joo!BB Logo\nfeed_image_url=http://www.joobb.org/images/logo.png\nfeed_image_link=http://www.joobb.org/\nfeed_image_description=This feed is provided by joobb.org. Please click to visit.\nfeed_image_desc_trunk_size=0\nfeed_image_desc_html_syndicate=0','show_latestitems=1\nshow_statistic=1\nshow_whosonline=1\nshow_legend=1\nshow_footer=1','role=1\nenable_bbcode=1\nenable_emotions=1\nauto_subscription=0','enable_attachments=1\nattachment_max=3\nattachment_max_file_size=512000\nattachment_file_types=jpg,png,gif,bmp,zip\nattachment_path=media/joobb/attachments','enable_images=1\nimage_max_file_size=512000\nimage_file_types=jpg,png,gif,bmp\nimage_path=media/joobb/images','captcha_edittopic=0\ncaptcha_deletetopic=0\ncaptcha_editpost=0\ncaptcha_deletepost=0','enable_line_numbers=0\nlink_target=_blank\nimage_max_width=500\nimage_max_height=375\nyoutube_width=425\nyoutube_height=344\nyoutube_allow_fullscreen=1\ngvideo_width=425\ngvideo_height=344',62,'2010-09-19 09:28:20');

CREATE TABLE IF NOT EXISTS `#__joobb_forums` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL default '1',
  `locked` tinyint(1) NOT NULL default '0',
  `ordering` int(11) NOT NULL default '1',
  `new_posts_time` int(5) NOT NULL default '0',
  `posts` int(11) NOT NULL default '0',
  `topics` int(11) NOT NULL default '0',
  `auth_view` int(3) NOT NULL default '0',
  `auth_read` int(3) NOT NULL default '0',
  `auth_post` int(3) NOT NULL default '0',
  `auth_post_all` int(3) NOT NULL default '0',
  `auth_reply` int(3) NOT NULL default '0',
  `auth_reply_all` int(3) NOT NULL default '0',
  `auth_edit` int(3) NOT NULL default '0',
  `auth_edit_all` int(3) NOT NULL default '0',
  `auth_delete` int(3) NOT NULL default '0',
  `auth_delete_all` int(3) NOT NULL default '0',
  `auth_move` int(3) NOT NULL default '0',
  `auth_reportpost` int(3) NOT NULL default '0',
  `auth_sticky` int(3) NOT NULL default '0',
  `auth_lock` int(3) NOT NULL default '0',
  `auth_lock_all` int(3) NOT NULL default '0',
  `auth_announce` int(3) NOT NULL default '0',
  `auth_attachments` int(3) NOT NULL default '0',
  `checked_out` int(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `id_cat` int(11) NOT NULL default '0',
  `id_last_post` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `id_cat` (`id_cat`),
  KEY `id_last_post` (`id_last_post`),
  KEY `ordering` (`ordering`)
) DEFAULT CHARSET=utf8;

INSERT INTO `#__joobb_forums` (`id`,`name`,`description`,`status`,`locked`,`ordering`,`new_posts_time`,`posts`,`topics`,`auth_view`,`auth_read`,`auth_post`,`auth_post_all`,`auth_reply`,`auth_reply_all`,`auth_edit`,`auth_edit_all`,`auth_delete`,`auth_delete_all`,`auth_move`,`auth_reportpost`,`auth_sticky`,`auth_lock`,`auth_lock_all`,`auth_announce`,`auth_attachments`,`checked_out`,`checked_out_time`,`id_cat`,`id_last_post`) VALUES 
(1,'Test Forum','Test Forum',1,0,1,30,1,1,0,0,1,4,1,4,1,4,1,4,3,1,3,3,4,3,1,0,'0000-00-00 00:00:00',1,1);

CREATE TABLE IF NOT EXISTS `#__joobb_forums_auth` (
  `id_forum` int(11) NOT NULL default '0',
  `id_user` int(11) NOT NULL default '0',
  `role` tinyint(1) NOT NULL default '0',
  `id_group` int(11) NOT NULL default '0',
  KEY `id_forum` (`id_forum`),
  KEY `id_group` (`id_group`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__joobb_groups` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `description` text NOT NULL,
  `published` tinyint(1) NOT NULL default '1',
  `role` tinyint(1) NOT NULL default '0',
  `checked_out` int(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) DEFAULT CHARSET=utf8;

INSERT INTO `#__joobb_groups` (`id`,`name`,`description`,`published`,`role`,`checked_out`,`checked_out_time`) VALUES 
(1,'Admins (Main Role)','Forum Admins',1,4,0,'0000-00-00 00:00:00'),
(2,'Moderators (Main Role)','Forum Moderators',1,3,0,'0000-00-00 00:00:00'),
(3,'Private (Main Role)','Forum Private Access',1,2,0,'0000-00-00 00:00:00'),
(4,'Users (Main Role)','Forum Users',1,1,0,'0000-00-00 00:00:00');

CREATE TABLE IF NOT EXISTS `#__joobb_groups_users` (
  `id_group` int(11) NOT NULL default '0',
  `id_user` int(11) NOT NULL default '0',
  KEY `id_group` (`id_group`),
  KEY `id_user` (`id_user`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__joobb_posts` (
  `id` int(11) NOT NULL auto_increment,
  `subject` varchar(255) NOT NULL default '',
  `text` text NOT NULL,
  `date_post` datetime NOT NULL default '0000-00-00 00:00:00',
  `date_last_edit` datetime NOT NULL default '0000-00-00 00:00:00',
  `id_user_last_edit` int(11) NOT NULL default '0',
  `enable_bbcode` tinyint(1) NOT NULL default '1',
  `enable_emotions` tinyint(1) NOT NULL default '1',
  `ip_poster` varchar(15) NOT NULL default '',
  `icon_function` varchar(255) NOT NULL default '',
  `id_topic` int(11) NOT NULL default '0',
  `id_forum` int(11) NOT NULL default '0',
  `id_user` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `id_forum` (`id_forum`),
  KEY `id_user` (`id_user`),
  KEY `id_topic` (`id_topic`)
) DEFAULT CHARSET=utf8;

INSERT INTO `#__joobb_posts` (`id`,`subject`,`text`,`date_post`,`date_last_edit`,`id_user_last_edit`,`enable_bbcode`,`enable_emotions`,`ip_poster`,`icon_function`,`id_topic`,`id_forum`,`id_user`) VALUES 
(1,'Welcome to Joo!BB','[b]Welcome to Joo!BB[/b] :)','2007-08-13 22:00:00','0000-00-00 00:00:00','0',1,1,'127.0.0.1','postPost',1,1,0);

CREATE TABLE IF NOT EXISTS `#__joobb_posts_guests` (
  `id_post` int(11) NOT NULL default '0',
  `guest_name` varchar(255) NOT NULL default '',
  KEY `id_post` (`id_post`)
) DEFAULT CHARSET=utf8;

INSERT INTO `#__joobb_posts_guests` (`id_post`,`guest_name`) VALUES 
(1,'Joo!BB Administrator');

CREATE TABLE IF NOT EXISTS `#__joobb_ranks` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `description` text NOT NULL,
  `min_posts` int(11) NOT NULL default '0',
  `published` tinyint(1) NOT NULL default '1',
  `rank_file` varchar(255) NOT NULL default '',
  `checked_out` int(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) DEFAULT CHARSET=utf8;

INSERT INTO `#__joobb_ranks` (`id`,`name`,`description`,`min_posts`,`published`,`rank_file`,`checked_out`,`checked_out_time`) VALUES
(1,'Joo!BB - Newie','Joo!BB - Newie',0,1,'stars_1.png',0,'0000-00-00 00:00:00'),
(2,'Joo!BB - User','Joo!BB - User',25,1,'stars_2.png',0,'0000-00-00 00:00:00'),
(3,'Joo!BB - Experienced','Joo!BB - Experienced',50,1,'stars_3.png',0,'0000-00-00 00:00:00'),
(4,'Joo!BB - Hero','Joo!BB - Hero',100,1,'stars_4.png',0,'0000-00-00 00:00:00'),
(5,'Joo!BB - Master','Joo!BB - Master',200,1,'stars_5.png',0,'0000-00-00 00:00:00');

CREATE TABLE IF NOT EXISTS `#__joobb_topics` (
  `id` int(11) NOT NULL auto_increment,
  `views` int(11) unsigned NOT NULL default '0',
  `replies` int(11) unsigned NOT NULL default '0',
  `status` tinyint(3) NOT NULL default '0',
  `vote` tinyint(1) NOT NULL default '0',
  `type` tinyint(3) NOT NULL default '0',
  `id_forum` int(11) NOT NULL default '0',
  `id_first_post` int(11) unsigned NOT NULL default '0',
  `id_last_post` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `type` (`type`),
  KEY `status` (`status`),
  KEY `id_forum` (`id_forum`)
) DEFAULT CHARSET=utf8;

INSERT INTO `#__joobb_topics` (`id`,`views`,`replies`,`status`,`vote`,`type`,`id_forum`,`id_first_post`,`id_last_post`) VALUES 
(1,0,0,0,0,2,1,1,1);

CREATE TABLE IF NOT EXISTS `#__joobb_topics_subscriptions` (
  `id_topic` int(11) NOT NULL default '0',
  `id_user` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id_topic`,`id_user`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__joobb_updates` (
  `version` varchar(100) NOT NULL DEFAULT '',
  `update_file` varchar(255) NOT NULL DEFAULT '',
  `date_install` datetime NOT NULL default '0000-00-00 00:00:00',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`version`)
) DEFAULT CHARSET=utf8;

INSERT INTO `#__joobb_updates` (`version`,`update_file`,`status`) VALUES
 ('1.0.0 Phobos RC1','update_1.0.0_Phobos_RC1.joobb.sql',0),
 ('1.0.0 Phobos RC2','update_1.0.0_Phobos_RC2.joobb.sql',0),
 ('1.0.0 Phobos RC3','update_1.0.0_Phobos_RC3.joobb.sql',0),
 ('1.0.0 Phobos RC4','update_1.0.0_Phobos_RC4.joobb.sql',0),
 ('1.0.0 Phobos RC5','update_1.0.0_Phobos_RC5.joobb.sql',0),
 ('1.0.0 Phobos Stable','install.joobb.sql',1);

CREATE TABLE IF NOT EXISTS `#__joobb_users` (
  `id` int(11) NOT NULL default '0',
  `posts` mediumint(8) unsigned NOT NULL default '0',
  `role` tinyint(1) unsigned NOT NULL default '1',
  `enable_bbcode` tinyint(1) NOT NULL default '1',
  `enable_emotions` tinyint(1) NOT NULL default '1',
  `auto_subscription` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) DEFAULT CHARSET=utf8;