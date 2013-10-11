<?php
/**
 * swmenufree v5.0
 * http://swonline.biz
 * Copyright 2006 Sean White
 * */
//error_reporting (E_ERROR | E_WARNING | E_PARSE | E_NOTICE); 
defined('_JEXEC') or die('Restricted access');
$absolute_path = JPATH_ROOT;
$config = &JFactory::getConfig();
$database = &JFactory::getDBO();
require_once($absolute_path . "/modules/mod_swmenufree/styles.php");
require_once($absolute_path . "/modules/mod_swmenufree/functions.php");

$css_load = 0;
$preview = 0;
$id = 0;
$q = explode("&", $_SERVER["QUERY_STRING"]);
foreach ($q as $qi) {
    if ($qi != "") {
        $qa = explode("=", $qi);
        list ($key, $val) = $qa;
        if ($val)
            $$key = urldecode($val);
    }
}

reset($_POST);
while (list ($key, $val) = each($_POST)) {
    if ($val)
        $$key = $val;
    $swmenufree[$key] = $val;
}
$id = @$mid ? $mid : $id;
$row = & JTable::getInstance('module');
// load the row from the db table
$row->load($id);

//echo $preview;
if ($preview == 1) {
    $id = JRequest::getVar('mid', 0);


    $query = "SELECT * FROM #__swmenufree_config WHERE id = 1";
    $database->setQuery($query);
    $result = $database->loadObjectList();

    while (list ($key, $val) = each($result[0])) {
        $swmenufree[$key] = $val;
    }
    //echo $id;

    $registry = new JRegistry();
    $registry->loadINI($row->params);
    $params = $registry->toObject();
    //$params = mosParseParams( $row->params );
    $menu = @$params->menutype ? $params->menutype : 'mainmenu';
    $moduleID = @$params->moduleID;
    $menustyle = @$params->menustyle;
    $parent_id = @$params->parentid ? $params->parentid : 0;
    $hybrid = @$params->hybrid ? $params->hybrid : 0;
    $active_menu = @$params->active_menu ? $params->active_menu : 0;
    $parent_level = @$params->parent_level ? $params->parent_level : 0;
    $levels = @$params->levels ? $params->levels : 0;
    $sub_sub_indicator = @$params->sub_sub_indicator ? $params->sub_sub_indicator : 0;
    $top_sub_indicator = @$params->top_sub_indicator ? $params->top_sub_indicator : 0;
    $complete_shadow = @$params->complete_shadow ? $params->complete_shadow : 0;
    $top_shadow = @$params->top_shadow ? $params->top_shadow : 0;
    $sub_shadow = @$params->sub_shadow ? $params->sub_shadow : 0;
    $selectbox_hack = @$params->selectbox_hack ? $params->selectbox_hack : 0;
    $auto_position = @$params->auto_position ? $params->auto_position : 0;
    // $auto_position = @$params->auto_position ?  $params->auto_position :  0;
    $padding_hack = @$params->padding_hack ? $params->padding_hack : 0;
    $border_hack = @$params->border_hack ? $params->border_hack : 0;
    //$css_load = JRequest::getVar( 'cssload', 0 );
    $preview_background = JRequest::getVar('preview_background', '#FFFFFF');
} else if ($preview == 2) {
    $query = "SELECT * FROM #__swmenu_config WHERE id = " . $id;
    $database->setQuery($query);
    $result = $database->loadObjectList();

    while (list ($key, $val) = each($result[0])) {
        $swmenufree[$key] = $val;
    }
    $registry = new JRegistry();
    $registry->loadINI($row->params);
    $params = $registry->toObject();
    // $params = mosParseParams( $row->params );
    $menu = @$params->menutype ? $params->menutype : 'mainmenu';
    $moduleID = @$params->moduleID;
    $menustyle = @$params->menustyle ? $params->menustyle : 'popoutmenu';
    $parent_id = @$params->parentid ? $params->parentid : 0;
    $hybrid = @$params->hybrid ? $params->hybrid : 0;
    $active_menu = @$params->active_menu ? $params->active_menu : 0;
    $parent_level = @$params->parent_level ? $params->parent_level : 0;
    $levels = @$params->levels ? $params->levels : 0;
    $sub_sub_indicator = @$params->sub_sub_indicator ? $params->sub_sub_indicator : 0;
    $top_sub_indicator = @$params->top_sub_indicator ? $params->top_sub_indicator : 0;
    $complete_shadow = @$params->complete_shadow ? $params->complete_shadow : 0;
    $top_shadow = @$params->top_shadow ? $params->top_shadow : 0;
    $sub_shadow = @$params->sub_shadow ? $params->sub_shadow : 0;
    $css_load = @$params->cssload ? $params->cssload : 0;
    $selectbox_hack = @$params->selectbox_hack ? $params->selectbox_hack : 0;
    $auto_position = @$params->auto_position ? $params->auto_position : 0;
    $padding_hack = @$params->padding_hack ? $params->padding_hack : 0;
    $border_hack = @$params->border_hack ? $params->border_hack : 0;
    $preview_background = JRequest::getVar('preview_background', '#FFFFFF');
} else {

    $menu = JRequest::getVar('menutype', "mainmenu");
    $parent_id = JRequest::getVar('parentid', 0);
    $levels = JRequest::getVar('levels', 0);

    $moduleID = JRequest::getVar('id', 0);
    $hybrid = JRequest::getVar('hybrid', 0);
    $active_menu = JRequest::getVar('active_menu', 0);
    $parent_level = JRequest::getVar('parent_level', 0);
    $tables = JRequest::getVar('tables', 0);
    $selectbox_hack = JRequest::getVar('selectbox_hack', 0);
    $id = $id ? $id : 1000000;
    $sub_sub_indicator = JRequest::getVar('sub_sub_indicator', 0);
    $top_sub_indicator = JRequest::getVar('top_sub_indicator', 0);
    $sub_indicator_align = JRequest::getVar('sub_indicator_align', 'left');
    $sub_indicator_top = JRequest::getVar('sub_indicator_top', 0);
    $sub_indicator_left = JRequest::getVar('sub_indicator_left', 0);
    $complete_shadow = JRequest::getVar('complete_shadow', 0);
    $top_shadow = JRequest::getVar('top_shadow', 0);
    $sub_shadow = JRequest::getVar('sub_shadow', 0);
    $auto_position = JRequest::getVar('auto_position', 0);
    $padding_hack = JRequest::getVar('padding_hack', 0);
    $border_hack = JRequest::getVar('border_hack', 0);
    $flash_hack = JRequest::getVar('flash_hack', 0);
    $swmenufree['id'] = $swmenufree['id'] ? $swmenufree['id'] : 0;
    $preview_background = JRequest::getVar('preview_background', '#FFFFFF');


    $params = "";
    $params.="top_sub_indicator=" . $top_sub_indicator . "\n";
    $params.="sub_sub_indicator=" . $sub_sub_indicator . "\n";
    $params.="sub_indicator_align=" . $sub_indicator_align . "\n";
    $params.="sub_indicator_top=" . $sub_indicator_top . "\n";
    $params.="sub_indicator_left=" . $sub_indicator_left . "\n";
    $swmenufree['sub_indicator'] = $params;


    $ctl_corner = JRequest::getVar('ctl_corner', 0);
    $ctr_corner = JRequest::getVar('ctr_corner', 0);
    $cbl_corner = JRequest::getVar('cbl_corner', 0);
    $cbr_corner = JRequest::getVar('cbr_corner', 0);
    $c_corner_size = JRequest::getVar('c_corner_size', 0);
    $c_corner_style = JRequest::getVar('c_corner_style', 'none');
    $ttl_corner = JRequest::getVar('ttl_corner', 0);
    $ttr_corner = JRequest::getVar('ttr_corner', 0);
    $tbl_corner = JRequest::getVar('tbl_corner', 0);
    $tbr_corner = JRequest::getVar('tbr_corner', 0);
    $t_corner_size = JRequest::getVar('t_corner_size', 0);
    $t_corner_style = JRequest::getVar('t_corner_style', 'none');
    $stl_corner = JRequest::getVar('stl_corner', 0);
    $str_corner = JRequest::getVar('str_corner', 0);
    $sbl_corner = JRequest::getVar('sbl_corner', 0);
    $sbr_corner = JRequest::getVar('sbr_corner', 0);
    $s_corner_size = JRequest::getVar('s_corner_size', 0);
    $s_corner_style = JRequest::getVar('s_corner_style', 'none');
//	echo $tl_corner;
    $params = "";
    $params.="c_corner_style=" . $c_corner_style . "\n";
    $params.="c_corner_size=" . $c_corner_size . "\n";
    $params.="ctl_corner=" . $ctl_corner . "\n";
    $params.="ctr_corner=" . $ctr_corner . "\n";
    $params.="cbl_corner=" . $cbl_corner . "\n";
    $params.="cbr_corner=" . $cbr_corner . "\n";
    $params.="t_corner_style=" . $t_corner_style . "\n";
    $params.="t_corner_size=" . $t_corner_size . "\n";
    $params.="ttl_corner=" . $ttl_corner . "\n";
    $params.="ttr_corner=" . $ttr_corner . "\n";
    $params.="tbl_corner=" . $tbl_corner . "\n";
    $params.="tbr_corner=" . $tbr_corner . "\n";
    $params.="s_corner_style=" . $s_corner_style . "\n";
    $params.="s_corner_size=" . $s_corner_size . "\n";
    $params.="stl_corner=" . $stl_corner . "\n";
    $params.="str_corner=" . $str_corner . "\n";
    $params.="sbl_corner=" . $sbl_corner . "\n";
    $params.="sbr_corner=" . $sbr_corner . "\n";

    $swmenufree['corners'] = $params;

    if ($swmenufree['top_ttf']) {
        $filename = file_get_contents('' . $absolute_path . '/modules/mod_swmenufree/fonts/' . $swmenufree['top_ttf'] . '');
        $pos_start = strpos($filename, "font-family") + 14;
        $pos_end = strpos($filename, "\"", $pos_start) - $pos_start;
        $fontname = substr($filename, $pos_start, $pos_end);
        $params = "fontFile=" . $top_ttf . "\n";
        $params.= "fontFace=" . $fontname . "\n";
        $swmenufree['top_ttf'] = $params;
    }
    //echo $row->sub_ttf;

    if ($swmenufree['sub_ttf']) {
        $filename = file_get_contents('' . $absolute_path . '/modules/mod_swmenufree/fonts/' . $swmenufree['sub_ttf'] . '');
        $pos_start = strpos($filename, "font-family") + 14;
        $pos_end = strpos($filename, "\"", $pos_start) - $pos_start;
        $fontname = substr($filename, $pos_start, $pos_end);
        $params = "fontFile=" . $sub_ttf . "\n";
        $params.= "fontFace=" . $fontname . "\n";
        $swmenufree['sub_ttf'] = $params;
    }
}


global $database, $my, $Itemid;
//echo $border_hack;
if ($menu && $menustyle) {

    $content = "\n<!--swmenufree6.2 " . $menustyle . " by http://www.swmenupro.com-->\n";

    if ($menu && $menustyle) {

        $final_menu = array();
        $swmenufree_array = swGetMenuLinksFree($menu, $id, $hybrid, 1);
        $ordered = chainFree('ID', 'PARENT', 'ORDER', $swmenufree_array, $parent_id, $levels);

        $menutype = JRequest::getVar('menutype', '');


        //  $out = JRequest::getVar( 'php_out', '' );
        for ($i = 0; $i < count($ordered); $i++) {
            $swmenu = $ordered[$i];
            $swmenu['URL'] = "javascript:void(0)";

            $final_menu[] = array("TITLE" => $swmenu['TITLE'], "URL" => 'javascript:void(0);', "ID" => $swmenu['ID'], "PARENT" => $swmenu['PARENT'], "ORDER" => $swmenu['ORDER'], "TARGET" => 0, "ACCESS" => $swmenu['ACCESS']);
        }


        if (count($final_menu)) {
            if ($menustyle == "popoutmenu") {
                $swmenufree['position'] = "relative";
            } else {
                $swmenufree['position'] = "center";
            }
            echo previewHead($preview_background);





            echo "<script type=\"text/javascript\" src=\"../modules/mod_swmenufree/jquery-1.6.min.js\"></script>\n";
//	echo "<script type=\"text/javascript\" src=\"../modules/mod_swmenufree/jquery.colorbox.js\"></script>\n";


            if ($swmenufree['corners']) {
                echo "<script type=\"text/javascript\" src=\"../modules/mod_swmenufree/jquery.corner.js\"></script>\n";
            }

            if ($swmenufree['top_ttf']) {
                $registry = new JRegistry();
                $registry->loadINI($swmenufree['top_ttf']);
                $params = $registry->toObject();
                $fontfile = @$params->fontFile ? $params->fontFile : '';
                $fontface = @$params->fontFace ? $params->fontFace : '';
                //echo $swmenufree['top_ttf'];
                echo "<script type=\"text/javascript\" src=\"../modules/mod_swmenufree/cufon-yui.js\"></script>\n";

                echo "<script type=\"text/javascript\" src=\"../modules/mod_swmenufree/fonts/" . $fontfile . "\"></script>\n";
            }

            if ($swmenufree['sub_ttf']) {

                $registry = new JRegistry();
                $registry->loadINI($swmenufree['sub_ttf']);
                $params = $registry->toObject();
                $fontfile = @$params->fontFile ? $params->fontFile : '';
                $fontface = @$params->fontFace ? $params->fontFace : '';

                if (!$swmenufree['top_ttf']) {
                    echo "<script type=\"text/javascript\" src=\"../modules/mod_swmenufree/cufon-yui.js\"></script>\n";
                }

                echo "<script type=\"text/javascript\" src=\"../modules/mod_swmenufree/fonts/" . $fontfile . "\"></script>\n";
            }





            //echo $menustyle;

            if ($preview) {
                $ordered = chainFree('ID', 'PARENT', 'ORDER', $final_menu, $parent_id, $levels);
            } else {
                $ordered = chainFree('ID', 'PARENT', 'ORDER', $final_menu, 0, $levels);
            }

           
            if ($menustyle == "mygosumenu") {
                $content.= doGosuMenuPreview($ordered, $swmenufree, $active_menu, $css_load, $selectbox_hack, $padding_hack, $auto_position, $complete_shadow, $top_shadow, $sub_shadow, $sub_sub_indicator, $border_hack, $top_sub_indicator);
            }
            if ($menustyle == "superfishmenu") {
                $content.= dosuperFishMenuPreview($ordered, $swmenufree, $active_menu, $css_load, $selectbox_hack, $padding_hack, $border_hack);
            }
            if ($menustyle == "transmenu") {
                $content.= doTransMenuPreview($ordered, $swmenufree, $active_menu, $sub_sub_indicator, $parent_id, $css_load, 0, $complete_shadow, $top_shadow, $sub_shadow, $padding_hack, $auto_position, $border_hack, $top_sub_indicator);
            }
        }
    }
    $content.="\n<!--End swmenufree menu module-->\n";
}



function doSuperfishMenuPreview($ordered, $swmenufree, $active_menu, $css_load, $selectbox_hack, $padding_hack, $border_hack) {
    //echo previewHead();
    //echo '<script type="text/javascript" src="../modules/mod_swmenufree/jquery.dropshadow.js"></script>';
    //	echo "<script type=\"text/javascript\" src=\"../modules/mod_swmenufree/jquery-1.2.6.pack.js\"></script>\n";
    echo "<script type=\"text/javascript\" src=\"../modules/mod_swmenufree/hoverIntent.js\"></script>\n";
    echo "<script type=\"text/javascript\" src=\"../modules/mod_swmenufree/superfish.js\"></script>\n";
    echo "<script type=\"text/javascript\" src=\"../modules/mod_swmenufree/supersubs.js\"></script>\n";


    $manual = JRequest::getVar("preview", 0);
    if ($manual == 1) {
        $css = JRequest::getVar("filecontent", '');
        echo "\n<style type='text/css'>\n";
        echo "<!--\n";
        echo str_replace("\\", "", $css);
        echo "\n-->\n";
        echo "</style>\n";
    } else if ($css_load) {

        echo "<link type='text/css' href='../modules/mod_swmenufree/styles/menu" . $swmenufree['id'] . ".css' rel='stylesheet' />\n";
    } else {

        if ((substr(swmenuGetBrowserFree(), 0, 5) != "MSIE6") && $padding_hack) {
            $swmenufree = fixPaddingFree($swmenufree);
        }
        echo "\n<style type='text/css'>\n";
        echo "<!--\n";
        echo superfishMenuStyleFree($swmenufree, $border_hack);
        echo "\n-->\n";
        echo "</style>\n";
    }
    echo "</head><body>";
    if (($swmenufree['main_width'] == 0) && ($swmenufree['orientation'] == "vertical")) {
        echo "<div align=\"center\" style=\"margin:auto;width:200px;\" >";
    } else {
        echo "<div align=\"center\" style=\"margin:auto;\" >";
    }
    echo SuperfishMenuFree($ordered, $swmenufree, $active_menu, $selectbox_hack, 0);
    echo "</div>";
    //echo changeBgColor();
    echo "</body></html>";
}

function doGosuMenuPreview($ordered, $swmenufree, $active_menu, $css_load, $selectbox_hack, $padding_hack, $auto_position, $complete_shadow, $top_shadow, $sub_shadow, $sub_sub_indicator, $border_hack, $top_sub_indicator) {
    global $mosConfig_live_site;
//echo previewHead();
//echo '<script type="text/javascript" src="../modules/mod_swmenufree/jquery-1.2.6.pack.js"></script>';
 //   echo '<script type="text/javascript" src="../modules/mod_swmenufree/jquery.dropshadow.js"></script>';
//echo '<script type="text/javascript" src="../modules/mod_swmenufree/ie5.js"></script>';
    echo '<script type="text/javascript" src="../modules/mod_swmenufree/DropDownMenuX_Packed.js"></script>';



    $manual = JRequest::getVar("preview", 0);
    if ($manual == 1) {
        $css = JRequest::getVar("filecontent", '');
        echo "\n<style type='text/css'>\n";
        echo "<!--\n";
        echo str_replace("\\", "", $css);
        echo "\n-->\n";
        echo "</style>\n";
    } else if ($css_load) {

        echo "<link type='text/css' href='../modules/mod_swmenufree/styles/menu" . $swmenufree['id'] . ".css' rel='stylesheet' />\n";
    } else {

        if ((substr(swmenuGetBrowserFree(), 0, 5) != "MSIE6") && $padding_hack) {
            $swmenufree = fixPaddingFree($swmenufree);
        }
        echo "\n<style type='text/css'>\n";
        echo "<!--\n";
        echo gosuMenuStyleFree($swmenufree, $ordered, $border_hack);
        echo "\n-->\n";
        echo "</style>\n";
    }
    echo "</head><body>";
    echo GosuMenuFree($ordered, $swmenufree, $active_menu, $selectbox_hack, $auto_position, 0);
    echo changeBgColor();
    echo "</body></html>";
}

function doTransMenuPreview($ordered, $swmenufree, $active_menu, $sub_sub_indicator, $parent_id, $css_load, $selectbox_hack, $complete_shadow, $top_shadow, $sub_shadow, $padding_hack, $auto_position, $border_hack, $top_sub_indicator) {
//global $mosConfig_live_site;
//echo previewHead();
//echo '<script type="text/javascript" src="../modules/mod_swmenufree/jquery.dropshadow.js"></script>';
    echo '<script type="text/javascript" src="../modules/mod_swmenufree/transmenu_Packed.js"></script>';
    $manual = JRequest::getVar("preview", 0);
    if ($manual == 1) {
        $css = JRequest::getVar("filecontent", '');
        echo "\n<style type='text/css'>\n";
        echo "<!--\n";
        echo str_replace('\\', '', $css);
        echo "\n-->\n";
        echo "</style>\n";
    } else if ($css_load) {

        echo "<link type='text/css' href='../modules/mod_swmenufree/styles/menu" . $swmenufree['id'] . ".css' rel='stylesheet' />\n";
    } else {
        if ((substr(swmenuGetBrowserFree(), 0, 5) != "MSIE6") && $padding_hack) {
            $swmenufree = fixPaddingFree($swmenufree);
        }
        echo "\n<style type='text/css'>\n";
        echo "<!--\n";
        echo transMenuStyleFree($swmenufree, $border_hack);
        echo "\n-->\n";
        echo "</style>\n";
    }
    echo "</head><body><center>";
    echo transMenuFree($ordered, $swmenufree, $active_menu, $sub_sub_indicator, $parent_id, $selectbox_hack, $auto_position, 0, $complete_shadow, $top_shadow, 1);
//echo changeBgColor();
    echo "</center></body></html>";
}

function previewHead($preview_background) {
    echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
    echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
    echo "<head>\n<title>swmenufree Menu Module Preview</title>\n";
    echo "<meta http-equiv=\"X-UA-Compatible\" content=\"IE=EmulateIE7\" />";
    echo "<META HTTP-EQUIV=\"Pragma\" CONTENT=\"no-cache\" />";
    echo "\n<style type='text/css'>\n";
    echo "<!--\n";
    //echo	"body{\nmargin-top:20px;\n}\n";
    echo "#bg_table{\nposition:absolute;top:700px;left:250px;\n}\n";
    //echo "body{z-index:1000;background-color:#fff;}\n";
    echo "\n-->\n";
    echo "</style>\n";
    ?>
    <script type="text/javascript">
        <!--
        function changeBG(){
            document.body.style.backgroundColor = '<?php echo $preview_background; ?>';
            //alert(document.getElementById('back_color').value);
        }

        -->
    </script>
    <?php
}

function changeBgColor() {
    ?>


    <script type="text/javascript">
        <!--
        changeBG();
        //-->
    </script>

    <?php
}
?>
 


