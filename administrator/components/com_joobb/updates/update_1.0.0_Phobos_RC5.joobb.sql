INSERT INTO `#__joobb_updates` (`version`,`update_file`,`status`) VALUES
 ('1.0.0 Phobos RC5','update_1.0.0_Phobos_RC5.joobb.sql',0)
ON DUPLICATE KEY UPDATE version=version;

ALTER TABLE `#__joobb_configs` ADD `image_settings` text NOT NULL AFTER `attachment_settings`;

UPDATE `#__joobb_configs` SET `image_settings` = 'enable_images=1\nimage_max_file_size=512000\nimage_file_types=jpg,png,gif,bmp\nimage_path=media/joobb/images';
