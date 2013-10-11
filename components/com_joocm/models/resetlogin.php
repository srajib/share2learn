<?php
/**
 * @version $Id: resetlogin.php 22 2009-12-25 20:07:22Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2009 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!CM directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!CM Reset Login Model
 *
 * @package Joo!CM
 */
class JoocmModelResetLogin extends JoocmModel
{
	
	/**
	 * get joocm user object
	 *
	 * @access public
	 * @return object
	 */
	function getJoocmUser() {
		
		$db   =& JFactory::getDBO();
		$activation	= JRequest::getVar('activation', '');
		$joocmUser = null;

		$query = "SELECT u.id"
				. "\n FROM #__users AS u"
				. "\n WHERE u.activation = '$activation'"
				;
		$db->setQuery($query);
		$userId = $db->loadResult();
		
		if ($userId) {
			$joocmUser =& JoocmUser::getInstance($userId);
		}
			
		return $joocmUser;
	}
}
?>