<?php
/**
 * @version $Id: interface.php 206 2011-11-14 20:37:29Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!CM  directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!CM Interface View
 *
 * @package Joo!CM
 */
class ViewInterface {

	/**
	 * show interfaces
	 */	
	function showInterfaces(&$rows, $pageNav, &$lists) {

		// initialize variables
		$user	=& JFactory::getUser();
			
		$document =& JFactory::getDocument();
		$document->addStyleSheet(JOOCM_ADMINCSS_LIVE.DL.'icon.css');
		
		$task	= JRequest::getVar('task'); ?>
		<div id="submenu-box">
			<div class="t"><div class="t"><div class="t"></div></div></div>			
			<div class="m">
				<ul id="submenu"><li>
					<?php $class = ($task == 'joocm_interface_view') ? 'class="active"' : ''; ?>
					<a <?php echo $class; ?> href="index.php?option=com_joocm&task=joocm_interface_view"><?php echo JText::_('COM_JOOCM_INTERFACES'); ?></a>
				</li><li>
					<?php $class = ($task == 'joocm_link_view') ? 'class="active"' : ''; ?>
					<a <?php echo $class; ?> href="index.php?option=com_joocm&task=joocm_link_view"><?php echo JText::_('COM_JOOCM_LINKS'); ?></a>
				</li></ul>
				<div class="clr"></div>
			</div>
			<div class="b"><div class="b"><div class="b"></div></div></div>
		</div>
		<form action="index.php?option=com_joocm" method="post" name="adminForm">
			<table><tr>
				<td width="100%">
				</td>
				<td nowrap="nowrap">
                	<?php echo $lists['client'];?>
					<?php echo $lists['type'];?>
				</td>
			</tr></table>
			<table class="adminlist" cellspacing="1">
			<thead><tr>
                <th nowrap="nowrap" width="5">
                    <?php echo JText::_('Num'); ?>
                </th>
                <th nowrap="nowrap" width="5">
                    <input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count($rows); ?>);" />
                </th>
                <th nowrap="nowrap" width="82%">
                    <?php echo JHTML::_('grid.sort', 'COM_JOOCM_NAME', 'i.name', @$lists['filter_order_Dir'], @$lists['filter_order'], 'joocm_interface_view'); ?>
                </th>
                <th nowrap="nowrap" width="5%">
                    <?php echo JHTML::_('grid.sort', 'COM_JOOCM_CLIENT', 'i.client', @$lists['filter_order_Dir'], @$lists['filter_order'], 'joocm_interface_view'); ?>
                </th>
                <th nowrap="nowrap" width="5%">
                    <?php echo JHTML::_('grid.sort', 'COM_JOOCM_SYSTEM', 'i.system', @$lists['filter_order_Dir'], @$lists['filter_order'], 'joocm_interface_view'); ?>
                </th>
                <th nowrap="nowrap" width="8%">
                    <?php echo JHTML::_('grid.sort', 'COM_JOOCM_ORDER', 'i.ordering', @$lists['filter_order_Dir'], @$lists['filter_order'], 'joocm_interface_view'); ?>
                    <?php echo JHTML::_('grid.order', $rows, 'filesave.png', 'joocm_interface_saveorder' ); ?>
                </th>
                <th nowrap="nowrap" width="1%">
                    <?php echo JHTML::_('grid.sort', 'COM_JOOCM_PUBLISHED', 'i.published', @$lists['filter_order_Dir'], @$lists['filter_order'], 'joocm_interface_view'); ?>
                </th>
                <th nowrap="nowrap" width="1%">
                    <?php echo JHTML::_('grid.sort', 'COM_JOOCM_ID', 'i.id', @$lists['filter_order_Dir'], @$lists['filter_order'], 'joocm_interface_view'); ?>
                </th>
			</tr></thead>
			<tfoot><tr>
				<td colspan="8"><?php echo $pageNav->getListFooter(); ?></td>
			</tr></tfoot>			
			<tbody><?php
			$k = 0;
			for ($i=0, $n=count($rows); $i < $n; $i++) {
				$row 	=& $rows[$i];
				
				$img_published = $row->published ? 'tick.png' : 'publish_x.png';
				$task_published = $row->published ? 'joocm_interface_unpublish' : 'joocm_interface_publish';
				$alt_published = $row->published ? JText::_('COM_JOOCM_PUBLISHED') : JText::_('COM_JOOCM_UNPUBLISHED');
				
				$client = $row->client ? JText::_('COM_JOOCM_BACKEND') : JText::_('COM_JOOCM_FRONTEND');
				$system = $row->system ? JText::_('COM_JOOCM_YES') : JText::_('COM_JOOCM_NO');
								
				$link = 'index.php?option=com_joocm&task=joocm_interface_edit&hidemainmenu=1&cid[]='. $row->id;
				
				$checked = JHTML::_('grid.checkedout', $row, $i); ?>
				<tr class="<?php echo "row$k"; ?>">
					<td><?php echo $pageNav->getRowOffset($i); ?></td>
					<td><?php echo $checked; ?></td>
					<td><?php
					if (JTable::isCheckedOut($user->get ('id'), $row->checked_out)) {
						echo JText::_($row->name);
					} else { ?>
						<a href="<?php echo JRoute::_($link); ?>">
							<?php echo htmlspecialchars(JText::_($row->name), ENT_QUOTES); ?>
						</a><?php
					} ?>
					</td>
					<td align="center"><?php echo $client; ?></td>
                    <td align="center"><?php echo $system; ?></td>
					<td class="order">
						<span><?php echo $pageNav->orderUpIcon($i, ($row->client == @$rows[$i-1]->client), 'joocm_interface_orderup', 'COM_JOOCM_ORDERUP', true); ?></span>
						<span><?php echo $pageNav->orderDownIcon($i, $n, ($row->client == @$rows[$i+1]->client), 'joocm_interface_orderdown', 'COM_JOOCM_ORDERDOWN', true); ?></span>
						<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" class="text_area" style="text-align: center" />
					</td>																			
					<td align="center">
						<a href="javascript: void(0);" onClick="return listItemTask('cb<?php echo $i;?>','<?php echo $task_published;?>')">
							<img src="images/<?php echo $img_published;?>" width="16" height="16" border="0" alt="<?php echo $alt_published; ?>" />
						</a>
					</td>																																																												
					<td><?php echo $row->id; ?></td>
				</tr><?php
				$k = 1 - $k;
			} ?>
			</tbody>
			</table>
			<input type="hidden" name="option" value="com_joocm" />
			<input type="hidden" name="task" value="joocm_interface_view" />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="hidemainmenu" value="0" />
			<input type="hidden" name="filter_order" value="<?php echo $lists['filter_order']; ?>" />
			<input type="hidden" name="filter_order_Dir" value="" />
		</form><?php
	}
	
	/**
	 * edit interface
	 */
	function editInterface(&$row, &$lists) {

		$document =& JFactory::getDocument();
		$document->addStyleSheet(JOOCM_ADMINCSS_LIVE.DL.'icon.css'); ?>
		<script language="javascript" type="text/javascript">
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'joocm_interface_cancel') {
				submitform(pressbutton); return;
			}

			// do field validation
			if (trim(form.name.value) == "") {
				alert("<?php echo JText::sprintf('COM_JOOCM_MSGFIELDREQUIRED', JText::_('COM_JOOCM_NAME'), JText::_('COM_JOOCM_INTERFACE')); ?>");
			} else {
				submitform(pressbutton);
			}
		}
		</script>
		<form action="index.php" method="post" name="adminForm">
			<div class="col width-50">
				<fieldset class="adminform">
					<legend><?php echo JText::_('COM_JOOCM_INTERFACEDETAILS'); ?></legend>		
					<table class="admintable" cellspacing="1"><tr>
						<td class="key">
							<label for="name">
								<?php echo JText::_('COM_JOOCM_NAME'); ?>
							</label>
						</td><td>
							<input type="text" name="name" id="name" class="inputbox" size="80" value="<?php echo $row->name; ?>" maxlength="255" />
						</td>
					</tr><tr>
						<td class="key">
							<label for="com">
								<?php echo JText::_('COM_JOOCM_COMPONENT'); ?>
							</label>
						</td><td>
							<input type="text" name="com" id="com" class="inputbox" size="80" value="<?php echo $row->com; ?>" maxlength="255" />
						</td>
					</tr><tr>
						<td class="key">
							<label for="com_icon">
								<?php echo JText::_('COM_JOOCM_COMPONENTICON'); ?>
							</label>
						</td><td>
							<input type="text" name="com_icon" id="com_icon" class="inputbox" size="80" value="<?php echo $row->com_icon; ?>" maxlength="255" />
						</td>
					</tr><tr>
						<td class="key">
							<label for="url_params">
								<?php echo JText::_('COM_JOOCM_URL'); ?>
							</label>
						</td><td>
							<input type="text" name="url" id="url" class="inputbox" size="80" value="<?php echo $row->url; ?>" maxlength="255" />
						</td>
					</tr><tr>
						<td class="key">
							<label for="client">
								<?php echo JText::_('COM_JOOCM_CLIENT'); ?>
							</label>
						</td>
						<td><?php echo $lists['client']; ?></td>
					</tr><tr>
						<td class="key">
							<label for="show_restriction" class="hasTip" title="<?php echo JText::_('COM_JOOCM_SHOWRESTRICTION') .'::'. JText::_('COM_JOOCM_INTERFACESHOWRESTRICTIONDESC'); ?>">
								<?php echo JText::_('COM_JOOCM_SHOWRESTRICTION'); ?>
							</label>
						</td>
						<td><?php echo $lists['show_restriction']; ?></td>
					</tr><tr>
						<td class="key">
							<label for="published" title="<?php echo JText::_('COM_JOOCM_PUBLISHED') .'::'. JText::_('COM_JOOCM_INTERFACEPUBLISHEDDESC'); ?>">
								<?php echo JText::_('COM_JOOCM_PUBLISHED'); ?>
							</label>
						</td>
						<td><?php echo $lists['published']; ?></td>
					</tr></table>
				</fieldset>
			</div>
			<div class="clr"></div>				
			<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
			<input type="hidden" name="option" value="com_joocm" />
			<input type="hidden" name="task" value="" />
		</form><?php
	}
}
?>