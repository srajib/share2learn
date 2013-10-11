<?php
/**
 * @version $Id: joocm.php 208 2012-02-20 07:04:33Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!CM directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!CM Helper
 *
 * @package Joo!CM
 */
class JoocmHelper
{

	/**
	 * get a joocm user object
	 */
	function &getJoocmUser() {
	
		// if there is a userid in the session, load the application user object with the logged in user.
		$user = &JFactory::getUser();
		
		$userId = 0;
		if (is_object($user)) {
			$userId = (int)$user->get('id');
		}
		
		$instance =& JoocmUser::getInstance($userId);
		return $instance;
	}
	
	/**
	 * check if a joocm user is logged in
	 */	
	function isUserLoggedIn() {
		$joocmUser =& JoocmHelper::getJoocmUser();
	    return ($joocmUser->get('id')) ? true : false;
	}
	
	/**
	 * get a JoobbEditor object
	 *
	 * @access public
	 * @return object JoobbEditor object
	 */
	function &getEditor() {
		jimport('joomla.html.editor');

		// get the editor configuration setting
		$joocmConfig =& JoocmConfig::getInstance();
		$editor = $joocmConfig->getEditor();

		$instance =& JoobbEditor::getInstance($editor);

		return $instance;
	}

	function Date($date) {
		jimport('joomla.utilities.date');
		
		$joocmConfig	=& JoocmConfig::getInstance();
		$joocmUser		=& JoocmHelper::getJoocmUser();
		
		$instance = new JDate($date);
		$timeFormat = '';
		
		$daylightSavingTime = $joocmConfig->getConfigSettings('daylight_saving_time') ? date("I", strtotime($date)) : 0;
		
		if ($joocmUser->get('id')) {
			$instance->setOffset($joocmUser->getParam('timezone') + $daylightSavingTime);
			$timeFormat = $joocmUser->get('time_format');
		} else {
			$instance->setOffset($joocmConfig->getConfigSettings('time_zone') + $daylightSavingTime);
			$timeFormat = $joocmConfig->getConfigSettings('time_format');
		}
		
		return $instance->toFormat($timeFormat);
	}

	function formatDate($date, $timeformat, $timeZoneOffset=0) {
		jimport('joomla.utilities.date');

		$instance = new JDate($date);
		$instance->setOffset($timeZoneOffset);
		
		return $instance->toFormat($timeformat);
	}
						
	function getCurrentTimeZoneName($short = false) {

		// initialize variables
		$joocmConfig	=& JoocmConfig::getInstance();
		$joocmUser		=& JoocmHelper::getJoocmUser();

		if ($joocmUser->get('id')) {
			$timeZoneName = JoocmHelper::getTimeZoneName($joocmUser->getParam('timezone', $joocmConfig->getUserSettingsDefaults('time_zone')), $short);
			
			if (!$timeZoneName) {
				$timeZoneName = JoocmHelper::getTimeZoneName($joocmConfig->getConfigSettings('time_zone'), $short);
			}
		} else {
			$timeZoneName = JoocmHelper::getTimeZoneName($joocmConfig->getConfigSettings('time_zone'), $short);
		}

		return $timeZoneName;
	}
		
	function getLocale() {
		static $joocmLocale;

		if (!isset($joocmLocale)) {
			$params = JComponentHelper::getParams('com_languages');
			$joocmLocale = $params->get('site', 'en-GB');
		}

		return $joocmLocale;
	}
		
	function getLink($function, $dynamicParams = '') {

		// initialize variables
		$db	=& JFactory::getDBO();
		
		$query = "SELECT l.*"
				. "\n FROM #__joocm_links AS l"
				. "\n WHERE l.function = '$function'"
				. "\n AND l.published = 1"
				;
		$db->setQuery($query);
		$link = $db->loadObject();
		
		if (!$link) {
			return '';
		}
		
		if ($link->replacement != '') {
			$replacementDim = preg_split('/=/', $link->replacement);
			$dynamicParams = str_replace($replacementDim[0], $replacementDim[1], $dynamicParams);
		}
		
		$Itemid = JoocmHelper::getMenuId($link->com);
		
		return JRoute::_('index.php?option='.$link->com.$link->url.$dynamicParams.'&Itemid='.$Itemid, true);
	}
		
	function getMenuId($option) {

		// initialize variables
		$db	=& JFactory::getDBO();
		
		$query = "SELECT m.id"
				. "\n FROM #__menu AS m"
				. "\n WHERE m.link LIKE '%$option%'"
				. "\n AND m.published = 1"
				;
		$db->setQuery($query);
		$menuId = $db->loadResult();
		
		if (!isset($menuId) || $menuId == 0) {
			$menuId = JRequest::getVar('Itemid', 0);
		}
		
		return $menuId;
	}
			
	function getItemId($option = '') {
		
		$Itemid = JRequest::getVar('Itemid', 0);
		
		if ($Itemid == 0) {
			$menus =& JSite::getMenu();
			
			if (isset($menus)) {
				$menu = $menus->getActive();
				
				if (isset($menu)) {
					$Itemid = $menu->id;
				}
			}
			
			if ($option != '' && $Itemid == 0) {
				$Itemid = JoocmHelper::getMenuId($option);
			}
		}
		
		return $Itemid;
	}
		
	/**
	 * get version
	 *
	 * @access public
	 * @return string version
	 */
	function getVersion($istallFile = '') {
		$version = JText::_('COM_JOOCM_UNKNOWNVERSION');

		if (file_exists($istallFile)) {
			$xmlParser =& JFactory::getXMLParser('Simple');
			$xmlParser->loadFile($istallFile);
	
			$xmlDocument =& $xmlParser->document;
			
			if ($xmlDocument) {
				$version = $xmlDocument->getElementByPath('version')->toString();
			}
		}
		
		return $version;
	}
		
	/**
	 * get available version
	 *
	 * @access public
	 * @return string version
	 */
	function getAvailableVersion($host = '', $versionFile = '', $timeOut = 5) {

		if(@fsockopen($host, "80", $errno, $errstr, $timeOut)) {
			jimport('joomla.filesystem.file');
			$version = JFile::read($versionFile);
			if (!$version) {
				$version = JText::_('COM_JOOCM_UNKNOWNVERSION');
			}
		} else {
			$version = JText::_('COM_JOOCM_VERSIONHOSTNOTAVAILABLE');
		}

		return $version;
	}
	
	/**
	 * set redirect
	 */
	function setRedirect() {
		$uri = JURI::getInstance();
		$joocmRedirect = $uri->toString();
		
		if (isset($joocmRedirect)) {
			$session =& JFactory::getSession();
			$session->set('joocmRedirect', $joocmRedirect);
		}
	}	
	
	/**
	 * get redirect
	 *
	 * @return string version
	 */
	function getRedirect() {
		$session =& JFactory::getSession();
		$joocmRedirect = $session->get('joocmRedirect');
		
		if (isset($joocmRedirect)) {
			$redirect = $joocmRedirect;
		} else {
			$redirect = JoocmHelper::getLink('main');
		}
		return $redirect;
	}

	function getProfileURL($userId) {

		// initialize variables		
		$joocmConfig	=& JoocmConfig::getInstance();
		$joocmUserView	=& JoocmUser::getInstance($userId);
		
		$userName = ($joocmConfig->getConfigSettings('show_user_as') == 0) ? $joocmUserView->get('name') : $joocmUserView->get('username');

		$profileURL = $joocmUserView->get('id') .'-'. preg_replace("{[\ ]+}", "_", preg_replace("{[^A-Za-z0-9-\ ]}U", "", $userName));

		return $profileURL;
	}
		
	function createElement($field, $fieldvalue = '0') {
	
		$inputClass = 'class="inputbox'. ($field->required ? ' required"' : '"');
		$disabled = $field->disabled ? '" disabled"' : '';
				
		switch ($field->element) {
			case '0':		// TextBox			
				$field->element = '<input type="text" name="'.$field->name.'" id="'.$field->name.'" '.$inputClass.' size="'.$field->length.'" value="'.$fieldvalue.'" maxlength="'.$field->size.'"'.$disabled.' />';
				break;
			case '1':		// TextArea		
				$field->element = '<textarea name="'.$field->name.'" rows="'.$field->rows.'" cols="'.$field->columns.'" id="'.$field->name.'" class="inputbox" '.$disabled.'>'.$fieldvalue.'</textarea>';
				break;				
			case '2':		// CheckBox
				// not implemented yet
				break;
			case '3':		// RadioButton
				$field->element = JHTML::_('select.radiolist', JoocmHelper::_getProfileFieldListOptions($field->id_profile_field_list), $field->name, 'class="inputbox"'.$disabled, 'value', 'name', $fieldvalue);			
				break;
			case '4':		// ListBox
				break;
			case '5':		// ComboBox	
				$field->element = JHTML::_('select.genericlist', JoocmHelper::_getProfileFieldListOptions($field->id_profile_field_list), $field->name, 'class="inputbox" size="1"'.$disabled, 'value', 'name', $fieldvalue);						
				break;															
			default:
				;
				break;
		}			
		return $field;
	}	
	
	function _getProfileFieldListOptions($idProfileFieldList) {
		
		// initialize variables
		$db		=& JFactory::getDBO();
			
		// list profile field list values		
		$query = "SELECT v.*"
				. "\n FROM #__joocm_profiles_fields_lists_values AS v"
				. "\n WHERE v.id_profile_field_list = $idProfileFieldList"
				. "\n AND v.published = 1"
				. "\n ORDER BY v.ordering"
				;
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	function setDefaultConfig($id_config) {
	
		// initialize variables
		$db		=& JFactory::getDBO();
		
		// set selected config to true
		$query = "UPDATE #__joocm_configs"
				. "\n SET default_config = 1"
				. "\n WHERE id = ". $id_config
				;
		$db->setQuery($query);
		
		if ($db->query()) {
				
			// set all other configs to false
			$query = "UPDATE #__joocm_configs"
					. "\n SET default_config = 0"
					. "\n WHERE id <> ". $id_config
					;
			$db->setQuery($query);		

			if (!$db->query()) {
				JError::raiseError(1001, $db->getErrorMsg());
			}						
		} else {
			JError::raiseError(1001, $db->getErrorMsg());
		}				
	}

	function changeField(&$field, $id) {

		// initialize variables
		$db		=& JFactory::getDBO();
		
		switch ($field->element) {
			case '0':		// TextBox
				$fieldtype = ($field->type == 'varchar' || $field->type == 'integer') ?  $field->type.'('.$field->size.')' : $field->type;			
				break;
			case '1':		// TextArea
				$field->type = 'text';
				$fieldtype = $field->type;		
				break;
			case '2':		// CheckBox
			case '3':		// RadioButton
			case '4':		// ListBox
			case '5':		// ComboBox												
			default:
				$field->type = 'integer';
				$field->size = 3;
				$fieldtype = $field->type.'('.$field->size.')';
				break;
		}			
		
		$old =& JTable::getInstance('JoocmProfileField');
		$old->load($id);
								
		// check in the field
		$query = "ALTER TABLE #__joocm_profiles"
				. "\n CHANGE $old->name $field->name $fieldtype"
				;
		$db->setQuery($query);

		if (!$db->query()) {
			$query = "ALTER TABLE #__joocm_profiles"
					. "\n ADD $field->name $fieldtype"
					;	
			$db->setQuery($query);		
			if (!$db->query()) {
				$app =& JFactory::getApplication();
				$app->redirect('index.php?option=com_joocm&task=joocm_profilefield_edit&cid[]='. $id .'&hidemainmenu=1', $db->getErrorMsg()); return false;
			}
		}
	}

	function dropField(&$field) {

		// initialize variables
		$db		=& JFactory::getDBO();
								
		// check out the field
		$query = "ALTER TABLE #__joocm_profiles"
				. "\n DROP $field->name"
				;	
		$db->setQuery($query);		
		if (!$db->query()) {
			$app =& JFactory::getApplication();
			$app->redirect('index.php?option=com_joocm&task=joocm_profilefield_edit&cid[]='. $id .'&hidemainmenu=1', $db->getErrorMsg()); return false;
		}
	}
					
	function parseXMLFile($folderName, $fileName, $type) {

		$xml = JApplicationHelper::parseXMLInstallFile($folderName.DS.$fileName);

		if ($xml['type'] != $type) {
			return false;
		}

		$data = new StdClass();
		
		foreach($xml as $key => $value) {
			$data->$key = $value;
		}
		
		$data->directory = $folderName;
		$data->checked_out = 0;
		$data->file_name = $fileName;

		return $data;	
	}
	
	function getTimeZoneName($offset = 0, $short = false) {
		if ($short) {
			$timeZones = JoocmHelper::getTimeZonesShort();
		} else {
			$timeZones = JoocmHelper::getTimeZones();
		}
		return $timeZones[$offset];
	}
	
	function getTimeZoneOptions() {
		$timeZonesOptions = array();
		$timeZones = JoocmHelper::getTimeZones();
			
		foreach ($timeZones as $k=>$v) {
			$timeZonesOptions[] = JHTML::_('select.option', $k, JText::_($v), 'offset', 'name');
		}
		return $timeZonesOptions;
	}	

	// same as joomla time zone list. I didn't found an interface in
	// joomla framework for getting the time zone list. 	
	function getTimeZones() {
		static $timeZones;
		
		if (empty($timeZones)) {

			$timeZones = array (
				'-12' => JText::_('(UTC -12:00) International Date Line West'),
				'-11' => JText::_('(UTC -11:00) Midway Island, Samoa'),
				'-10' => JText::_('(UTC -10:00) Hawaii'),
				'-9.5' => JText::_('(UTC -09:30) Taiohae, Marquesas Islands'),
				'-9' => JText::_('(UTC -09:00) Alaska'),
				'-8' => JText::_('(UTC -08:00) Pacific Time (US &amp; Canada)'),
				'-7' => JText::_('(UTC -07:00) Mountain Time (US &amp; Canada)'),
				'-6' => JText::_('(UTC -06:00) Central Time (US &amp; Canada), Mexico City'),
				'-5' => JText::_('(UTC -05:00) Eastern Time (US &amp; Canada), Bogota, Lima'),
				'-4' => JText::_('(UTC -04:00) Atlantic Time (Canada), Caracas, La Paz'),
				'-4.5' => JText::_('(UTC -04:30) Venezuela'),
				'-3.5' => JText::_('(UTC -03:30) St. John\'s, Newfoundland, Labrador'),
				'-3' => JText::_('(UTC -03:00) Brazil, Buenos Aires, Georgetown'),
				'-2' => JText::_('(UTC -02:00) Mid-Atlantic'),
				'-1' => JText::_('(UTC -01:00) Azores, Cape Verde Islands'),
				'0' => JText::_('(UTC 00:00) Western Europe Time, London, Lisbon, Casablanca'),
				'1' => JText::_('(UTC +01:00) Amsterdam, Berlin, Brussels, Copenhagen, Madrid, Paris'),
				'2' => JText::_('(UTC +02:00) Istanbul, Jerusalem, Kaliningrad, South Africa'),
				'3' => JText::_('(UTC +03:00) Baghdad, Riyadh, Moscow, St. Petersburg'),
				'3.5' => JText::_('(UTC +03:30) Tehran'),
				'4' => JText::_('(UTC +04:00) Abu Dhabi, Muscat, Baku, Tbilisi'),
				'4.5' => JText::_('(UTC +04:30) Kabul'),
				'5' => JText::_('(UTC +05:00) Ekaterinburg, Islamabad, Karachi, Tashkent'),
				'5.5' => JText::_('(UTC +05:30) Bombay, Calcutta, Madras, New Delhi'),
				'5.75' => JText::_('(UTC +05:45) Kathmandu'),
				'6' => JText::_('(UTC +06:00) Almaty, Dhaka, Colombo'),
				'6.3' => JText::_('(UTC +06:30) Yagoon'),
				'7' => JText::_('(UTC +07:00) Bangkok, Hanoi, Jakarta'),
				'8' => JText::_('(UTC +08:00) Beijing, Perth, Singapore, Hong Kong'),
				'8.75' => JText::_('(UTC +08:00) Western Australia'),
				'9' => JText::_('(UTC +09:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk'),
				'9.5' => JText::_('(UTC +09:30) Adelaide, Darwin, Yakutsk'),
				'10' => JText::_('(UTC +10:00) Eastern Australia, Guam, Vladivostok'),
				'10.5' => JText::_('(UTC +10:30) Lord Howe Island (Australia)'),
				'11' => JText::_('(UTC +11:00) Magadan, Solomon Islands, New Caledonia'),
				'11.3' => JText::_('(UTC +11:30) Norfolk Island'),
				'12' => JText::_('(UTC +12:00) Auckland, Wellington, Fiji, Kamchatka'),
				'12.75' => JText::_('(UTC +12:45) Chatham Island'),
				'13' => JText::_('(UTC +13:00) Tonga'),
				'14' => JText::_('(UTC +14:00) Kiribati'));
		}

		return $timeZones;
	}
	

	function getTimeZonesShort() {
		static $timeZonesShort;
		
		if (empty($timeZonesShort)) {

			$timeZonesShort = array (
				'-12' => JText::_('UTC -12:00'),
				'-11' => JText::_('UTC -11:00'),
				'-10' => JText::_('UTC -10:00'),
				'-9.5' => JText::_('UTC -09:30'),
				'-9' => JText::_('UTC -09:00'),
				'-8' => JText::_('UTC -08:00'),
				'-7' => JText::_('UTC -07:00'),
				'-6' => JText::_('UTC -06:00'),
				'-5' => JText::_('UTC -05:00'),
				'-4' => JText::_('UTC -04:00'),
				'-4.5' => JText::_('UTC -04:30'),
				'-3.5' => JText::_('UTC -03:30'),
				'-3' => JText::_('UTC -03:00'),
				'-2' => JText::_('UTC -02:00'),
				'-1' => JText::_('UTC -01:00'),
				'0' => JText::_('UTC 00:00'),
				'1' => JText::_('UTC +01:00'),
				'2' => JText::_('UTC +02:00'),
				'3' => JText::_('UTC +03:00'),
				'3.5' => JText::_('UTC +03:30'),
				'4' => JText::_('UTC +04:00'),
				'4.5' => JText::_('UTC +04:30'),
				'5' => JText::_('UTC +05:00'),
				'5.5' => JText::_('UTC +05:30'),
				'5.75' => JText::_('UTC +05:45'),
				'6' => JText::_('UTC +06:00'),
				'6.3' => JText::_('UTC +06:30'),
				'7' => JText::_('UTC +07:00'),
				'8' => JText::_('UTC +08:00'),
				'8.75' => JText::_('UTC +08:00'),
				'9' => JText::_('UTC +09:00'),
				'9.5' => JText::_('UTC +09:30'),
				'10' => JText::_('UTC +10:00'),
				'10.5' => JText::_('UTC +10:30'),
				'11' => JText::_('UTC +11:00'),
				'11.3' => JText::_('TC +11:30'),
				'12' => JText::_('UTC +12:00'),
				'12.75' => JText::_('UTC +12:45'),
				'13' => JText::_('UTC +13:00'),
				'14' => JText::_('UTC +14:00'));
		}

		return $timeZonesShort;
	}		
}
?>