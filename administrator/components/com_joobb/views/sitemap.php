<?php
/**
 * @version $Id: sitemap.php 204 2011-11-13 18:27:41Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!BB Sitemap View
 *
 * @package Joo!BB
 */
class ViewSitemap {

	/**
	 * show sitemap
	 */	
	function showSitemap(&$lists) {

		// initialize variables
		$task	= JRequest::getVar('task'); ?>
		<div id="submenu-box">
			<div class="t"><div class="t"><div class="t"></div></div></div>			
			<div class="m">
				<ul id="submenu"><li>
					<?php $class = ($task == 'joobb_install_view') ? 'class="active"' : ''; ?>
					<a <?php echo $class; ?> href="index.php?option=com_joobb&task=joobb_install_view"><?php echo JText::_('COM_JOOBB_INSTALLATION'); ?></a>
				</li><li>
					<?php $class = ($task == 'joobb_usersync_view') ? 'class="active"' : ''; ?>
					<a <?php echo $class; ?> href="index.php?option=com_joobb&task=joobb_usersync_view"><?php echo JText::_('COM_JOOBB_USERSYNC'); ?></a>
				</li><li>
					<?php $class = ($task == 'joobb_sitemap_view') ? 'class="active"' : ''; ?>
					<a <?php echo $class; ?> href="index.php?option=com_joobb&task=joobb_sitemap_view"><?php echo JText::_('COM_JOOBB_SITEMAP'); ?></a>
				</li></ul>
				<div class="clr"></div>
			</div>
			<div class="b"><div class="b"><div class="b"></div></div></div>
		</div>
		<form action="index.php" method="post" name="adminForm" autocomplete="off">
			<div class="col width-50">
				<fieldset class="adminform">
					<legend><?php echo JText::_('COM_JOOBB_SITEMAP'); ?></legend>
					<table class="admintable" cellspacing="1"><tr>
						<td class="key">
							<label for="filename">
								<?php echo JText::_('COM_JOOBB_SITEMAPFILENAME'); ?>
							</label>
						</td><td>
							<input type="text" name="filename" id="filename" class="inputbox" size="50" value="joobb_sitemap.xml" maxlength="255" />
						</td>
					</tr><tr>
						<td class="key">
							<label for="role">
								<?php echo JText::_('COM_JOOBB_PRIORITY'); ?>
							</label>
						</td><td>
							<?php echo $lists['priority']; ?>
						</td>
					</tr><tr>
						<td class="key">
							<label for="changefreq">
								<?php echo JText::_('COM_JOOBB_CHANGEFREQUENCY'); ?>
							</label>
						</td><td>
							<?php echo $lists['changefreq']; ?>
						</td>
					</tr><tr>
						<td class="key">
							<label for="limit">
								<?php echo JText::_('COM_JOOBB_LIMIT'); ?>
							</label>
						</td><td>
							<input type="text" name="limit" id="limit" class="inputbox" size="50" value="50000" maxlength="11" />
						</td>
					</tr></table>
				</fieldset>	
			</div>
			<div class="clr"></div>
			<input type="hidden" name="option" value="com_joobb" />
			<input type="hidden" name="task" value="" />
		</form><?php
	}
} ?>