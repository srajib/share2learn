<?php
/*
# ------------------------------------------------------------------------
# Free Slide SP1 - Slideshow module for Joomla 1.6
# ------------------------------------------------------------------------
# Copyright (C) 2010 JoomShaper.com. All Rights Reserved.
# @license - GNU/GPL, see LICENSE.php,
# Author: JoomShaper.com
# Websites:  http://www.joomshaper.com -  http://www.joomxpert.com
# ------------------------------------------------------------------------
*/

// no direct access
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$document 				= JFactory::getDocument();
$uniqid					= $module->id;
$showtitle				= $params->get('showtitle',1) ? true : false;
$titlelinked			= $params->get('titlelinked',1) ? true : false;
$showarticle			= $params->get('showarticle',1) ? true : false;
$showimage				= $params->get('showimage',1) ? true : false;
$imagelinked			= $params->get('imagelinked',1) ? true : false;
$showmore				= $params->get('showmore',1) ? true : false;
$showarrows				= $params->get('showarrows',1) ? true : false;
$autoplay				= $params->get('autoplay',1) ? true : false;
$titlelimit				= $params->get('titlelimit',20);
$desclimit				= $params->get('desclimit',30);
$moretext				= $params->get('moretext', 'Read More...');
$width					= $params->get('width', 320);
$height					= $params->get('height', 200);
$interval 				= $params->get("interval", 5000);
$speed 					= $params->get('speed', 1000);
$effects				= $params->get('effects','cover-inplace-fade');
$transition 			= $params->get("transition", "Sine.easeOut");

// Include the syndicate functions only once
require_once (dirname(__FILE__).DS.'helper.php');
$list = modFSSP1Helper::getList($params);

//Detect mootools version
$moo=JPluginHelper::isEnabled('system', 'mtupgrade') ? '12' : '11';

//Load Layout Style
$document->addScript(JURI::base() . 'modules/mod_freeslider_sp1/assets/js/script_moo' . $moo . '.js');
$document->addStylesheet(JURI::base() . 'modules/mod_freeslider_sp1/assets/css/style.css');

require(JModuleHelper::getLayoutPath('mod_freeslider_sp1'));
?>