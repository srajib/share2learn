<?php
/**
 * @version $Id: usersync.php 206 2011-11-14 20:37:29Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!CM  directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!CM User Synchronization View
 *
 * @package Joo!CM
 */
class ViewUserSync {

	/**
	 * show user synchronization
	 */	
	function showUserSync(&$lists) {

		// initialize variables
		$document =& JFactory::getDocument();
		$document->addStyleSheet(JOOCM_ADMINCSS_LIVE.DL.'icon.css');
		
		$task	= JRequest::getVar('task'); ?>
		<div id="submenu-box">
			<div class="t"><div class="t"><div class="t"></div></div></div>			
			<div class="m">
				<ul id="submenu"><li>
					<?php $class = ($task == 'joocm_install_view') ? 'class="active"' : ''; ?>
					<a <?php echo $class; ?> href="index.php?option=com_joocm&task=joocm_install_view"><?php echo JText::_('COM_JOOCM_INSTALLATION'); ?></a>
				</li><li>
					<?php $class = ($task == 'joocm_usersync_view') ? 'class="active"' : ''; ?>
					<a <?php echo $class; ?> href="index.php?option=com_joocm&task=joocm_usersync_view"><?php echo JText::_('COM_JOOCM_USERSYNC'); ?></a>
				</li></ul>
				<div class="clr"></div>
			</div>
			<div class="b"><div class="b"><div class="b"></div></div></div>
		</div>
		<form action="index.php" method="post" name="adminForm" autocomplete="off">
			<div class="col width-60">
				<fieldset class="adminform">
					<legend><?php echo JText::_('COM_JOOCM_JOOMLAUSERGROUP'); ?></legend>
					<table class="admintable" cellspacing="1"><tr>
						<td class="key">
							<label for="role">
								<?php echo JText::_('COM_JOOCM_GROUP'); ?>
							</label>
						</td><td>
							<?php echo $lists['joomlagroup']; ?>
						</td>
					</tr></table>
				</fieldset>
				<fieldset class="adminform">
					<legend><?php echo JText::_('COM_JOOCM_USERDETAILSJOOCM'); ?></legend>
					<table class="admintable" cellspacing="1"><tr>
						<td class="key">
						</td><td class="key">
						</td><td class="key">
                        	<?php echo JText::_('COM_JOOCM_UPDATEEXISTINGUSERS'); ?>
                        </td>
					</tr><tr>
						<td class="key">
							<label for="system_emails">
								<?php echo JText::_('COM_JOOCM_RECEIVESYSTEMEMAILS'); ?>
							</label>
						</td><td>
							<?php echo $lists['system_emails']; ?>
						</td><td>
                        	<center><input type="checkbox" id="system_emails_update" name="update[]" value="system_emails" /></center>
                        </td>
					</tr><tr>
						<td class="key">
							<label for="agreed_terms">
								<?php echo JText::_('COM_JOOCM_AGREEDTERMS'); ?>
							</label>
						</td><td>
							<?php echo $lists['agreed_terms']; ?>
						</td><td>
                        	<center><input type="checkbox" id="agreed_terms_update" name="update[]" value="agreed_terms" /></center>
                        </td>
					</tr><tr>
						<td class="key">
							<label for="hide"><?php echo JText::_('COM_JOOCM_HIDE'); ?></label>
						</td><td>
							<?php echo $lists['hide']; ?>
						</td><td>
                        	<center><input type="checkbox" id="hide_update" name="update[]" value="hide" /></center>
                        </td>
					</tr><tr>
						<td class="key">
							<label for="show_email">
								<?php echo JText::_('COM_JOOCM_SHOWEMAIL'); ?>
							</label>
						</td><td>
							<?php echo $lists['show_email']; ?>
						</td><td>
                        	<center><input type="checkbox" id="show_email_update" name="update[]" value="show_email" /></center>
                        </td>
					</tr><tr>
						<td class="key">
							<label for="show_online_state">
								<?php echo JText::_('COM_JOOCM_SHOWONLINESTATE'); ?>
							</label>
						</td><td>
							<?php echo $lists['show_online_state']; ?>
						</td><td>
                        	<center><input type="checkbox" id="show_online_state_update" name="update[]" value="show_online_state" /></center>
                        </td>
					</tr><tr>
						<td class="key">
							<label for="time_format">
								<?php echo JText::_('COM_JOOCM_TIMEFORMAT'); ?>
							</label>
						</td><td>
							<?php echo $lists['time_format']; ?>
						</td><td>
                        	<center><input type="checkbox" id="time_format_update" name="update[]" value="time_format" /></center>
                        </td>
					</tr></table>
				</fieldset>		
			</div>
			<div class="clr"></div>
			<input type="hidden" name="option" value="com_joocm" />
			<input type="hidden" name="task" value="" />
		</form><?php
	}
}
?>