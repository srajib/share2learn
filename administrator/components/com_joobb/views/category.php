<?php
/**
 * @version $Id: category.php 204 2011-11-13 18:27:41Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!BB Category View
 *
 * @package Joo!BB
 */
class ViewCategory {

	/**
	 * shows categories
	 */	
	function showCategories(&$rows, $pageNav, &$lists) {

		// initialize variables
		$user	=& JFactory::getUser();
		$task	= JRequest::getVar('task'); ?>
		<script language="javascript" type="text/javascript">
		function submitbutton(pressbutton) {
			if (pressbutton == 'joobb_category_delete') {
				if (document.adminForm.boxchecked.value > 0) {
					var confirmed = confirm("<?php echo JText::_('COM_JOOBB_MSGWARNINGDELETECATEGORY'); ?>");
					if (!confirmed){
						pressbutton = 'joobb_category_view';
					}
				}
			}
			submitform(pressbutton);
		}
		</script>
		<div id="submenu-box">
			<div class="t"><div class="t"><div class="t"></div></div></div>		
			<div class="m">
				<ul id="submenu"><li>
					<?php $class = ($task == 'joobb_forum_view') ? 'class="active"' : ''; ?>
					<a <?php echo $class; ?> href="index.php?option=com_joobb&task=joobb_forum_view"><?php echo JText::_('COM_JOOBB_FORUMS'); ?></a>
				</li><li>
					<?php $class = ($task == 'joobb_category_view') ? 'class="active"' : ''; ?>
					<a <?php echo $class; ?> href="index.php?option=com_joobb&task=joobb_category_view"><?php echo JText::_('COM_JOOBB_CATEGORIES'); ?></a>
				</li></ul>
				<div class="clr"></div>
			</div>
			<div class="b"><div class="b"><div class="b"></div></div></div>
		</div>
		<form action="index.php?option=com_joobb" method="post" name="adminForm">
			<table class="adminlist" cellspacing="1">
			<thead><tr>
				<th nowrap="nowrap" width="5">
					<?php echo JText::_('Num'); ?>
				</th>
				<th nowrap="nowrap" width="5">
					<input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count($rows); ?>);" />
				</th>
				<th nowrap="nowrap" width="88%">
					<?php echo JHTML::_('grid.sort', 'COM_JOOBB_CATEGORY', 'c.name', @$lists['filter_order_Dir'], @$lists['filter_order'], 'joobb_category_view'); ?>
				</th>
				<th nowrap="nowrap" width="1%">
					<?php echo JHTML::_('grid.sort', 'COM_JOOBB_PUBLISHED', 'c.published', @$lists['filter_order_Dir'], @$lists['filter_order'], 'joobb_category_view'); ?>
				</th>
				<th nowrap="nowrap" width="10%">
					<?php echo JHTML::_('grid.sort', 'COM_JOOBB_ORDER', 'c.ordering', @$lists['filter_order_Dir'], @$lists['filter_order'], 'joobb_category_view'); ?>
					<?php echo JHTML::_('grid.order', $rows, 'filesave.png', 'joobb_category_saveorder' ); ?>
				</th>																							
				<th nowrap="nowrap" width="1%">
					<?php echo JHTML::_('grid.sort', 'COM_JOOBB_ID', 'c.id', @$lists['filter_order_Dir'], @$lists['filter_order'], 'joobb_category_view'); ?>
				</th>
			</tr></thead>
			<tfoot><tr>
				<td colspan="6"><?php echo $pageNav->getListFooter(); ?></td>
			</tr></tfoot>			
			<tbody><?php
			$k = 0;
			for ($i=0, $n=count($rows); $i < $n; $i++) {
				$row 	=& $rows[$i];
				
				$img_published = $row->published ? 'tick.png' : 'publish_x.png';
				$task_published = $row->published ? 'joobb_category_unpublish' : 'joobb_category_publish';
				$alt_published = $row->published ? JText::_('COM_JOOBB_PUBLISHED') : JText::_('COM_JOOBB_UNPUBLISHED');
								
				$link = 'index.php?option=com_joobb&task=joobb_category_edit&hidemainmenu=1&cid[]='. $row->id;
				
				$checked = JHTML::_('grid.checkedout', $row, $i); ?>
				<tr class="<?php echo "row$k"; ?>">
					<td><?php echo $i+1 ?></td>
					<td><?php echo $checked; ?></td>
					<td><?php
					if (JTable::isCheckedOut($user->get('id'), $row->checked_out)) {
						echo $row->name;
					} else { ?>
						<a href="<?php echo JRoute::_($link); ?>">
							<?php echo htmlspecialchars($row->name, ENT_QUOTES); ?>
						</a><?php
					} ?>				
					</td>
					<td align="center">
						<a href="javascript: void(0);" onClick="return listItemTask('cb<?php echo $i;?>','<?php echo $task_published;?>')">
						<img src="images/<?php echo $img_published;?>" width="16" height="16" border="0" alt="<?php echo $alt_published; ?>" />
						</a>
					</td>						
					<td class="order">
						<span><?php echo $pageNav->orderUpIcon($i, true, 'joobb_category_orderup', 'COM_JOOBB_ORDERUP', true); ?></span>
						<span><?php echo $pageNav->orderDownIcon($i, $n, true, 'joobb_category_orderdown', 'COM_JOOBB_ORDERDOWN', true); ?></span>
						<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" class="text_area" style="text-align: center" />
					</td>																																																							
					<td><?php echo $row->id; ?></td>
				</tr><?php
				$k = 1 - $k;
			} ?>
			</tbody>
			</table>
			<input type="hidden" name="option" value="com_joobb" />
			<input type="hidden" name="task" value="joobb_category_view" />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="hidemainmenu" value="0" />
			<input type="hidden" name="filter_order" value="<?php echo $lists['filter_order']; ?>" />
			<input type="hidden" name="filter_order_Dir" value="" />
		</form><?php
	}
	
	/**
	 * edit category
	 */
	function editCategory(&$row, &$lists) { ?>
		<script language="javascript" type="text/javascript">
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'joobb_category_cancel') {
				submitform(pressbutton); return;
			}

			// do field validation
			if (trim(form.name.value) == "") {
				alert("<?php echo JText::sprintf('COM_JOOBB_MSGFIELDREQUIRED', JText::_('COM_JOOBB_NAME'), JText::_('COM_JOOBB_CATEGORY')); ?>");
			} else {
				submitform(pressbutton);
			}
		}
		</script>
		<form action="index.php" method="post" name="adminForm" id="adminForm">
			<div class="col width-50">
				<fieldset class="adminform">
					<legend>
						<?php echo JText::_('COM_JOOBB_CATEGORYDETAILS'); ?>
					</legend>
					<table class="admintable" cellspacing="1"><tr>
						<td class="key">
							<label for="name" class="hasTip" title="<?php echo JText::_('COM_JOOBB_NAME') .'::'. JText::_('COM_JOOBB_CATEGORYNAMEDESC'); ?>">
								<?php echo JText::_('COM_JOOBB_NAME'); ?>
							</label>
						</td><td>
							<input type="text" name="name" id="name" class="inputbox" size="50" value="<?php echo $row->name; ?>" maxlength="255" />
						</td>
					</tr><tr>
						<td class="key">
							<label for="published" class="hasTip" title="<?php echo JText::_('COM_JOOBB_PUBLISHED') .'::'. JText::_('COM_JOOBB_CATEGORYPUBLISHEDDESC'); ?>">
								<?php echo JText::_('COM_JOOBB_PUBLISHED'); ?>
							</label>
						</td>
						<td><?php echo $lists['published']; ?></td>
					</tr></table>
				</fieldset>
			</div>
			<div class="clr"></div>				
			<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
			<input type="hidden" name="option" value="com_joobb" />
			<input type="hidden" name="task" value="" />
		</form><?php
	}
} ?>