<?php
/**
 * @version $Id: user.php 206 2011-11-14 20:37:29Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!CM  directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!CM User View
 *
 * @package Joo!CM
 */
class ViewUser {

	/**
	 * show users
	 */
	function showUsers(&$rows, &$pageNav, &$lists) {

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
			<table><tr>
				<td width="100%">
					<?php echo JText::_('COM_JOOCM_FILTER'); ?>:
					<input type="text" name="search" id="search" value="<?php echo $lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
					<button onclick="this.form.submit();"><?php echo JText::_('COM_JOOCM_GO'); ?></button>
					<button onclick="getElementById('search').value='';this.form.submit();"><?php echo JText::_('COM_JOOCM_RESET'); ?></button>
				</td>
				<td nowrap="nowrap">
					<?php echo $lists['type'];?>
					<?php echo $lists['logged'];?>
				</td>
			</tr></table>

			<table class="adminlist" cellpadding="1">
				<thead><tr>
					<th width="2%" class="title">
						<?php echo JText::_('NUM'); ?>
					</th>
					<th width="3%" class="title">
						<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows); ?>);" />
					</th>
					<th class="title">
						<?php echo JHTML::_('grid.sort', 'COM_JOOCM_NAME', 'a.name', @$lists['order_Dir'], @$lists['order'], 'joocm_user_view'); ?>
					</th>
					<th width="15%" class="title" >
						<?php echo JHTML::_('grid.sort', 'COM_JOOCM_USERNAME', 'a.username', @$lists['order_Dir'], @$lists['order'], 'joocm_user_view'); ?>
					</th>
					<th width="5%" class="title" nowrap="nowrap">
						<?php echo JText::_('COM_JOOCM_LOGGEDIN'); ?>
					</th>
					<th width="5%" class="title" nowrap="nowrap">
						<?php echo JHTML::_('grid.sort', 'COM_JOOCM_ENABLED', 'a.block', @$lists['order_Dir'], @$lists['order'], 'joocm_user_view'); ?>
					</th>
					<th width="15%" class="title">
						<?php echo JHTML::_('grid.sort', 'COM_JOOCM_GROUP', 'a.groupname', @$lists['order_Dir'], @$lists['order'], 'joocm_user_view'); ?>
					</th>
					<th width="15%" class="title">
						<?php echo JHTML::_('grid.sort', 'COM_JOOCM_EMAIL', 'a.email', @$lists['order_Dir'], @$lists['order'], 'joocm_user_view'); ?>
					</th>
					<th width="10%" class="title">
						<?php echo JHTML::_('grid.sort', 'COM_JOOCM_LASTVISIT', 'a.lastvisitDate', @$lists['order_Dir'], @$lists['order'], 'joocm_user_view'); ?>
					</th>
					<th width="1%" class="title" nowrap="nowrap">
						<?php echo JHTML::_('grid.sort', 'COM_JOOCM_ID', 'a.id', @$lists['order_Dir'], @$lists['order'], 'joocm_user_view'); ?>
					</th>
				</tr></thead>
				<tfoot><tr>
					<td colspan="11"><?php echo $pageNav->getListFooter(); ?></td>
				</tr></tfoot>
			<tbody><?php
			$k = 0;
			for ($i=0, $n=count($rows); $i < $n; $i++) {
				$row 	=& $rows[$i];

				$img 	= $row->block ? 'publish_x.png' : 'tick.png';
				$task 	= $row->block ? 'joocm_user_unblock' : 'joocm_user_block';
				$alt 	= $row->block ? JText::_('COM_JOOCM_ENABLED') : JText::_('COM_JOOCM_BLOCKED');
				
				$link 	= 'index.php?option=com_joocm&task=joocm_user_edit&cid[]='. $row->id. '&hidemainmenu=1'; ?>
				<tr class="<?php echo "row$k"; ?>">
					<td><?php echo $i+1+$pageNav->limitstart;?></td>
					<td><?php echo JHTML::_('grid.id', $i, $row->id); ?></td>
					<td><?php
					if (JTable::isCheckedOut($user->get ('id'), $row->id)) {
						echo $row->name;
					} else { ?>
						<a href="<?php echo JRoute::_($link); ?>">
							<?php echo htmlspecialchars($row->name, ENT_QUOTES); ?>
						</a><?php
					} ?>
					</td>
					<td><?php echo $row->username; ?></td>
					<td align="center">
						<?php echo $row->loggedin ? '<img src="images/tick.png" width="16" height="16" border="0" alt="" />': '<img src="components/com_joocm/images/menu/spacer.png" width="16" height="16" border="0" alt="" />'; ?>
					</td>
					<td align="center">
						<a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $task;?>')">
							<img src="images/<?php echo $img;?>" width="16" height="16" border="0" alt="<?php echo $alt; ?>" /></a>
					</td>
					<td><?php echo JText::_($row->groupname); ?></td>
					<td><a href="mailto:<?php echo $row->email; ?>"><?php echo $row->email; ?></a></td>
					<td nowrap="nowrap">
						<?php echo ($row->lastvisitDate == '0000-00-00 00:00:00') ?  'No visit since registration' : JoocmHelper::Date($row->lastvisitDate); ?>
					</td>
					<td><?php echo $row->id; ?></td>
				</tr><?php
				$k = 1 - $k;
			} ?>
			</tbody>
			</table>
			<input type="hidden" name="option" value="com_joocm" />
			<input type="hidden" name="task" value="joocm_user_view" />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="hidemainmenu" value="0" />
			<input type="hidden" name="filter_order" value="<?php echo $lists['order']; ?>" />
			<input type="hidden" name="filter_order_Dir" value="" />
		</form><?php
	}
	
	/**
	 * edit user
	 */
	function editUser(&$user, &$lists) {

		 // initialize variables
		$document =& JFactory::getDocument();
		$document->addStyleSheet(JOOCM_ADMINCSS_LIVE.DL.'icon.css'); ?>
		<script language="javascript" type="text/javascript">
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'joocm_user_cancel') {
				submitform(pressbutton); return;
			}
			var r = new RegExp("[\<|\>|\"|\'|\%|\;|\(|\)|\&|\+|\-]", "i");

			// do field validation
			if (trim(form.name.value) == "") {
				alert("<?php echo JText::sprintf('COM_JOOCM_MSGFIELDREQUIRED', JText::_('COM_JOOCM_NAME'), JText::_('COM_JOOCM_USER')); ?>");
			} else if (form.username.value == "") {
				alert("<?php echo JText::sprintf('COM_JOOCM_MSGFIELDREQUIRED', JText::_('COM_JOOCM_USERNAME'), JText::_('COM_JOOCM_USER')); ?>");
			} else if (r.exec(form.username.value) || form.username.value.length < 3) {
				alert("<?php echo JText::_('WARNLOGININVALID', true); ?>");
			} else if (trim(form.email.value) == "") {
				alert("<?php echo JText::sprintf('COM_JOOCM_MSGFIELDREQUIRED', JText::_('COM_JOOCM_EMAILADDRESS'), JText::_('COM_JOOCM_USER')); ?>");
			} else {
				submitform(pressbutton);
			}
		}
		</script>
		<form action="index.php" method="post" name="adminForm" id="adminForm">
			<div class="col width-50">
				<fieldset class="adminform">
					<legend><?php echo JText::_('COM_JOOCM_USERDETAILSJOOMLA'); ?></legend>
					<table class="admintable" cellspacing="1"><tr>
						<td class="key">
							<label for="name"><?php echo JText::_('COM_JOOCM_NAME'); ?></label>
						</td><td>
							<input type="text" name="name" id="name" class="inputbox" size="40" value="<?php echo $user->get('name'); ?>" maxlength="255" />
						</td>
					</tr><tr>
						<td class="key">
							<label for="username"><?php echo JText::_('COM_JOOCM_USERNAME'); ?></label>
						</td><td>
							<input type="text" name="username" id="username" class="inputbox" size="40" value="<?php echo $user->get('username'); ?>" maxlength="150" />
						</td>
					</tr><tr>
						<td class="key">
							<label for="email"><?php echo JText::_('COM_JOOCM_EMAIL'); ?></label>
						</td><td>
							<input class="inputbox" type="text" name="email" id="email" size="40" value="<?php echo $user->get('email'); ?>" maxlength="100" />
						</td>
					</tr><tr>
						<td class="key">
							<label for="password"><?php echo JText::_('COM_JOOCM_NEWPASSWORD'); ?></label>
						</td><td><?php
						if(!$user->get('password')) : ?>
							<input class="inputbox disabled" type="password" name="password" id="password" size="40" value="" disabled="disabled" /><?php
						else : ?>
							<input class="inputbox" type="password" name="password" id="password" size="40" value="" maxlength="100" /><?php
						endif; ?>
						</td>
					</tr><tr>
						<td class="key">
							<label for="password2">
								<?php echo JText::_('COM_JOOCM_VERIFYPASSWORD'); ?>
							</label>
						</td><td><?php
						if(!$user->get('password')) : ?>
							<input class="inputbox disabled" type="password" name="password2" id="password2" size="40" value="" disabled="disabled" /><?php
						else : ?>
							<input class="inputbox" type="password" name="password2" id="password2" size="40" value="" maxlength="100" /><?php
						endif; ?>
						</td>
					</tr><?php
					if($user->get('id')) { ?>
					<tr>
						<td valign="top" class="key"><?php echo JText::_('COM_JOOCM_GROUP'); ?></td>
						<td><?php echo $user->get('usertype'); ?></td>
					</tr><?php
					} ?>
					<tr>
						<td class="key"><?php echo JText::_('COM_JOOCM_BLOCKUSER'); ?></td>
						<td><?php echo $lists['block']; ?></td>
					</tr><tr>
						<td class="key"><?php echo JText::_('COM_JOOCM_RECEIVESYSTEMEMAILS'); ?></td>
						<td><?php echo $lists['sendEmail']; ?></td>
					</tr><?php
					if($user->get('id')) { ?>
					<tr>
						<td class="key"><?php echo JText::_('COM_JOOCM_REGISTERDATE'); ?></td>
						<td><?php echo $user->get('registerDate'); ?></td>
					</tr><tr>
						<td class="key"><?php echo JText::_('COM_JOOCM_LASTVISITDATE'); ?></td>
						<td><?php echo $user->get('lastvisitDate'); ?></td>
					</tr><?php
					} ?>
					</table>
				</fieldset>			
				<fieldset class="adminform">
					<legend><?php echo JText::_('COM_JOOCM_PARAMETERS'); ?></legend>
					<table class="admintable"><tr>
						<td><?php
							$file = JOOCM_ADMINPARAMS.DS.'users_params.xml';
							$params = new JParameter($user->get('params'), $file);
							echo $params->render('params'); ?>
						</td>
					</tr></table>
				</fieldset>				
			</div>
			<div class="col width-50">
				<fieldset class="adminform">
					<legend><?php echo JText::_('COM_JOOCM_USERDETAILSJOOCM'); ?></legend>
					<table class="admintable" cellspacing="1"><tr>
						<td class="key">
							<label for="system_emails"><?php echo JText::_('COM_JOOCM_RECEIVESYSTEMEMAILS'); ?></label>
						</td>
						<td><?php echo $lists['system_emails']; ?></td>
					</tr><tr>
						<td class="key">
							<label for="agreed_terms"><?php echo JText::_('COM_JOOCM_AGREEDTERMS'); ?></label>
						</td>
						<td><?php echo $lists['agreed_terms']; ?></td>
					</tr><tr>
						<td class="key">
							<label for="hide"><?php echo JText::_('COM_JOOCM_HIDE'); ?></label>
						</td>
						<td><?php echo $lists['hide']; ?></td>
					</tr></table>
				</fieldset>
				<fieldset class="adminform">
					<legend><?php echo JText::_('COM_JOOCM_PREFERENCES'); ?></legend>
						<table class="admintable" cellspacing="1"><tr>
							<td class="key"><?php echo JText::_('COM_JOOCM_SHOWEMAIL'); ?></td>
							<td><?php echo $lists['show_email']; ?></td>
						</tr><tr>
							<td class="key"><?php echo JText::_('COM_JOOCM_SHOWONLINESTATE'); ?></td>
							<td><?php echo $lists['show_online_state']; ?></td>
						</tr><tr>
						<td class="key">
							<label for="time_format"><?php echo JText::_('COM_JOOCM_TIMEFORMAT'); ?></label>
						</td>
						<td><?php echo $lists['timeformats']; ?></td>
					</tr></table>
				</fieldset>
				<fieldset class="adminform">
					<legend><?php echo JText::_('COM_JOOCM_SIGNATURE'); ?></legend>
					<table class="admintable" cellspacing="1"><tr>
						<td class="key">
							<label for="signature">
								<?php echo JText::_('COM_JOOCM_SIGNATURE'); ?>
							</label>
						</td>
						<td><textarea name="signature" id="signature" rows="5" cols="50" class="inputbox"><?php echo $user->get('signature'); ?></textarea></td>
					</tr></table>
				</fieldset>	
			</div>			
			<div class="clr"></div>
			<div class="col100">
				<fieldset class="adminform">
					<legend><?php echo JText::_('COM_JOOCM_PROFILE'); ?></legend><?php
					ViewUser::_getProfileFieldSets($lists['fieldsets'], $lists['fields']); ?>
				</fieldset>
			</div>
			<input type="hidden" name="id" value="<?php echo $user->get('id'); ?>" />
			<input type="hidden" name="option" value="com_joocm" />
			<input type="hidden" name="task" value="" />
		</form><?php
	}
	
	function _getProfileFieldSets($fieldsets, $fields) {
		
		if (count($fieldsets)) {
			for ($i=0, $n=count($fieldsets); $i < $n; $i++) {
				$fieldset =& $fieldsets[$i]; ?>	
				<fieldset class="adminform">
					<legend><?php echo JText::_($fieldset->name); ?></legend>
					<table class="admintable"><?php
					for ($j=0, $m=count($fields); $j < $m; $j++) {
						$field 	=& $fields[$j];
						if ($fieldset->id == $field->id_profile_field_set) { ?>
						<tr>
							<td class="key">
								<label for="<?php echo $field->name; ?>">
									<?php echo JText::_($field->title); ?>
								</label>
							</td>
                            <td><?php echo $field->element; ?></td>
						</tr><?php
						}
					} ?>								
					</table>		
				</fieldset><?php
			}
		} else {
			echo JText::_('COM_JOOCM_MSGJOOCMPROFILESNOTACTIVE');
		}		
	}
}
?>