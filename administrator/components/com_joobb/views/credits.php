<?php
/**
 * @version $Id: credits.php 204 2011-11-13 18:27:41Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!BB Credits View
 *
 * @package Joo!BB
 */
class ViewCredits {

	/**
	 * shows credits
	 */	
	function showCredits() { ?>		
		<form action="index.php?option=com_joobb" method="post" name="adminForm">
			<table><tr align="center" valign="middle">
                <td align="left" valign="top" width="70%" style="font-size: 13px;">
                    <h1><strong><?php echo JText::_('COM_JOOBB_CREDITSWELCOME'); ?></strong></h1>
                    <h2><?php echo JText::_('COM_JOOBB_CREDITSWHATISJOOBB'); ?></h2>
                    <p><?php echo JText::_('COM_JOOBB_CREDITSWHATISJOOBBTEXT'); ?></p>
                    <h2><?php echo JText::_('COM_JOOBB_CREDITSLEADDEVELOPER'); ?></h2>
                    <ul><li>Robert Stemplewski</li></ul>
                    <h2><?php echo JText::_('COM_JOOBB_CREDITSDEVELOPERS'); ?></h2>
					<ul><li>Ramses aka Andy</li></ul>
                    <h2><?php echo JText::_('COM_JOOBB_CREDITSBETATESTER'); ?></h2>
                    <ul>
                        <li>Olaf Dryja</li>
                        <li>Jeff Hetrick</li>
                        <li>Jim Kass</li>
                        <li>Ramses aka Andy</li>
                    </ul>
                    <h2><?php echo JText::_('COM_JOOBB_CREDITSDOCUMENTATIONTEAM'); ?></h2>
                    <ul>
                        <li>Olaf Dryja</li>
                        <li>Jeff Hetrick</li>
                        <li>Robert Stemplewski</li>
                    </ul>
                    <h2><?php echo JText::_('COM_JOOBB_CREDITSSPECIALTHANKS'); ?></h2>
                    <ul>
                        <li><?php echo JText::_('COM_JOOBB_CREDITSSPECIALTHANKS1'); ?></li>
                        <li><?php echo JText::_('COM_JOOBB_CREDITSSPECIALTHANKS2'); ?></li>
                        <li><?php echo JText::_('COM_JOOBB_CREDITSSPECIALTHANKS3'); ?></li>															
                    </ul>
                </td>
                <td align="left" valign="top" width="30%" style="padding-left: 50px;">
                    <img src="<?php echo JOOBB_ADMINIMAGES_LIVE.DL.'install'.DL.'joobb_box.png'; ?>" border="1" alt="<?php echo JText::_('COM_JOOBB_JOOBB'); ?>" />
                </td>
			</tr></table>
			<input type="hidden" name="option" value="com_joobb" />
			<input type="hidden" name="task" value="" />
		</form><?php
	}
} ?>