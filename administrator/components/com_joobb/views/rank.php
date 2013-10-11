<?php
/**
 * @version $Id: rank.php 204 2011-11-13 18:27:41Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!BB Rank View
 *
 * @package Joo!BB
 */
class ViewRank {

	/**
	 * show ranks
	 */	
	function showRanks(&$rows, $pageNav, &$lists) {
	
		// initialize variables
		$user	=& JFactory::getUser();
		$task	= JRequest::getVar('task'); ?>
		<div id="submenu-box">
			<div class="t"><div class="t"><div class="t"></div></div></div>			
			<div class="m">
				<ul id="submenu"><li>
					<?php $class = ($task == 'joobb_user_view') ? 'class="active"' : ''; ?>
					<a <?php echo $class; ?> href="index.php?option=com_joobb&task=joobb_user_view"><?php echo JText::_('COM_JOOBB_USERS'); ?></a>
				</li><li>
						<?php $class = ($task == 'joobb_group_view') ? 'class="active"' : ''; ?>
						<a <?php echo $class; ?> href="index.php?option=com_joobb&task=joobb_group_view"><?php echo JText::_('COM_JOOBB_GROUPS'); ?></a>
				</li><li>
					<?php $class = ($task == 'joobb_rank_view') ? 'class="active"' : ''; ?>
					<a <?php echo $class; ?> href="index.php?option=com_joobb&task=joobb_rank_view"><?php echo JText::_('COM_JOOBB_RANKS'); ?></a>
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
                <th nowrap="nowrap" width="30%">
                    <?php echo JHTML::_('grid.sort', 'COM_JOOBB_NAME', 'r.name', @$lists['filter_order_Dir'], @$lists['filter_order'], 'joobb_rank_view'); ?>
                </th>
                <th nowrap="nowrap" width="60%">
                    <?php echo JHTML::_('grid.sort', 'COM_JOOBB_DESCRIPTION', 'r.description', @$lists['filter_order_Dir'], @$lists['filter_order'], 'joobb_rank_view'); ?>
                </th>
                <th nowrap="nowrap" width="5%">
                    <?php echo JHTML::_('grid.sort', 'COM_JOOBB_MINPOSTS', 'r.min_posts', @$lists['filter_order_Dir'], @$lists['filter_order'], 'joobb_rank_view'); ?>
                </th>									
                <th nowrap="nowrap" width="1%">
                    <?php echo JHTML::_('grid.sort', 'COM_JOOBB_PUBLISHED', 'r.published', @$lists['filter_order_Dir'], @$lists['filter_order'], 'joobb_rank_view'); ?>
                </th>																													
                <th nowrap="nowrap" width="1%">
                    <?php echo JHTML::_('grid.sort', 'COM_JOOBB_ID', 'r.id', @$lists['filter_order_Dir'], @$lists['filter_order'], 'joobb_rank_view'); ?>
                </th>			
			</tr></thead>
			<tfoot><tr>
				<td colspan="7"><?php echo $pageNav->getListFooter(); ?></td>
			</tr></tfoot>			
			<tbody><?php
			$k = 0;
			for ($i=0, $n=count($rows); $i < $n; $i++) {
				$row 	=& $rows[$i];
				
				$img_published = $row->published ? 'tick.png' : 'publish_x.png';
				$task_published = $row->published ? 'joobb_rank_unpublish' : 'joobb_rank_publish';
				$alt_published = $row->published ? JText::_('COM_JOOBB_PUBLISHED') :  JText::_('COM_JOOBB_UNPUBLISHED');
								
				$link = 'index.php?option=com_joobb&task=joobb_rank_edit&hidemainmenu=1&cid[]='. $row->id; ?>
				<tr class="<?php echo "row$k"; ?>">
					<td><?php echo $i+1 ?></td>
					<td><?php echo JHTML::_('grid.id', $i, $row->id); ?></td>
					<td><?php
					if (JTable::isCheckedOut($user->get('id'), $row->id)) {
						echo $row->name;
					} else { ?>
						<a href="<?php echo JRoute::_($link); ?>">
							<?php echo htmlspecialchars($row->name, ENT_QUOTES); ?>
						</a><?php
					} ?>
					</td>
					<td><?php echo $row->description; ?></td>
					<td><?php echo $row->min_posts; ?></td>
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
			<input type="hidden" name="option" value="com_joobb" />
			<input type="hidden" name="task" value="joobb_rank_view" />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="hidemainmenu" value="0" />
			<input type="hidden" name="filter_order" value="<?php echo $lists['filter_order']; ?>" />
			<input type="hidden" name="filter_order_Dir" value="" />
		</form><?php
	}
	
	/**
	 * edit rank
	 */
	function editRank(&$row, &$lists) { ?>
		<script language="javascript" type="text/javascript">
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'joobb_rank_cancel') {
				submitform(pressbutton); return;
			}
			var r = new RegExp("[\<|\>|\"|\'|\%|\;|\(|\)|\&|\+|\-]", "i");

			// do field validation
			if (trim(form.name.value) == "") {
				alert("<?php echo JText::sprintf('COM_JOOBB_MSGFIELDREQUIRED', JText::_('COM_JOOBB_NAME'), JText::_('COM_JOOBB_RANK')); ?>");
			} else {
				submitform(pressbutton);
			}
		}
		</script>
		<form action="index.php" method="post" name="adminForm">
			<div class="col width-50">
				<fieldset class="adminform">
					<legend><?php echo JText::_('COM_JOOBB_RANKDETAILS'); ?></legend>
					<table class="admintable" cellspacing="1"><tr>
                        <td class="key">
                            <label for="name">
                                <?php echo JText::_('COM_JOOBB_NAME'); ?>
                            </label>
                        </td>
                        <td>
                            <input type="text" name="name" id="name" class="inputbox" size="50" value="<?php echo $row->name; ?>" maxlength="255" />
                        </td>
                    </tr><tr>
                        <td class="key">
                            <label for="description">
                                <?php echo JText::_('COM_JOOBB_DESCRIPTION'); ?>
                            </label>
                        </td>
                        <td>
                            <textarea name="description" id="description" rows="5" cols="50" class="inputbox"><?php echo $row->description; ?></textarea>
                        </td>
                    </tr><tr>
                        <td class="key">
                            <label for="min_posts">
                                <?php echo JText::_('COM_JOOBB_MINPOSTS'); ?>
                            </label>
                        </td>
                        <td>
                            <input type="text" name="min_posts" id="min_posts" class="inputbox" size="50" value="<?php echo $row->min_posts; ?>" maxlength="50" />
                        </td>
                    </tr><tr>
                        <td class="key">
                            <label for="published">
                                <?php echo JText::_('COM_JOOBB_PUBLISHED'); ?>
                            </label>
                        </td>
                        <td><?php echo $lists['published']; ?></td>
                    </tr><tr>
                        <td class="key">
                            <label for="rank_file">
                                <?php echo JText::_('COM_JOOBB_RANKIMAGEFILE'); ?>
                            </label>
                        </td>
                        <td>
                            <input type="text" name="rank_file" id="rank_file" class="inputbox" size="50" value="<?php echo $row->rank_file; ?>" maxlength="255" />
                        </td>
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