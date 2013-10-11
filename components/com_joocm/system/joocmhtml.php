<?php
/**
 * @version $Id: joocmhtml.php 203 2011-10-26 15:45:59Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2009 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!CM directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!CM HTML
 *
 * @package Joo!CM
 */
class JoocmHTML
{

	/**
	 * joocm HTML
	 */	
	function JoocmHTML() {
	}
	
	function _($html) {
		return htmlspecialchars($html);
	}
	
	function stripTags($html) {
		return strip_tags($html);
	}
	
	/**
	 * create icon button
	 */  	
	function createIconButton($link, $image, $text) {
		$image = JURI::root().DL.$image; ?>
		<div class="icon" style="float:left;">
			<a href="<?php echo $link; ?>">
				<img src="<?php echo $image; ?>" alt="<?php echo $text; ?>"  title="<?php echo $text; ?>" />
				<span><?php echo $text; ?></span>
			</a>
		</div><?php
	}	
}
?>