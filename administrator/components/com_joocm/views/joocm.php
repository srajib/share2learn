<?php
/**
 * @version $Id: joocm.php 206 2011-11-14 20:37:29Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!CM  directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!CM Panel View
 *
 * @package Joo!CM
 */
class ViewJoocm {

	/**
	 * show joocm panel
	 */
	function showControlPanel(&$rows) {
		
		// initialize variables	
		$document =& JFactory::getDocument();
		$document->addStyleSheet(JOOCM_ADMINCSS_LIVE.DL.'icon.css'); 
			
		$pane   =& JPane::getInstance('sliders'); ?>
		<table><tr>
			<td width="55%" valign="top">
				<div id="cpanel"><?php
				foreach ($rows as $row) {
					$link = 'index.php?option='.$row->com.$row->url;
					JoocmHTML::createIconButton($link, $row->com_icon, JText::_($row->name));
				} ?>
				</div>
			</td>
			<td width="45%" valign="top"><?php
				echo $pane->startPane("cm-pane");
				echo $pane->startPanel(JText::_('COM_JOOCM_WELCOME'), "welcome" ); ?>
				<table class="adminlist"><tr>
					<td>
						<?php echo JText::sprintf('COM_JOOCM_WELCOMETEXT', '<a href="http://www.joobb.org/community.html" target="_blank">http://www.joobb.org/community.html</a>', '<a href="http://www.joobb.org/documentation" target="_blank">http://www.joobb.org/documentation</a>'); ?>
					</td>
				</tr></table><?php
				echo $pane->endPanel();
				echo $pane->endPane(); ?>				
			</td>			
		</tr></table><?php
	}
}
?>