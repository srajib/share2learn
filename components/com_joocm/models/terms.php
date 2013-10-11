<?php
/**
 * @version $Id: terms.php 22 2009-12-25 20:07:22Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2009 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!CM  directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!CM Terms Model
 *
 * @package Joo!CM
 */
class JoocmModelTerms extends JoocmModel
{
	/**
	 * terms table object
	 *
	 * @var object
	 */
	var $_terms = null;

	/**
	 * get terms
	 *
	 * @return object
	 */
	function getTerms() {

		// load the forum
		if (empty($this->_terms)) {
			$db		=& JFactory::getDBO();
			$locale = JoocmHelper::getLocale();
			
			do {
				$query = "SELECT t.id"
						. "\n FROM #__joocm_terms AS t"
						. "\n WHERE t.locale = ".$db->Quote($locale)
						. "\n AND t.published = 1"
						;
				$db->setQuery($query);
				$termsId = $db->loadResult();

				if ($termsId) {
					$tryAgain = 0;
				} else {
					if ($locale == 'en-GB') {
						$tryAgain = 0;
					} else {
						$locale = 'en-GB'; 
						$tryAgain = 1;					
					}
				}
			} while ($tryAgain);
			
			$this->_terms =& JTable::getInstance('JoocmTerms');
			$this->_terms->load($termsId);
		}

		return $this->_terms;
	}
}
?>