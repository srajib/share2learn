<?php
/**
 * @version $Id: image.php 22 2009-12-25 20:07:22Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!CM directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */

require_once('captchaimage.php');

session_id($_GET['sid']);
session_start();

$joocmCaptchaImage =& JoocmCaptchaImage::getInstance();
$joocmCaptchaImage->setFontSizeRange($_SESSION['font_size_range']);
$joocmCaptchaImage->setFontColor($_SESSION['font_color']);
$joocmCaptchaImage->createImage($_SESSION['captcha_code']);
session_write_close(); ?>