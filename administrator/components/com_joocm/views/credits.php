<?php
/**
 * @version $Id: credits.php 206 2011-11-14 20:37:29Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!CM  directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!CM Credits View
 *
 * @package Joo!CM
 */
class ViewCredits {

	/**
	 * shows credits
	 */	
	function showCredits() {

		$document =& JFactory::getDocument();
		$document->addStyleSheet(JOOCM_ADMINCSS_LIVE.DL.'icon.css'); ?>		
		<form action="index.php?option=com_joocm" method="post" name="adminForm">
			<table><tr align="center" valign="middle">
                <td align="left" valign="top" width="70%" style="font-size: 13px;">
                    <h1><strong><?php echo JText::_('COM_JOOCM_CREDITSWELCOME'); ?></strong></h1>
                    <h2><?php echo JText::_('COM_JOOCM_CREDITSWHATISJOOCM'); ?></h2>
                    <p><?php echo JText::_('COM_JOOCM_CREDITSWHATISJOOBBTEXT'); ?></p>
                    <h2><?php echo JText::_('COM_JOOCM_CREDITSLEADDEVELOPER'); ?></h2>
                    <ul><li>Robert Stemplewski</li></ul>
                    <h2><?php echo JText::_('COM_JOOCM_CREDITSDEVELOPERS'); ?></h2>
					<ul><li>Ramses aka Andy</li></ul>
                    <h2><?php echo JText::_('COM_JOOCM_CREDITSBETATESTER'); ?></h2>
                    <ul>
                        <li>Olaf Dryja</li>
                        <li>Jeff Hetrick</li>
                        <li>Jim Kass</li>
                        <li>Ramses aka Andy</li>
					</ul>
                    <h2><?php echo JText::_('COM_JOOCM_CREDITSDOCUMENTATIONTEAM'); ?></h2>
                    <ul>
                        <li>Olaf Dryja</li>
                        <li>Jeff Hetrick</li>
                        <li>Robert Stemplewski</li>
                    </ul>
                    <h2><?php echo JText::_('COM_JOOCM_CREDITSSPECIALTHANKS'); ?></h2>
                    <ul>
                        <li><?php echo JText::_('COM_JOOCM_CREDITSSPECIALTHANKS1'); ?> </li>
                        <li><?php echo JText::_('COM_JOOCM_CREDITSSPECIALTHANKS2'); ?> </li>															
                    </ul>
                </td>
                <td align="left" valign="top" width="30%" style="padding-left: 50px;">
                    <img src="<?php echo JOOCM_ADMINIMAGES_LIVE.DL.'install'.DL.'joocm_box.png'; ?>" border="1" alt="<?php echo JText::_('JOOCM_JOOCM'); ?>" />
                </td>
			</tr></table>
			<input type="hidden" name="option" value="com_joocm" />
			<input type="hidden" name="task" value="" />
		</form><?php
	}
}
?>