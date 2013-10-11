<?php
/**
 * @version $Id: forum.php 204 2011-11-13 18:27:41Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!BB Forum View
 *
 * @package Joo!BB
 */
class ViewForum {

	/**
	 * show forums
	 */
	function showForums(&$rows, $pageNav, &$lists) {

		// initialize variables
		$user		=& JFactory::getUser();
		$joobbAuth 	=& JoobbAuth::getInstance();
		$task		= JRequest::getVar('task'); ?>
		<script language="javascript" type="text/javascript">
		function submitbutton(pressbutton) {
			if (pressbutton == 'joobb_forum_delete') {
				if (document.adminForm.boxchecked.value > 0) {
					var confirmed = confirm("<?php echo JText::_('COM_JOOBB_MSGWARNINGDELETEFORUM'); ?>");
					if (!confirmed){
						pressbutton = 'joobb_forum_view';
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
				<th nowrap="nowrap" width="40%">
					<?php echo JHTML::_('grid.sort', 'COM_JOOBB_FORUM', 'f.name', @$lists['filter_order_Dir'], @$lists['filter_order'], 'joobb_forum_view'); ?>
				</th>
				<th nowrap="nowrap" width="20%">
					<?php echo JHTML::_('grid.sort', 'COM_JOOBB_CATEGORY', 'c.name', @$lists['filter_order_Dir'], @$lists['filter_order'], 'joobb_forum_view'); ?>
				</th>
				<th nowrap="nowrap" width="1%">
					<?php echo JHTML::_('grid.sort', 'COM_JOOBB_STATUS', 'f.status', @$lists['filter_order_Dir'], @$lists['filter_order'], 'joobb_forum_view'); ?>
				</th>										
				<th nowrap="nowrap" width="1%">
					<?php echo JHTML::_('grid.sort', 'COM_JOOBB_POSTS', 'f.posts', @$lists['filter_order_Dir'], @$lists['filter_order'], 'joobb_forum_view'); ?>
				</th>
				<th nowrap="nowrap" width="1%">
					<?php echo JHTML::_('grid.sort', 'COM_JOOBB_TOPICS', 'f.topics', @$lists['filter_order_Dir'], @$lists['filter_order'], 'joobb_forum_view'); ?>
				</th>					
				<th nowrap="nowrap" width="8%">
					<?php echo JHTML::_('grid.sort', 'COM_JOOBB_ORDER', 'c.ordering, f.ordering', @$lists['filter_order_Dir'], @$lists['filter_order'], 'joobb_forum_view'); ?>
					<?php echo JHTML::_('grid.order', $rows, 'filesave.png', 'joobb_forum_saveorder' ); ?>
				</th>
				<th nowrap="nowrap" width="1%">
					<?php echo JHTML::_('grid.sort', 'COM_JOOBB_VIEW', 'f.auth_view', @$lists['filter_order_Dir'], @$lists['filter_order'], 'joobb_forum_view'); ?>
				</th>
				<th nowrap="nowrap" width="1%">
					<?php echo JHTML::_('grid.sort', 'COM_JOOBB_READ', 'f.auth_read', @$lists['filter_order_Dir'], @$lists['filter_order'], 'joobb_forum_view'); ?>
				</th>
				<th nowrap="nowrap" width="1%">
					<?php echo JHTML::_('grid.sort', 'COM_JOOBB_POST', 'f.auth_post', @$lists['filter_order_Dir'], @$lists['filter_order'], 'joobb_forum_view'); ?>
				</th>
				<th nowrap="nowrap" width="1%">
					<?php echo JHTML::_('grid.sort', 'COM_JOOBB_REPLY', 'f.auth_reply', @$lists['filter_order_Dir'], @$lists['filter_order'], 'joobb_forum_view'); ?>
				</th>
				<th nowrap="nowrap" width="1%">
					<?php echo JHTML::_('grid.sort', 'COM_JOOBB_EDIT', 'f.auth_edit', @$lists['filter_order_Dir'], @$lists['filter_order'], 'joobb_forum_view'); ?>
				</th>
				<th nowrap="nowrap" width="1%">
					<?php echo JHTML::_('grid.sort', 'COM_JOOBB_DELETE', 'f.auth_delete', @$lists['filter_order_Dir'], @$lists['filter_order'], 'joobb_forum_view'); ?>
				</th>
				<th nowrap="nowrap" width="1%">
					<?php echo JHTML::_('grid.sort', 'COM_JOOBB_MOVE', 'f.auth_move', @$lists['filter_order_Dir'], @$lists['filter_order'], 'joobb_forum_view'); ?>
				</th>
				<th nowrap="nowrap" width="1%">
					<?php echo JHTML::_('grid.sort', 'COM_JOOBB_REPORT', 'f.auth_reportpost', @$lists['filter_order_Dir'], @$lists['filter_order'], 'joobb_forum_view'); ?>
				</th>
				<th nowrap="nowrap" width="1%">
					<?php echo JHTML::_('grid.sort', 'COM_JOOBB_STICKY', 'f.auth_sticky', @$lists['filter_order_Dir'], @$lists['filter_order'], 'joobb_forum_view'); ?>
				</th>
				<th nowrap="nowrap" width="1%">
					<?php echo JHTML::_('grid.sort', 'COM_JOOBB_LOCK', 'f.auth_lock', @$lists['filter_order_Dir'], @$lists['filter_order'], 'joobb_forum_view'); ?>
				</th>
				<th nowrap="nowrap" width="1%">
					<?php echo JHTML::_('grid.sort', 'COM_JOOBB_ANNOUNCE', 'f.auth_announce', @$lists['filter_order_Dir'], @$lists['filter_order'], 'joobb_forum_view'); ?>
				</th>
				<th nowrap="nowrap" width="1%">
					<?php echo JHTML::_('grid.sort', 'COM_JOOBB_ATTACHMENTS', 'f.auth_attachments', @$lists['filter_order_Dir'], @$lists['filter_order'], 'joobb_forum_view'); ?>
				</th>																																			
				<th nowrap="nowrap" width="1%">
					<?php echo JHTML::_('grid.sort', 'COM_JOOBB_ID', 'f.id', @$lists['filter_order_Dir'], @$lists['filter_order'], 'joobb_forum_view'); ?>
				</th>			
			</tr></thead>
			<tfoot><tr>
				<td colspan="22"><?php echo $pageNav->getListFooter(); ?></td>
			</tr></tfoot>			
			<tbody><?php
			$k = 0;
			for ( $i=0, $n=count( $rows ); $i < $n; $i++ ) {
				$row 	=& $rows[$i];
				
				$img_published = $row->status ? 'tick.png' : 'publish_x.png';
				$task_published = $row->status ? 'joobb_forum_unpublish' : 'joobb_forum_publish';
				$alt_published = $row->status ? JText::_('COM_JOOBB_PUBLISHED') :  JText::_('COM_JOOBB_UNPUBLISHED');				
				
				$link = 'index.php?option=com_joobb&task=joobb_forum_edit&hidemainmenu=1&cid[]='. $row->id;
				
				$checked = JHTML::_( 'grid.checkedout', $row, $i );	?>
				<tr class="<?php echo "row$k"; ?>">
					<td><?php echo $pageNav->getRowOffset($i); ?></td>
					<td><?php echo $checked; ?></td>
					<td><?php
					if (JTable::isCheckedOut($user->get('id'), $row->checked_out)) {
						echo $row->name;
					} else { ?>
						<a href="<?php echo JRoute::_( $link ); ?>">
							<?php echo htmlspecialchars($row->name, ENT_QUOTES); ?>
						</a><?php
					} ?>				
					</td>
					<td><?php echo $row->category; ?></td>
					<td align="center">
						<a href="javascript: void(0);" onClick="return listItemTask('cb<?php echo $i;?>','<?php echo $task_published;?>')">
						<img src="images/<?php echo $img_published;?>" width="16" height="16" border="0" alt="<?php echo $alt_published; ?>" />
						</a>
					</td>										
					<td align="center"><?php echo isset($row->posts) ? $row->posts : '-'; ?></td>
					<td align="center">
						<?php echo isset($row->topics) ? $row->topics : '-'; ?>
					</td>	
					<td class="order">
						<span><?php echo $pageNav->orderUpIcon( $i, ($row->id_cat == @$rows[$i-1]->id_cat), 'joobb_forum_orderup', 'COM_JOOBB_ORDERUP', true); ?></span>
						<span><?php echo $pageNav->orderDownIcon( $i, $n, ($row->id_cat == @$rows[$i+1]->id_cat), 'joobb_forum_orderdown', 'COM_JOOBB_ORDERDOWN', true); ?></span>
						<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" class="text_area" style="text-align: center" />
					</td>
					<td align="center"><?php echo $joobbAuth->getAuthText($row->auth_view); ?></td>
					<td align="center"><?php echo $joobbAuth->getAuthText($row->auth_read); ?></td>
					<td align="center"><?php echo $joobbAuth->getAuthText($row->auth_post); ?></td>
					<td align="center"><?php echo $joobbAuth->getAuthText($row->auth_reply); ?></td>
					<td align="center"><?php echo $joobbAuth->getAuthText($row->auth_edit); ?></td>
					<td align="center"><?php echo $joobbAuth->getAuthText($row->auth_delete); ?></td>
					<td align="center"><?php echo $joobbAuth->getAuthText($row->auth_move); ?></td>
					<td align="center"><?php echo $joobbAuth->getAuthText($row->auth_reportpost); ?></td>
					<td align="center"><?php echo $joobbAuth->getAuthText($row->auth_sticky); ?></td>
					<td align="center"><?php echo $joobbAuth->getAuthText($row->auth_lock); ?></td>
					<td align="center"><?php echo $joobbAuth->getAuthText($row->auth_announce); ?></td>
					<td align="center"><?php echo $joobbAuth->getAuthText($row->auth_attachments); ?></td>																																																							
					<td><?php echo $row->id; ?></td>
				</tr><?php
				$k = 1 - $k;
			} ?>
			</tbody>
			</table>
			<input type="hidden" name="option" value="com_joobb" />
			<input type="hidden" name="task" value="joobb_forum_view" />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="hidemainmenu" value="0" />
			<input type="hidden" name="filter_order" value="<?php echo $lists['filter_order']; ?>" />
			<input type="hidden" name="filter_order_Dir" value="" />
		</form><?php
	}
	
	/**
	 * edit forum
	 */
	function editForum(&$row, &$lists) {
	
		// initialize variables
		$editor =& JFactory::getEditor(); ?>
		<script language="javascript" type="text/javascript">
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'joobb_forum_cancel') {
				submitform(pressbutton); return;
			}

			// do field validation
			if (trim(form.name.value) == "") {
				alert("<?php echo JText::sprintf('COM_JOOBB_MSGFIELDREQUIRED', JText::_('COM_JOOBB_NAME'), JText::_('COM_JOOBB_FORUM')); ?>");
			} else {
				submitform(pressbutton);
			}
		}		
		</script>
		<form action="index.php" method="post" name="adminForm">
			<div class="col100">
				<fieldset class="adminform">
					<legend><?php echo JText::_('COM_JOOBB_FORUMDETAILS'); ?></legend>
					<table class="admintable" cellspacing="1"><tr>
						<td class="key">
							<label for="name" class="hasTip" title="<?php echo JText::_('COM_JOOBB_NAME') .'::'. JText::_('COM_JOOBB_FORUMNAMEDESC'); ?>">
								<?php echo JText::_('COM_JOOBB_NAME'); ?>
							</label>
						</td>
						<td>
							<input type="text" name="name" id="name" class="inputbox" size="50" value="<?php echo $row->name; ?>" maxlength="255" />
						</td>
					</tr><tr>
						<td class="key">
							<label for="description" class="hasTip" title="<?php echo JText::_('COM_JOOBB_DESCRIPTION') .'::'. JText::_('COM_JOOBB_FORUMDESCRIPTIONDESC'); ?>">
								<?php echo JText::_('COM_JOOBB_DESCRIPTION'); ?>
							</label>						
						</td>
						<td>
							<table cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td valign="top">
							<?php echo $editor->display('description',  $row->description , '100%', '250', '75', '20') ; ?>
							</td></tr></table>
						</td>
					</tr><tr>
						<td class="key">
							<label for="id_cat" class="hasTip" title="<?php echo JText::_('COM_JOOBB_CATEGORY') .'::'. JText::_('COM_JOOBB_FORUMCATEGORYDESC'); ?>">
								<?php echo JText::_('COM_JOOBB_CATEGORY'); ?>
							</label>						
						</td>
						<td><?php echo $lists['categories']; ?></td>
					</tr><tr>
						<td class="key">
							<label class="hasTip" title="<?php echo JText::_('COM_JOOBB_ENABLED') .'::'. JText::_('COM_JOOBB_FORUMENABLEDDESC'); ?>">
								<?php echo JText::_('COM_JOOBB_ENABLED'); ?>
							</label>
						</td>
						<td><?php echo $lists['status']; ?></td>
					</tr><tr>
						<td class="key">
							<label class="hasTip" title="<?php echo JText::_('COM_JOOBB_LOCKED') .'::'. JText::_('COM_JOOBB_FORUMLOCKEDDESC'); ?>">
								<?php echo JText::_('COM_JOOBB_LOCKED'); ?>
							</label>
						</td>
						<td><?php echo $lists['locked']; ?></td>
					</tr><tr>
						<td class="key">
							<label for="new_posts_time" class="hasTip" title="<?php echo JText::_('COM_JOOBB_NEWPOSTSTIME') .'::'. JText::_('COM_JOOBB_FORUMNEWPOSTSTIMEDESC'); ?>">
								<?php echo JText::_('COM_JOOBB_NEWPOSTSTIME'); ?>
							</label>
						</td><td>
							<input type="text" name="new_posts_time" id="new_posts_time" class="inputbox" size="20" value="<?php echo $row->new_posts_time; ?>" maxlength="5" />
						</td>
					</tr></table>
				</fieldset>
			</div>
			<div class="col width-50">
				<fieldset class="adminform">
					<legend><?php echo JText::_('COM_JOOBB_FORUMPERMISSIONS'); ?></legend>
					<table class="admintable" cellspacing="1"><tr>
						<td class="key">
							<label for="auth_view" class="hasTip" title="<?php echo JText::_('COM_JOOBB_VIEW') .'::'. JText::_('COM_JOOBB_FORUMVIEWDESC'); ?>">
								<?php echo JText::_('COM_JOOBB_VIEW'); ?>
							</label>
						</td>
						<td><?php echo $lists['auth_view']; ?></td>
					</tr><tr>
						<td class="key">
							<label for="auth_read" class="hasTip" title="<?php echo JText::_('COM_JOOBB_READ') .'::'. JText::_('COM_JOOBB_FORUMREADDESC'); ?>">
								<?php echo JText::_('COM_JOOBB_READ'); ?>
							</label>
						</td>
						<td><?php echo $lists['auth_read']; ?></td>
					</tr><tr>
						<td class="key">
							<label for="auth_post" class="hasTip" title="<?php echo JText::_('COM_JOOBB_POST') .'::'. JText::_('COM_JOOBB_FORUMPOSTDESC'); ?>">
								<?php echo JText::_('COM_JOOBB_POST'); ?>
							</label>
						</td>
						<td><?php echo $lists['auth_post']; ?></td>
					</tr><tr>
						<td class="key">
							<label for="auth_post_all" class="hasTip" title="<?php echo JText::_('COM_JOOBB_POSTALL') .'::'. JText::_('COM_JOOBB_FORUMPOSTALLDESC'); ?>">
								<?php echo JText::_('COM_JOOBB_POSTALL'); ?>
							</label>
						</td>
						<td><?php echo $lists['auth_post_all']; ?></td>
					</tr><tr>
						<td class="key">
							<label for="auth_reply" class="hasTip" title="<?php echo JText::_('COM_JOOBB_REPLY') .'::'. JText::_('COM_JOOBB_FORUMREPLYDESC'); ?>">
								<?php echo JText::_('COM_JOOBB_REPLY'); ?>
							</label>
						</td>
						<td><?php echo $lists['auth_reply']; ?></td>
					</tr><tr>
						<td class="key">
							<label for="auth_reply_all" class="hasTip" title="<?php echo JText::_('COM_JOOBB_REPLYALL') .'::'. JText::_('COM_JOOBB_FORUMREPLYALLDESC'); ?>">
								<?php echo JText::_('COM_JOOBB_REPLYALL'); ?>
							</label>
						</td>
						<td><?php echo $lists['auth_reply_all']; ?></td>
					</tr><tr>
						<td class="key">
							<label for="auth_edit" class="hasTip" title="<?php echo JText::_('COM_JOOBB_EDIT') .'::'. JText::_('COM_JOOBB_FORUMEDITDESC'); ?>">
								<?php echo JText::_('COM_JOOBB_EDIT'); ?>
							</label>
						</td>
						<td><?php echo $lists['auth_edit']; ?></td>
					</tr><tr>
						<td class="key">
							<label for="auth_edit_all" class="hasTip" title="<?php echo JText::_('COM_JOOBB_EDITALL') .'::'. JText::_('COM_JOOBB_FORUMEDITALLDESC'); ?>">
								<?php echo JText::_('COM_JOOBB_EDITALL'); ?>
							</label>
						</td>
						<td><?php echo $lists['auth_edit_all']; ?></td>
					</tr><tr>
						<td class="key">
							<label for="auth_delete" class="hasTip" title="<?php echo JText::_('COM_JOOBB_DELETE') .'::'. JText::_('COM_JOOBB_FORUMDELETEDESC'); ?>">
								<?php echo JText::_('COM_JOOBB_DELETE'); ?>
							</label>
						</td>
						<td><?php echo $lists['auth_delete']; ?></td>
					</tr><tr>
						<td class="key">
							<label for="auth_delete_all" class="hasTip" title="<?php echo JText::_('COM_JOOBB_DELETEALL') .'::'. JText::_('COM_JOOBB_FORUMDELETEALLDESC'); ?>">
								<?php echo JText::_('COM_JOOBB_DELETEALL'); ?>
							</label>
						</td>
						<td><?php echo $lists['auth_delete_all']; ?></td>
					</tr><tr>
						<td class="key">
							<label for="auth_move" class="hasTip" title="<?php echo JText::_('COM_JOOBB_MOVE') .'::'. JText::_('COM_JOOBB_FORUMMOVEDESC'); ?>">
								<?php echo JText::_('COM_JOOBB_MOVE'); ?>
							</label>
						</td>
						<td><?php echo $lists['auth_move']; ?></td>
					</tr><tr>
						<td class="key">
							<label for="auth_reportpost" class="hasTip" title="<?php echo JText::_('COM_JOOBB_REPORTPOST') .'::'. JText::_('COM_JOOBB_FORUMREPORTPOSTDESC'); ?>">
								<?php echo JText::_('COM_JOOBB_REPORTPOST'); ?>
							</label>
						</td>
						<td><?php echo $lists['auth_reportpost']; ?></td>
					</tr><tr>
						<td class="key">
							<label for="auth_sticky" class="hasTip" title="<?php echo JText::_('COM_JOOBB_STICKY') .'::'. JText::_('COM_JOOBB_FORUMSTICKYDESC'); ?>">
								<?php echo JText::_('COM_JOOBB_STICKY'); ?>
							</label>
						</td>
						<td><?php echo $lists['auth_sticky']; ?></td>
					</tr><tr>
						<td class="key">
							<label for="auth_lock" class="hasTip" title="<?php echo JText::_('COM_JOOBB_LOCK') .'::'. JText::_('COM_JOOBB_FORUMLOCKDESC'); ?>">
								<?php echo JText::_('COM_JOOBB_LOCK'); ?>
							</label>
						</td>
						<td><?php echo $lists['auth_lock']; ?></td>
					</tr><tr>
						<td class="key">
							<label for="auth_lock_all" class="hasTip" title="<?php echo JText::_('COM_JOOBB_LOCKALL') .'::'. JText::_('COM_JOOBB_FORUMLOCKALLDESC'); ?>">
								<?php echo JText::_('COM_JOOBB_LOCKALL'); ?>
							</label>
						</td>
						<td><?php echo $lists['auth_lock_all']; ?></td>
					</tr><tr>
						<td class="key">
							<label for="auth_announce" class="hasTip" title="<?php echo JText::_('COM_JOOBB_ANNOUNCE') .'::'. JText::_('COM_JOOBB_FORUMANNOUNCEDESC'); ?>">
								<?php echo JText::_('COM_JOOBB_ANNOUNCE'); ?>
							</label>
						</td>
						<td><?php echo $lists['auth_announce']; ?></td>
					</tr><tr>
						<td class="key">
							<label for="auth_attachments" class="hasTip" title="<?php echo JText::_('COM_JOOBB_ATTACHMENTS') .'::'. JText::_('COM_JOOBB_FORUMATTACHMENTSDESC'); ?>">
								<?php echo JText::_('COM_JOOBB_ATTACHMENTS'); ?>
							</label>
						</td>
						<td><?php echo $lists['auth_attachments']; ?></td>
					</tr></table>				
				</fieldset>
			</div>
			<div class="col width-50">
				<fieldset class="adminform">
					<legend><?php echo JText::_('COM_JOOBB_FORUMINFORMATION'); ?></legend>
					<table class="admintable" cellspacing="1"><tr>
						<td class="key">
							<label for="posts" class="hasTip" title="<?php echo JText::_('COM_JOOBB_POSTS') .'::'. JText::_('COM_JOOBB_FORUMPOSTSDESC'); ?>">
								<?php echo JText::_('COM_JOOBB_POSTS'); ?>
							</label>
						</td>
						<td><?php echo $row->posts; ?></td>
					</tr><tr>
						<td class="key">
							<label for="topics" class="hasTip" title="<?php echo JText::_('COM_JOOBB_TOPICS') .'::'. JText::_('COM_JOOBB_FORUMTOPICSDESC'); ?>">
								<?php echo JText::_('COM_JOOBB_TOPICS'); ?>
							</label>
						</td>
						<td><?php echo $row->topics; ?></td>
					</tr><tr>
						<td class="key">
							<label for="last_post_href" class="hasTip" title="<?php echo JText::_('COM_JOOBB_LASTPOST') .'::'. JText::_('COM_JOOBB_FORUMLASTPOSTDESC'); ?>">
								<?php echo JText::_('COM_JOOBB_LASTPOST'); ?>
							</label>
						</td>
						<td><?php echo $row->last_post_href; ?></td>
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