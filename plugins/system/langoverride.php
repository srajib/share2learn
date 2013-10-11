<?php
/**
 * Language Override Plugin 
 * Copyright (C) 2010 GWE Systems Ltd, All rights reserved.
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307,USA.
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * -----------------------------------------------------------------------------
 *
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );


class plgSystemLangoverride extends JPlugin{

	function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
	}
	
	function onAfterInitialise()
	{	
		// preload the menu for Joomfish's sake
		//$app = JFactory::getApplication();
		//$menu = $app->getMenu();
		$lang =& JFactory::getLanguage();
		$lang = new GLanguage($lang->getTag());

		if ($this->params->get("overridecss",0)){
			$document	= &JFactory::getDocument();
			if (is_a($document ,"JDocumentHTML")){

				$options = array (
					'charset'	=> 'utf-8',
					'lineend'	=> 'unix',
					'tab'		=> '  ',
					'language'	=> $lang->getTag(),
					'direction'	=> $lang->isRTL() ? 'rtl' : 'ltr'
				);

				$document = new GDocument($options);
			}
		}
	}
}


class GLanguage extends JLanguage
{
	function load( $extension = 'joomla', $basePath = JPATH_BASE, $lang = null, $reload = false ) {
		$presult = parent::load($extension , $basePath, $lang , $reload);

		if (!JFactory::getApplication()->isAdmin()){
			// if joomfish exists but we have not set the database handler yet then skip this.
			if (class_exists("plgSystemJFDatabase", false)) {
				$db =  JFactory::getDBO();
				if (!is_a($db, "JFDatabase")){
					return;
				}
			}
			$menu = JSite::getMenu();
			$item = $menu->getActive();
			if (!$item) return;
		}
		
		static $template;
		if (!isset($template)){
			// Load template entries for the active menuid and the default template
			$db =& JFactory::getDBO();
			$query = 'SELECT template'
				. ' FROM #__templates_menu'
				. ' WHERE client_id = 0 AND menuid = 0'
				. ' ORDER BY menuid DESC'
				;
			$db->setQuery($query, 0, 1);
			$template = $db->loadResult();
			// I can't do this since it will force the site always to use the default template!!
			//	$template = JFactory::getApplication()->getTemplate();
		}
		$result = false;
		if ($presult){
			if ($basePath==JPATH_SITE || $basePath==JPATH_ADMINISTRATOR){
				$result = parent::load($extension , JPATH_SITE.DS."templates".DS.$template, $lang , $reload);
			}
		}
		return $presult || $result;
	}


	/**
	* Translate function, mimics the php gettext (alias _) function
	*
	 *  Override to handle Joomla 1.6 language files and strip out the "_QQ_" and replace with "
	*/
	function _($string, $jsSafe = false)
	{
		//$key = str_replace( ' ', '_', strtoupper( trim( $string ) ) );echo '<br />'.$key;
		$key = strtoupper($string);
		$key = substr($key, 0, 1) == '_' ? substr($key, 1) : $key;

		if (isset ($this->_strings[$key]))
		{
			$string = $this->_debug ? "&bull;".$this->_strings[$key]."&bull;" : $this->_strings[$key];

			// GWE MOD!
			$string = str_replace('"_QQ_"', '"', $string);

			// Store debug information
			if ( $this->_debug )
			{
				$caller = $this->_getCallerInfo();

				if ( ! array_key_exists($key, $this->_used ) ) {
					$this->_used[$key] = array();
				}

				$this->_used[$key][] = $caller;
			}

		}
		else
		{
			if (defined($string))
			{
				$string = $this->_debug ? '!!'.constant($string).'!!' : constant($string);

				// Store debug information
				if ( $this->_debug )
				{
					$caller = $this->_getCallerInfo();

					if ( ! array_key_exists($key, $this->_used ) ) {
						$this->_used[$key] = array();
					}

					$this->_used[$key][] = $caller;
				}
			}
			else
			{
				if ($this->_debug)
				{
					$caller	= $this->_getCallerInfo();
					$caller['string'] = $string;

					if ( ! array_key_exists($key, $this->_orphans ) ) {
						$this->_orphans[$key] = array();
					}

					$this->_orphans[$key][] = $caller;

					$string = '??'.$string.'??';
				}
			}
		}

		if ($jsSafe) {
			$string = addslashes($string);
		}

		return $string;
	}


}

include_once(JPATH_SITE."/libraries/joomla/document/document.php");
include_once(JPATH_SITE."/libraries/joomla/document/html/html.php");
class GDocument extends JDocumentHTML
{
	/*
	function addScript($url, $type="text/javascript") {
		$this->_scripts[$url] = $type;
	}
	*/
	
	function addStyleSheet($url, $type = 'text/css', $media = null, $attribs = array())
	{
		// always load the original
		$this->_styleSheets[$url]['mime']		= $type;
		$this->_styleSheets[$url]['media']		= $media;
		$this->_styleSheets[$url]['attribs']	= $attribs;

		if (strpos($url,  JURI::root(true).'/')===0){

			$template = JFactory::getApplication()->getTemplate();
			$cssfile = basename($url);
			 $cssfile = JPATH_SITE.DS."templates".DS.$template.DS.'css'.DS.$cssfile;
			 if (file_exists($cssfile)){
				$url = JURI::root(true)."/templates/$template/css/".basename($url);
				$this->_styleSheets[$url]['mime']		= $type;
				$this->_styleSheets[$url]['media']		= $media;
				$this->_styleSheets[$url]['attribs']	= $attribs;
			 }
		}
	}

}
