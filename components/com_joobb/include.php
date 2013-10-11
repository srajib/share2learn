<?php
/**
 * @version $Id: include.php 224 2012-03-01 14:57:39Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2012 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_SITE.DS.'components'.DS.'com_joocm'.DS.'include.php');

/**
 * Joo!BB
 *
 * @package Joo!BB
 */
define('JOOBB_BASEPATH_LIVE', JURI::root().'components'.DL.'com_joobb');
define('JOOBB_TEMPLATES_LIVE', JOOBB_BASEPATH_LIVE.DL.'assets'.DL.'templates');
define('JOOBB_EMOTIONS_LIVE', JOOBB_BASEPATH_LIVE.DL.'assets'.DL.'emotions');
define('JOOBB_ICONS_LIVE', JOOBB_BASEPATH_LIVE.DL.'assets'.DL.'icons');

define('JOOBB_BASEPATH', JPATH_SITE.DS.'components'.DS.'com_joobb');
define('JOOBB_TEMPLATES', JOOBB_BASEPATH.DS.'assets'.DS.'templates');
define('JOOBB_EMOTIONS', JOOBB_BASEPATH.DS.'assets'.DS.'emotions');
define('JOOBB_ICONS', JOOBB_BASEPATH.DS.'assets'.DS.'icons');

define('JOOBB_MEDIA', JPATH_SITE.DS.'media'.DS.'joobb');

JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joobb'.DS.'tables');

JLoader::register('JoobbHelper', JOOBB_BASEPATH.DS.'helpers'.DS.'joobb.php');
JLoader::register('JoobbAttachment', JOOBB_BASEPATH.DS.'system'.DS.'joobbattachment.php');
JLoader::register('JoobbAuth', JOOBB_BASEPATH.DS.'system'.DS.'joobbauth.php');
JLoader::register('JoobbBreadCrumbs', JOOBB_BASEPATH.DS.'system'.DS.'joobbbreadcrumbs.php');
JLoader::register('JoobbButtonSet', JOOBB_BASEPATH.DS.'system'.DS.'joobbbuttonset.php');
JLoader::register('JoobbConfig', JOOBB_BASEPATH.DS.'system'.DS.'joobbconfig.php');
JLoader::register('JoobbEditor', JOOBB_BASEPATH.DS.'system'.DS.'joobbeditor.php');
JLoader::register('JoobbEmotionSet', JOOBB_BASEPATH.DS.'system'.DS.'joobbemotionset.php');
JLoader::register('JoobbEngine', JOOBB_BASEPATH.DS.'system'.DS.'joobbengine.php');
JLoader::register('JoobbFeed', JOOBB_BASEPATH.DS.'system'.DS.'joobbfeed.php');
JLoader::register('JoobbGeSHi', JOOBB_BASEPATH.DS.'system'.DS.'joobbgeshi.php');
JLoader::register('JoobbIconSet', JOOBB_BASEPATH.DS.'system'.DS.'joobbiconset.php');
JLoader::register('JoobbImage', JOOBB_BASEPATH.DS.'system'.DS.'joobbimage.php');
JLoader::register('JoobbMail', JOOBB_BASEPATH.DS.'system'.DS.'joobbmail.php');
JLoader::register('JoobbMessageQueue', JOOBB_BASEPATH.DS.'system'.DS.'joobbmessagequeue.php');
JLoader::register('JoobbModel', JOOBB_BASEPATH.DS.'system'.DS.'joobbmodel.php');
JLoader::register('JoobbPost', JOOBB_BASEPATH.DS.'system'.DS.'joobbpost.php');
JLoader::register('JoobbRank', JOOBB_BASEPATH.DS.'system'.DS.'joobbrank.php');
JLoader::register('JoobbSitemap', JOOBB_BASEPATH.DS.'system'.DS.'joobbsitemap.php');
JLoader::register('JoobbTemplate', JOOBB_BASEPATH.DS.'system'.DS.'joobbtemplate.php');
JLoader::register('JoobbUser', JOOBB_BASEPATH.DS.'system'.DS.'joobbuser.php');

$tasks = array("joobbpreviewtopic" => "joobbPreview", 
			 "joobbpreviewpost" => "joobbPreview",
			 "joobbsavetopic" => "joobbSaveTopic",
			 "joobbsavepost" => "joobbSavePost",
			 "joobbdeletetopic" => "joobbDeleteTopic",
			 "joobbdeletepost" => "joobbDeletePost",
			 "joobbreportpost" => "joobbReportPost",
			 "joobblocktopic" => "joobbLockTopic",
			 "joobbunlocktopic" => "joobbUnlockTopic",
			 "joobbmovetopic" => "joobbMoveTopic",
			 "joobbsubscribetopic" => "joobbSubscribeTopic",
			 "joobbunsubscribetopic" => "joobbUnsubscribeTopic",
			 "joobbsavesettings" => "joobbSaveSettings",
			 "joobbfeed" => "joobbFeed");

?>