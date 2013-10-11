<?php
/**
 * @version $Id: include.php 224 2012-03-01 14:57:39Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2012 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// firefox works correctly. we need a web based separator.
define('DL', '/'); 

/**
 * Joo!CM
 *
 * @package Joo!CM
 */
define('JOOCM_BASEPATH_LIVE', JURI::root().'components'.DL.'com_joocm');
define('JOOCM_CAPTCHAS_LIVE', JOOCM_BASEPATH_LIVE.DL.'assets'.DL.'captchas');
define('JOOCM_IMAGES_LIVE', JOOCM_BASEPATH_LIVE.DL.'assets'.DL.'images');
define('JOOCM_STYLES_LIVE', JOOCM_BASEPATH_LIVE.DL.'assets'.DL.'css');

define('JOOCM_BASEPATH', JPATH_SITE.DS.'components'.DS.'com_joocm');
define('JOOCM_CAPTCHAS', JOOCM_BASEPATH.DS.'assets'.DS.'captchas');
define('JOOCM_IMAGES', JOOCM_BASEPATH.DS.'assets'.DS.'images');

define('JOOCM_MEDIA', JPATH_SITE.DS.'media'.DS.'joocm');

JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joocm'.DS.'tables');

JLoader::register('JoocmHelper', JOOCM_BASEPATH.DS.'helpers'.DS.'joocm.php');
JLoader::register('JoocmAvatar', JOOCM_BASEPATH.DS.'system'.DS.'joocmavatar.php');
JLoader::register('JoocmCaptcha', JOOCM_BASEPATH.DS.'system'.DS.'joocmcaptcha.php');
JLoader::register('JoocmConfig', JOOCM_BASEPATH.DS.'system'.DS.'joocmconfig.php');
JLoader::register('JoocmGD', JOOCM_BASEPATH.DS.'system'.DS.'joocmgd.php');
JLoader::register('JoocmHTML', JOOCM_BASEPATH.DS.'system'.DS.'joocmhtml.php');
JLoader::register('JoocmInstaller', JOOCM_BASEPATH.DS.'system'.DS.'joocmintstaller.php');
JLoader::register('JoocmMail', JOOCM_BASEPATH.DS.'system'.DS.'joocmmail.php');
JLoader::register('JoocmModel', JOOCM_BASEPATH.DS.'system'.DS.'joocmmodel.php');
JLoader::register('JoocmUser', JOOCM_BASEPATH.DS.'system'.DS.'joocmuser.php');

$tasks = array("joocmlogin" => "joocmLogin", 
			 "joocmlogout" => "joocmLogout",
			 "joocmactivateprofile" => "joocmActivateProfile",
			 "joocmsaveaccount" => "joocmSaveAccount",
			 "joocmsavesettings" => "joocmSaveSettings",
			 "joocmsaveprofile" => "joocmSaveProfile",
			 "joocmregister" => "joocmRegister",
			 "joocmrequestlogin" => "joocmRequestLogin",
			 "joocmresetlogin" => "joocmResetLogin",
			 "joocmsaveavatar" => "joocmSaveAvatar",
			 "joocmuploadavatar" => "joocmUploadAvatar",
			 "joocmdeleteavatar" => "joocmDeleteAvatar",
			 "joocmagreedterms" => "joocmAgreedTerms");
?>