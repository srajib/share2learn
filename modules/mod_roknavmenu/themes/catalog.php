<?php
/**
 * @version   3.4 February 3, 2012
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
 
require_once(dirname(__FILE__).'/fusion/theme.php');
RokNavMenu::registerTheme(dirname(__FILE__).'/basic','basic', 'Default Basic', 'RokNavMenuBasicTheme');

require_once(dirname(__FILE__).'/basic/theme.php');
RokNavMenu::registerTheme(dirname(__FILE__).'/fusion','default_fusion', 'Default Fusion', 'RokNavMenuFusionTheme');