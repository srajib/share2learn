<?php
/**
 * @version $Id: avatar.php 206 2011-11-14 20:37:29Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!CM  directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!CM Avatar View
 *
 * @package Joo!CM
 */
class ViewAvatar {

	/**
	 * show avatars
	 */	
	function showAvatars(&$rows, &$pageNav, &$lists) {

		JHTML::_('behavior.tooltip');
		
		// initialize variables
		$user	=& JFactory::getUser();
				
		$document =& JFactory::getDocument();
		$document->addStyleSheet(JOOCM_ADMINCSS_LIVE.DL.'icon.css');
		
		$task	= JRequest::getVar('task'); ?>
		<div id="submenu-box">
			<div class="t"><div class="t"><div class="t"></div></div></div>			
			<div class="m">
				<ul id="submenu"><li>
					<?php $class = ($task == 'joocm_user_view') ? 'class="active"' : ''; ?>
					<a <?php echo $class; ?> href="index.php?option=com_joocm&task=joocm_user_view"><?php echo JText::_('COM_JOOCM_USERS'); ?></a>
				</li><li>
					<?php $class = ($task == 'joocm_avatar_view') ? 'class="active"' : ''; ?>
					<a <?php echo $class; ?> href="index.php?option=com_joocm&task=joocm_avatar_view"><?php echo JText::_('COM_JOOCM_AVATARS'); ?></a>
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
				<th nowrap="nowrap" width="78%">
					<?php echo JHTML::_('grid.sort', 'COM_JOOCM_AVATARFILE', 'a.avatar_file', @$lists['filter_order_Dir'], @$lists['filter_order'], 'joocm_avatar_view'); ?>
				</th>
				<th nowrap="nowrap" width="10%">
					<?php echo JText::_('COM_JOOCM_AVATARUSER'); ?>
				</th>
				<th nowrap="nowrap" width="10%">
					<?php echo JText::_('COM_JOOCM_AVATARPREVIEW'); ?>
				</th>								
				<th nowrap="nowrap" width="1%">
					<?php echo JHTML::_('grid.sort', 'COM_JOOCM_PUBLISHED', 'a.published', @$lists['filter_order_Dir'], @$lists['filter_order'], 'joocm_avatar_view'); ?>
				</th>																													
				<th nowrap="nowrap" width="1%">
					<?php echo JHTML::_('grid.sort', 'COM_JOOCM_ID', 'a.id', @$lists['filter_order_Dir'], @$lists['filter_order'], 'joocm_avatar_view'); ?>
				</th>			
			</tr></thead>
			<tfoot><tr>
				<td colspan="7"><?php echo $pageNav->getListFooter(); ?></td>
			</tr></tfoot>			
			<tbody><?php
			$k = 0;
			for ($i=0, $n=count($rows); $i < $n; $i++) {
				$row 	=& $rows[$i];
				
				if ($row->id_user) {
					$pos = strpos($row->avatar_file, 'http://');
					if ($pos === false) {
						$avatar = JURI::root().$lists['avatar_path'].DL.$row->id_user.DL.$row->avatar_file;
					} else {
						$avatar = $row->avatar_file;
					}
				} else {
					$avatar = JURI::root().$lists['avatar_path'].DL.'standard'.DL.$row->avatar_file;
					$row->name = JText::_('COM_JOOCM_STANDARDAVATAR');
				}

				$img_published = $row->published ? 'tick.png' : 'publish_x.png';
				$task_published = $row->published ? 'joocm_avatar_unpublish' : 'joocm_avatar_publish';
				$alt_published = $row->published ? JText::_('COM_JOOCM_PUBLISHED') :  JText::_('COM_JOOCM_UNPUBLISHED');
								
				$link = 'index.php?option=com_joocm&task=joocm_avatar_edit&hidemainmenu=1&cid[]='. $row->id; ?>
				<tr class="<?php echo "row$k"; ?>">
					<td><?php echo $pageNav->getRowOffset($i); ?></td>
					<td><?php echo JHTML::_('grid.id', $i, $row->id); ?></td>
					<td><?php
						if (JTable::isCheckedOut($user->get ('id'), $row->id)) {
							echo $row->name;
						} else { ?>
							<span class="editlinktip hasTip" title="<?php echo $row->name;?>::<img src=&quot;<?php echo $avatar;?>&quot; border=&quot;1&quot; alt=&quot;<?php echo $row->name; ?>&quot; />" >
							<a href="<?php echo JRoute::_($link); ?>">
								<?php echo htmlspecialchars($row->avatar_file, ENT_QUOTES); ?>
							</a></span><?php
						} ?>				
					</td>
					<td align="center"><?php echo $row->name; ?></td>
					<td align="center"><span class="editlinktip hasTip" title="<?php echo $row->name;?>::<img src=&quot;<?php echo $avatar;?>&quot; border=&quot;1&quot; alt=&quot;<?php echo $row->name; ?>&quot; />" >
						<img src="<?php echo $avatar; ?>" width="16" height="16" border="0" alt="<?php echo $row->name; ?>" />
					</span></td>									
					<td align="center">
						<a href="javascript: void(0);" onClick="return listItemTask('cb<?php echo $i;?>','<?php echo $task_published; ?>')">
							<img src="images/<?php echo $img_published; ?>" width="16" height="16" border="0" alt="<?php echo $alt_published; ?>" />
						</a>
					</td>																																																							
					<td><?php echo $row->id; ?></td>
				</tr><?php
				$k = 1 - $k;
			} ?>
			</tbody>
			</table>
			<input type="hidden" name="option" value="com_joocm" />
			<input type="hidden" name="task" value="joocm_avatar_view" />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="hidemainmenu" value="0" />
			<input type="hidden" name="filter_order" value="<?php echo $lists['filter_order']; ?>" />
			<input type="hidden" name="filter_order_Dir" value="" />
		</form><?php
	}
	
	/**
	 * edit terms
	 */
	function editAvatar(&$row, &$lists) {

		$document =& JFactory::getDocument();
		$document->addStyleSheet(JOOCM_ADMINCSS_LIVE.DL.'icon.css');

		if ($row->id_user) {
			$pos = strpos($row->avatar_file, 'http://');
			if ($pos === false) {
				$avatar = JURI::root().$lists['avatar_path'].DL.$row->id_user.DL.$row->avatar_file;
			} else {
				$avatar = $row->avatar_file;
			}
		} else {
			$avatar = JURI::root().$lists['avatar_path'].DL.'standard'.DL.$row->avatar_file;
			$row->name = JText::_('COM_JOOCM_STANDARDAVATAR');
		} ?>
		<script language="javascript" type="text/javascript">
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'joocm_avatar_cancel') {
				submitform(pressbutton); return;
			}

			// do field validation
			if (trim(form.name.value) == "") {
				alert("<?php echo JText::sprintf('COM_JOOCM_MSGFIELDREQUIRED', JText::_('COM_JOOCM_NAME'), JText::_('COM_JOOCM_AVATAR')); ?>");
			} else {
				submitform(pressbutton);
			}
		}
		</script>
		<form action="index.php" method="post" name="adminForm">
			<div class="col100">
				<fieldset class="adminform">
					<legend><?php echo JText::_('COM_JOOCM_AVATARDETAILS'); ?></legend>
					<table class="admintable" cellspacing="1"><tr>
						<td class="key">
							<label for="avatar_file" class="hasTip" title="<?php echo JText::_('COM_JOOCM_AVATARFILE') .'::'. JText::_('COM_JOOCM_AVATARFILEDESC'); ?>">
								<?php echo JText::_('COM_JOOCM_AVATARFILE'); ?>
							</label>
						</td><td>
							<textarea name="avatar_file" id="avatar_file" rows="5" cols="50" class="inputbox"><?php echo $row->avatar_file; ?></textarea>
						</td>
						<td><img src="<?php echo $avatar; ?>" border="0" alt="<?php echo $row->name; ?>" /></td>
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
			<div class="clr"></div>				
			<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
			<input type="hidden" name="option" value="com_joocm" />
			<input type="hidden" name="task" value="" />
		</form><?php
	}
}
?>