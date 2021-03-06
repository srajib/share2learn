<?php defined( 'KOOWA' ) or die( 'Restricted access' );
/**
 * @version		$Id: listvalues.php 794 2011-01-10 18:44:32Z stian $
 * @category	NinjaForge Plugin Manager
 * @copyright	Copyright (C) 2007 - 2011 NinjaForge. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://ninjaforge.com
 */

/**
 * Install button class for a toolbar
 * 
 * @author		Stian Didriksen <stian@ninjaforge.com>
 * @category	Napi
 * @package		Napi_Toolbar
 * @subpackage	Button
 */
class ComNinjaToolbarButtonListvalues extends NToolbarButtonLink
{
	public function __construct(KConfig $options)
	{
		$options->text	= 'List Values';
		parent::__construct($options);
	}
	
	public function getLink()
	{
		$query['view']	= 'listvalues';
		return parent::getLink($query);
	}
	public function render()
	{
		return parent::render();
	}
}