<?php

/**
 * swmenufree v4.5
 * http://swonline.biz
 * Copyright 2006 Sean White
 * */
defined('_JEXEC') or die('Restricted access');

function gosuMenuStyleFree($swmenufree, $ordered, $border_hack) {
    global $mainframe;
    $absolute_path = JPATH_ROOT;
    // echo $border_hack;
    $live_site = $mainframe->isAdmin() ? $mainframe->getSiteURL() : JURI::base();
    if (substr($live_site, (strlen($live_site) - 1), 1) == "/") {
        $live_site = substr($live_site, 0, (strlen($live_site) - 1));
    }
    if (substr($swmenufree['complete_background_image'], 0, 1) == "/") {
        $swmenufree['complete_background_image'] = substr($swmenufree['complete_background_image'], 1, (strlen($swmenufree['complete_background_image']) - 1));
    }
    if (substr($swmenufree['main_back_image'], 0, 1) == "/") {
        $swmenufree['main_back_image'] = substr($swmenufree['main_back_image'], 1, (strlen($swmenufree['main_back_image']) - 1));
    }
    if (substr($swmenufree['main_back_image_over'], 0, 1) == "/") {
        $swmenufree['main_back_image_over'] = substr($swmenufree['main_back_image_over'], 1, (strlen($swmenufree['main_back_image_over']) - 1));
    }
    if (substr($swmenufree['sub_back_image'], 0, 1) == "/") {
        $swmenufree['sub_back_image'] = substr($swmenufree['sub_back_image'], 1, (strlen($swmenufree['sub_back_image']) - 1));
    }
    if (substr($swmenufree['sub_back_image_over'], 0, 1) == "/") {
        $swmenufree['sub_back_image_over'] = substr($swmenufree['sub_back_image_over'], 1, (strlen($swmenufree['sub_back_image_over']) - 1));
    }
    if (substr($swmenufree['active_background_image'], 0, 1) == "/") {
        $swmenufree['active_background_image'] = substr($swmenufree['active_background_image'], 1, (strlen($swmenufree['active_background_image']) - 1));
    }


    $swmenufree['complete_background_image'] = $swmenufree['complete_background_image'] ? $live_site . "/" . $swmenufree['complete_background_image'] : "";
    $swmenufree['main_back_image'] = $swmenufree['main_back_image'] ? $live_site . "/" . $swmenufree['main_back_image'] : "";
    $swmenufree['main_back_image_over'] = $swmenufree['main_back_image_over'] ? $live_site . "/" . $swmenufree['main_back_image_over'] : "";
    $swmenufree['sub_back_image'] = $swmenufree['sub_back_image'] ? $live_site . "/" . $swmenufree['sub_back_image'] : "";
    $swmenufree['sub_back_image_over'] = $swmenufree['sub_back_image_over'] ? $live_site . "/" . $swmenufree['sub_back_image_over'] : "";
    $swmenufree['active_background_image'] = $swmenufree['active_background_image'] ? $live_site . "/" . $swmenufree['active_background_image'] : "";



    //$str="#outertable {\n";
    //$str.=" top: ".$swmenufree['main_top']."px !important ; \n";
    //$str.=" left: ".$swmenufree['main_left']."px; \n";
    //$str.=" border:".$swmenufree['main_border']."  ; \n";
    //$str.=" padding:".$swmenufree['complete_padding']." !important; \n";
    //$str.=is_file($absolute_path."/".$swmenufree['complete_background_image']) ? " background-image: URL(\"".$live_site."/".$swmenufree['complete_background_image']."\") ;\n":"";
    //$str.=$swmenufree['complete_background']?" background-color: ".$swmenufree['complete_background']." !important ; \n":"";
    //$str.=" display: block; \n";
    //$str.=" position: relative; \n";
    //$str.=" z-index: 101; \n";
    //$str.="}\n";



    $str = "#outerwrap {\n";
    $str.=" top: " . $swmenufree['main_top'] . "px !important ; \n";
    $str.=" left: " . $swmenufree['main_left'] . "px; \n";
    $str.=" border:" . $swmenufree['main_border'] . "  ; \n";
    $str.=" padding:" . $swmenufree['complete_padding'] . " !important; \n";
    $str.=$swmenufree['complete_background_image'] ? " background-image: URL(\"" . $swmenufree['complete_background_image'] . "\") ;\n" : "";
    $str.=$swmenufree['complete_background'] ? " background-color: " . $swmenufree['complete_background'] . " !important ; \n" : "";
    $str.=" display: block; \n";
    $str.=" position: relative; \n";
    $str.=" z-index: 99; \n";
    $str.="}\n";


    //$str.="#menu {\n";
    //$str.="border:".$swmenufree['main_border']." !important ; \n";
    //$str.=" padding:".$swmenufree['complete_padding']."; \n";
    //$str.=is_file($absolute_path."/".$swmenufree['complete_background_image']) ? " background-image: URL(\"".$live_site."/".$swmenufree['complete_background_image']."\") ;\n":"";
    //$str.=$swmenufree['complete_background']?" background-color: ".$swmenufree['complete_background']." !important ; \n":"";
    //$str.=" position: relative; \n";
    //$str.=" z-index: 0; \n";
    //$str.="}\n";

    $str.=".ddmx a.item1,\n";
    $str.=".ddmx a.item1:hover,\n";
    $str.=".ddmx a.item1-active,\n";
    $str.=".ddmx a.item1-active:hover {\n";
    $str.=" padding: " . $swmenufree['main_padding'] . " !important ; \n";
    //$str.=" top: ".$swmenufree['main_top']."px !important ; \n";
    //$str.=" left: ".$swmenufree['main_left']."px; \n";
    $str.=" font-size: " . $swmenufree['main_font_size'] . "px !important ; \n";
    $str.=" font-family: " . $swmenufree['font_family'] . " !important ; \n";
    $str.=" text-align: " . $swmenufree['main_align'] . " !important ; \n";
    $str.=" font-weight: " . $swmenufree['font_weight'] . " !important ; \n";
    $str.=$swmenufree['main_font_color'] ? " color: " . $swmenufree['main_font_color'] . "  ; \n" : "";

    switch ($swmenufree['top_font_extra']) {
        case "italic":
        case "oblique":
            $str.=" font-style:" . $swmenufree['top_font_extra'] . " !important;\n";
            $str.=" text-decoration: none !important;\n";
            $str.=" text-transform: none !important;\n";
            break;
        case "underline":
        case "overline":
        case "line-through":
            $str.=" text-decoration:" . $swmenufree['top_font_extra'] . " !important;\n";
            $str.=" font-style: normal !important;\n";
            $str.=" text-transform: none !important;\n";
            break;
        case "uppercase":
        case "lowercase":
        case "capitalize":
            $str.=" text-transform:" . $swmenufree['top_font_extra'] . " !important;\n";
            $str.=" text-decoration: none !important;\n";
            $str.=" font-style: normal !important;\n";
            break;
        default:
            $str.=" font-style: normal !important;\n";
            $str.=" text-decoration: none !important;\n";
            $str.=" text-transform: none !important;\n";
            break;
    }


    if (($swmenufree['orientation'] == "vertical/left" || $swmenufree['orientation'] == "vertical/right" || $swmenufree['orientation'] == "vertical") && $border_hack) {
        $str.= " border-top: " . $swmenufree['main_border_over'] . "; \n";
        $str.= " border-right: " . $swmenufree['main_border_over'] . "; \n";
        $str.= " border-left: " . $swmenufree['main_border_over'] . "; \n";
        $str.= " border-bottom: 0; \n";
    } else if ($border_hack) {
        $str.= " border-top: " . $swmenufree['main_border_over'] . "; \n";
        $str.= " border-bottom: " . $swmenufree['main_border_over'] . "; \n";
        $str.= " border-left: " . $swmenufree['main_border_over'] . "; \n";
        $str.= " border-right: 0; \n";
    } else {
        $str.= " border: " . $swmenufree['main_border_over'] . ";\n";
    }


    //$str.=" text-decoration: none !important ; \n";
    $str.=" display: block; \n";
    $str.=" white-space: " . $swmenufree['top_wrap'] . "; \n";
    $str.=" position: relative; \n";
    $str.=" z-index: 200; \n";
    $str.=" margin:" . $swmenufree['top_margin'] . " ; \n";
    $str.=$swmenufree['main_back_image'] ? " background-image: URL(\"" . $swmenufree['main_back_image'] . "\") !important ;\n" : "background-image:none !important; \n";
    //$str.=$swmenufree['main_back']?" background-color: ".$swmenufree['main_back']." !important ; \n":"";
    if ($swmenufree['main_width'] != 0) {
        $str.= " width:" . $swmenufree['main_width'] . "px; \n";
    }
    if ($swmenufree['main_height'] != 0) {
        $str.= " height:" . $swmenufree['main_height'] . "px; \n";
    }
    $str.=$swmenufree['main_back'] ? " background-color: " . $swmenufree['main_back'] . "  !important; \n" : "";
    $str.="}\n";

    $str.=".ddmx td.item11.last a.item1-active,\n";
    $str.=".ddmx td.item11.last a.item1 {\n";
    $str.= " border: " . $swmenufree['main_border_over'] . ";\n";
    $str.="}\n";




    $str.= ".ddmx a.item1-active,\n";
    $str.= ".ddmx a.item1-active:hover ,\n";
    $str.= ".ddmx .last a:hover,\n";
    $str.= ".ddmx .acton.last a,\n";
    $str.= ".ddmx a.item1:hover{\n";

    //$str.= " padding:10px !important ; \n";
    //$str.= " border-top: ".$swmenufree['main_border_over']." !important ; \n";
    //$str.= " border-left: ".$swmenufree['main_border_over']." !important ; \n";
    $str.=" white-space: " . $swmenufree['top_wrap'] . "; \n";
    //if($swmenufree['orientation']=="vertical/left" || $swmenufree['orientation']=="vertical/right"){
    //	$str.= " border-right: ".$swmenufree['main_border_over']." !important ; \n";
    //}else{
    //	$str.= " border-bottom: ".$swmenufree['main_border_over'].";\n" ;
    //}
    $str.=$swmenufree['main_over'] ? " background-color: " . $swmenufree['main_over'] . " !important ; \n" : "";
    $str.= $swmenufree['main_back_image_over'] ? "background-image: URL(\"" . $swmenufree['main_back_image_over'] . "\") !important;\n" : "background-image:none !important;\n";
    $str.="}\n";


    $str.= ".ddmx .item11:hover,\n";
    $str.= ".ddmx .item11.acton:hover,\n";
    $str.= ".ddmx .item11.last:hover,\n";
    //$str.= ".ddmx .item11.acton.last a.item1,\n";
    $str.= ".ddmx .item11.acton a.item1,\n";
    //$str.= ".ddmx .item11.acton.last a:hover,\n";
    $str.= ".ddmx .item11.acton a:hover,\n";
    $str.= ".ddmx .item11 a:hover,\n";
    $str.= ".ddmx .item11.last a:hover,\n";
    $str.= ".ddmx a.item1-active,\n";
    $str.= ".ddmx a.item1-active:hover {\n";
    //$str.= is_file($absolute_path."/".$swmenufree['main_back_image_over']) ? "background-image: URL(\"".$live_site."/".$swmenufree['main_back_image_over']."\") ;\n":"background-image:none !important;\n";
    $str.=$swmenufree['main_font_color_over'] ? " color: " . $swmenufree['main_font_color_over'] . "  ; \n" : "";
    //$str.=$swmenufree['main_over']?" background-color: ".$swmenufree['main_over']." !important ; \n":"";
    $str.="}\n";



    $str.= ".ddmx .acton a.item1-active,\n";
    //$str.= ".ddmx td.item11-acton-last a.item1-active,\n";
    //$str.= ".ddmx td.item11-acton-last a.item1:hover,\n";
    //$str.= ".ddmx td.item11-acton-last a.item1,\n";
    $str.= ".ddmx .acton a.item1:hover,\n";
    $str.= ".ddmx .acton a.item1 {\n";
    //$str.= " padding:10px !important ; \n";
    //$str.= " border-top: ".$swmenufree['main_border_over']." !important ; \n";
    //$str.= " border-left: ".$swmenufree['main_border_over']." !important ; \n";
    $str.=" white-space: " . $swmenufree['top_wrap'] . "; \n";
    //if($swmenufree['orientation']=="vertical/left" || $swmenufree['orientation']=="vertical/right"){
    //	$str.= " border-right: ".$swmenufree['main_border_over']." !important ; \n";
    //}else{
    //	$str.= " border-bottom: ".$swmenufree['main_border_over'].";\n" ;
    //}
    $str.= $swmenufree['active_background_image'] ? " background-image: URL(\"" . $swmenufree['active_background_image'] . "\") !important ;\n" : "background-image:none !important;\n";

    $str.=$swmenufree['active_background'] ? " background-color: " . $swmenufree['active_background'] . " !important; \n" : "";
    $str.=$swmenufree['active_font'] ? " color: " . $swmenufree['active_font'] . " !important ; \n" : "";
    $str.="}\n";

    $str.= ".ddmx a.item2,\n";
    $str.= ".ddmx a.item2:hover,\n";
    $str.= ".ddmx a.item2-active,\n";
    $str.= ".ddmx a.item2-active:hover {\n";
    $str.= " padding: " . $swmenufree['sub_padding'] . " !important ; \n";
    $str.= " font-size: " . $swmenufree['sub_font_size'] . "px !important ; \n";
    $str.= " font-family: " . $swmenufree['sub_font_family'] . " !important ; \n";
    $str.= " text-align: " . $swmenufree['sub_align'] . " !important ; \n";
    $str.= " font-weight: " . $swmenufree['font_weight_over'] . " !important ; \n";
    switch ($swmenufree['sub_font_extra']) {
        case "italic":
        case "oblique":
            $str.=" font-style:" . $swmenufree['sub_font_extra'] . " !important;\n";
            $str.=" text-decoration: none !important;\n";
            $str.=" text-transform: none !important;\n";
            break;
        case "underline":
        case "overline":
        case "line-through":
            $str.=" text-decoration:" . $swmenufree['sub_font_extra'] . " !important;\n";
            $str.=" font-style: normal !important;\n";
            $str.=" text-transform: none !important;\n";
            break;
        case "uppercase":
        case "lowercase":
        case "capitalize":
            $str.=" text-transform:" . $swmenufree['sub_font_extra'] . " !important;\n";
            $str.=" text-decoration: none !important;\n";
            $str.=" font-style: normal !important;\n";
            break;
        default:
            $str.=" font-style: normal !important;\n";
            $str.=" text-decoration: none !important;\n";
            $str.=" text-transform: none !important;\n";
            break;
    }
    $str.= " display: block; \n";
    $str.=" white-space: " . $swmenufree['sub_wrap'] . " !important; \n";
    //$str.= " position: relative; \n";
    //$str.= " z-index:1000; \n";

    if ($swmenufree['sub_height'] != 0) {
        $str.= " height:" . $swmenufree['sub_height'] . "px; \n";
    }
    $str.= " opacity:" . ($swmenufree['specialA'] / 100) . "; \n";
    $str.= " filter:alpha(opacity=" . ($swmenufree['specialA']) . ") \n";
    $str.="}\n";


    //$str.= ".ddmx td.item11-active a.item2:hover ,\n";
    $str.= ".ddmx a.item2-active ,\n";
    $str.= ".ddmx a.item2 {\n";
    if ($swmenufree['sub_width'] != 0) {
        $str.= " width:" . $swmenufree['sub_width'] . "px !important; \n";
    }
    $str.= $swmenufree['sub_back_image'] ? " background-image: URL(\"" . $swmenufree['sub_back_image'] . "\") ;\n" : "background-image:none !important;\n";
    $str.=$swmenufree['sub_back'] ? " background-color: " . $swmenufree['sub_back'] . " !important ; \n" : "";
    $str.=$swmenufree['sub_font_color'] ? " color: " . $swmenufree['sub_font_color'] . " !important ; \n" : "";
    $str.= " border-top: " . $swmenufree['sub_border_over'] . " !important ; \n";
    $str.= " border-left: " . $swmenufree['sub_border_over'] . " !important ; \n";
    $str.= " border-right: " . $swmenufree['sub_border_over'] . " !important ; \n";
    $str.="}\n";

    $str.= ".ddmx a.item2-active.last,\n";
    $str.= ".ddmx a.item2.last {\n";
//	$str.= is_file($absolute_path."/".$swmenufree['sub_back_image']) ? " background-image: URL(\"".$live_site."/".$swmenufree['sub_back_image']."\") ;\n":"";
//	$str.=$swmenufree['sub_back']?" background-color: ".$swmenufree['sub_back']." !important ; \n":"";
    //$str.=$swmenufree['sub_font_color']?" color: ".$swmenufree['sub_font_color']." !important ; \n":"";
    $str.= " border-bottom: " . $swmenufree['sub_border_over'] . " !important ; \n";
    $str.= " z-index:500; \n";
    $str.="}\n";


    //$str.= ".ddmx a.item2.last-active:hover ,\n";
    $str.= ".ddmx .section a.item2:hover,\n";
    $str.= ".ddmx a.item2-active,\n";
    $str.= ".ddmx a.item2-active:hover {\n";
    $str.= $swmenufree['sub_back_image_over'] ? " background-image: URL(\"" . $swmenufree['sub_back_image_over'] . "\") !important ;\n" : "background-image:none !important;\n";
    $str.=$swmenufree['sub_over'] ? " background-color: " . $swmenufree['sub_over'] . " !important ; \n" : "";
    $str.=$swmenufree['sub_font_color_over'] ? " color: " . $swmenufree['sub_font_color_over'] . " !important ; \n" : "";
    $str.= " border-top: " . $swmenufree['sub_border_over'] . " !important ; \n";
    $str.= " border-left: " . $swmenufree['sub_border_over'] . " !important ; \n";
    $str.= " border-right: " . $swmenufree['sub_border_over'] . " !important ; \n";
    $str.= "}\n";

    $str.= ".ddmx .section {\n";
    $str.= " border: " . $swmenufree['sub_border'] . " ; \n";
    $str.= " position: absolute; \n";
    $str.= " visibility: hidden; \n";
    $str.= " display: block; \n";
    $str.= " z-index: -1; \n";
    //if ($swmenufree['sub_width']!=0){$str.= " width:".$swmenufree['sub_width']."px !important; \n";}
    //$str.=$swmenufree['sub_back']?" background-color: ".$swmenufree['sub_back']." !important ; \n":"";
    $str.="}\n";
    //$str.= ".jquery-corner { position: relative; }";
    $str.= ".ddmx .inner_section {\n";
    //$str.= ".jquery-corner { position: relative; }";
    //$str.="div.autosize { display: table;width:auto}";
    //$str.="div.autosize > div { display: table-cell; }";
    $str.=$swmenufree['sub_back'] ? " background-color: " . $swmenufree['sub_back'] . " ; \n" : "";
    $str.=" display: block; \n";
    //$str.= " width:100%; \n";
    $str.="}\n";

    $str.= ".ddmx .subsection a{\n";
    //$str.= " border: ".$swmenufree['sub_border']." !important ; \n";
    //$str.= " position: absolute; \n";
    if ($swmenufree['main_width'] != 0) {
        $str.= " width:" . $swmenufree['main_width'] . "px; \n";
    }
    //$str.= " display: block; \n";
    $str.= " white-space:normal !important; \n";
    $str.="}\n";

    $str.= ".ddmxframe {\n";
    $str.= " border: " . $swmenufree['sub_border'] . " !important ; \n";
    $str.="}\n";


    $str.= "* html .ddmx td { position: relative; } /* ie 5.0 fix */\n";
    //$str.="-->\n";
    //$str.="</style>\n";

    $str.=".ddmx .item2-active img,\n";
    $str.=".ddmx .item2 img,\n";
    $str.=".ddmx .item1-active img,\n";
    $str.=".ddmx .item1 img{\n";
    $str.=" border:none;\n";
    $str.="}\n";





    return $str;
}

function superfishMenuStyleFree($swmenufree, $border_hack) {
    global $mainframe;
    $absolute_path = JPATH_ROOT;
    // echo $border_hack;
    $live_site = $mainframe->isAdmin() ? $mainframe->getSiteURL() : JURI::base();
    if (substr($live_site, (strlen($live_site) - 1), 1) == "/") {
        $live_site = substr($live_site, 0, (strlen($live_site) - 1));
    }
    if (substr($swmenufree['complete_background_image'], 0, 1) == "/") {
        $swmenufree['complete_background_image'] = substr($swmenufree['complete_background_image'], 1, (strlen($swmenufree['complete_background_image']) - 1));
    }
    if (substr($swmenufree['main_back_image'], 0, 1) == "/") {
        $swmenufree['main_back_image'] = substr($swmenufree['main_back_image'], 1, (strlen($swmenufree['main_back_image']) - 1));
    }
    if (substr($swmenufree['main_back_image_over'], 0, 1) == "/") {
        $swmenufree['main_back_image_over'] = substr($swmenufree['main_back_image_over'], 1, (strlen($swmenufree['main_back_image_over']) - 1));
    }
    if (substr($swmenufree['sub_back_image'], 0, 1) == "/") {
        $swmenufree['sub_back_image'] = substr($swmenufree['sub_back_image'], 1, (strlen($swmenufree['sub_back_image']) - 1));
    }
    if (substr($swmenufree['sub_back_image_over'], 0, 1) == "/") {
        $swmenufree['sub_back_image_over'] = substr($swmenufree['sub_back_image_over'], 1, (strlen($swmenufree['sub_back_image_over']) - 1));
    }
    if (substr($swmenufree['active_background_image'], 0, 1) == "/") {
        $swmenufree['active_background_image'] = substr($swmenufree['active_background_image'], 1, (strlen($swmenufree['active_background_image']) - 1));
    }


    $swmenufree['complete_background_image'] = $swmenufree['complete_background_image'] ? $live_site . "/" . $swmenufree['complete_background_image'] : "";
    $swmenufree['main_back_image'] = $swmenufree['main_back_image'] ? $live_site . "/" . $swmenufree['main_back_image'] : "";
    $swmenufree['main_back_image_over'] = $swmenufree['main_back_image_over'] ? $live_site . "/" . $swmenufree['main_back_image_over'] : "";
    $swmenufree['sub_back_image'] = $swmenufree['sub_back_image'] ? $live_site . "/" . $swmenufree['sub_back_image'] : "";
    $swmenufree['sub_back_image_over'] = $swmenufree['sub_back_image_over'] ? $live_site . "/" . $swmenufree['sub_back_image_over'] : "";
    $swmenufree['active_background_image'] = $swmenufree['active_background_image'] ? $live_site . "/" . $swmenufree['active_background_image'] : "";

    $str = "#sfmenu {\n";
    //$str.=" top: ".$swmenupro['main_top']."px !important ; \n";
    //$str.=" left: ".$swmenupro['main_left']."px; \n";
    $str.=" border:" . $swmenufree['main_border'] . "  ; \n";
    $str.=" padding:" . $swmenufree['complete_padding'] . " !important; \n";
    $str.=$swmenufree['complete_background_image'] ? " background-image: URL(\"" . $swmenufree['complete_background_image'] . "\") ;\n" : "";
    $str.=$swmenufree['complete_background'] ? " background-color: " . $swmenufree['complete_background'] . " !important ; \n" : "";
//	$str.=" display: block; \n";
//	$str.=" position: relative; \n";
//	$str.=" z-index: 99; \n";
    $str.="}\n";

    //$str="<style type=\"text/css\">\n";
    //$str.="<!--\n";
    $str.=".sw-sf, .sw-sf * {\n";
    //$str.="border:".$swmenufree['main_border']." !important ; \n";
    $str.="margin: 0 !important ; \n";
    $str.="padding: 0 !important ; \n";
    $str.="list-style: none !important ; \n";
    $str.="}\n";

    $str.=".sw-sf {\n";
    //$str.="height:auto; \n";
    //$str.=" border: ".$swmenufree['main_border']." !important ; \n";
    $str.="line-height: 1.0 !important ; \n";
    $str.="}\n";

    $str.=".sw-sf hr {display: block; clear: left; margin: -0.66em 0; visibility: hidden;}\n";


    $str.=".sw-sf ul{\n";
    $str.="position: absolute; \n";
    $str.="top: -999em; \n";
    //$str.=" border: ".$swmenufree['main_border']." !important ; \n";
    //if ($swmenufree['main_width']!=0){$str.= " width:".$swmenufree['main_width']."px; \n";}else{$str.= " width:100%; \n";}
    $str.="width: 10em; \n";
    $str.="display: block; \n";
    $str.="}\n";

    $str.=".sw-sf ul li {\n";
    //$str.="display:block; \n";
    $str.="width: 100% !important ; \n";
    //$str.="height: 1px !important ; \n";
    $str.="}\n";

    $str.=".sw-sf li:hover {\n";
    $str.="z-index:300 ; \n";
    $str.="}\n";

    $str.=".sw-sf li:hover {\n";
    $str.="visibility: inherit ; \n";
    $str.="}\n";

    $str.=".sw-sf li {\n";
    $str.="float: left; \n";
    $str.="position: relative; \n";
    //$str.="display: inline; \n";
    $str.="}\n";

    $str.=".sw-sf li li{\n";
    $str.=" top: 0 !important ; \n";
    $str.=" left: 0; \n";
    //$str.="float: left; \n";
    $str.="position: relative; \n";
    $str.="}\n";

    $str.=".sw-sf a {\n";
    $str.="display: block; \n";
    $str.="position: relative; \n";
    $str.="}\n";

    $str.=".sw-sf li:hover ul ,\n";
    $str.=".sw-sf li.sfHover ul {\n";
    $str.="left: 0; \n";
    $str.="top: 2.5em; \n";
    $str.="z-index: 400; \n";
    $str.="width:100%; \n";
    $str.="}\n";

    $str.="ul.sw-sf li:hover li ul ,\n";
    $str.="ul.sw-sf li.sfHover li ul {\n";
    $str.="top: -999em; \n";
    $str.="}\n";

    $str.="ul.sw-sf li li:hover ul ,\n";
    $str.="ul.sw-sf li li.sfHover ul {\n";
    $str.="left: 10em; \n";
//	if ($swmenufree['main_width']!=0){$str.= " left:".$swmenufree['main_width']."px; \n";}else{$str.= " left:100%; \n";}
    $str.="top: 0; \n";
    $str.="}\n";

    $str.="ul.sw-sf li li:hover li ul ,\n";
    $str.="ul.sw-sf li li.sfHover li ul {\n";
    $str.="top: -999em; \n";
    $str.="}\n";

    $str.="ul.sw-sf li li li:hover ul ,\n";
    $str.="ul.sw-sf li li li.sfHover ul {\n";
    $str.="left: 10em; \n";
//	if ($swmenufree['main_width']!=0){$str.= " left:".$swmenufree['main_width']."px; \n";}else{$str.= " left:100%; \n";}
    $str.="top: 0; \n";
    $str.="}\n";

    $str.="#sfmenu {\n";
    $str.="position: relative; \n";
    $str.="border: " . $swmenufree['main_border'] . " !important ; \n";
    $str.="top: " . $swmenufree['main_top'] . "px !important ; \n";
    $str.="left: " . $swmenufree['main_left'] . "px; \n";
    $str.="}\n";

    $str.=".sf-section {\n";
    //$str.="position: relative; \n";
    $str.="border: " . $swmenufree['sub_border'] . " !important ; \n";
    $str.="}\n";

    if ($swmenufree['orientation'] == "vertical") {


        $str.=".sw-sf.sf-vertical, .sw-sf.sf-vertical li {\n";
        //if ($swmenufree['main_width']==0){$str.= "float:none; \n";}
        //$str.="float:none !important; \n";
        $str.="display:block !important; \n";
        //$str.="outline:0; \n";
        //$str.=" border: ".$swmenufree['main_border']." !important ; \n";
        $str.="margin: 0 !important ; \n";
        if ($swmenufree['main_width'] != 0) {
            $str.= " width:" . $swmenufree['main_width'] . "px; \n";
        } else {
            $str.= "width:100%; \n";
        }
        //if ($swmenufree['main_height']!=0){$str.= " height:".$swmenufree['main_height']."px; \n";}
        $str.="}\n";

        $str.=".sw-sf.sf-vertical li:hover ul, .sw-sf.sf-vertical li.sfHover ul {\n";
        //$str.="width:auto; \n";
        if ($swmenufree['main_width'] != 0) {
            $str.= " left:" . ($swmenufree['main_width'] + $swmenufree['level1_sub_left']) . "px; \n";
        } else {
            $str.= " left:100%; \n";
        }
        $str.="top:" . $swmenufree['level1_sub_top'] . "px !important ; \n";
        $str.="}\n";
    } else {

        $str.=".sw-sf li.sfHover li , .sw-sf li:hover li {\n";
        //$str.="width:auto; \n";
        //if ($swmenufree['main_width']!=0){$str.= " left:".($swmenufree['main_width']+$swmenufree['level1_sub_left'])."px; \n";}else{$str.= " left:100%; \n";}
        $str.="top:" . $swmenufree['level1_sub_top'] . "px !important ; \n";
        $str.="left:" . $swmenufree['level1_sub_left'] . "px !important ; \n";
        $str.="}\n";
    }

    $str.=".sw-sf li.sfHover li.sfHover li {\n";
    //$str.="left: 10em !important ; \n";
    $str.="top:" . $swmenufree['level2_sub_top'] . "px !important; \n";
    $str.="left:" . $swmenufree['level2_sub_left'] . "px !important; \n";
    $str.="}\n";



    $str.=".sw-sf a.item1 {\n";

    $str.=" padding: " . $swmenufree['main_padding'] . " !important ; \n";
    //$str.=" top: ".$swmenufree['main_top']."px !important ; \n";
    //$str.=" left: ".$swmenufree['main_left']."px; \n";
    $str.=" font-size: " . $swmenufree['main_font_size'] . "px !important ; \n";
    $str.=" font-family: " . $swmenufree['font_family'] . " !important ; \n";
    $str.=" text-align: " . $swmenufree['main_align'] . " !important ; \n";
    $str.=" font-weight: " . $swmenufree['font_weight'] . " !important ; \n";
    $str.=$swmenufree['main_font_color'] ? " color: " . $swmenufree['main_font_color'] . " !important ; \n" : "";
    $str.=" margin:" . $swmenufree['top_margin'] . " !important ; \n";
    switch ($swmenufree['top_font_extra']) {
        case "italic":
        case "oblique":
            $str.=" font-style:" . $swmenufree['top_font_extra'] . " !important;\n";
            $str.=" text-decoration: none !important;\n";
            $str.=" text-transform: none !important;\n";
            break;
        case "underline":
        case "overline":
        case "line-through":
            $str.=" text-decoration:" . $swmenufree['top_font_extra'] . " !important;\n";
            $str.=" font-style: normal !important;\n";
            $str.=" text-transform: none !important;\n";
            break;
        case "uppercase":
        case "lowercase":
        case "capitalize":
            $str.=" text-transform:" . $swmenufree['top_font_extra'] . " !important;\n";
            $str.=" text-decoration: none !important;\n";
            $str.=" font-style: normal !important;\n";
            break;
        default:
            $str.=" font-style: normal !important;\n";
            $str.=" text-decoration: none !important;\n";
            $str.=" text-transform: none !important;\n";
            break;
    }


    if (($swmenufree['orientation'] == "vertical/left" || $swmenufree['orientation'] == "vertical/right" || $swmenufree['orientation'] == "vertical") && $border_hack) {
        $str.= " border-top: " . $swmenufree['main_border_over'] . "; \n";
        $str.= " border-right: " . $swmenufree['main_border_over'] . "; \n";
        $str.= " border-left: " . $swmenufree['main_border_over'] . "; \n";
        $str.= " border-bottom: 0; \n";
    } else if ($border_hack) {
        $str.= " border-top: " . $swmenufree['main_border_over'] . "; \n";
        $str.= " border-bottom: " . $swmenufree['main_border_over'] . "; \n";
        $str.= " border-left: " . $swmenufree['main_border_over'] . "; \n";
        $str.= " border-right: 0; \n";
    } else {
        $str.= " border: " . $swmenufree['main_border_over'] . ";\n";
    }
    $str.=" display: block; \n";
    $str.=" white-space: " . $swmenufree['top_wrap'] . "; \n";
    $str.=" position: relative; \n";
    //$str.="z-index: 100; \n";
    $str.=$swmenufree['main_back_image'] ? " background-image: URL(\"" . $swmenufree['main_back_image'] . "\") ;\n" : "";
    $str.=$swmenufree['main_back'] ? " background-color: " . $swmenufree['main_back'] . " !important ; \n" : "";
    if ($swmenufree['main_width'] != 0) {
        $str.= " width:" . $swmenufree['main_width'] . "px; \n";
    }
    if ($swmenufree['main_height'] != 0) {
        $str.= " height:" . $swmenufree['main_height'] . "px; \n";
    }
    //$str.=$swmenufree['main_back']?" background-color: ".$swmenufree['main_back']." !important ; \n":"";
    $str.="}\n";

    $str.=".sw-sf a.item1.last {\n";

    if ($swmenufree['orientation'] == "vertical") {
        $str.= " border-bottom: " . $swmenufree['main_border_over'] . " !important ; \n";
        //$str.= " border-right: 0 !important ; \n";
    } else {
        $str.= " border-right: " . $swmenufree['main_border_over'] . " !important;\n";
        //$str.= " border-bottom: 0 !important ; \n";
    }
    $str.="}\n";

    //$str.= ".sw-sf .current a.item1,\n";
    //$str.= ".sw-sf li:hover,\n";
    //$str.= ".sw-sf li.sfHover,\n";
    $str.= ".sw-sf li.sfHover a.item1,\n";
    $str.= ".sw-sf a:focus,\n";
    $str.= ".sw-sf a:hover ,\n";
    $str.= ".sw-sf a:active {\n";

    $str.=$swmenufree['main_back_image_over'] ? " background-image: URL(\"" . $swmenufree['main_back_image_over'] . "\") ;\n" : "";
    $str.=$swmenufree['main_font_color_over'] ? " color: " . $swmenufree['main_font_color_over'] . " !important ; \n" : "";
    $str.=$swmenufree['main_over'] ? " background-color: " . $swmenufree['main_over'] . " !important ; \n" : "";
    $str.="}\n";

    $str.= ".sw-sf .current a.item1{\n";


    $str.= $swmenufree['active_background_image'] ? " background-image: URL(\"" . $swmenufree['active_background_image'] . "\") !important ;\n" : "background-image:none !important;\n";

    $str.=$swmenufree['active_background'] ? " background-color: " . $swmenufree['active_background'] . " !important; \n" : "";
    $str.=$swmenufree['active_font'] ? " color: " . $swmenufree['active_font'] . " !important ; \n" : "";
    $str.="}\n";


    $str.= ".sw-sf  a.item2 {\n";
    $str.= " padding: " . $swmenufree['sub_padding'] . " !important ; \n";
    $str.= " font-size: " . $swmenufree['sub_font_size'] . "px !important ; \n";
    $str.= " font-family: " . $swmenufree['sub_font_family'] . " !important ; \n";
    $str.= " text-align: " . $swmenufree['sub_align'] . " !important ; \n";
    $str.= " font-weight: " . $swmenufree['font_weight_over'] . " !important ; \n";
    switch ($swmenufree['sub_font_extra']) {
        case "italic":
        case "oblique":
            $str.=" font-style:" . $swmenufree['sub_font_extra'] . " !important;\n";
            $str.=" text-decoration: none !important;\n";
            $str.=" text-transform: none !important;\n";
            break;
        case "underline":
        case "overline":
        case "line-through":
            $str.=" text-decoration:" . $swmenufree['sub_font_extra'] . " !important;\n";
            $str.=" font-style: normal !important;\n";
            $str.=" text-transform: none !important;\n";
            break;
        case "uppercase":
        case "lowercase":
        case "capitalize":
            $str.=" text-transform:" . $swmenufree['sub_font_extra'] . " !important;\n";
            $str.=" text-decoration: none !important;\n";
            $str.=" font-style: normal !important;\n";
            break;
        default:
            $str.=" font-style: normal !important;\n";
            $str.=" text-decoration: none !important;\n";
            $str.=" text-transform: none !important;\n";
            break;
    }
    $str.= " display: block; \n";
    $str.=" white-space: " . $swmenufree['sub_wrap'] . " !important; \n";
    $str.=$swmenufree['sub_back_image'] ? " background-image: URL(\"" . $swmenufree['sub_back_image'] . "\") ;\n" : "";
    $str.=$swmenufree['sub_back'] ? " background-color: " . $swmenufree['sub_back'] . " !important ; \n" : "";
    $str.=$swmenufree['sub_font_color'] ? " color: " . $swmenufree['sub_font_color'] . " !important ; \n" : "";

    $str.= " position: relative; \n";
    $str.= " border-top: " . $swmenufree['sub_border_over'] . " !important ; \n";
    $str.= " border-left: " . $swmenufree['sub_border_over'] . " !important ; \n";
    $str.= " border-right: " . $swmenufree['sub_border_over'] . " !important ; \n";
    if ($swmenufree['sub_width'] != 0) {
        $str.= " width:" . $swmenufree['sub_width'] . "px; \n";
    }
    if ($swmenufree['sub_height'] != 0) {
        $str.= " height:" . $swmenufree['sub_height'] . "px; \n";
    }
    $str.= " opacity:" . ($swmenufree['specialA'] / 100) . "; \n";
    $str.= " filter:alpha(opacity=" . ($swmenufree['specialA']) . ") \n";
    $str.="}\n";

    $str.=".sw-sf a.item2.last {\n";
    $str.= " border-bottom: " . $swmenufree['sub_border_over'] . " !important ; \n";
    $str.="}\n";

    //$str.= ".sw-sf li li a:hover,\n";
    //$str.= ".sw-sf li.sfHover li.sfHover li.sw-sf-subactive a.item2,\n";
    $str.= ".sw-sf li.sfHover li.sfHover a.item2,\n";
    $str.= ".sw-sf li.sfHover li.sfHover li.sfHover a.item2,\n";
    $str.= ".sw-sf li.sfHover li.sfHover li.sfHover li.sfHover a.item2,\n";
    $str.= ".sw-sf li.sfHover li.sfHover li.sfHover li.sfHover li.sfHover a.item2,\n";
    $str.= ".sw-sf li.sfHover li.sfHover li.sfHover li.sfHover li.sfHover li.sfHover a.item2,\n";
    $str.= ".sw-sf li.sfHover li.sfHover li.sfHover li.sfHover li.sfHover li.sfHover li.sfHover a.item2,\n";
    $str.= ".sw-sf li.sfHover a.item2:hover,\n";
    $str.= ".sw-sf li.sfHover  li.sfHover a.item2:hover,\n";
    $str.= ".sw-sf li.sfHover  li.sfHover li.sfHover a.item2:hover,\n";
    $str.= ".sw-sf li.sfHover  li.sfHover li.sfHover li.sfHover a.item2:hover,\n";
    $str.= ".sw-sf li.sfHover  li.sfHover li.sfHover li.sfHover li.sfHover a.item2:hover,\n";
    $str.= ".sw-sf li.sfHover  li.sfHover li.sfHover li.sfHover li.sfHover li.sfHover a.item2:hover,\n";
    $str.= ".sw-sf li.sfHover  li.sfHover li.sfHover li.sfHover li.sfHover li.sfHover li.sfHover a.item2:hover,\n";
    $str.= ".sw-sf  a.item2:hover {\n";
    $str.= $swmenufree['sub_back_image_over'] ? " background-image: URL(\"" . $swmenufree['sub_back_image_over'] . "\") ;\n" : " background-image:none ;\n";
    $str.=$swmenufree['sub_over'] ? " background-color: " . $swmenufree['sub_over'] . " !important ; \n" : "";
    $str.=$swmenufree['sub_font_color_over'] ? " color: " . $swmenufree['sub_font_color_over'] . " !important ; \n" : "";
    //$str.= " filter:alpha(opacity=". ($swmenufree['specialA']).") \n";
    $str.= "}\n";

    $str.= ".sw-sf li.sfHover li.sfHover li a.item2,\n";
    $str.= ".sw-sf li.sfHover li.sfHover li.sfHover li a.item2,\n";
    $str.= ".sw-sf li.sfHover li.sfHover li.sfHover li.sfHover li a.item2,\n";
    $str.= ".sw-sf li.sfHover li.sfHover li.sfHover li.sfHover li.sfHover li a.item2,\n";
    $str.= ".sw-sf li.sfHover li.sfHover li.sfHover li.sfHover li.sfHover li.sfHover li a.item2,\n";
    $str.= ".sw-sf li.sfHover li.sfHover li.sfHover li.sfHover li.sfHover li.sfHover li.sfHover li a.item2{\n";
    $str.= $swmenufree['sub_back_image'] ? " background-image: URL(\"" . $swmenufree['sub_back_image'] . "\") ;\n" : " background-image:none ;\n";
    $str.=$swmenufree['sub_back'] ? " background-color: " . $swmenufree['sub_back'] . " !important ; \n" : "";
    $str.=$swmenufree['sub_font_color'] ? " color: " . $swmenufree['sub_font_color'] . " !important ; \n" : "";
    //$str.= " filter:alpha(opacity=". ($swmenufree['specialA']).") \n";
    $str.="}\n";



    /*
      $str.=".sf-shadow ul {\n";
      $str.="background:	url('".$live_site."/modules/mod_swmenufree/images/superfish/shadow.png') no-repeat bottom right !important; \n";
      $str.="padding: 0 8px 9px 0 ; \n";
      $str.="-moz-border-radius-bottomleft: 17px;  \n";
      $str.="-moz-border-radius-topright: 17px;  \n";
      $str.="-webkit-border-top-right-radius: 17px; \n";
      $str.="-webkit-border-bottom-left-radius: 17px; \n";
      $str.="}\n";

      $str.=".sf-shadow ul.sf-shadow-off {\n";
      $str.="background:	transparent; \n";

      $str.="}\n";
     */

    return $str;
}

function transMenuStyleFree($swmenufree, $border_hack) {
    global $mainframe;
    $absolute_path = JPATH_ROOT;
    $live_site = $mainframe->isAdmin() ? $mainframe->getSiteURL() : JURI::base();
    if (substr($live_site, (strlen($live_site) - 1), 1) == "/") {
        $live_site = substr($live_site, 0, (strlen($live_site) - 1));
    }
    if (substr($swmenufree['complete_background_image'], 0, 1) == "/") {
        $swmenufree['complete_background_image'] = substr($swmenufree['complete_background_image'], 1, (strlen($swmenufree['complete_background_image']) - 1));
    }
    if (substr($swmenufree['main_back_image'], 0, 1) == "/") {
        $swmenufree['main_back_image'] = substr($swmenufree['main_back_image'], 1, (strlen($swmenufree['main_back_image']) - 1));
    }
    if (substr($swmenufree['main_back_image_over'], 0, 1) == "/") {
        $swmenufree['main_back_image_over'] = substr($swmenufree['main_back_image_over'], 1, (strlen($swmenufree['main_back_image_over']) - 1));
    }
    if (substr($swmenufree['sub_back_image'], 0, 1) == "/") {
        $swmenufree['sub_back_image'] = substr($swmenufree['sub_back_image'], 1, (strlen($swmenufree['sub_back_image']) - 1));
    }
    if (substr($swmenufree['sub_back_image_over'], 0, 1) == "/") {
        $swmenufree['sub_back_image_over'] = substr($swmenufree['sub_back_image_over'], 1, (strlen($swmenufree['sub_back_image_over']) - 1));
    }
    if (substr($swmenufree['active_background_image'], 0, 1) == "/") {
        $swmenufree['active_background_image'] = substr($swmenufree['active_background_image'], 1, (strlen($swmenufree['active_background_image']) - 1));
    }


    $swmenufree['complete_background_image'] = $swmenufree['complete_background_image'] ? $live_site . "/" . $swmenufree['complete_background_image'] : "";
    $swmenufree['main_back_image'] = $swmenufree['main_back_image'] ? $live_site . "/" . $swmenufree['main_back_image'] : "";
    $swmenufree['main_back_image_over'] = $swmenufree['main_back_image_over'] ? $live_site . "/" . $swmenufree['main_back_image_over'] : "";
    $swmenufree['sub_back_image'] = $swmenufree['sub_back_image'] ? $live_site . "/" . $swmenufree['sub_back_image'] : "";
    $swmenufree['sub_back_image_over'] = $swmenufree['sub_back_image_over'] ? $live_site . "/" . $swmenufree['sub_back_image_over'] : "";
    $swmenufree['active_background_image'] = $swmenufree['active_background_image'] ? $live_site . "/" . $swmenufree['active_background_image'] : "";


    //<style type="text/css">
    //<!--
    $str = ".transMenu {\n";
    $str.= " position:absolute ; \n";
    $str.= " overflow:hidden; \n";
    $str.= " left:-1000px; \n";
    $str.= " top:-1000px; \n";
    $str.= "}\n";

    $str.= ".transMenu .content {\n";
    $str.= " position:absolute  ; \n";
    $str.= "}\n";

    $str.= ".transMenu .items {\n";
    $str.= $swmenufree['sub_width'] ? " width: " . $swmenufree['sub_width'] . "px !important;\n" : "";
    $str.= " border: " . $swmenufree['sub_border'] . " ; \n";
    $str.= " position:relative ; \n";
    $str.= " left:0px; top:0px; \n";
    $str.= " z-index:2; \n";

    $str.= "}\n";

    //$str.= ".transMenu.top .items {\n";
    //$str.= "}\n";

    $str.= ".transMenu  td \n";
    $str.= "{\n";
    //	$str.=" margin:".$swmenufree['top_margin']." !important; \n";
    //$str.= " border: ". $swmenufree['sub_border_over']." ; \n";
    $str.= " padding: " . $swmenufree['sub_padding'] . " !important;  \n";
    $str.= " font-size: " . $swmenufree['sub_font_size'] . "px !important ; \n";
    $str.= " font-family: " . $swmenufree['sub_font_family'] . " !important ; \n";
    $str.= " text-align: " . $swmenufree['sub_align'] . " !important ; \n";
    $str.= " font-weight: " . $swmenufree['font_weight_over'] . " !important ; \n";
    $str.=$swmenufree['sub_font_color'] ? " color: " . $swmenufree['sub_font_color'] . " !important ; \n" : "";

    $str.= "} \n";

    $str.= "#subwrap \n";
    $str.= "{ \n";
    $str.= " text-align: left ; \n";
    $str.= "}\n";

   $str.= ".transMenu  .item:hover td, \n";
	$str.= ".transMenu  .item.hover td\n";
	$str.= "{ \n";
	$str.=$swmenufree['sub_font_color_over']?" color: ".$swmenufree['sub_font_color_over']." !important ; \n":"";
	$str.= "}\n";

    $str.= ".transMenu .item { \n";
    //$str.= is_file($absolute_path."/".$swmenufree['sub_back_image']) ? " background-image: URL(\"".$live_site."/".$swmenufree['sub_back_image']."\") ;\n":"";
    //$str.=$swmenufree['sub_back']?" background-color: ".$swmenufree['sub_back']." !important ; \n":"";
    //$str.=" margin:".$swmenufree['top_margin']." !important; \n";
    //$str.= " opacity:". ($swmenufree['specialA']/100)."; \n";
    //$str.= " filter:alpha(opacity=". ($swmenufree['specialA']).") \n";
    $str.= $swmenufree['sub_height'] ? " height: " . $swmenufree['sub_height'] . "px;" : "";
    $str.= " text-decoration: none ; \n";
    $str.= $swmenufree['sub_width'] ? " width: " . $swmenufree['sub_width'] . "px;" : "";
    switch ($swmenufree['sub_font_extra']) {
        case "italic":
        case "oblique":
            $str.=" font-style:" . $swmenufree['sub_font_extra'] . " !important;\n";
            $str.=" text-decoration: none !important;\n";
            $str.=" text-transform: none !important;\n";
            break;
        case "underline":
        case "overline":
        case "line-through":
            $str.=" text-decoration:" . $swmenufree['sub_font_extra'] . " !important;\n";
            $str.=" font-style: normal !important;\n";
            $str.=" text-transform: none !important;\n";
            break;
        case "uppercase":
        case "lowercase":
        case "capitalize":
            $str.=" text-transform:" . $swmenufree['sub_font_extra'] . " !important;\n";
            $str.=" text-decoration: none !important;\n";
            $str.=" font-style: normal !important;\n";
            break;
        default:
            $str.=" font-style: normal !important;\n";
            $str.=" text-decoration: none !important;\n";
            $str.=" text-transform: none !important;\n";
            break;
    }
    //$str.= " border-bottom: ". $swmenufree['sub_border_over']." ; \n";
    //$str.= " border-left: ". $swmenufree['sub_border_over']." ; \n";
    //$str.= " border-right: ". $swmenufree['sub_border_over']." ; \n";
    //$str.= " display: block; \n";
    $str.=" white-space: " . $swmenufree['sub_wrap'] . "; \n";
    $str.= " cursor:pointer; \n";
    //$str.= " cursor:hand; \n";
    $str.= "}\n";


    $str.= ".transMenu .item td { \n";
    $str.= " border-bottom: " . $swmenufree['sub_border_over'] . " ; \n";
    $str.= " border-left: " . $swmenufree['sub_border_over'] . " ; \n";
    $str.= " border-right: " . $swmenufree['sub_border_over'] . " ; \n";
    //$str.= " display: inline; \n";
    //$str.=" white-space: ".$swmenufree['sub_wrap']."; \n";
    //$str.= " cursor:pointer; \n";
    //$str.= " cursor:hand; \n";
    $str.= "}\n";



    $str.= ".transMenu .item .top_item { \n";
    $str.= " border-top: " . $swmenufree['sub_border_over'] . " ; \n";
    $str.= "}\n";


    $str.= ".transMenu .background {\n";
    $str.= $swmenufree['sub_back_image'] ? " background-image: URL(\"" . $swmenufree['sub_back_image'] . "\") ;\n" : "";
    $str.=$swmenufree['sub_back'] ? " background-color: " . $swmenufree['sub_back'] . " !important ; \n" : "";
    $str.= " position:absolute ; \n";
    $str.= " left:0px; top:0px; \n";
    $str.= " z-index:1; \n";
    $str.= " opacity:" . ($swmenufree['specialA'] / 100) . "; \n";
    $str.= " filter:alpha(opacity=" . ($swmenufree['specialA']) . "); \n";
//$str.= " width:100% !important; \n";
    $str.= "}\n";

    $str.= ".transMenu .shadowRight { \n";
    $str.= " position:absolute ; \n";
    $str.= " z-index:3; \n";
    if ($swmenufree['extra']) {
        $str.= " top:3px; width:2px; \n";
    } else {
        $str.= " top:-3000px; width:2px; \n";
    }
    $str.= " opacity:" . ($swmenufree['specialA'] / 100) . "; \n";
    $str.= " filter:alpha(opacity=" . ($swmenufree['specialA']) . ");\n";
    $str.= "}\n";

    $str.= ".transMenu .shadowBottom { \n";
    $str.= " position:absolute ; \n";
    $str.= " z-index:1; \n";
    if ($swmenufree['extra']) {
        $str.= " left:3px; height:2px; \n";
    } else {
        $str.= " left:-3000px; height:2px; \n";
    }
    $str.= " opacity:" . ($swmenufree['specialA'] / 100) . "; \n";
    $str.= " filter:alpha(opacity=" . ($swmenufree['specialA']) . ");\n";
    $str.= "}\n";

   $str.= ".transMenu .item.hover ,\n";
        $str.= ".transMenu .item:hover {\n";
    $str.= $swmenufree['sub_back_image_over'] ? " background-image: URL(\"" . $swmenufree['sub_back_image_over'] . "\") ;\n" : "";
    $str.=$swmenufree['sub_over'] ? " background-color: " . $swmenufree['sub_over'] . " !important ; \n" : "";
    $str.= "}\n";

    $str.= ".transMenu .transImage { \n";
    $str.= " padding:3px !important ; \n";
    //$str.="text-align:right;\n";
    $str.="width:10px;\n";
    $str.= "}\n";




    $str.= "#td_menu_wrap {\n";
    $str.= " top: " . $swmenufree['main_top'] . "px; \n";
    $str.= " left: " . $swmenufree['main_left'] . "px; \n";
    //$str.= " width:80%; \n";
    //$str.= " margin:0px !important ; \n";
    //$str.= " background-color:#ccc; \n";
    $str.= " border: " . $swmenufree['main_border'] . " ; \n";
    $str.= " z-index: 1; \n";
    $str.= " position:relative; \n";
    $str.=" padding:" . $swmenufree['complete_padding'] . " !important; \n";
    $str.=$swmenufree['complete_background_image'] ? " background-image: URL(\"" . $swmenufree['complete_background_image'] . "\") ;\n" : "";
    //$str.=$swmenufree['main_back']?" background-color: ".$swmenufree['main_back']." !important ; \n":"";
    //if ($swmenufree['main_width']!=0){$str.= " width:".$swmenufree['main_width']."px; \n";}
    //if ($swmenufree['main_height']!=0){$str.= " height:".$swmenufree['main_height']."px; \n";}
    $str.=$swmenufree['complete_background'] ? " background-color: " . $swmenufree['complete_background'] . " !important ; \n" : "";
    $str.= "}\n";

    //$str.= "#swwmenu_wrap {\n";
    //$str.= " top: ". $swmenufree['main_top']."px; \n";
    //$str.= " left: ". $swmenufree['main_left']."px; \n";
    //$str.= " position:relative; \n";
    //  $str.= "}\n";



    $str.= "table.swmenu {\n";
    //$str.= " top: ". $swmenufree['main_top']."px; \n";
    //$str.= " left: ". $swmenufree['main_left']."px; \n";
    //$str.= " position:relative; \n";
    //$str.= " margin:0px !important ; \n";
    //$str.=" padding:".$swmenufree['complete_padding']." !important; \n";
    //$str.=is_file($absolute_path."/".$swmenufree['complete_background_image']) ? " background-image: URL(\"".$live_site."/".$swmenufree['complete_background_image']."\") ;\n":"";
    //$str.=$swmenufree['main_back']?" background-color: ".$swmenufree['main_back']." !important ; \n":"";
    //if ($swmenufree['main_width']!=0){$str.= " width:".$swmenufree['main_width']."px; \n";}
    //if ($swmenufree['main_height']!=0){$str.= " height:".$swmenufree['main_height']."px; \n";}
    //$str.=$swmenufree['complete_background']?" background-color: ".$swmenufree['complete_background']." !important ; \n":"";
    //$str.= " border: ". $swmenufree['main_border']." ; \n";
    //$str.= " width:80%; \n";
    //$str.= " z-index: 200; \n";
    $str.= "}\n";

    $str.= "#swmenu a:hover,\n";
    $str.= "#swmenu a.hover   { \n";
    $str.= $swmenufree['main_back_image_over'] ? " background-image: URL(\"" . $swmenufree['main_back_image_over'] . "\") ;\n" : "";
    $str.=$swmenufree['main_font_color_over'] ? " color: " . $swmenufree['main_font_color_over'] . " !important ; \n" : "";
    $str.=$swmenufree['main_over'] ? " background-color: " . $swmenufree['main_over'] . " !important ; \n" : "";
    $str.= "}\n";

    $str.= "#trans-active a.hover, \n";
    $str.= "#trans-active a:hover, \n";
    $str.= "#trans-active a{\n";
    $str.=$swmenufree['active_background'] ? " background-color: " . $swmenufree['active_background'] . " !important ; \n" : "";
    $str.=$swmenufree['active_font'] ? " color: " . $swmenufree['active_font'] . " !important ; \n" : "";
    //$str.=$swmenufree['main_font_color_over']?" color: ".$swmenufree['main_font_color_over']." !important ; \n":"";
    $str.= $swmenufree['active_background_image'] ? " background-image: URL(\"" . $swmenufree['active_background_image'] . "\") ;\n" : "";
    //$str.=$swmenufree['main_over']?" background-color: ".$swmenufree['main_over']." !important ; \n":"";
    $str.= "} \n";


    $str.= "table.swmenu a{\n";
    $str.= " margin:0px !important ; \n";
    $str.= " padding: " . $swmenufree['main_padding'] . " !important ; \n";
    $str.= " display:block !important; \n";
    $str.= " position:relative !important ; \n";
    $str.=$swmenufree['main_font_color'] ? " color: " . $swmenufree['main_font_color'] . " !important ; \n" : "";
    $str.= "}\n";

    //$str.= "#menu a.hover a,\n";
    $str.= "table.swmenu a.transtop,\n";
    $str.= "table.swmenu a:visited,\n";
    $str.= "table.swmenu a:link {\n";
    if ($swmenufree['main_width'] != 0) {
        $str.= " width:" . $swmenufree['main_width'] . "px; \n";
    }
    if ($swmenufree['main_height'] != 0) {
        $str.= " height:" . $swmenufree['main_height'] . "px; \n";
    }
    $str.= " font-size: " . $swmenufree['main_font_size'] . "px !important ; \n";
    $str.= " font-family: " . $swmenufree['font_family'] . " !important ; \n";
    $str.= " text-align: " . $swmenufree['main_align'] . " !important ; \n";
    $str.= " font-weight: " . $swmenufree['font_weight'] . " !important ; \n";
    $str.=$swmenufree['main_font_color'] ? " color: " . $swmenufree['main_font_color'] . " !important ; \n" : "";
    $str.= " text-decoration: none !important ; \n";
    $str.= " margin-bottom:0px !important ; \n";
    $str.= " display:block !important; \n";
    //$str.= " white-space:nowrap ; \n";
    $str.=$swmenufree['main_back'] ? " background-color: " . $swmenufree['main_back'] . " !important ; \n" : "";
    $str.= $swmenufree['main_back_image'] ? " background-image: URL(\"" . $swmenufree['main_back_image'] . "\") ;\n" : "";
    switch ($swmenufree['top_font_extra']) {
        case "italic":
        case "oblique":
            $str.=" font-style:" . $swmenufree['top_font_extra'] . " !important;\n";
            $str.=" text-decoration: none !important;\n";
            $str.=" text-transform: none !important;\n";
            break;
        case "underline":
        case "overline":
        case "line-through":
            $str.=" text-decoration:" . $swmenufree['top_font_extra'] . " !important;\n";
            $str.=" font-style: normal !important;\n";
            $str.=" text-transform: none !important;\n";
            break;
        case "uppercase":
        case "lowercase":
        case "capitalize":
            $str.=" text-transform:" . $swmenufree['top_font_extra'] . " !important;\n";
            $str.=" text-decoration: none !important;\n";
            $str.=" font-style: normal !important;\n";
            break;
        default:
            $str.=" font-style: normal !important;\n";
            $str.=" text-decoration: none !important;\n";
            $str.=" text-transform: none !important;\n";
            break;
    }
    $str.=" white-space: " . $swmenufree['top_wrap'] . "; \n";
    $str.=" position: relative; \n";
    $str.=" margin:" . $swmenufree['top_margin'] . " !important; \n";

    if (($swmenufree['orientation'] == "vertical/left" || $swmenufree['orientation'] == "vertical/right") && $border_hack) {
        $str.= " border-top: " . $swmenufree['main_border_over'] . "; \n";
        $str.= " border-right: " . $swmenufree['main_border_over'] . "; \n";
        $str.= " border-left: " . $swmenufree['main_border_over'] . "; \n";
        $str.= " border-bottom: 0; \n";
    } else if ($border_hack) {
        $str.= " border-top: " . $swmenufree['main_border_over'] . "; \n";
        $str.= " border-bottom: " . $swmenufree['main_border_over'] . "; \n";
        $str.= " border-left: " . $swmenufree['main_border_over'] . "; \n";
        $str.= " border-right: 0; \n";
    } else {
        $str.= " border: " . $swmenufree['main_border_over'] . ";\n";
    }
    $str.= "}\n";
//echo $border_hack."border";
    $str.= "table.swmenu td {\n";
    if (substr($swmenufree['orientation'], 0, 8) == "vertical") {
        //$str.= " border-right: ".$swmenufree['main_border_over']." ; \n";
    } else {
        //$str.= " border-bottom: ".$swmenufree['main_border_over']." ; \n";
    }
    //$str.= " border-top: ". $swmenufree['main_border_over']." ; \n";
    //$str.= " border-left: ". $swmenufree['main_border_over']." ; \n";
    //$str.= is_file($absolute_path."/".$swmenufree['main_back_image']) ? " background-image: URL(\"".$live_site."/".$swmenufree['main_back_image']."\") ;\n":"";
    //$str.=$swmenufree['main_back']?" background-color: ".$swmenufree['main_back']." !important ; \n":"";
    $str.= "} \n";

    $str.= "table.swmenu td.last a {\n";

    $str.= " border: " . $swmenufree['main_border_over'] . " ; \n";

    $str.= "} \n";






    $str.= "#swmenu span {\n";
    $str.= " display:none; \n";
    $str.= "}\n";



    return $str;
}

?>
