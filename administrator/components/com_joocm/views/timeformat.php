<?php
/**
 * @version $Id: timeformat.php 206 2011-11-14 20:37:29Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!CM  directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!CM Time Format View
 *
 * @package Joo!CM
 */
class ViewTimeFormat {

	/**
	 * show time formats
	 */	
	function showTimeFormats(&$rows, $pageNav, &$lists) {

		// initialize variables
		$user	=& JFactory::getUser();
		
		$document =& JFactory::getDocument();
		$document->addStyleSheet(JOOCM_ADMINCSS_LIVE.DL.'icon.css');
		
		$task	= JRequest::getVar('task'); ?>
		<div id="submenu-box">
			<div class="t"><div class="t"><div class="t"></div></div></div>			
			<div class="m">
				<ul id="submenu"><li>
					<?php $class = ($task == 'joocm_timeformat_view') ? 'class="active"' : ''; ?>
					<a <?php echo $class; ?> href="index.php?option=com_joocm&task=joocm_timeformat_view"><?php echo JText::_('COM_JOOCM_TIMEFORMATS'); ?></a>
				</li><li>
					<?php $class = ($task == 'joocm_terms_view') ? 'class="active"' : ''; ?>
					<a <?php echo $class; ?> href="index.php?option=com_joocm&task=joocm_terms_view"><?php echo JText::_('COM_JOOCM_TERMS'); ?></a>
				</li></ul>
				<div class="clr"></div>
			</div>
			<div class="b"><div class="b"><div class="b"></div></div></div>
		</div>
		<form action="index.php?option=com_joocm" method="post" name="adminForm">
			<table class="adminlist" cellspacing="1">
			<thead><tr>
				<th nowrap="nowrap" width="5">
					<?php echo JText::_('Num'); ?>
				</th>
				<th nowrap="nowrap" width="5">
					<input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count($rows); ?>);" />
				</th>
				<th nowrap="nowrap" width="45%">
					<?php echo JHTML::_('grid.sort', 'COM_JOOCM_NAME', 'f.name', @$lists['filter_order_Dir'], @$lists['filter_order'], 'joocm_timeformat_view'); ?>
				</th>
				<th nowrap="nowrap" width="30%">
					<?php echo JHTML::_('grid.sort', 'COM_JOOCM_TIMEFORMAT', 'f.timeformat', @$lists['filter_order_Dir'], @$lists['filter_order'], 'joocm_timeformat_view'); ?>
				</th>
				<th nowrap="nowrap" width="20%">
					<?php echo JText::_('COM_JOOCM_TIMEFORMATEXAMPLE'); ?>
				</th>
				<th width="5%">
					<?php echo JText::_('COM_JOOCM_DEFAULT'); ?>
				</th>																		
				<th nowrap="nowrap" width="1%">
					<?php echo JHTML::_('grid.sort', 'COM_JOOCM_PUBLISHED', 'f.published', @$lists['filter_order_Dir'], @$lists['filter_order'], 'joocm_timeformat_view'); ?>
				</th>
				<th nowrap="nowrap" width="1%">
					<?php echo JHTML::_('grid.sort', 'COM_JOOCM_ID', 'f.id', @$lists['filter_order_Dir'], @$lists['filter_order'], 'joocm_timeformat_view'); ?>
				</th>
			</tr></thead>
			<tfoot><tr>
				<td colspan="8"><?php echo $pageNav->getListFooter(); ?></td>
			</tr></tfoot>			
			<tbody><?php
			$k = 0;
			for ($i=0, $n=count($rows); $i < $n; $i++) {
				$row 	=& $rows[$i];
				
				$defaultTimeFormat = ($lists['default_timeformat'] == $row->timeformat);
	
				$img_default = $defaultTimeFormat ? JOOCM_ADMINIMAGES_LIVE.DL.'menu'.DL.'icon-16-default.png' : JOOCM_ADMINIMAGES_LIVE.DL.'menu'.DL.'spacer.png';
				$alt_default = $defaultTimeFormat ? JText::_('COM_JOOCM_DEFAULT') :  JText::_('COM_JOOCM_NOTDEFAULT');				
				
				$img_published = $row->published ? 'tick.png' : 'publish_x.png';
				$task_published = $row->published ? 'joocm_timeformat_unpublish' : 'joocm_timeformat_publish';
				$alt_published = $row->published ? JText::_('COM_JOOCM_PUBLISHED') : JText::_('COM_JOOCM_UNPUBLISHED');
								
				$link = 'index.php?option=com_joocm&task=joocm_timeformat_edit&hidemainmenu=1&cid[]='. $row->id;
				
				$checked = JHTML::_('grid.checkedout', $row, $i);																																		
				?>
				<tr class="<?php echo "row$k"; ?>">
					<td><?php echo $pageNav->getRowOffset($i); ?></td>
					<td><?php echo $checked; ?></td>
					<td><?php
						if (JTable::isCheckedOut($user->get ('id'), $row->checked_out)) {
							echo $row->name;
						} else {
							?>
							<a href="<?php echo JRoute::_($link); ?>">
								<?php echo htmlspecialchars($row->name, ENT_QUOTES); ?>
							</a>
							<?php
						} ?>				
					</td>
					<td><?php echo $row->timeformat; ?></td>
					<td><?php echo JoocmHelper::formatDate(time(), $row->timeformat); ?></td>
					<td align="center">
						<img src="<?php echo $img_default; ?>" width="16" height="16" border="0" alt="<?php echo $alt_default; ?>" />
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
			<input type="hidden" name="task" value="joocm_timeformat_view" />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="hidemainmenu" value="0" />
			<input type="hidden" name="filter_order" value="<?php echo $lists['filter_order']; ?>" />
			<input type="hidden" name="filter_order_Dir" value="" />
		</form>
		<?php
	}
	
	/**
	 * edit time format
	 */
	function editTimeFormat(&$row, &$lists) {

		$document =& JFactory::getDocument();
		$document->addStyleSheet(JOOCM_ADMINCSS_LIVE.DL.'icon.css'); ?>
		<script language="javascript" type="text/javascript">
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'joocm_timeformat_cancel') {
				submitform(pressbutton); return;
			}

			// do field validation
			if (trim(form.name.value) == "") {
				alert("<?php echo JText::sprintf('COM_JOOCM_MSGFIELDREQUIRED', JText::_('COM_JOOCM_NAME'), JText::_('COM_JOOCM_TIMEFORMAT')); ?>");
			} else {
				submitform(pressbutton);
			}
		}
		</script>
		<form action="index.php" method="post" name="adminForm">
			<div class="col width-50">
				<fieldset class="adminform">
					<legend><?php echo JText::_('COM_JOOCM_TIMEFORMATDETAILS'); ?></legend>		
					<table class="admintable" cellspacing="1"><tr>
						<td class="key">
							<label for="name">
								<?php echo JText::_('COM_JOOCM_NAME'); ?>
							</label>
						</td><td>
							<input type="text" name="name" id="name" class="inputbox" size="40" value="<?php echo $row->name; ?>" maxlength="50" />
						</td>
					</tr><tr>
						<td class="key">
							<label for="timeformat">
								<?php echo JText::_('COM_JOOCM_TIMEFORMAT'); ?>
							</label>
						</td><td>
							<input type="text" name="timeformat" id="timeformat" class="inputbox" size="40" value="<?php echo $row->timeformat; ?>" maxlength="25" />
						</td>
					</tr><tr>
						<td class="key">
							<label for="published">
								<?php echo JText::_('COM_JOOCM_PUBLISHED'); ?>
							</label>
						</td>
						<td><?php echo $lists['published']; ?></td>
					</tr></table>
				</fieldset>
			</div>
			<div class="col width-50">
				<fieldset class="adminform">
					<legend><?php echo JText::_('COM_JOOCM_TIMEFORMATEXAMPLE'); ?></legend>
					<table class="admintable" cellspacing="1"><tr>
						<td><?php echo JoocmHelper::formatDate(time(), $row->timeformat); ?></td>
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