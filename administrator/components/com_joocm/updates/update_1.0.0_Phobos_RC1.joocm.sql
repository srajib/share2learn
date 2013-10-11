INSERT INTO `#__joocm_updates` (`version`,`update_file`,`status`) VALUES
 ('1.0.0 Phobos RC1','update_1.0.0_Phobos_RC1.joocm.sql',0)
ON DUPLICATE KEY UPDATE version=version;

ALTER TABLE `#__joocm_links` CHANGE `com` `com` varchar(255) NOT NULL default '';
ALTER TABLE `#__joocm_links` ADD `replacement` text NOT NULL AFTER `function`;

INSERT INTO `#__joocm_links` (`name`,`com`,`url`,`function`,`replacement`,`published`,`checked_out`,`checked_out_time`) VALUES
 ('User List Online','com_joocm','&view=userlist&status=online','userlistonline','',1,0,'0000-00-00 00:00:00'),
 ('Edit Account','com_joocm','&view=editaccount','editaccount','',1,0,'0000-00-00 00:00:00'),
 ('Edit Settings','com_joocm','&view=editsettings','editsettings','',1,0,'0000-00-00 00:00:00'),
 ('Joo!CM Main','com_joocm','&view=main','main','',1,0,'0000-00-00 00:00:00');

ALTER TABLE `#__joocm_terms` CHANGE `description` `description` text NOT NULL;
ALTER TABLE `#__joocm_terms` CHANGE `keywords` `keywords` text NOT NULL;

DELETE FROM `#__joocm_interfaces` WHERE com = 'com_joocm' AND url = '&view=login' AND client = 0;
DELETE FROM `#__joocm_interfaces` WHERE com = 'com_joocm' AND url = '&view=terms' AND client = 0;
DELETE FROM `#__joocm_interfaces` WHERE com = 'com_joocm' AND url = '&view=register' AND client = 0;
DELETE FROM `#__joocm_interfaces` WHERE com = 'com_joocm' AND url = '&view=editaccount' AND client = 0;
DELETE FROM `#__joocm_interfaces` WHERE com = 'com_joocm' AND url = '&view=editprofile' AND client = 0;
DELETE FROM `#__joocm_interfaces` WHERE com = 'com_joocm' AND url = '&view=editsettings' AND client = 0;
DELETE FROM `#__joocm_interfaces` WHERE com = 'com_joocm' AND url = '&view=avatar' AND client = 0;
DELETE FROM `#__joocm_interfaces` WHERE com = 'com_joocm' AND url = '&view=userlist' AND client = 0;
DELETE FROM `#__joocm_interfaces` WHERE com = 'com_joocm' AND url = '&view=whosonline' AND client = 0;
DELETE FROM `#__joocm_interfaces` WHERE com = 'com_joocm' AND url = '&view=joocmlogout' AND client = 0;
DELETE FROM `#__joocm_interfaces` WHERE com = 'com_joocm' AND url = '&task=joocmlogout' AND client = 0;

UPDATE `#__joocm_interfaces` SET `system` = 1 WHERE `url` = '&task=joocm_link_view';
UPDATE `#__joocm_interfaces` SET `ordering` = 8 WHERE `url` = '&task=joocm_link_view';
UPDATE `#__joocm_interfaces` SET `ordering` = 9 WHERE `url` = '&task=joocm_usersync_view';
UPDATE `#__joocm_interfaces` SET `ordering` = 10 WHERE `url` = '&task=joocm_credits_view';

UPDATE `#__joocm_interfaces` SET `url` = '&task=joocm_install_view' WHERE `url` = '&task=joocm_usersync_view';
