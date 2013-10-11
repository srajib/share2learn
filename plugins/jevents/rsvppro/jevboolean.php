<?php

/**
 * JEvents Component for Joomla 1.5.x
 *
 * @version     $Id: jevboolean.php 1569 2009-09-16 06:22:03Z geraint $
 * @package     JEvents
 * @copyright   Copyright (C) 2008-2009 GWE Systems Ltd
 * @license     GNU/GPLv2, see http://www.gnu.org/licenses/gpl-2.0.html
 * @link        http://www.jevents.net
 */
// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

if (!JVersion::isCompatible("1.6.0"))
{

	class JElementJevboolean extends JElement
	{

		/**
		 * Element name
		 *
		 * @access	protected
		 * @var		string
		 */
		var $_name = 'Jevboolean';

		function fetchElement($name, $value, &$node, $control_name)
		{

			// Must load admin language files
			$lang = & JFactory::getLanguage();
			$lang->load("com_jevents", JPATH_ADMINISTRATOR);

			$options = array();
			$options[] = JHTML::_('select.option', 0, JText::_( 'JEV_NO' ));
			$options[] = JHTML::_('select.option', 1, JText::_( 'JEV_YES' ));

			return JHTML::_('select.radiolist', $options, '' . $control_name . '[' . $name . ']', '', 'value', 'text', $value, $control_name . $name);

		}

	}

}
else if (JVersion::isCompatible("1.6.0"))
{
	jimport('joomla.html.html');
	jimport('joomla.form.formfield');
	jimport('joomla.form.helper');
	JFormHelper::loadFieldClass('radio');

	/**
	 * JEVMenu Field class for the JEvents Component
	 *
	 * @package		JEvents.fields
	 * @subpackage	com_banners
	 * @since		1.6
	 */
	class JFormFieldJEVBoolean extends JFormFieldRadio
	{

		/**
		 * The form field type.s
		 *
		 * @var		string
		 * @since	1.6
		 */
		protected $type = 'JEVBoolean';

		/**
		 * Method to get the field options.
		 *
		 * @return	array	The field option objects.
		 * @since	1.6
		 */
		public function getOptions()
		{
			// Initialize variables.
			$options = array();

			$file = JPATH_ADMINISTRATOR . '/components/com_jevents/elements/jevboolean.php';
			if (file_exists($file))
			{
				include_once($file);
			}
			else
			{
				die("JEvents Locations Fields\n<br />This module needs the JEvents Locations component");
			}

			return JElementJevboolean::fetchElement($this->name, $this->value, $this->element, $this->type, true);  // RSH 10/5/10 - Use the original code for J!1.6

		}

	}

}