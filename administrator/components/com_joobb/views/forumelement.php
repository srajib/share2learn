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

/**
 * Joo!BB Forum Element View
 *
 * @package Joo!BB
 */
class ViewForumElement {

	/**
	 * show forums
	 */
	function showForums(&$rows, $pageNav, &$lists) { ?>
		<form action="index.php?option=com_joobb" method="post" name="adminForm">
			<table class="adminlist" cellspacing="1">
			<thead><tr>
				<th nowrap="nowrap" width="5">
					<?php echo JText::_('Num'); ?>
				</th>
				<th nowrap="nowrap" width="45%">
					<?php echo JHTML::_('grid.sort', 'COM_JOOBB_FORUM', 'f.name', @$lists['filter_order_Dir'], @$lists['filter_order'], 'joobb_forumelement_view'); ?>
				</th>
				<th nowrap="nowrap" width="40%">
					<?php echo JHTML::_('grid.sort', 'COM_JOOBB_CATEGORY', 'c.name', @$lists['filter_order_Dir'], @$lists['filter_order'], 'joobb_forumelement_view'); ?>
				</th>
				<th nowrap="nowrap" width="1%">
					<?php echo JHTML::_('grid.sort', 'COM_JOOBB_STATUS', 'f.status', @$lists['filter_order_Dir'], @$lists['filter_order'], 'joobb_forumelement_view'); ?>
				</th>										
				<th nowrap="nowrap" width="1%">
					<?php echo JHTML::_('grid.sort', 'COM_JOOBB_POSTS', 'f.posts', @$lists['filter_order_Dir'], @$lists['filter_order'], 'joobb_forumelement_view'); ?>
				</th>
				<th nowrap="nowrap" width="1%">
					<?php echo JHTML::_('grid.sort', 'COM_JOOBB_TOPICS', 'f.topics', @$lists['filter_order_Dir'], @$lists['filter_order'], 'joobb_forumelement_view'); ?>
				</th>																														
				<th nowrap="nowrap" width="1%">
					<?php echo JHTML::_('grid.sort', 'COM_JOOBB_ID', 'f.id', @$lists['filter_order_Dir'], @$lists['filter_order'], 'joobb_forumelement_view'); ?>
				</th>			
			</tr></thead>
			<tfoot><tr>
				<td colspan="22"><?php echo $pageNav->getListFooter(); ?></td>
			</tr></tfoot>			
			<tbody><?php
			$k = 0;
			for ($i=0, $n=count( $rows ); $i < $n; $i++) {
				$row 	=& $rows[$i];
				
				$img_published = $row->status ? 'tick.png' : 'publish_x.png';
				$alt_published = $row->status ? JText::_('COM_JOOBB_PUBLISHED') :  JText::_('COM_JOOBB_UNPUBLISHED');				

				$checked = JHTML::_('grid.checkedout', $row, $i);	?>
				<tr class="<?php echo "row$k"; ?>">
					<td><?php echo $pageNav->getRowOffset($i); ?></td>
					<td>
						<a style="cursor: pointer;" onclick="window.parent.jSelectForum('<?php echo $row->id; ?>', '<?php echo str_replace(array("'", "\""), array("\\'", ""),$row->name); ?>', '<?php echo JRequest::getVar('object'); ?>');">
							<?php echo htmlspecialchars($row->name, ENT_QUOTES, 'UTF-8'); ?>
						</a>
					</td>
					<td><?php echo $row->category; ?></td>
					<td align="center">
						<img src="images/<?php echo $img_published;?>" width="16" height="16" border="0" alt="<?php echo $alt_published; ?>" />
					</td>										
					<td align="center"><?php echo isset($row->posts) ? $row->posts : '-'; ?></td>
					<td align="center">
						<?php echo isset($row->topics) ? $row->topics : '-'; ?>
					</td>																																																	
					<td><?php echo $row->id; ?></td>
				</tr><?php
				$k = 1 - $k;
			} ?>
			</tbody>
			</table>
			<input type="hidden" name="option" value="com_joobb" />
			<input type="hidden" name="task" value="joobb_forumelement_view" />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="filter_order" value="<?php echo $lists['filter_order']; ?>" />
			<input type="hidden" name="filter_order_Dir" value="" />
			<input type="hidden" name="object" value="<?php echo JRequest::getVar('object'); ?>" />
			<input type="hidden" name="tmpl" value="component" />
		</form><?php
	}
} ?>