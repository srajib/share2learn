<?php
/**
 * @version $Id: install.php 206 2011-11-14 20:37:29Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!CM  directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!CM Install View
 *
 * @package Joo!CM
 */
class ViewInstall {

	/**
	 * show install
	 */	
	function showInstall(&$updateRows, &$rows, &$lists) {

		JHTML::_('behavior.tooltip');
		
		// initialize variables
		$user	=& JFactory::getUser();
				
		$document =& JFactory::getDocument();
		$document->addStyleSheet(JOOCM_ADMINCSS_LIVE.DL.'icon.css');
		
		$task = JRequest::getVar('task'); ?>
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
			<div class="col100">
				<fieldset class="adminform">
					<div class="col width-60">
						<fieldset class="adminform">
							<legend><?php echo JText::_('COM_JOOCM_VERSIONINFORMATION'); ?></legend>		
							<table class="admintable" cellspacing="1"><tr>
								<td class="key">
									<label for="name">
										<?php echo JText::_('COM_JOOCM_INSTALLEDVERSION'); ?>
									</label>
								</td>
								<td><?php echo $lists['version']; ?></td>
							</tr><tr>
								<td class="key">
									<label for="name">
										<?php echo JText::_('COM_JOOCM_AVAILABLEVERSION'); ?>
									</label>
								</td>
								<td><?php echo $lists['available_version']; ?></td>
							</tr></table>
							<center>
							<table border="0" cellpadding="20" cellspacing="20"><tr>
								<td align="center" valign="middle">
									<a href="http://www.joobb.org/downloads.html" target="_blank">
										<img src="<?php echo JOOCM_ADMINIMAGES_LIVE.DL.'install'.DL.'download.png'; ?>" alt="<?php echo JText::_('COM_JOOCM_IMGALTDOWNLOAD'); ?>" title="<?php echo JText::_('COM_JOOCM_IMGALTDOWNLOAD'); ?>" />
									</a>
								</td>
							</tr></table>
							</center><?php
							if (count($updateRows)) { ?>
							<h2><?php echo JText::_('COM_JOOCM_INSTALLHISTORY'); ?></h2>
							<table class="adminlist" cellspacing="1">
							<thead><tr>
								<th nowrap="nowrap" width="5"><?php echo JText::_('Num'); ?></th>
								<th width="30%"><?php echo JText::_('COM_JOOCM_VERSION'); ?></th>
								<th width="35%"><?php echo JText::_('COM_JOOCM_INSTALLTIME'); ?></th>
								<th width="30%"><?php echo JText::_('COM_JOOCM_ACTION'); ?></th>			
							</tr></thead>
							<tbody><?php
							$k = 0;
							for ($i=0, $n=count($updateRows); $i < $n; $i++) {
								$updateRow =& $updateRows[$i]; ?>
								<tr class="<?php echo "row$k"; ?>">
									<td><?php echo $i+1 ?></td>
									<td><?php echo $updateRow->version; ?></td>
									<td><?php echo JoocmHelper::Date($updateRow->date_install); ?></td>
									<td><?php echo $updateRow->status == 1 ? JText::_('COM_JOOCM_INSTALL') : JText::_('COM_JOOCM_UPDATE'); ?></td>
								</tr><?php
								$k = 1 - $k;
							} ?>
							</tbody>
							</table><?php
							} ?>
						</fieldset>
						<fieldset class="adminform">
							<legend><?php echo JText::_('COM_JOOCM_DATABASEINFORMATION'); ?></legend><?php
							if (count($rows)) { ?>
							<h2><?php echo JText::_('COM_JOOCM_INSTALLEDTABLES'); ?></h2>
							<table class="adminlist" cellspacing="1">
							<thead><tr>
								<th nowrap="nowrap" width="5"><?php echo JText::_('Num'); ?></th>
								<th width="30%"><?php echo JText::_('COM_JOOCM_NAME'); ?></th>
								<th width="30%"><?php echo JText::_('COM_JOOCM_CREATETIME'); ?></th>
								<th width="30%"><?php echo JText::_('COM_JOOCM_UPDATETIME'); ?></th>
								<th width="5%"><?php echo JText::_('COM_JOOCM_SIZE'); ?></th>			
							</tr></thead>
							<tbody><?php
							$k = 0;
							for ($i=0, $n=count($rows); $i < $n; $i++) {
								$row =& $rows[$i]; ?>
								<tr class="<?php echo "row$k"; ?>">
									<td><?php echo $i+1 ?></td>
									<td><?php echo $row->Name; ?></td>
									<td><?php echo JoocmHelper::Date($row->Create_time); ?></td>
									<td><?php echo JoocmHelper::Date($row->Update_time); ?></td>
									<td><?php echo $row->Data_length; ?></td>
								</tr><?php
								$k = 1 - $k;
							} ?>
							</tbody>
							</table>
							<h2><?php echo JText::_('COM_JOOCM_WARNING'); ?></h2>
							<p><?php echo JText::_('COM_JOOCM_INSTALLWARNINGINFO'); ?></p><?php
							} else { ?>
							<h2><?php echo JText::_('COM_JOOCM_WARNING'); ?></h2>
							<p><?php echo JText::_('COM_JOOCM_MSGNODBINSTALLED'); ?></p><?php
							} ?>
							<center>
							<table border="0" cellpadding="20" cellspacing="20"><tr>
								<td align="center" valign="middle">
									<a href="index.php?option=com_joocm&task=joocm_install_reinstall">
										<img src="<?php echo JOOCM_ADMINIMAGES_LIVE.DL.'install'.DL.'reinstall.png'; ?>" alt="<?php echo JText::_('COM_JOOCM_IMGALTREINSTALL'); ?>" title="<?php echo JText::_('COM_JOOCM_IMGALTREINSTALL'); ?>" />
									</a>
								</td>
							</tr></table>
							</center>
						</fieldset>
					</div>
					<div align="right">
						<img src="<?php echo JOOCM_ADMINIMAGES_LIVE.DL.'install'.DL.'joocm_box.png'; ?>" alt="<?php echo JText::_('COM_JOOCM_JOOCM'); ?>" title="<?php echo JText::_('COM_JOOCM_JOOCM'); ?>" />
					</div>
				</fieldset>		
			</div>
			<div class="clr"></div>
			<input type="hidden" name="option" value="com_joocm" />
			<input type="hidden" name="task" value="" />
		</form><?php
	}
} ?>