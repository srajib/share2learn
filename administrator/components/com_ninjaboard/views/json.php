<?php defined( 'KOOWA' ) or die( 'Restricted access' );
/**
 * @category	Ninjaboard
 * @copyright	Copyright (C) 2007 - 2011 NinjaForge. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://ninjaforge.com
 */

class ComNinjaboardViewJson extends ComNinjaViewJson
{
	public function display()
	{
		//For debugging
		//$this->_document->setMimeEncoding('text/html');
		
		return parent::display();
	}
}