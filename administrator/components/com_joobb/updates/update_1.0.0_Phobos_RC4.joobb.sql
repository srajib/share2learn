INSERT INTO `#__joobb_updates` (`version`,`update_file`,`status`) VALUES
 ('1.0.0 Phobos RC4','update_1.0.0_Phobos_RC4.joobb.sql',0)
ON DUPLICATE KEY UPDATE version=version;
