<?php
/**
 * @version   3.4 February 3, 2012
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

require_once(dirname(__FILE__) . "/librokmenu/includes.php");
require_once(dirname(__FILE__) . "/helper.php");

if (!class_exists('RokNavMenu')) {
    class RokNavMenu extends RokMenu
    {
        static $themes = array();
        static $current_template;

        public function __construct($args)
        {
            self::setFrontSideTemplate();
            self::loadCatalogs();
            parent::__construct($args);
        }

        protected function getProvider()
        {
            require_once(dirname(__FILE__) . '/providers/RokMenuProviderJoomla.php');
            return new RokMenuProviderJoomla($this->args);
        }

        protected function getRenderer()
        {
            // if its a registered theme its a 2x theme
            if (array_key_exists('theme', $this->args) && array_key_exists($this->args['theme'], self::$themes)) {
                $themeinfo = self::$themes[$this->args['theme']];
                $themeclass = $themeinfo['class'];

                $renderer = new RokNavMenu2XRenderer();

                $theme = new $themeclass();
                $renderer->setTheme($theme);
            }
            else {
                // its a 1x theme
                $renderer = new RokNavMenu1XRenderer();
            }
            return $renderer;
        }

        public function render()
        {
            $this->renderHeader();
            echo $this->renderMenu();
        }

        public static function registerTheme($path, $name, $fullname, $themeClass)
        {
            $theme = array('name' => $name, 'fullname' => $fullname, 'path' => $path, 'class' => $themeClass);
            self::$themes[$name] = $theme;
        }

        public static function loadCatalogs(){
            if (empty(self::$themes)){
                // load the module themes catalog
                require_once(dirname(__FILE__).'/../themes/catalog.php');
                //load the templates themes
                @include_once(JPATH_ROOT.'/templates/'.self::$current_template."/html/mod_roknavmenu/themes/catalog.php");
            }
        }

        public static function setFrontSideTemplate()
        {
            $current_site =& JFactory::getApplication();
            if (!$current_site->isAdmin()){
                $app = &JApplication::getInstance('site', array(), 'J');
                self::$current_template = $app->getTemplate();
            }
            else {
                $db =& JFactory::getDBO();
                // Get the current default template
                $query = ' SELECT template '
                         . ' FROM #__templates_menu '
                         . ' WHERE client_id = 0 '
                         . ' AND menuid = 0 ';
                $db->setQuery($query);
                $defaultemplate = $db->loadResult();
                self::$current_template = $defaultemplate;
            }
        }
    }
}
