INSERT INTO `#__joocm_updates` (`version`,`update_file`,`status`) VALUES
 ('1.0.0 Phobos RC3','update_1.0.0_Phobos_RC3.joocm.sql',0)
ON DUPLICATE KEY UPDATE version=version;

ALTER TABLE `#__joocm_users` ADD `hide` tinyint(1) NOT NULL default '0' AFTER `agreed_terms`;
