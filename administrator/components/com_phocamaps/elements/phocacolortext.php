<?php
/*
 * @package Joomla 1.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('JPATH_BASE') or die();

class JElementPhocaColorText extends JElement
{
	var	$_name 			= 'PhocaColorText';
	var $_phocaParams 	= null;

	function fetchElement($name, $value, &$node, $control_name)
	{
		$component	= 'com_phocamaps';
		$document	= &JFactory::getDocument();
		$option 	= JRequest::getCmd('option');
		
		$globalValue = &$this->_getPhocaParameter( $name );
		
		// Color Picker
		JHTML::stylesheet( 'picker.css', 'administrator/components/'.$component.'/assets/jcp/' );
		$document->addScript(JURI::base(true).'/components/'.$component.'/assets/jcp/picker.js');
		

		$size = ( $node->attributes('size') ? 'size="'.$node->attributes('size').'"' : '' );
		$class = ( $node->attributes('class') ? 'class="'.$node->attributes('class').'"' : 'class="text_area"' );
        /*
         * Required to avoid a cycle of encoding &
         * html_entity_decode was used in place of htmlspecialchars_decode because
         * htmlspecialchars_decode is not compatible with PHP 4
         */
        $value = htmlspecialchars(html_entity_decode($value, ENT_QUOTES), ENT_QUOTES);

		// MENU - Set default value to "" because of saving "" value into the menu link ( use global = "")
		if ($option == "com_menus") {
			$defaultValue	= $node->attributes('default');
			if ($value == $defaultValue) {
				$value = '';
			}
		}
		
		$html ='<input type="text" name="'.$control_name.'['.$name.']" id="'.$control_name.$name.'" value="'.$value.'" '.$class.' '.$size.' />';		
		
		// Color Picker
		$html .= '<span style="margin-left:10px" onclick="openPicker(\''.$control_name.$name.'\')"  class="picker_buttons">' . JText::_('Pick color') . '</span>';
		
		// MENU - Display the global value
		if ($option == "com_menus") {
			$html .= '<span style="margin-left:10px;">[</span><span style="background:#fff"> ' . $globalValue . ' </span><span>]</span>';
		}
	return $html;
	}
	
	function _setPhocaParams(){
	
		$component 		= 'com_phocamaps';
		$table 			=& JTable::getInstance('component');
		$table->loadByOption( $component );
		$phocaParams 		= new JParameter( $table->params );
		$this->_phocaParams	= $phocaParams;
	}

	function _getPhocaParameter( $name ){
	
		// Don't call sql query by every param item (it will be loaded only one time)
		if (!$this->_phocaParams) {
			$params = &$this->_setPhocaParams();
		}
		$globalValue 	= &$this->_phocaParams->get( $name, '' );	
		return $globalValue;
	}
}
?>