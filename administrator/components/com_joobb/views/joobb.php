<?php
/**
 * @version $Id: joobb.php 204 2011-11-13 18:27:41Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!BB Panel View
 *
 * @package Joo!BB
 */
class ViewJoobb {

	/**
	 * show joobb panel
	 */
	function showControlPanel( &$rows ) {
		
		// initialize variables
		$pane =& JPane::getInstance('sliders'); ?>
		<table><tr>
			<td width="55%" valign="top">
				<div id="cpanel"><?php
				$link = 'index.php?option=com_joobb&task=joobb_forum_view';
				ViewJoobb::createIconButton($link, 'icon-48-board.png', JText::_('COM_JOOBB_BOARD'));
			
				$link = 'index.php?option=com_joobb&task=joobb_config_view';
				ViewJoobb::createIconButton($link, 'icon-48-configuration.png', JText::_('COM_JOOBB_CONFIG'));

				$link = 'index.php?option=com_joobb&task=joobb_template_view';
				ViewJoobb::createIconButton($link, 'icon-48-template.png', JText::_('COM_JOOBB_TEMPLATES'));
																																												
				$link = 'index.php?option=com_joobb&task=joobb_user_view';
				ViewJoobb::createIconButton($link, 'icon-48-joobbuser.png', JText::_('COM_JOOBB_USERS') );
							
				$link = 'index.php?option=com_joobb&task=joobb_group_view';
				ViewJoobb::createIconButton($link, 'icon-48-group.png', JText::_('COM_JOOBB_GROUPS') );

				$link = 'index.php?option=com_joobb&task=joobb_rank_view';
				ViewJoobb::createIconButton($link, 'icon-48-rank.png', JText::_('COM_JOOBB_RANKS') );

				$link = 'index.php?option=com_joobb&task=joobb_install_view';
				ViewJoobb::createIconButton($link, 'icon-48-joobbtools.png', JText::_('COM_JOOBB_TOOLS') );
																			
				$link = 'index.php?option=com_joobb&task=joobb_credits_view';
				ViewJoobb::createIconButton($link, 'icon-48-credits.png', JText::_('COM_JOOBB_CREDITS') ); ?>
			</div>
			</td>
			<td width="45%" valign="top"><?php
				echo $pane->startPane("bb-pane");
				echo $pane->startPanel(JText::_('COM_JOOBB_WELCOME'), "welcome" ); ?>
				<table class="adminlist"><tr>
					<td>
						<?php echo JText::sprintf('COM_JOOBB_WELCOMETEXT', '<a href="http://www.joobb.org/community.html" target="_blank">http://www.joobb.org/community.html</a>', '<a href="http://www.joobb.org/documentation" target="_blank">http://www.joobb.org/documentation</a>'); ?>
					</td>
				</tr></table><?php
				echo $pane->endPanel();
				echo $pane->startPanel(JText::_('COM_JOOBB_BOARDSTATISTIC'), "board" ); ?>
				<table class="adminlist" cellspacing="1">
				<tbody><?php
				$k = 0;
				for ( $i=0, $n=count( $rows ); $i < $n; $i++ ) {
					$row 	=& $rows[$i]; ?>
					<tr>
						<td width="50%"><?php echo $row->description; ?></td>																																																							
						<td width="50%"><?php echo $row->value; ?></td>
					</tr><?php
					$k = 1 - $k;
				} ?>																
				</tbody>					
				</table><?php	
				echo $pane->endPanel();
				echo $pane->endPane(); ?>				
			</td>			
		</tr></table><?php
	}

	/**
	 * create icon button
	 */  	
	function createIconButton($link, $image, $text) {
		$config =& JFactory::getConfig();
		
		$image = JOOBB_ADMINIMAGES_LIVE.DL.'header'.DL.$image; ?>
		<div style="float:left;">
			<div class="icon">
				<a href="<?php echo $link; ?>">
					<img src="<?php echo $image; ?>" alt="<?php echo $text; ?>" align="top" border="0" />
					<span><?php echo $text; ?></span>
				</a>
			</div>
		</div><?php
	}
} ?>