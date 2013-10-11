<?php
/**
 * @version $Id: joobbmessagequeue.php 135 2010-08-13 10:03:14Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!BB Message Queue
 *
 * @package Joo!BB
 */
class JoobbMessageQueue
{
	/**
	 * messege data
	 *
	 * @var array
	 */
	var $_messages = array();

	function JoobbMessageQueue() {	 
	}
	
	/**
	 * get instance
	 *
	 * @access	public
	 * @return	object
	 */
	function &getInstance() {
	
		static $joobbMessageQueue;

		if (!is_object($joobbMessageQueue)) {
			$joobbMessageQueue = new JoobbMessageQueue();
		}

		return $joobbMessageQueue;
	}
		
	function addMessage($msg, $type='info') {
		$message->message = $msg;
		$message->type = $type;
		$this->_messages[] = $message;
		
		$session =& JFactory::getSession();
		$session->set('joobbMessage', $this->_messages);
	}
			
	function getMessages() {
		$session =& JFactory::getSession();
		$this->_messages = $session->get('joobbMessage');
		$session->set('joobbMessage', null);	
		return $this->_messages;
	}
}
?>