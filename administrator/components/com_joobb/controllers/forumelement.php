<?php
/**
 * @version $Id$
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT.DS.'views'.DS.'forumelement.php');

/**
 * Joo!BB Forum Element Controller
 *
 * @package Joo!BB
 */
class ControllerForumElement extends JController
{

	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
		
		// register controller tasks
		$this->registerTask('joobb_forumelement_view', 'showForums');
	}
	
	/**
	 * compiles a list of forums
	 */
	function showForums() {

		// initialize variables
		$app		=& JFactory::getApplication();
		$db			=& JFactory::getDBO();

		$context			= 'com_joobb.joobb_forumelement_view';
		$filter_order		= $app->getUserStateFromRequest("$context.filter_order", 'filter_order', 'c.ordering, f.ordering');
		$filter_order_Dir	= $app->getUserStateFromRequest("$context.filter_order_Dir", 'filter_order_Dir', '');
		$limit				= $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart			= $app->getUserStateFromRequest($context.'limitstart', 'limitstart', 0, 'int');
		
		$orderby = "\n ORDER BY $filter_order $filter_order_Dir";
		
		// get the total number of records
		$query = "SELECT COUNT(*)"
				. "\n FROM #__joobb_forums AS f"
				. "\n INNER JOIN #__joobb_categories AS c ON f.id_cat = c.id"
				. $orderby
				;
		$db->setQuery($query);
		$total = $db->loadResult();
	
		// create the pagination object
		jimport('joomla.html.pagination');
		$pagination = new JPagination($total, $limitstart, $limit);		
		
		$query = "SELECT f.*, c.name AS category"
				. "\n FROM #__joobb_forums AS f"
				. "\n INNER JOIN #__joobb_categories AS c ON f.id_cat = c.id"
				. $orderby
				;
		$db->setQuery($query, $pagination->limitstart, $pagination->limit);
		$rows = $db->loadObjectList();
		
		// parameter list
		$lists = array();
		
		// table ordering
		$lists['filter_order'] = $filter_order;
		$lists['filter_order_Dir']	= $filter_order_Dir;
		
		ViewForumElement::showForums($rows, $pagination, $lists);	
	}
}
?>