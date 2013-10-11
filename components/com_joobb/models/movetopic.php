<?php
/**
 * @version $Id: movetopic.php 22 2009-12-25 20:07:22Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2009 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!BB Move Topic Model
 *
 * @package Joo!BB
 */
class JoobbModelMoveTopic extends JoobbModel
{
	
	/**
	 * forums data array
	 *
	 * @var array
	 */
	var $_forums = null;

	/**
	 * get the forums
	 * 
	 * @access public
	 * @return array
	 */
	function getForums($forumId) {
	
		// load the forums
		if (empty($this->_forums)) {
			$query = "SELECT f.*, c.name AS category_name"
					. "\n FROM #__joobb_forums AS f"
					. "\n INNER JOIN #__joobb_categories AS c ON f.id_cat = c.id"
					. "\n WHERE f.status = 1"
					. "\n AND f.id <> $forumId"
					. "\n ORDER BY f.ordering"
					;
			$this->_forums = $this->_getList($query);
		}
		
		return $this->_forums;
	}
	
}
?>