<?php
/**
 * @version $Id: config.php 210 2012-02-20 20:37:58Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT.DS.'views'.DS.'config.php');

/**
 * Joo!BB Config Controller
 *
 * @package Joo!BB
 */
class ControllerConfig extends JController
{

	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
		
		// register controller tasks
		$this->registerTask('joobb_config_view', 'showConfig');
		$this->registerTask('joobb_config_save', 'saveConfig');
		$this->registerTask('joobb_config_apply', 'saveConfig');
		$this->registerTask('joobb_config_cancel', 'cancelEditConfig');
	}
	
	/**
	 * shows the configuration in editing mode
	 */
	function showConfig() {

		// initialize variables
		$db				=& JFactory::getDBO();
		$user			=& JFactory::getUser();
		$joobbConfig	=& JoobbConfig::getInstance();

		$row =& JTable::getInstance('JoobbConfig');
		$row->load(1);

		// is someone else editing this configuration?
		if (JTable::isCheckedOut($user->get('id'), $row->checked_out)) {
			$editingUser =& JFactory::getUser($row->checked_out);
			$this->setRedirect('index.php?option=com_joobb&task=joobb_config_view', JText::sprintf('COM_JOOBB_MSGBEINGEDITTED', JText::_('COM_JOOBB_CONFIG'), $row->name, $editingUser->name), 'notice'); return;
		}
		
		// check out configuration so nobody else can edit it
		$row->checkout($user->get('id'));
		
		// parameter list
		$lists = array();

		// list emotion sets
		$emotionSetsList = JFolder::folders(JOOBB_EMOTIONS);
				
		$emotionSets = array();
		foreach ($emotionSetsList as $emotionSetFolder) {
			$fileList = JFolder::files(JOOBB_EMOTIONS.DS.$emotionSetFolder, '.xml');
			foreach ($fileList as $emotionSetFile) {
				if(!$data = JoobbHelper::parseXMLFile(JOOBB_EMOTIONS.DS.$emotionSetFolder, $emotionSetFile, 'emotionset')) {
					continue;
				} else {
					$emotionSets[] = JHTML::_('select.option', $data->file_name, $data->name);
				}
			}
		}
		$lists['emotionsets'] = JHTML::_('select.genericlist',  $emotionSets, 'emotion_set', 'class="inputbox" size="1"', 'value', 'text', $row->emotion_set);		
		
		// list icon sets
		$iconSetsList = JFolder::folders(JOOBB_ICONS);
				
		$iconSets = array();
		foreach ($iconSetsList as $iconSetFolder) {
			$fileList = JFolder::files(JOOBB_ICONS.DS.$iconSetFolder, '.xml');
			foreach ($fileList as $iconSetFile) {
				if(!$data = JoobbHelper::parseXMLFile(JOOBB_ICONS.DS.$iconSetFolder, $iconSetFile, 'iconset')) {
					continue;
				} else {
					$iconSets[] = JHTML::_('select.option', $data->file_name, $data->name);
				}
			}
		}
		$lists['iconsets'] = JHTML::_('select.genericlist',  $iconSets, 'icon_set', 'class="inputbox" size="1"', 'value', 'text', $row->icon_set);				
	
		// list BB editors
		$query = "SELECT element, name"
				. "\n FROM #__plugins"
				. "\n WHERE folder = 'editors-bb'"
				. "\n AND published = 1"
				. "\n ORDER BY ordering, name"
				;
		$db->setQuery( $query );
		$lists['editors'] = JHTML::_('select.genericlist',  $db->loadObjectList(), 'editor', 'class="inputbox" size="1"', 'element', 'name', $row->editor);

		// list icons
		$joobbIconSet = new JoobbIconSet($joobbConfig->getIconSetFile());
		$postIconRows = $joobbIconSet->getIconsByGroup('iconPost');
		$lists['topicicons'] = JHTML::_('select.genericlist',  $postIconRows, 'topic_icon_function', 'class="inputbox" size="1"', 'function', 'title', $row->topic_icon_function);
		$lists['posticons'] = JHTML::_('select.genericlist',  $postIconRows, 'post_icon_function', 'class="inputbox" size="1"', 'function', 'title', $row->post_icon_function);

		// board settings params definitions
		$file = JOOBB_ADMINPARAMS.DS.'config_board_settings.xml';
		$lists['board_settings'] = new JParameter($row->board_settings, $file);

		// latest post settings params definitions
		$file = JOOBB_ADMINPARAMS.DS.'config_latestpost_settings.xml';
		$lists['latestpost_settings'] = new JParameter($row->latestpost_settings, $file);
		
		// feed settings params definitions
		$file = JOOBB_ADMINPARAMS.DS.'config_feed_settings.xml';
		$lists['feed_settings'] = new JParameter($row->feed_settings, $file);
		
		// attachment settings params definitions
		$file = JOOBB_ADMINPARAMS.DS.'config_attachment_settings.xml';
		$lists['attachment_settings'] = new JParameter($row->attachment_settings, $file);
		
		// images settings params definitions
		$file = JOOBB_ADMINPARAMS.DS.'config_image_settings.xml';
		$lists['image_settings'] = new JParameter($row->image_settings, $file);
				
		// view settings params definitions
		$file = JOOBB_ADMINPARAMS.DS.'config_view_settings.xml';
		$lists['view_settings'] = new JParameter($row->view_settings, $file);

		// user setting default params definitions
		$file = JOOBB_ADMINPARAMS.DS.'config_user_settings_defaults.xml';
		$lists['user_settings_defaults'] = new JParameter($row->user_settings_defaults, $file);
		
		// captcha settings params definitions
		$file = JOOBB_ADMINPARAMS.DS.'config_captcha_settings.xml';
		$lists['captcha_settings'] = new JParameter($row->captcha_settings, $file);
		
		// parse settings params definitions
		$file = JOOBB_ADMINPARAMS.DS.'config_parse_settings.xml';
		$lists['parse_settings'] = new JParameter($row->parse_settings, $file);
				
		ViewConfig::showConfig($row, $lists);			
	}
	
	/**
	 * cancels edit configuration operation
	 */
	function cancelEditConfig() {

		// check in configuration so other can edit it
		$row =& JTable::getInstance('JoobbConfig');
		$row->bind(JRequest::get('post'));
		$row->checkin();

		$this->setRedirect('index.php?option=com_joobb&task=joobb_controlpanel');
	}
	
	/**
	 * save the configuration
	 */	
	function saveConfig() {
		global $mainframe;

		// initialize variables
		$db 	=& JFactory::getDBO();
		$post	= JRequest::get('post');
		$row 	=& JTable::getInstance('JoobbConfig');

		if (!$row->bind($post)) {
			echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}
	
		// save params
		$board_settings = JRequest::getVar('board_settings', array(), 'post', 'array');
		if (is_array($board_settings)) {
			$txt = array();
			foreach ($board_settings as $k=>$v) {
				if (is_array($v)) {
					$v = implode("|", $v);
				}
				$txt[] = "$k=$v";
			}
			$row->board_settings = implode("\n", $txt);
		}
		
		// save params
		$latestpost_settings = JRequest::getVar('latestpost_settings', array(), 'post', 'array');
		if (is_array($latestpost_settings)) {
			$txt = array();
			foreach ($latestpost_settings as $k=>$v) {
				$txt[] = "$k=$v";
			}
			$row->latestpost_settings = implode("\n", $txt);
		}
		
		// save params
		$feed_settings = JRequest::getVar('feed_settings', array(), 'post', 'array');
		if (is_array($feed_settings)) {
			$txt = array();
			foreach ($feed_settings as $k=>$v) {
				$txt[] = "$k=$v";
			}
			$row->feed_settings = implode("\n", $txt);
		}
		
		// save params
		$view_settings = JRequest::getVar('view_settings', array(), 'post', 'array');
		if (is_array($view_settings)) {
			$txt = array();
			foreach ($view_settings as $k=>$v) {
				$txt[] = "$k=$v";
			}
			$row->view_settings = implode("\n", $txt);
		}

		// save params
		$user_settings_defaults = JRequest::getVar('user_settings_defaults', array(), 'post', 'array');
		if (is_array($user_settings_defaults)) {
			$txt = array();
			foreach ($user_settings_defaults as $k=>$v) {
				$txt[] = "$k=$v";
			}
			$row->user_settings_defaults = implode("\n", $txt);
		}
		
		// save params
		$attachment_settings = JRequest::getVar('attachment_settings', array(), 'post', 'array');
		if (is_array($attachment_settings)) {
			$txt = array();
			foreach ($attachment_settings as $k=>$v) {
				$txt[] = "$k=$v";
			}
			$row->attachment_settings = implode("\n", $txt);
		}
		
		// save params
		$image_settings = JRequest::getVar('image_settings', array(), 'post', 'array');
		if (is_array($image_settings)) {
			$txt = array();
			foreach ($image_settings as $k=>$v) {
				$txt[] = "$k=$v";
			}
			$row->image_settings = implode("\n", $txt);
		}
			
		// save params
		$captcha_settings = JRequest::getVar('captcha_settings', array(), 'post', 'array');
		if (is_array($captcha_settings)) {
			$txt = array();
			foreach ($captcha_settings as $k=>$v) {
				$txt[] = "$k=$v";
			}
			$row->captcha_settings = implode("\n", $txt);
		}
	
		// save params
		$parse_settings = JRequest::getVar('parse_settings', array(), 'post', 'array');
		if (is_array($parse_settings)) {
			$txt = array();
			foreach ($parse_settings as $k=>$v) {
				$txt[] = "$k=$v";
			}
			$row->parse_settings = implode("\n", $txt);
		}
			
		if (!$row->check()) {
			echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}

		if (!$row->store()) {
			echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}
		$row->checkin();

		switch (JRequest::getCmd('task')) {
			case 'joobb_config_apply':
				$link = 'index.php?option=com_joobb&task=joobb_config_view';
				break;
			case 'joobb_config_save':
			default:
				$link = 'index.php?option=com_joobb&task=joobb_controlpanel';
				break;
		}
		
		$msg = JText::sprintf('COM_JOOBB_MSGSUCCESSFULLYSAVED', JText::_('COM_JOOBB_CONFIG'), $row->name);
		$this->setRedirect($link, $msg);
	}
}
?>