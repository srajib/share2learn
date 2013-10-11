<?php
/**
 * @version $Id: joobbrank.php 135 2010-08-13 10:03:14Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!BB Rank
 *
 * @package Joo!BB
 */
class JoobbRank
{
	
	/**
	 * ranks data array
	 *
	 * @var array
	 */
	var $ranks = null;
	
	/**
	 * total ranks
	 *
	 * @var integer
	 */
	var $total = null;
	
	/**
	 * get joobb rank object
	 *
	 * @access public
	 * @return object of JoobbRank
	 */		
	function JoobbRank() {
		$this->loadRanks();
	}
	
	/**
	 * get joobb rank object
	 *
	 * @access public
	 * @return object of JoobbRank
	 */
	function &getInstance() {
	
		static $joobbRank;

		if (!is_object($joobbRank)) {
			$joobbRank = new JoobbRank();
		}

		return $joobbRank;
	}
		
	/**
	 * load ranks
	 * 
	 * @access public
	 */
	function loadRanks() {
	
		// load ranks
		if (empty($this->ranks)) {
			$db		=& JFactory::getDBO();
				
			$query = "SELECT r.*"
					. "\n FROM #__joobb_ranks AS r"
					. "\n ORDER BY r.min_posts DESC"
					;
			$db->setQuery($query);
			
			$this->ranks = $db->loadObjectList();
			$this->total = count($this->ranks);
		}
	}
	
	/**
	 * get rank
	 * 
	 * @access public
	 * @return object
	 */
	function getRank($totalUserPosts) {

		$rank = null;
		for ($i=0; $i < $this->total; $i++) {
			if ($this->ranks[$i]->min_posts <= $totalUserPosts) {
				$rank = $this->ranks[$i];
				break;
			}
		}
			
		return $rank;
	}
}
?>