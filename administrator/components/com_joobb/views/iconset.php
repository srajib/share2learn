<?php
/**
 * @version $Id: iconset.php 204 2011-11-13 18:27:41Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!BB Icon Set View
 *
 * @package Joo!BB
 */
class ViewIconSet {

	/**
	 * show icon sets
	 */	
	function showIconSets(&$rows, $pageNav) {

		// initialize variables
		$user	=& JFactory::getUser();
		$task	= JRequest::getVar('task'); ?>
		<div id="submenu-box">
			<div class="t"><div class="t"><div class="t"></div></div></div>			
			<div class="m">
				<ul id="submenu"><li>
					<?php $class = ($task == 'joobb_template_view') ? 'class="active"' : ''; ?>
                    <a <?php echo $class; ?> href="index.php?option=com_joobb&task=joobb_template_view"><?php echo JText::_('COM_JOOBB_TEMPLATES'); ?></a>
				</li><li>
					<?php $class = ($task == 'joobb_emotionset_view') ? 'class="active"' : ''; ?>
                    <a <?php echo $class; ?> href="index.php?option=com_joobb&task=joobb_emotionset_view"><?php echo JText::_('COM_JOOBB_EMOTIONSETS'); ?></a>
				</li><li>
					<?php $class = ($task == 'joobb_iconset_view') ? 'class="active"' : ''; ?>
                    <a <?php echo $class; ?> href="index.php?option=com_joobb&task=joobb_iconset_view"><?php echo JText::_('COM_JOOBB_ICONSETS'); ?></a>
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
                <th nowrap="nowrap" width="45%">
                    <?php echo JText::_('COM_JOOBB_NAME'); ?>
                </th>
                <th width="5%">
                    <?php echo JText::_('COM_JOOBB_DEFAULT'); ?>
                </th>
                <th width="10%" align="center">
                    <?php echo JText::_('COM_JOOBB_VERSION'); ?>
                </th>
                <th width="10%" class="title">
                    <?php echo JText::_('COM_JOOBB_DATE'); ?>
                </th>
                <th width="25%" class="title">
                    <?php echo JText::_('COM_JOOBB_AUTHOR'); ?>
                </th>
			</tr></thead>
			<tfoot><tr>
				<td colspan="7"><?php echo $pageNav->getListFooter(); ?></td>
			</tr></tfoot>			
			<tbody><?php
			$k = 0;
			for ($i=0, $n=count( $rows ); $i < $n; $i++) {
				$row 	=& $rows[$i];
				
				if (isset($row->authorUrl) && $row->authorUrl != '') {
					if (isset($row->author) && $row->author != '') {
						$row->author = '<a href="'. $row->authorUrl .'" target="_blank">'. $row->author .'</a>';
					} else {
						$row->author = '<a href="'. $row->authorUrl .'" target="_blank">'. str_replace('http://', '', $row->authorUrl) .'</a>';
					}
				}
				
				$img_default = $row->default_icon_set ? JOOBB_ADMINIMAGES_LIVE.DL.'menu'.DL.'icon-16-default.png' : JOOBB_ADMINIMAGES_LIVE.DL.'menu'.DL.'spacer.png';
				$alt_default = $row->default_icon_set ? JText::_('COM_JOOBB_DEFAULT') :  JText::_('COM_JOOBB_NOTDEFAULT');
		
				$link = 'index.php?option=com_joobb&task=joobb_iconset_view'; ?>
				<tr class="<?php echo "row$k"; ?>">
					<td><?php echo $pageNav->getRowOffset( $i ); ?></td>
					<td>
						<input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row->file_name; ?>" onclick="isChecked(this.checked);" />
					</td>
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
						<img src="<?php echo $img_default; ?>" width="16" height="16" border="0" alt="<?php echo $alt_default; ?>" />
					</td>
					<td align="center"><?php echo $row->version; ?></td>
					<td><?php echo $row->creationdate; ?></td>
					<td><?php echo $row->author; ?></td>
				</tr><?php
				$k = 1 - $k;
			} ?>
			</tbody>
			</table>
			<input type="hidden" name="option" value="com_joobb" />
			<input type="hidden" name="task" value="joobb_iconset_view" />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="hidemainmenu" value="0" />
		</form><?php
	}
} ?>