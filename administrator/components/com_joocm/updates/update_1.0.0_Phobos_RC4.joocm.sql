INSERT INTO `#__joocm_updates` (`version`,`update_file`,`status`) VALUES
 ('1.0.0 Phobos RC4','update_1.0.0_Phobos_RC4.joocm.sql',0)
ON DUPLICATE KEY UPDATE version=version;
