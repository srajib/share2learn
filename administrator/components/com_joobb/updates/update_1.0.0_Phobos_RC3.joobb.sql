INSERT INTO `#__joobb_updates` (`version`,`update_file`,`status`) VALUES
 ('1.0.0 Phobos RC3','update_1.0.0_Phobos_RC3.joobb.sql',0)
ON DUPLICATE KEY UPDATE version=version;

ALTER TABLE `#__joobb_forums` ADD `auth_post_all` int(3) NOT NULL default '0' AFTER `auth_post`;
ALTER TABLE `#__joobb_forums` ADD `auth_reply_all` int(3) NOT NULL default '0' AFTER `auth_reply`;
ALTER TABLE `#__joobb_forums` ADD `auth_lock_all` int(3) NOT NULL default '0' AFTER `auth_lock`;
ALTER TABLE `#__joobb_posts` ADD `id_user_last_edit` int(11) NOT NULL default '0' AFTER `date_last_edit`;

UPDATE `#__joobb_forums` SET `auth_post_all` = 4;
UPDATE `#__joobb_forums` SET `auth_reply_all` = 4;
UPDATE `#__joobb_forums` SET `auth_lock_all` = 4;
