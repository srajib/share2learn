INSERT INTO `#__joobb_updates` (`version`,`update_file`,`status`) VALUES
 ('1.0.0 Phobos RC1','update_1.0.0_Phobos_RC1.joobb.sql',0)
ON DUPLICATE KEY UPDATE version=version;

ALTER TABLE `#__joobb_configs` ADD `template` varchar(255) NOT NULL default '' AFTER `id_design`;
UPDATE `#__joobb_configs` SET `template` = 'joobb.xml';
ALTER TABLE `#__joobb_configs` ADD `theme` varchar(255) NOT NULL default '' AFTER `template`;
UPDATE `#__joobb_configs` SET `theme` = 'joobb_black.xml';
ALTER TABLE `#__joobb_configs` ADD `emotion_set` varchar(255) NOT NULL default '' AFTER `theme`;
UPDATE `#__joobb_configs` SET `emotion_set` = (SELECT emotion_set FROM `#__joobb_designs` WHERE default_design = 1);
ALTER TABLE `#__joobb_configs` ADD `icon_set` varchar(255) NOT NULL default '' AFTER `emotion_set`;
UPDATE `#__joobb_configs` SET `icon_set` = (SELECT icon_set FROM `#__joobb_designs` WHERE default_design = 1);
ALTER TABLE `#__joobb_configs` DROP `id_design`;
ALTER TABLE `#__joobb_configs` DROP `name`;
ALTER TABLE `#__joobb_configs` DROP `default_config`;

DROP TABLE IF EXISTS `#__joobb_designs`;



