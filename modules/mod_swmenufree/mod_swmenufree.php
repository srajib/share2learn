<?php

/**
 * swmenufree v6.0 for Joomla1.5
 * http://swmenupro.com
 * Copyright 2007 Sean White
 * */
//error_reporting (E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
defined('_JEXEC') or die('Restricted access');


global $my, $Itemid, $mainframe;

$absolute_path = JPATH_ROOT;
$live_site = $mainframe->isAdmin() ? $mainframe->getSiteURL() : JURI::base();
if (substr($live_site, (strlen($live_site) - 1), 1) == "/") {
    $live_site = substr($live_site, 0, (strlen($live_site) - 1));
}
$database = &JFactory::getDBO();
require_once($absolute_path . "/modules/mod_swmenufree/styles.php");
require_once($absolute_path . "/modules/mod_swmenufree/functions.php");

$do_menu = 1;
$template = @$params->get('template') ? strval($params->get('template')) : "All";
$language = @$params->get('language') ? strval($params->get('language')) : "All";
$component = @$params->get('component') ? strval($params->get('component')) : "All";

if ($template != "" && $template != "All") {
    if ($mainframe->getTemplate() != $template) {
        $do_menu = 0;
    }
}
if ($language != "" && $language != "All") {
    if ($lang != $language) {
        $do_menu = 0;
    }
}
if ($component != "" && $component != "All") {

    if (trim(JRequest::getVar('option', '')) != $component) {
        $do_menu = 0;
    }
}

if ($do_menu) {

    $menu = @$params->get('menutype') ? strval($params->get('menutype')) : "mainmenu";
    $id = @$params->get('moduleID') ? intval($params->get('moduleID')) : 0;
    $menustyle = @$params->get('menustyle') ? strval($params->get('menustyle')) : "popoutmenu";
    $parent_level = @$params->get('parent_level') ? intval($params->get('parent_level')) : 0;
    $levels = @$params->get('levels') ? intval($params->get('levels')) : 25;
    $parent_id = @$params->get('parentid') ? intval($params->get('parentid')) : 0;
    $active_menu = @$params->get('active_menu') ? intval($params->get('active_menu')) : 0;
    $hybrid = @$params->get('hybrid') ? intval($params->get('hybrid')) : 0;
    $editor_hack = @$params->get('editor_hack') ? intval($params->get('editor_hack')) : 0;
    $sub_indicator = @$params->get('sub_indicator') ? intval($params->get('sub_indicator')) : 0;
    $css_load = @$params->get('cssload') ? $params->get('cssload') : 0;
    $use_table = @$params->get('tables') ? $params->get('tables') : 0;
    $cache = @$params->get('cache') ? $params->get('cache') : 0;
    $cache_time = @$params->get('cache_time') ? $params->get('cache_time') : "1 hour";
    $selectbox_hack = @$params->get('selectbox_hack') ? intval($params->get('selectbox_hack')) : 0;
    $padding_hack = @$params->get('padding_hack') ? intval($params->get('padding_hack')) : 0;
    $overlay_hack = @$params->get('overlay_hack') ? intval($params->get('overlay_hack')) : 0;
    $auto_position = @$params->get('auto_position') ? intval($params->get('auto_position')) : 0;
    $show_shadow = @$params->get('show_shadow') ? intval($params->get('show_shadow')) : 0;
    $border_hack = @$params->get('border_hack') ? intval($params->get('border_hack')) : 0;

    $disable_jquery = @$params->get('disable_jquery') ? intval($params->get('disable_jquery')) : 0;
    $flash_hack = @$params->get('flash_hack') ? intval($params->get('flash_hack')) : 0;
    $my_task = trim(JRequest::getVar('task', ''));
    if (($my_task == "edit" || $my_task == "new") && $editor_hack) {
        $editor_hack = 0;
    } else {
        $editor_hack = 1;
    }

    if ($disable_jquery) {
        define('_swjquery_defined', 1);
    }
    if ($flash_hack) {
        $headtag = "<script type=\"text/javascript\" src=\"" . $live_site . "/modules/mod_swmenufree/fix_wmode2transparent_swf.js\"></script>\n";
        $GLOBALS['mainframe']->addCustomHeadTag($headtag);
    }
    $query = "SELECT * FROM #__swmenufree_config WHERE id = 1";
    $database->setQuery($query);
    $result = $database->loadObjectList();
    $swmenufree = array();
    while (list ($key, $val) = each($result[0])) {
        $swmenufree[$key] = $val;
    }
    $content = "\n<!--swMenuFree7.0_J1.5 " . $menustyle . " by http://www.swmenupro.com-->\n";

    $GLOBALS['mainframe']->addCustomHeadTag("\n<!-- start - swMenuFree javascript and CSS links -->\n");
    
    if(($menu=="virtuemart2"||$menu=="virtueprod2")&&$parent_id!=0){$parent_id=$parent_id+10000;}
    if(($menu=="virtuemart"||$menu=="virtueprod")&&$parent_id!=0){$parent_id=$parent_id+10000;}
    
    
   
        $corner = 1;
        $c_corner = stripos($swmenufree['corners'], "c_corner_style=none");
        $t_corner = stripos($swmenufree['corners'], "t_corner_style=none");
        $s_corner = stripos($swmenufree['corners'], "s_corner_style=none");
        if (($t_corner !== false) && ($s_corner !== false) && ($c_corner !== false)) {
            $corner = 0;
        }
         if (!defined('_swjquery_defined')) {
        if (($swmenufree['extra'] != "" && $swmenufree['extra'] != "none" && $swmenufree['extra'] != "1" && $swmenufree['extra'] != "0") || $overlay_hack || $corner) {
            $headtag = "<script type=\"text/javascript\" src=\"" . $live_site . "/modules/mod_swmenufree/jquery-1.6.min.js\"></script>\n";
            define('_swjquery_defined', 1);
            $GLOBALS['mainframe']->addCustomHeadTag($headtag);
        }
         }
        if ($corner && !defined('_swcorners_defined')) {
            $headtag = "<script type=\"text/javascript\" src=\"" . $live_site . "/modules/mod_swmenufree/jquery.corner.js\"></script>\n";
            //$headtag.= "<script type=\"text/javascript\" src=\"".$live_site."/modules/mod_swmenupro/jquery.jcorners.js\"></script>\n";
            define('_swcorners_defined', 1);
            $GLOBALS['mainframe']->addCustomHeadTag($headtag);
        }
    
    if ($swmenufree['top_ttf']) {
        $f = explode("\n", $swmenufree['top_ttf']);
        $f = explode("=", $f[0]);
        //echo $f[1];
        if (!defined('_swcufon_defined')) {
            $headtag = "<script type=\"text/javascript\" src=\"" . $live_site . "/modules/mod_swmenufree/cufon-yui.js\"></script>\n";
            define('_swcufon_defined', 1);
            $GLOBALS['mainframe']->addCustomHeadTag($headtag);
        }
        $headtag = "<script type=\"text/javascript\" src=\"" . $live_site . "/modules/mod_swmenufree/fonts/" . $f[1] . "\"></script>\n";
        $GLOBALS['mainframe']->addCustomHeadTag($headtag);
    }

    if ($swmenufree['sub_ttf']) {

        $f = explode("\n", $swmenufree['sub_ttf']);
        $f = explode("=", $f[0]);

        if (!defined('_swcufon_defined')) {
            $headtag = "<script type=\"text/javascript\" src=\"" . $live_site . "/modules/mod_swmenufree/cufon-yui.js\"></script>\n";
            define('_swcufon_defined', 1);
            $GLOBALS['mainframe']->addCustomHeadTag($headtag);
        }
        if ($swmenufree['sub_ttf'] != $swmenufree['top_ttf']) {
            $headtag = "<script type=\"text/javascript\" src=\"" . $live_site . "/modules/mod_swmenufree/fonts/" . $f[1] . "\"></script>\n";
            $GLOBALS['mainframe']->addCustomHeadTag($headtag);
        }
    }


    if ($menu && $id && $menustyle) {
        if ($css_load == 1) {
            $headtag = "<link type='text/css' href='" . $live_site . "/modules/mod_swmenufree/styles/menu.css' rel='stylesheet' />\n";
            $GLOBALS['mainframe']->addCustomHeadTag($headtag);
        }

        $ordered = swGetMenuFree($menu, $id, $hybrid, $cache, $cache_time, $use_table, $parent_id, $levels);
        if (count($ordered)) {

            if ($active_menu) {
                $active_menu = sw_getactiveFree($ordered);
            }
            $ordered = chainFree('ID', 'PARENT', 'ORDER', $ordered, $parent_id, $levels);
        }

        if (count($ordered) && ($swmenufree['orientation'] == 'horizontal/left')) {
            $topcount = count(chainFree('ID', 'PARENT', 'ORDER', $ordered, $parent_id, 1));
            for ($i = 0; $i < count($ordered); $i++) {
                $swmenu = $ordered[$i];
                if ($swmenu['indent'] == 0) {
                    $ordered[$i]['ORDER'] = $topcount;
                    $topcount--;
                }
            }
            $ordered = chainFree('ID', 'PARENT', 'ORDER', $ordered, $parent_id, $levels);
        }
        $sub_active = 0;
        if (count($ordered)) {
            if ($menustyle == "mygosumenu") {
                $content.= doGosuMenuFree($ordered, $swmenufree, $active_menu, $css_load, $selectbox_hack, $padding_hack, $overlay_hack, $border_hack, $auto_position);
            }
            if ($menustyle == "transmenu") {
                $content.= doTransMenuFree($ordered, $swmenufree, $active_menu, $sub_indicator, $parent_id, $css_load, $selectbox_hack, $show_shadow, $auto_position, $padding_hack, $overlay_hack, $border_hack);
            }

            if ($menustyle == "superfishmenu") {
                $content.= doSuperfishMenuFree($ordered, $swmenufree, $active_menu, $css_load, $selectbox_hack, $padding_hack, $border_hack, $overlay_hack);
            }
        }
    }
    $GLOBALS['mainframe']->addCustomHeadTag("\n<!-- end - swMenuFree javascript and CSS links -->\n");
    $content.="\n<!--End swMenuFree menu module-->\n";

    return $content;
}

function doGosuMenuFree($ordered, $swmenufree, $active_menu, $css_load, $selectbox_hack, $padding_hack, $overlay_hack, $border_hack, $auto_position) {
    $live_site = JURI::base();
    if (substr($live_site, (strlen($live_site) - 1), 1) == "/") {
        $live_site = substr($live_site, 0, (strlen($live_site) - 1));
    }
    $str = "";
    $headtag = "<script type=\"text/javascript\" src=\"" . $live_site . "/modules/mod_swmenufree/DropDownMenuX_Packed.js\"></script>\n";
    
    $GLOBALS['mainframe']->addCustomHeadTag($headtag);
    if (!$css_load) {
        if ((substr(swmenuGetBrowserFree(), 0, 5) != "MSIE6") && $padding_hack) {
            $swmenufree = fixPaddingFree($swmenufree);
        }
        $str.= "\n<style type='text/css'>\n";
        $str.= "<!--\n";
        $str.= gosuMenuStyleFree($swmenufree, $ordered, $border_hack);
        $str.= "\n-->\n";
        $str.= "</style>\n";
        $GLOBALS['mainframe']->addCustomHeadTag($str);
    }
    $str = GosuMenuFree($ordered, $swmenufree, $active_menu, $selectbox_hack, $auto_position, $overlay_hack);
    return $str;
}

function doTransMenuFree($ordered, $swmenufree, $active_menu, $sub_indicator, $parent_id, $css_load, $selectbox_hack, $show_shadow, $auto_position, $padding_hack, $overlay_hack, $border_hack) {
    $live_site = JURI::base();
    if (substr($live_site, (strlen($live_site) - 1), 1) == "/") {
        $live_site = substr($live_site, 0, (strlen($live_site) - 1));
    }
    $str = "";
    $headtag = "<script type=\"text/javascript\" src=\"" . $live_site . "/modules/mod_swmenufree/transmenu_Packed.js\"></script>\n";
    
    $GLOBALS['mainframe']->addCustomHeadTag($headtag);
    if (!$css_load) {
        if ((substr(swmenuGetBrowserFree(), 0, 5) != "MSIE6") && $padding_hack) {
            $swmenufree = fixPaddingFree($swmenufree);
        }
        $str.= "\n<style type='text/css'>\n";
        $str.= "<!--\n";
        $str.= transMenuStyleFree($swmenufree, $border_hack);
        $str.= "\n-->\n";
        $str.= "</style>\n";
        $GLOBALS['mainframe']->addCustomHeadTag($str);
    }
    $str = transMenuFree($ordered, $swmenufree, $active_menu, $sub_indicator, $parent_id, $selectbox_hack, $auto_position, $overlay_hack);
    return $str;
}

function doSuperfishMenuFree($ordered, $swmenufree, $active_menu, $css_load, $selectbox_hack, $padding_hack, $border_hack, $overlay_hack) {
    $live_site = JURI::base();
    if (substr($live_site, (strlen($live_site) - 1), 1) == "/") {
        $live_site = substr($live_site, 0, (strlen($live_site) - 1));
    }
    $str = "";
    //$show_shadow=1;
    if (!$css_load) {
        if ((substr(swmenuGetBrowserFree(), 0, 5) != "MSIE6") && $padding_hack) {
            $swmenufree = fixPaddingFree($swmenufree);
        }
        $str.= "\n<style type='text/css'>\n";
        $str.= "<!--\n";
        $str.= superfishMenuStyleFree($swmenufree, $border_hack);
        $str.= "\n-->\n";
        $str.= "</style>\n";
        $GLOBALS['mainframe']->addCustomHeadTag($str);
    }

    $headtag = "";

    if (!defined('_swjquery_defined')) {
        $headtag.= "<script type=\"text/javascript\" src=\"" . $live_site . "/modules/mod_swmenufree/jquery-1.6.min.js\"></script>\n";
        define('_swjquery_defined', 1);
    }
    //$headtag.= "<script type=\"text/javascript\" src=\"".$live_site."/modules/mod_swmenufree/jquery-1.2.6.pack.js\"></script>\n";
    //$headtag.= "<script type=\"text/javascript\" src=\"".$live_site."/modules/mod_swmenufree/jquery.topzindex.min.js\"></script>\n";
    $headtag.= "<script type=\"text/javascript\" src=\"" . $live_site . "/modules/mod_swmenufree/hoverIntent.js\"></script>\n";
    $headtag.= "<script type=\"text/javascript\" src=\"" . $live_site . "/modules/mod_swmenufree/superfish.js\"></script>\n";
    $headtag.= "<script type=\"text/javascript\" src=\"" . $live_site . "/modules/mod_swmenufree/supersubs.js\"></script>\n";

    //if (!defined( '_swshadow_defined' )&&$show_shadow){
    //$headtag.= "<script type=\"text/javascript\" src=\"".$live_site."/modules/mod_swmenupro/jquery.dropshadow.js\"></script>\n";
    //$headtag.= "<script type=\"text/javascript\" src=\"".$live_site."/modules/mod_swmenupro/shadedborder.js\"></script>\n";
    //define( '_swshadow_defined', 1 );
    //}
    $GLOBALS['mainframe']->addCustomHeadTag($headtag);

    $str = SuperfishMenuFree($ordered, $swmenufree, $active_menu, $selectbox_hack, $overlay_hack);
    return $str;
}
?>



