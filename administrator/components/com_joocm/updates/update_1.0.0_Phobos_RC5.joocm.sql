INSERT INTO `#__joocm_updates` (`version`,`update_file`,`status`) VALUES
 ('1.0.0 Phobos RC5','update_1.0.0_Phobos_RC5.joocm.sql',0)
ON DUPLICATE KEY UPDATE version=version;

UPDATE `#__joocm_interfaces` SET `name` = 'COM_JOOCM_MESSAGES' WHERE `name` = 'CM_MESSAGES';
UPDATE `#__joocm_interfaces` SET `name` = 'COM_JOOCM_USERS' WHERE `name` = 'CM_USERS';
UPDATE `#__joocm_interfaces` SET `name` = 'COM_JOOCM_TOOLS' WHERE `name` = 'CM_TOOLS';
UPDATE `#__joocm_interfaces` SET `name` = 'COM_JOOCM_TIMEFORMATS' WHERE `name` = 'CM_TIMEFORMATS';
UPDATE `#__joocm_interfaces` SET `name` = 'COM_JOOCM_TERMS' WHERE `name` = 'CM_TERMS';
UPDATE `#__joocm_interfaces` SET `name` = 'COM_JOOCM_PROFILEFIELDS' WHERE `name` = 'CM_PROFILEFIELDS';
UPDATE `#__joocm_interfaces` SET `name` = 'COM_JOOCM_LINKS' WHERE `name` = 'CM_LINKS';
UPDATE `#__joocm_interfaces` SET `name` = 'COM_JOOCM_INTERFACES' WHERE `name` = 'CM_INTERFACES';
UPDATE `#__joocm_interfaces` SET `name` = 'COM_JOOCM_CREDITS' WHERE `name` = 'CM_CREDITS';
UPDATE `#__joocm_interfaces` SET `name` = 'COM_JOOCM_CONFIG' WHERE `name` = 'CM_CONFIG';
UPDATE `#__joocm_interfaces` SET `name` = 'COM_JOOCM_AVATARS' WHERE `name` = 'CM_AVATARS';