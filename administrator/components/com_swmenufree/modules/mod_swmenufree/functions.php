<?php

/**
 * swmenufree v6.0
 * http://swmenufree.com
 * Copyright 2006 Sean White
 * */
defined('_JEXEC') or die('Restricted access');

function swGetMenuFree($menu, $id, $hybrid, $cache, $cache_time, $use_table, $parent_id, $levels) {

    global $my, $mainframe;
    $start = time();

    $absolute_path = JPATH_ROOT;
    $config = &JFactory::getConfig();
    $langsw = $config->getValue('language');
    $swmenufree_array = array();
    $ordered = array();
    $final_menu = array();
    $file = $absolute_path . "/modules/mod_swmenufree/cache/$menu,$id,$hybrid,$cache,$cache_time,$use_table,$parent_id,$levels,$langsw.cache";
    if ($cache) {
        if (!file_exists($file)) {
            $swmenufree_array = swGetMenuLinksFree($menu, $id, $hybrid, $use_table);
            $final_menu = get_Final_MenuFree($swmenufree_array, $parent_id, $levels);
            $handle = fopen($file, 'w');
            fwrite($handle, var_export($final_menu, 1));
            fclose($handle);
        } else if (strtotime($cache_time, filemtime($file)) < strtotime("now") && is_writable($file)) {
            $swmenufree_array = swGetMenuLinksFree($menu, $id, $hybrid, $use_table);
            $final_menu = get_Final_MenuFree($swmenufree_array, $parent_id, $levels);
            $handle = fopen($file, 'w');
            fwrite($handle, var_export($final_menu, 1));
            fclose($handle);
        } else if (file_exists($file)) {
            $handle = fopen($file, 'r');
            $import = fread($handle, 1000000);
            fclose($handle);
            eval('$final_menu = ' . $import . ';');
        }
    } else {
        $swmenufree_array = swGetMenuLinksFree($menu, $id, $hybrid, $use_table);
        $final_menu = get_Final_MenuFree($swmenufree_array, $parent_id, $levels);
    }
    return $final_menu;
}

function get_Final_MenuFree($swmenufree_array, $parent_id, $levels) {
    global $mainframe;
    $valid = 0;
    $my = & JFactory::getUser();
    $final_menu = array();
    for ($i = 0; $i < count($swmenufree_array); $i++) {
        $swmenu = $swmenufree_array[$i];

        if ($swmenu['ACCESS'] <= $my->gid) {
            if ($swmenu['PARENT'] == $parent_id) {
                $valid++;
            }

            if (strcasecmp(substr($swmenu['URL'], 0, 4), "http")) {
                $swmenu['URL'] = JRoute::_($swmenu['URL'], 1);
            }

            $swmenu['URL'] = str_replace('&amp;', '&', $swmenu['URL']);
            $final_menu[] = array("TITLE" => $swmenu['TITLE'], "URL" => $swmenu['URL'], "ID" => $swmenu['ID'], "PARENT" => $swmenu['PARENT'], "ORDER" => $swmenu['ORDER'], "TARGET" => $swmenu['TARGET'], "ACCESS" => $swmenu['ACCESS']);
        }
    }
    if (count($final_menu) && $valid) {
        $final_menu = chainFree('ID', 'PARENT', 'ORDER', $final_menu, $parent_id, 25);
    } else {
        $final_menu = array();
    }
    return $final_menu;
}

function swGetMenuLinksFree($menu, $id, $hybrid, $use_tables) {
    global $lang, $database, $my, $absolute_path, $offset,$Itemid;
    $database = &JFactory::getDBO();
    $config = &JFactory::getConfig();
    $time_offset = $config->getValue('offset');
    $now = date("Y-m-d H:i:s", time() + $time_offset * 60 * 60);
    $swmenufree_array = array();
    if ($menu == "swcontentmenu") {
        $sql = "SELECT #__sections.* 
                FROM #__sections 
                INNER JOIN #__content ON #__content.sectionid = #__sections.id
                AND #__sections.published = 1
                AND ( publish_up = '0000-00-00 00:00:00' OR publish_up <= '$now'  )
                AND ( publish_down = '0000-00-00 00:00:00' OR publish_down >= '$now' )
                ORDER BY #__content.ordering
                ";
        $database->setQuery($sql);
        $result = $database->loadObjectList();

        for ($i = 0; $i < count($result); $i++) {
            $result2 = $result[$i];

            if ($use_tables) {
                $url = "index.php?option=com_content&view=section&id=" . $result2->id;
            } else {
                $url = "index.php?option=com_content&view=section&layout=blog&id=" . $result2->id;
            }

            $swmenufree_array[] = array("TITLE" => $result2->title, "URL" => $url, "ID" => $result2->id, "PARENT" => 0, "ORDER" => $result2->ordering, "TARGET" => 0, "ACCESS" => $result2->access);
        }

        $sql = "SELECT #__categories.* 
				FROM #__categories
                INNER JOIN #__content ON #__content.catid = #__categories.id
                AND #__categories.published = 1
                AND ( publish_up = '0000-00-00 00:00:00' OR publish_up <= '$now'  )
                AND ( publish_down = '0000-00-00 00:00:00' OR publish_down >= '$now' )
                ORDER BY #__content.ordering
                ";

        $database->setQuery($sql);
        $result = $database->loadObjectList();

        for ($i = 0; $i < count($result); $i++) {
            $result2 = $result[$i];


            if ($use_tables) {
                $url = "index.php?option=com_content&view=category&id=" . $result2->id;
            } else {
                $url = "index.php?option=com_content&view=category&layout=blog&id=" . $result2->id;
            }

            $swmenufree_array[] = array("TITLE" => $result2->title, "URL" => $url, "ID" => $result2->id + 1000, "PARENT" => $result2->section, "ORDER" => $result2->ordering, "TARGET" => 0, "ACCESS" => $result2->access);
        }

        $sql = "SELECT #__content.* 
				FROM #__content
                INNER JOIN #__categories ON #__content.catid = #__categories.id
                AND #__content.state = 1
                AND ( publish_up = '0000-00-00 00:00:00' OR publish_up <= '$now'  )
                AND ( publish_down = '0000-00-00 00:00:00' OR publish_down >= '$now' )
                ORDER BY #__content.ordering
                ";
        $database->setQuery($sql);
        $result = $database->loadObjectList();

        for ($i = 0; $i < count($result); $i++) {
            $result2 = $result[$i];

            $url = "index.php?option=com_content&view=article&catid=" . $result2->catid . "&id=" . $result2->id;
            $swmenufree_array[] = array("TITLE" => $result2->title, "URL" => $url, "ID" => $result2->id + 10000, "PARENT" => $result2->catid + 1000, "ORDER" => $result2->ordering, "TARGET" => 0, "ACCESS" => $result2->access);
        }
    } else if ($menu == "virtuemart" || $menu == "virtueprod") {
        $sql = "SELECT #__vm_category.* ,#__vm_category_xref.*
                FROM #__vm_category
                INNER JOIN #__vm_category_xref ON #__vm_category_xref.category_child_id= #__vm_category.category_id
                AND #__vm_category.category_publish = 'Y'
                ORDER BY #__vm_category.list_order
                ";
        $database->setQuery($sql);
        $result = $database->loadObjectList();
        for ($i = 0; $i < count($result); $i++) {
            $result2 = $result[$i];
            $url = "index.php?option=com_virtuemart&page=shop.browse&category_id=" . $result2->category_id . "&Itemid=" . ($Itemid) . "&swid=" . ($result2->category_id + 10000);
            $swmenufree_array[] = array(
                "TITLE" => $result2->category_name,
                "URL" => $url,
                "ID" => ($result2->category_id + 10000),
                "SECURE" => 0,
                "PARENT" => ($result2->category_parent_id ? (($result2->category_parent_id + 10000)) : 0),
                "ORDER" => $result2->list_order,
                "TARGET" => 0,
                "ACCESS" => 0,
             
            );
            if ($menu == "virtueprod") {
                $sql = "SELECT #__vm_product.* ,#__vm_product_category_xref.*
                FROM #__vm_product
                INNER JOIN #__vm_product_category_xref ON #__vm_product_category_xref.product_id= #__vm_product.product_id
                AND #__vm_product.product_publish = 'Y'
                AND #__vm_product_category_xref.category_id = $result2->category_id
          
                ";
                $database->setQuery($sql);
                $result3 = $database->loadObjectList();
                for ($j = 0; $j < count($result3); $j++) {
                    $result4 = $result3[$j];
                    $url = "index.php?option=com_virtuemart&page=shop.product_details&flypage=shop.flypage&product_id=" . $result4->product_id . "&category_id=" . $result4->category_id . "&manufacturer_id=" . $result4->vendor_id . "&Itemid=" . ($Itemid) . "&swid=" . ($result4->product_id + 100000);
                    $swmenufree_array[] = array(
                        "TITLE" => $result4->product_name,
                        "URL" => $url,
                        "ID" => ($result4->product_id + 100000),
                        "SECURE" => 0,
                        "PARENT" => ($result2->category_id ? (($result2->category_id + 10000)) : 0),
                        "ORDER" => $result2->list_order,
                        "TARGET" => 0,
                        "ACCESS" => 0,
                    );
                }
            }
        }
    } else if ($menu == "virtuemart2" || $menu == "virtueprod2") {
        $sql = "SELECT #__virtuemart_categories_en_gb.* ,#__virtuemart_category_categories.*,#__virtuemart_categories.*
                FROM #__virtuemart_categories,#__virtuemart_categories_en_gb
                INNER JOIN #__virtuemart_category_categories ON #__virtuemart_category_categories.category_child_id= #__virtuemart_categories_en_gb.virtuemart_category_id
                WHERE #__virtuemart_categories.virtuemart_category_id=#__virtuemart_categories_en_gb.virtuemart_category_id
                AND #__virtuemart_categories.published='1'
                ORDER BY #__virtuemart_categories.ordering
                ";
        $database->setQuery($sql);
        $result = $database->loadObjectList();
        
        for ($i = 0; $i < count($result); $i++) {
            $result2 = $result[$i];
            $url = "index.php?option=com_virtuemart&view=category&virtuemart_category_id=" . $result2->virtuemart_category_id ;
            $swmenufree_array[] = array(
                "TITLE" => $result2->category_name,
                "URL" => $url,
                "ID" => ($result2->virtuemart_category_id + 10000),
                "SECURE" => 0,
                "PARENT" => ($result2->category_parent_id ? (($result2->category_parent_id + 10000)) : 0),
                "ORDER" => $result2->ordering,
                "TARGET" => 0,
                "ACCESS" => 0,
             
            );
            if ($menu == "virtueprod2") {
                $sql = "SELECT #__virtuemart_products_en_gb.* ,#__virtuemart_product_categories.*,#__virtuemart_products.*
                FROM #__virtuemart_products,#__virtuemart_products_en_gb
                INNER JOIN #__virtuemart_product_categories ON #__virtuemart_product_categories.virtuemart_product_id= #__virtuemart_products_en_gb.virtuemart_product_id
                WHERE #__virtuemart_products.virtuemart_product_id=#__virtuemart_products_en_gb.virtuemart_product_id
                AND #__virtuemart_product_categories.virtuemart_category_id = $result2->virtuemart_category_id
                AND #__virtuemart_products.published='1'
                
          
                ";
                $database->setQuery($sql);
                $result3 = $database->loadObjectList();
               // echo count($result3);
                for ($j = 0; $j < count($result3); $j++) {
                    $result4 = $result3[$j];
                    $url = "index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=" . $result4->virtuemart_product_id . "&virtuemart_category_id=" . $result4->virtuemart_category_id;
                    $swmenufree_array[] = array(
                        "TITLE" => $result4->product_name,
                        "URL" => $url,
                        "ID" => ($result4->virtuemart_product_id + 100000),
                        "SECURE" => 0,
                        "PARENT" => ($result2->virtuemart_category_id ? (($result2->virtuemart_category_id + 10000)) : 0),
                        "ORDER" => $result2->ordering,
                        "TARGET" => 0,
                        "ACCESS" => 0,
                    );
                }
            }
        }
        
        } else {
        if ($hybrid) {
            $sql = "SELECT #__content.*
				FROM #__content
                INNER JOIN #__categories ON #__content.catid = #__categories.id
                AND #__content.state = 1
                AND ( publish_up = '0000-00-00 00:00:00' OR publish_up <= '$now'  )
                AND ( publish_down = '0000-00-00 00:00:00' OR publish_down >= '$now' )
                ORDER BY #__content.catid,#__content.ordering
                ";
            $database->setQuery($sql);
            $hybrid_content = $database->loadObjectList();


            $sql = "SELECT #__categories.*
				FROM #__categories
                WHERE #__categories.published = 1
                ORDER BY #__categories.ordering
                ";
            $database->setQuery($sql);
            $hybrid_cat = $database->loadObjectList();
        }

        $sql = "SELECT #__menu.* 
				FROM #__menu
                WHERE #__menu.menutype = '" . $menu . "' AND published = '1'
                ORDER BY parent, ordering
            ";

        $database->setQuery($sql);
        $result = $database->loadObjectList();

        $swmenufree_array = array();

        for ($i = 0; $i < count($result); $i++) {
            $result2 = $result[$i];


            switch ($result2->type) {
                case 'separator';
                    $mylink = "javascript:void(0);";
                    break;

                case 'url':
                    $mylink = $result2->link;
                    if (preg_match("/index.php\?/i", $result2->link)) {
                        if (!preg_match("/Itemid=/i", $result2->link)) {
                            $mylink .= "&Itemid=$result2->id";
                        }
                    }
                    break;

                case 'menulink';
                    $mylink = $result2->link;
                    break;

                default:
                    $mylink = "index.php?Itemid=" . $result2->id;
                    break;
            }
            $swmenufree_array[] = array("TITLE" => $result2->name, "URL" => $mylink, "ID" => $result2->id, "PARENT" => $result2->parent, "ORDER" => $result2->ordering, "TARGET" => $result2->browserNav, "ACCESS" => $result2->access);

            if ($hybrid) {
                $opt = array();
                parse_str($result2->link, $opt);
                $opt['view'] = @$opt['view'] ? $opt['view'] : 0;
                $opt['id'] = @$opt['id'] ? $opt['id'] : 0;


                if ($opt['view'] == "blogcategory" || $opt['view'] == "category") {

                    for ($j = 0; $j < count($hybrid_content); $j++) {
                        $row = $hybrid_content[$j];
                        if ($row->catid == $opt['id']) {

                            $url = "index.php?option=com_content&view=article&catid=" . $row->catid . "&id=" . $row->id . "&Itemid=" . $result2->id;
                            $swmenufree_array[] = array("TITLE" => $row->title, "URL" => $url, "ID" => $row->id + 100000, "PARENT" => $result2->id, "ORDER" => $row->ordering, "TARGET" => 0, "ACCESS" => $row->access);
                        }
                    }
                } else if ($opt['view'] == "blogsection" || $opt['view'] == "section") {
                    //echo "hello";
                    for ($j = 0; $j < count($hybrid_cat); $j++) {
                        $row = $hybrid_cat[$j];
                        if ($row->section == $opt['id'] && $opt['id']) {
                            //$j=count($hybrid_cat);

                            if ($use_tables) {
                                $url = "index.php?option=com_content&view=category&id=" . $row->id . "&Itemid=" . $result2->id;
                            } else {
                                $url = "index.php?option=com_content&view=category&layout=blog&id=" . $row->id . "&Itemid=" . $result2->id;
                            }
                            $swmenufree_array[] = array("TITLE" => $row->title, "URL" => $url, "ID" => $row->id + 10000, "PARENT" => $result2->id, "ORDER" => $row->ordering, "TARGET" => 0, "ACCESS" => $row->access);

                            for ($k = 0; $k < count($hybrid_content); $k++) {
                                $row2 = $hybrid_content[$k];
                                if ($row2->catid == $row->id) {

                                    $url = "index.php?option=com_content&view=article&catid=" . $row->id . "&id=" . $row2->id . "&Itemid=" . $result2->id;
                                    $swmenufree_array[] = array("TITLE" => $row2->title, "URL" => $url, "ID" => $row2->id + 100000, "PARENT" => $row->id + 10000, "ORDER" => $row2->ordering, "TARGET" => 0, "ACCESS" => $row2->access);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    return $swmenufree_array;
}

function chainFree($primary_field, $parent_field, $sort_field, $rows, $root_id = 0, $maxlevel = 25) {
    $c = new chainFree($primary_field, $parent_field, $sort_field, $rows, $root_id, $maxlevel);
    return $c->chainFreemenu_table;
}

class chainFree {

    var $table;
    var $rows;
    var $chainFreemenu_table;
    var $primary_field;
    var $parent_field;
    var $sort_field;

    function chainFree($primary_field, $parent_field, $sort_field, $rows, $root_id, $maxlevel) {
        $this->rows = $rows;
        $this->primary_field = $primary_field;
        $this->parent_field = $parent_field;
        $this->sort_field = $sort_field;
        $this->buildchainFree($root_id, $maxlevel);
    }

    function buildchainFree($rootcatid, $maxlevel) {
        foreach ($this->rows as $row) {
            $this->table[$row[$this->parent_field]][$row[$this->primary_field]] = $row;
        }
        $this->makeBranch($rootcatid, 0, $maxlevel);
    }

    function makeBranch($parent_id, $level, $maxlevel) {
        $rows = $this->table[$parent_id];
        $key_array1 = array_keys($rows);
        $key_array_size1 = sizeOf($key_array1);
        //for ($j=0;$j<$key_array_size1;$j++)
        foreach ($rows as $key => $value) {
            //$key = $key_array1[$j];
            $rows[$key]['key'] = $this->sort_field;
        }

        usort($rows, 'chainFreemenuCMP');
        $row_array = array_values($rows);
        $row_array_size = sizeOf($row_array);
        $i = 0;
        foreach ($rows as $item) {
            //$item = $row_array[$i];
            $item['ORDER'] = ($i + 1);
            $item['indent'] = $level;
            $i++;
            $this->chainFreemenu_table[] = $item;
            if ((isset($this->table[$item[$this->primary_field]])) && (($maxlevel > $level + 1) || ($maxlevel == 0))) {
                $this->makeBranch($item[$this->primary_field], $level + 1, $maxlevel);
            }
        }
    }

}

function chainFreemenuCMP($a, $b) {
    if ($a[$a['key']] == $b[$b['key']]) {
        return 0;
    }
    return($a[$a['key']] < $b[$b['key']]) ? -1 : 1;
}

function transMenuFree($ordered, $swmenufree, $active_menu, $sub_indicator, $parent_id, $selectbox_hack, $auto_position, $overlay_hack) {
    global $mainframe;
    $live_site = $mainframe->isAdmin() ? $mainframe->getSiteURL() : JURI::base();
    if (substr($live_site, (strlen($live_site) - 1), 1) == "/") {
        $live_site = substr($live_site, 0, (strlen($live_site) - 1));
    }

    $str = "";
    $name = "";
    $topcounter = 0;
    $counter = 0;
    $number = count(chainFree('ID', 'PARENT', 'ORDER', $ordered, $parent_id, 1));

    $registry = new JRegistry();
    $registry->loadINI($swmenufree['sub_indicator']);
    $sub_indicator = $registry->toObject();
    $top_sub_indicator = $sub_indicator->top_sub_indicator ? $sub_indicator->top_sub_indicator : '';
    $sub_sub_indicator = $sub_indicator->sub_sub_indicator ? $sub_indicator->sub_sub_indicator : '';
    $sub_indicator_align = $sub_indicator->sub_indicator_align ? $sub_indicator->sub_indicator_align : 'left';
    $sub_indicator_top = $sub_indicator->sub_indicator_top ? $sub_indicator->sub_indicator_top : 0;
    $sub_indicator_left = $sub_indicator->sub_indicator_left ? $sub_indicator->sub_indicator_left : 0;



    $str .= "<table id=\"menu_wrap\" class=\"swmenu\" align=\"" . $swmenufree['position'] . "\"><tr><td id=\"td_menu_wrap\" class=\"td_menu_wrap\">\n";
    $str .= "<table cellspacing=\"0\" cellpadding=\"0\" id=\"swmenu\" class=\"swmenu\" > \n";
    if (substr($swmenufree['orientation'], 0, 10) == "horizontal") {
        $str.= "<tr> \n";
    }

    foreach ($ordered as $top) {

        if ($top['indent'] == 0) {
            $top['URL'] = str_replace('&', '&amp;', $top['URL']);
            $topcounter++;

            $name = $top['TITLE'];

            if (substr($swmenufree['orientation'], 0, 8) == "vertical") {
                $str.= "<tr> \n";
            }
            if (($topcounter == $number) && ($top["ID"] == $active_menu)) {
                $str.= "<td id=\"trans-active\" class='last'> \n";
            } else if ($top["ID"] == $active_menu) {
                $str.= "<td id='trans-active'> \n";
            } else if ($topcounter == $number) {
                $str.= "<td class=\"last\"> \n";
            } else {
                $str.= "<td> \n";
            }

            if ((@$ordered[$counter + 1]['indent'] > $top['indent']) && $top_sub_indicator) {

                $name = "<img src='" . $live_site . $top_sub_indicator . "' align='" . $sub_indicator_align . "' style='position:relative;left:" . $sub_indicator_left . "px;top:" . $sub_indicator_top . "px;' alt=''  border='0' />" . $name;
            }

            switch ($top['TARGET']) {
                // cases are slightly different
                case 1:
                    // open in a new window
                    $str.= '<a id="menu' . $top['ID'] . '" href="' . $top['URL'] . '" target="_blank"  >' . $name . '</a>' . "\n";
                    break;

                case 2:
                    // open in a popup window
                    $str.= "<a href=\"#\" id=\"menu" . $top['ID'] . "\" onclick=\"javascript: window.open('" . $top['URL'] . "', '', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=780,height=550'); return false\" >" . $name . "</a>\n";
                    break;

                case 3:
                    // don't link it
                    $str.= '<a id="menu' . $top['ID'] . '" >' . $name . '</a>' . "\n";
                    break;

                default: // formerly case 2
                    $str.= '<a id="menu' . $top['ID'] . '" href="' . $top['URL'] . '" >';

                    $str.= $name . '</a>' . "\n";

                    break;
            }

            //$counter++;
            $str.= "</td> \n";

            if (substr($swmenufree['orientation'], 0, 8) == "vertical") {
                $str.= "</tr> \n";
            }
        }
        $counter++;
    }
    if (substr($swmenufree['orientation'], 0, 10) == "horizontal") {
        $str.= "</tr> \n";
    }
    $str .= "</table></td></tr></table><hr style=\"display:block;clear:left;margin:-0.66em 0;visibility:hidden;\" />  \n";
    $str.= "<div id=\"subwrap\"> \n";
    $str.="<script type=\"text/javascript\">\n";
    $str.="<!--\n";
    $str.="if (TransMenu.isSupported()) {\n";

    if ($swmenufree['orientation'] == "horizontal/down") {
        $str.= "var ms = new TransMenuSet(TransMenu.direction.down, " . $swmenufree['level1_sub_left'] . "," . $swmenufree['level1_sub_top'] . ", TransMenu.reference.bottomLeft);\n";
    } elseif ($swmenufree['orientation'] == "horizontal/up") {
        $str.= "var ms = new TransMenuSet(TransMenu.direction.up, " . $swmenufree['level1_sub_left'] . ", " . $swmenufree['level1_sub_top'] . ", TransMenu.reference.topLeft);\n";
    } elseif ($swmenufree['orientation'] == "horizontal/left") {
        $str.= "var ms = new TransMenuSet(TransMenu.direction.dleft, " . $swmenufree['level1_sub_left'] . "," . $swmenufree['level1_sub_top'] . ", TransMenu.reference.bottomRight);\n";
    } elseif ($swmenufree['orientation'] == "vertical/right") {
        $str.= "var ms = new TransMenuSet(TransMenu.direction.right, " . $swmenufree['level1_sub_left'] . ", " . $swmenufree['level1_sub_top'] . ", TransMenu.reference.topRight);\n";
    } elseif ($swmenufree['orientation'] == "vertical/left") {
        $str.= "var ms = new TransMenuSet(TransMenu.direction.left, " . $swmenufree['level1_sub_left'] . ", " . $swmenufree['level1_sub_top'] . ", TransMenu.reference.topLeft);\n";
    } elseif ($swmenufree['orientation'] == "vertical") {
        $str.= "var ms = new TransMenuSet(TransMenu.direction.right, " . $swmenufree['level1_sub_left'] . ", " . $swmenufree['level1_sub_top'] . ", TransMenu.reference.topRight);\n";
    } elseif ($swmenufree['orientation'] == "horizontal") {
        $str.= "var ms = new TransMenuSet(TransMenu.direction.down, " . $swmenufree['level1_sub_left'] . ", " . $swmenufree['level1_sub_top'] . ", TransMenu.reference.bottomLeft);\n";
    }
    $par = $ordered[0];

    foreach ($ordered as $sub) {
        $name = $sub['TITLE'];
        $sub2 = next($ordered);
        if ($sub['TARGET'] == "3") {
            $sub['TARGET'] = 0;
            $sub['URL'] = "javascript:void(0);";
        }
        if (($sub['indent'] == 0) && (($sub2['indent']) == 1)) {
            $str.= "var menu" . $sub['ID'] . " = ms.addMenu(document.getElementById(\"menu" . $sub['ID'] . "\"));\n ";
        } else if (($sub['ORDER'] == 1) && ($sub['indent'] > 1)) {
            $str.= "var menu" . ($sub['ID']) . " = menu" . findParFree($ordered, $par) . ".addMenu(menu" . findParFree($ordered, $par) . ".items[" . ($par['ORDER'] - 1) . "]," . $swmenufree['level2_sub_left'] . "," . $swmenufree['level2_sub_top'] . ");\n";
        }
        if ($sub['indent'] > 0) {
            $str.= "menu" . findParFree($ordered, $sub) . ".addItem(\"" . addslashes($name) . "\", \"" . addslashes($sub['URL']) . "\", \"" . $sub['TARGET'] . "\");\n";
        }
        $par = $sub;
    }

    if ($swmenufree['top_ttf']) {
        $registry = new JRegistry();
        $registry->loadINI($swmenufree['top_ttf']);
        $params = $registry->toObject();
        $topfontface = @$params->fontFace ? $params->fontFace : 'none';
    } else {
        $topfontface = "";
    }

    $str.="function init() {\n";
    $str.="if (TransMenu.isSupported()) {\n";
    $str.="TransMenu.initialize();\n";
    $counter = 0;
    for ($i = 0; $i < count($ordered); $i++) {
        if ($ordered[$i]['indent'] == 0) {
            $counter++;
            if (@$ordered[$i + 1]['indent'] == 1) {
                $str.= "menu" . ($ordered[$i]['ID']) . ".onactivate = function() {document.getElementById(\"menu" . $ordered[$i]['ID'] . "\").className = \"hover\"; };\n ";
                $str.= "menu" . ($ordered[$i]['ID']) . ".ondeactivate = function() {document.getElementById(\"menu" . $ordered[$i]['ID'] . "\").className = \"\"; };\n ";
            } else {
                $str.= "document.getElementById(\"menu" . $ordered[$i]['ID'] . "\").onmouseover = function() {\n";
                $str.= "ms.hideCurrent();\n";
                $str.= "this.className = \"hover\";\n";
                $str.= "}\n";
                $str.= "document.getElementById(\"menu" . $ordered[$i]['ID'] . "\").onmouseout = function() { this.className = \"\"; }\n";
            }
        }
    }

    $str.="}}\n";
    if ($sub_sub_indicator) {
        $str.="TransMenu.spacerGif = \"" . $live_site . "/modules/mod_swmenufree/images/transmenu/x.gif\";\n";

        $str.="TransMenu.dingbatOn = \"" . $live_site . $sub_sub_indicator . "\";\n";
        $str.="TransMenu.dingbatOff = \"" . $live_site . $sub_sub_indicator . "\"; \n";

        $str.="TransMenu.sub_indicator = true; \n";
    } else {
        $str.="TransMenu.dingbatSize = 0;\n";
        $str.="TransMenu.spacerGif = \"\";\n";
        $str.="TransMenu.dingbatOn = \"\";\n";
        $str.="TransMenu.dingbatOff = \"\"; \n";
        $str.="TransMenu.sub_indicator = false;\n";
    }
    $str.="TransMenu.menuPadding = 0;\n";
    $str.="TransMenu.itemPadding = 0;\n";
    $str.="TransMenu.shadowSize = 2;\n";
    $str.="TransMenu.shadowOffset = 3;\n";
    $str.="TransMenu.shadowColor = \"#888\";\n";
    $str.="TransMenu.shadowPng = \"" . $live_site . "/modules/mod_swmenufree/images/transmenu/grey-40.png\";\n";
    $str.="TransMenu.backgroundColor = \"" . $swmenufree['sub_back'] . "\";\n";
    $str.="TransMenu.backgroundPng = \"" . $live_site . "/modules/mod_swmenufree/images/transmenu/white-90.png\";\n";
    $str.="TransMenu.hideDelay = " . ($swmenufree['specialB'] * 2) . ";\n";
    $str.="TransMenu.slideTime = " . $swmenufree['specialB'] . ";\n";
    $str .= "TransMenu.selecthack = " . $selectbox_hack . ";\n";
    $str .= "TransMenu.autoposition = " . $auto_position . ";\n";
    $str .= "TransMenu.fontFace = \"" . $topfontface . "\";\n";
    $str .= "TransMenu.fontColor = \"" . $swmenufree['main_font_color'] . "\";\n";
    // $str .= "TransMenu.activeId = \"" . $active_id . "\";\n";
//    $str .= "TransMenu.preview = \"" . $preview . "\";\n";
    $str .= "TransMenu.renderAll();\n";


    $str.="if ( typeof window.addEventListener != \"undefined\" )\n";
    $str.="window.addEventListener( \"load\", init, false );\n";
    $str.="else if ( typeof window.attachEvent != \"undefined\" ) {\n";
    $str.="window.attachEvent( \"onload\", init);\n";
    $str.="}else{\n";
    $str.="if ( window.onload != null ) {\n";
    $str.="var oldOnload = window.onload;\n";
    $str.="window.onload = function ( e ) {\n";
    $str.="oldOnload( e );\n";
    $str.="init();\n";
    $str.="}\n}else\n";
    $str.="window.onload = init();\n";
    $str.="}\n}\n\n";

    $border1 = explode(" ", $swmenufree['main_border']);
    $border2 = explode(" ", $swmenufree['sub_border']);
    $border1[0] = rtrim(trim($border1[0]), 'px');
    $border2[0] = rtrim(trim($border2[0]), 'px');
    $border1[1] = trim($border1[1]);
    $border2[1] = trim($border2[1]);
    $border1[2] = trim($border1[2]);
    $border2[2] = trim($border2[2]);
    $border3 = explode(" ", $swmenufree['main_border_over']);
    $border4 = explode(" ", $swmenufree['sub_border_over']);
    $border3[0] = rtrim(trim($border3[0]), 'px');
    $border4[0] = rtrim(trim($border4[0]), 'px');
    $border3[1] = trim($border3[1]);
    $border4[1] = trim($border4[1]);
    $border3[2] = trim($border3[2]);
    $border4[2] = trim($border4[2]);

    if ($swmenufree['corners']) {
        $registry = new JRegistry();
        $registry->loadINI($swmenufree['corners']);
        $params = $registry->toObject();
        $c_corner_style = @$params->c_corner_style ? $params->c_corner_style : 0;
        if (($c_corner_style != 'none') && $c_corner_style) {
            $c_corner_size = @$params->c_corner_size ? $params->c_corner_size : 10;
            $ctl_corner = @$params->ctl_corner ? $params->ctl_corner : 0;
            $ctr_corner = @$params->ctr_corner ? $params->ctr_corner : 0;
            $cbl_corner = @$params->cbl_corner ? $params->cbl_corner : 0;
            $cbr_corner = @$params->cbr_corner ? $params->cbr_corner : 0;

            if (($border1[0] > 0) && ($border1[1] != 'none')) {
                $str .= "if (jQuery.browser.msie) {\n";
                //  $str .= "jQuery('#menu_wrap').css('z-index','-1');\n";
                //  $str .= "jQuery('#td_menu_wrap').css('z-index','-1');\n";
                //    $str .= "jQuery('#td_menu_wrap').css('position','relative');\n";
                $str .= "}\n";
                // $str .= "jQuery('#outertable').css('border', '0');\n";
                $str .= "jQuery('#td_menu_wrap').css('border', '0');\n";
                $str .= "jQuery('#menu_wrap').wrap('<table ><tr><td id=\"#swwmenu_wrap\"></td></tr></table>');\n";
                $str .= "jQuery('#menu_wrap').parent().css('background-color', '" . $border1[2] . "');\n";

                //$str .= "jQuery('#menu_wrap').parent().css('position', 'relative');\n";
                //    $str .= "jQuery('#menu_wrap').parent().position({left:" . $swmenufree['main_left'] . "});\n";
                $str .= "jQuery('#menu_wrap').parent().css('padding', '" . $border1[0] . "px');\n";
                $str .= "jQuery('#menu_wrap').parent().corner('" . $c_corner_style . " " . ($ctl_corner ? 'tl' : '') . " " . ($ctr_corner ? 'tr' : '') . " " . ($cbl_corner ? 'bl' : '') . " " . ($cbr_corner ? 'br' : '') . " " . ($c_corner_size + $border1[0]) . "px');\n";
                //$str .= "jQuery('#td_menu_wrap').css('z-index','1');\n";
            }



            //$str .= "jQuery('#wrap').wrap('<div></div>');\n";
            //$str .= "jQuery('#td_menu_wrap').css('z-index','-1');\n";
            $str .= "jQuery('#td_menu_wrap ').corner('" . $c_corner_style . " " . ($ctl_corner ? 'tl' : '') . " " . ($ctr_corner ? 'tr' : '') . " " . ($cbl_corner ? 'bl' : '') . " " . ($cbr_corner ? 'br' : '') . " " . $c_corner_size . "px');\n";
            //  $str .= "jQuery('div#wrap').css('position','absolute');\n";
        }
        $t_corner_style = @$params->t_corner_style ? $params->t_corner_style : 0;
        if (($t_corner_style != 'none') && $t_corner_style) {
            $t_corner_size = @$params->t_corner_size ? $params->t_corner_size : 10;
            $ttl_corner = @$params->ttl_corner ? $params->ttl_corner : 0;
            $ttr_corner = @$params->ttr_corner ? $params->ttr_corner : 0;
            $tbl_corner = @$params->tbl_corner ? $params->tbl_corner : 0;
            $tbr_corner = @$params->tbr_corner ? $params->tbr_corner : 0;
            $str .= "jQuery('#menu_wrap a').corner('" . $t_corner_style . " " . ($ttl_corner ? 'tl' : '') . " " . ($ttr_corner ? 'tr' : '') . " " . ($tbl_corner ? 'bl' : '') . " " . ($tbr_corner ? 'br' : '') . " " . $t_corner_size . "px');\n";
        }
        $s_corner_style = @$params->s_corner_style ? $params->s_corner_style : 0;
        if (($s_corner_style != 'none') && $s_corner_style) {
            $s_corner_size = @$params->s_corner_size ? $params->s_corner_size : 10;
            $stl_corner = @$params->stl_corner ? $params->stl_corner : 0;
            $str_corner = @$params->str_corner ? $params->str_corner : 0;
            $sbl_corner = @$params->sbl_corner ? $params->sbl_corner : 0;
            $sbr_corner = @$params->sbr_corner ? $params->sbr_corner : 0;
            $str .= "jQuery('#subwrap .background').corner('" . $s_corner_style . " " . ($stl_corner ? 'tl' : '') . " " . ($str_corner ? 'tr' : '') . " " . ($sbl_corner ? 'bl' : '') . " " . ($sbr_corner ? 'br' : '') . " " . $s_corner_size . "px');\n";
            $str .= "jQuery('#subwrap  .item.hover td ').corner('" . $s_corner_style . " " . ($stl_corner ? 'tl' : '') . " " . ($str_corner ? 'tr' : '') . " " . ($sbl_corner ? 'bl' : '') . " " . ($sbr_corner ? 'br' : '') . " " . $s_corner_size . "px');\n";
        }
    }





    if ($swmenufree['top_ttf']) {
        $registry = new JRegistry();
        $registry->loadINI($swmenufree['top_ttf']);
        $params = $registry->toObject();
        $topfontface = @$params->fontFace ? $params->fontFace : 'none';
        $str .= "Cufon.replace('table.swmenu a',{hover: true, fontFamily: '" . $topfontface . "' });\n";
    }
    if ($swmenufree['sub_ttf']) {
        $registry = new JRegistry();
        $registry->loadINI($swmenufree['sub_ttf']);
        $params = $registry->toObject();
        $subfontface = @$params->fontFace ? $params->fontFace : 'none';
        $str .= "Cufon.replace('#subwrap .item ',{hover: true, fontFamily: '" . $subfontface . "' });\n";
    }
    if ($overlay_hack) {
        $str .= "jQuery(document).ready(function($){\n";
        $str .= "jQuery('#menu_wrap').parents().css('overflow','visible');\n";
        $str .= "jQuery('html').css('overflow','auto');\n";
        $str .= "jQuery('#menu_wrap').parents().css('z-index','1');\n";
        $str .= "jQuery('#menu_wrap').css('z-index','101');\n";
        $str .= "});\n";
    }
    $str .= "//--> \n";
    $str .= "</script></div>  \n";
    return $str;
}

function findParFree($ordered, $sub) {
    $submenu = chainFree('ID', 'PARENT', 'ORDER', $ordered, $sub['PARENT'], 1);

    if ($sub['indent'] == 1) {
        return $submenu[0]['PARENT'];
    } else {
        return $submenu[0]['ID'];
    }
}

function GosuMenuFree($ordered, $swmenufree, $active_menu, $selectbox_hack, $auto_position, $overlay_hack) {
    global $mainframe, $Itemid;
    $absolute_path = JPATH_ROOT;
    $live_site = $mainframe->isAdmin() ? $mainframe->getSiteURL() : JURI::base();
    if (substr($live_site, (strlen($live_site) - 1), 1) == "/") {
        $live_site = substr($live_site, 0, (strlen($live_site) - 1));
    }
    $sub_active = 0;
    $name = "";
    $counter = 0;
    $doMenu = 1;
    $uniqueID = $swmenufree['id'];
    $number = count($ordered);
    $activesub = -1;
    $activesub2 = -1;
    $topcount = 0;
    $subcounter = 0;

    $registry = new JRegistry();
    $registry->loadINI($swmenufree['sub_indicator']);
    $sub_indicator = $registry->toObject();
    $top_sub_indicator = $sub_indicator->top_sub_indicator ? $sub_indicator->top_sub_indicator : '';
    $sub_sub_indicator = $sub_indicator->sub_sub_indicator ? $sub_indicator->sub_sub_indicator : '';
    $sub_indicator_align = $sub_indicator->sub_indicator_align ? $sub_indicator->sub_indicator_align : 'left';
    $sub_indicator_top = $sub_indicator->sub_indicator_top ? $sub_indicator->sub_indicator_top : 0;
    $sub_indicator_left = $sub_indicator->sub_indicator_left ? $sub_indicator->sub_indicator_left : 0;






    $str = "<table  id=\"outertable\" align=\"" . $swmenufree['position'] . "\" class=\"outer\"><tr><td><div id=\"outerwrap\">\n";
    $str .= "<table cellspacing=\"0\" border=\"0\" cellpadding=\"0\" id=\"menu\" class=\"ddmx\"  > \n";
    if ($swmenufree['orientation'] == "horizontal/down" || $swmenufree['orientation'] == "horizontal" || $swmenufree['orientation'] == "horizontal/left" || $swmenufree['orientation'] == "horizontal/up") {
        $str .= "<tr> \n";
    }
    while ($doMenu) {
        if ($ordered[$counter]['indent'] == 0) {
            $ordered[$counter]['URL'] = str_replace('&', '&amp;', $ordered[$counter]['URL']);
            $name = ($ordered[$counter]['TITLE']);
            if ($swmenufree['orientation'] == "vertical/right" || $swmenufree['orientation'] == "vertical" || $swmenufree['orientation'] == "vertical/left") {
                $str .= "<tr> \n";
            }
            $act = 0;
            if (islastFree($ordered, $counter)) {
                if (($ordered[$counter]['ID'] == $active_menu)) {
                    $str .= "<td class='item11 acton last'> \n";
                    $act = 1;
                    $activesub = $topcount;
                } else {
                    $str .= "<td class='item11 last'> \n";
                }
            } else {
                if (($ordered[$counter]['ID'] == $active_menu)) {
                    $str .= "<td class='item11 acton'> \n";
                    $act = 1;
                    $activesub = $topcount;
                } else {
                    $str .= "<td class='item11'> \n";
                }
            }
            $topcount++;

            // echo $top_sub_indicator;
            $classname = "item1";
            if ($ordered[$counter]['indent'] > @$ordered[$counter - 1]['indent']) {
                $classname .= " first";
            }
            if (($counter + 1 == $number) || islastFree($ordered, $counter)) {
                $classname .= " last";
            }
            if (($counter + 1 != $number) && ($ordered[$counter + 1]['indent'] > $ordered[$counter]['indent']) && $top_sub_indicator) {

                $name = "<img src='" . $live_site . $top_sub_indicator . "' align='" . $sub_indicator_align . "' style='position:relative;left:" . $sub_indicator_left . "px;top:" . $sub_indicator_top . "px;' alt=''  />" . $name;
            }


            switch ($ordered[$counter]['TARGET']) {
                case 1:
                    $str .= '<a href="' . $ordered[$counter]['URL'] . '" target="_blank" class="' . $classname . '" >' . $name . '</a>';
                    break;
                case 2:
                    $str .= "<a href=\"#\" onclick=\"javascript: window.open('" . $ordered[$counter]['URL'] . "', '', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=780,height=550'); return false\" class='" . $classname . "'>" . $name . "</a>\n";
                    break;
                case 3:
                    $str .= '<a class="' . $classname . '" >' . $name . '</a>';
                    break;
                default:
                    $str .= '<a href="' . $ordered[$counter]['URL'] . '" class="' . $classname . '">' . $name . '</a>';
                    break;
            }

            if ($counter + 1 == $number) {
                $doSubMenu = 0;
                $doMenu = 0;
                $str .= "<div class=\"section\" style=\"border:0 !important;display:none;\"></div> \n";
            } elseif ($ordered[$counter + 1]['indent'] == 0) {
                $doSubMenu = 0;
                $str .= "<div class=\"section\" style=\"border:0 !important;display:none;\"></div> \n";
            } else {
                $doSubMenu = 1;
            }
            $counter++;
            if ($activesub2 == -1) {
                $subcounter = 0;
            }
            while ($doSubMenu) {
                if ($ordered[$counter]['indent'] != 0) {
                    if ($ordered[$counter]['indent'] > $ordered[$counter - 1]['indent']) {
                        if ($act && $sub_active && ($swmenufree['orientation'] == "vertical/right")) {
                            $str .= '<div class="subsection"  >';
                        } else {
                            $str .= '<div class="section"  >';
                        }
                    }
                    $ordered[$counter]['URL'] = str_replace('&', '&amp;', $ordered[$counter]['URL']);
                    $name = ($ordered[$counter]['TITLE']);
                    if (($counter + 1 == $number) || ($ordered[$counter + 1]['indent'] == 0)) {
                        $doSubMenu = 0;
                    }
                    $style = " style=\"";
                    $classname = "item2";
                    if ($ordered[$counter]['indent'] > $ordered[$counter - 1]['indent']) {
                        $classname .= " first";
                    }
                    if (($counter + 1 == $number) || islastFree($ordered, $counter)) {
                        $classname .= " last";
                    }
                    if (($counter + 1 != $number) && ($ordered[$counter + 1]['indent'] > $ordered[$counter]['indent']) && $sub_sub_indicator) {

                        $name = "<img src='" . $live_site . $sub_sub_indicator . "' align='" . $sub_indicator_align . "' style='position:relative;left:" . $sub_indicator_left . "px;top:" . $sub_indicator_top . "px;' alt=''   />" . $name;
                    }
                    $style .= "\" ";

                    switch ($ordered[$counter]['TARGET']) {
                        case 1:
                            $str .= '<a href="' . $ordered[$counter]['URL'] . '" ' . $style . ' target="_blank" class="' . $classname . '" >' . $name . '</a>';
                            break;
                        case 2:
                            $str .= "<a href=\"#\" " . $style . " onclick=\"javascript: window.open('" . $ordered[$counter]['URL'] . "', '', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=780,height=550'); return false\" class=\"" . $classname . "\">" . $name . "</a>\n";
                            break;
                        case 3:
                            $str .= '<a class="' . $classname . '" ' . $style . ' >' . $name . '</a>';
                            break;
                        default:
                            $str .= "<a href=\"" . $ordered[$counter]['URL'] . "\" class=\"" . $classname . "\" " . $style . ">" . $name . "</a>\n";
                            break;
                    }

                    if (($counter + 1 == $number) || ($ordered[$counter + 1]['indent'] < $ordered[$counter]['indent'])) {
                        $str .= str_repeat('</div>', (($ordered[$counter]['indent']) - (@$ordered[$counter + 1]['indent'])));
                    }
                    $counter++;
                }
            }
        }
        $str .= "</td> \n";
        if ($swmenufree['orientation'] == "vertical/right" || $swmenufree['orientation'] == "vertical/left" || $swmenufree['orientation'] == "vertical") {
            $str .= "</tr> \n";
        }
        if ($counter == ($number)) {
            $doMenu = 0;
        }
    }
    if ($swmenufree['orientation'] == "horizontal/down" || $swmenufree['orientation'] == "horizontal/left" || $swmenufree['orientation'] == "horizontal/up" || $swmenufree['orientation'] == "horizontal") {
        $str .= "</tr> \n";
    }
    $str .= "</table></div></td></tr></table><hr style=\"display:block;clear:left;margin:-0.66em 0;visibility:hidden;\" /> \n";
    $str .= "<script type=\"text/javascript\">\n";
    $str .= "<!--\n";
    $str .= "function makemenu(){\n";
    $str .= "var ddmx = new DropDownMenuX('menu');\n";
    $str .= "ddmx.type = '" . $swmenufree['orientation'] . "'; \n";
    $str .= "ddmx.delay.show = 0;\n";
    $str .= "ddmx.iframename = 'ddmx';\n";
    $str .= "ddmx.delay.hide = " . $swmenufree['specialB'] . ";\n";
    $str .= "ddmx.effect = '" . ($swmenufree['extra'] ? $swmenufree['extra'] : 'none') . "';\n";
    $str .= "ddmx.position.levelX.left = " . $swmenufree['level2_sub_left'] . ";\n";
    $str .= "ddmx.position.levelX.top = " . $swmenufree['level2_sub_top'] . ";\n";
    $str .= "ddmx.position.level1.left = " . $swmenufree['level1_sub_left'] . ";\n";
    $str .= "ddmx.position.level1.top = " . $swmenufree['level1_sub_top'] . "; \n";
    $str .= "ddmx.fixIeSelectBoxBug = " . ($selectbox_hack ? 'true' : 'false') . ";\n";
    $str .= "ddmx.autoposition = " . ($auto_position ? 'true' : 'false') . ";\n";
    if ($sub_active && ($swmenufree['orientation'] == "horizontal")) {
        $str .= "ddmx.activesub='menu-" . $activesub . "-section' ;\n";
    } else {
        $str .= "ddmx.activesub='' ;\n";
    }
    $str .= "ddmx.init(); \n";
    $str .= "}\n";
    $str .= "if ( typeof window.addEventListener != \"undefined\" )\n";
    $str .= "window.addEventListener( \"load\", makemenu, false );\n";
    $str .= "else if ( typeof window.attachEvent != \"undefined\" ) { \n";
    $str .= "window.attachEvent( \"onload\", makemenu );\n";
    $str .= "}\n";
    $str .= "else {\n";
    $str .= "if ( window.onload != null ) {\n";
    $str .= "var oldOnload = window.onload;\n";
    $str .= "window.onload = function ( e ) { \n";
    $str .= "oldOnload( e ); \n";
    $str .= "makemenu() \n";
    $str .= "} \n";
    $str .= "}  \n";
    $str .= "else  { \n";
    $str .= "window.onload = makemenu();\n";
    $str .= "} }\n";
    $str .= "//--> \n";
    $str .= "</script>  \n";
    $str .= "<script type=\"text/javascript\">\n";
    $str .= "<!--\n";
    $border1 = explode(" ", $swmenufree['main_border']);
    $border2 = explode(" ", $swmenufree['sub_border']);
    $border1[0] = rtrim(trim($border1[0]), 'px');
    $border2[0] = rtrim(trim($border2[0]), 'px');
    $border1[1] = trim($border1[1]);
    $border2[1] = trim($border2[1]);
    $border1[2] = trim($border1[2]);
    $border2[2] = trim($border2[2]);
    $border3 = explode(" ", $swmenufree['main_border_over']);
    $border4 = explode(" ", $swmenufree['sub_border_over']);
    $border3[0] = rtrim(trim($border3[0]), 'px');
    $border4[0] = rtrim(trim($border4[0]), 'px');
    $border3[1] = trim($border3[1]);
    $border4[1] = trim($border4[1]);
    $border3[2] = trim($border3[2]);
    $border4[2] = trim($border4[2]);
    if ($swmenufree['corners']) {
        $registry = new JRegistry();
        $registry->loadINI($swmenufree['corners']);
        $params = $registry->toObject();
        $c_corner_style = @$params->c_corner_style ? $params->c_corner_style : 0;
        if (($c_corner_style != 'none') && $c_corner_style) {
            $c_corner_size = @$params->c_corner_size ? $params->c_corner_size : 10;
            $ctl_corner = @$params->ctl_corner ? $params->ctl_corner : 0;
            $ctr_corner = @$params->ctr_corner ? $params->ctr_corner : 0;
            $cbl_corner = @$params->cbl_corner ? $params->cbl_corner : 0;
            $cbr_corner = @$params->cbr_corner ? $params->cbr_corner : 0;
            if (($border1[0] > 0) && ($border1[1] != 'none')) {
                $str .= "if (jQuery.browser.msie) {\n";
                $str .= "jQuery('#outerwrap').css('z-index','-1');\n";
                $str .= "}\n";
                $str .= "jQuery('#outertable').css('border', '0');\n";
                $str .= "jQuery('#outerwrap').css('border', '0');\n";
                $str .= "jQuery('#outerwrap').wrap('<div></div>');\n";
                $str .= "jQuery('#outerwrap').parent().css('background-color', '" . $border1[2] . "');\n";
                $str .= "jQuery('#outerwrap').parent().css('padding', '" . $border1[0] . "px');\n";
                $str .= "jQuery('#outerwrap').parent().corner('" . $c_corner_style . " " . ($ctl_corner ? 'tl' : '') . " " . ($ctr_corner ? 'tr' : '') . " " . ($cbl_corner ? 'bl' : '') . " " . ($cbr_corner ? 'br' : '') . " " . ($c_corner_size + $border1[0]) . "px');\n";
            }
            $str .= "jQuery('#outerwrap ').corner('" . $c_corner_style . " " . ($ctl_corner ? 'tl' : '') . " " . ($ctr_corner ? 'tr' : '') . " " . ($cbl_corner ? 'bl' : '') . " " . ($cbr_corner ? 'br' : '') . " " . $c_corner_size . "px');\n";
        }
        $t_corner_style = @$params->t_corner_style ? $params->t_corner_style : 0;
        if (($t_corner_style != 'none') && $t_corner_style) {
            $t_corner_size = @$params->t_corner_size ? $params->t_corner_size : 10;
            $ttl_corner = @$params->ttl_corner ? $params->ttl_corner : 0;
            $ttr_corner = @$params->ttr_corner ? $params->ttr_corner : 0;
            $tbl_corner = @$params->tbl_corner ? $params->tbl_corner : 0;
            $tbr_corner = @$params->tbr_corner ? $params->tbr_corner : 0;
            if (($border3[0] != 0) && ($border3[1] != 'none')) {
                $str .= "if (jQuery.browser.msie) {\n";
                $str .= "jQuery('#menu .item1').css('z-index','-1');\n";
                $str .= "}\n";
                $str .= "jQuery('#menu .item1').css('border', '0');\n";
                $str .= "jQuery('#menu .item1').css('margin', '0');\n";
                $str .= "jQuery('#menu .item1').wrap('<div></div>');\n";
                $str .= "jQuery('#menu .item1').parent().css('margin', '" . $swmenufree['top_margin'] . "');\n";
                $str .= "jQuery('#menu .item1').parent().css('background-color', '" . $border3[2] . "');\n";
                $str .= "jQuery('#menu .item1').parent().css('padding', '" . $border3[0] . "px');\n";
                $str .= "jQuery('#menu .item1').css('left', '-0.5px');\n";
                $str .= "jQuery('#menu .item1').parent().corner('" . $t_corner_style . " " . ($ttl_corner ? 'tl' : '') . " " . ($ttr_corner ? 'tr' : '') . " " . ($tbl_corner ? 'bl' : '') . " " . ($tbr_corner ? 'br' : '') . " " . ($t_corner_size + $border3[0]) . "px');\n";
            }
            $str .= "jQuery('#menu .item1').corner('" . $t_corner_style . " " . ($ttl_corner ? 'tl' : '') . " " . ($ttr_corner ? 'tr' : '') . " " . ($tbl_corner ? 'bl' : '') . " " . ($tbr_corner ? 'br' : '') . " " . $t_corner_size . "px');\n";
        }
        $s_corner_style = @$params->s_corner_style ? $params->s_corner_style : 0;
        if (($s_corner_style != 'none') && $s_corner_style) {
            $s_corner_size = @$params->s_corner_size ? $params->s_corner_size : 10;
            $stl_corner = @$params->stl_corner ? $params->stl_corner : 0;
            $str_corner = @$params->str_corner ? $params->str_corner : 0;
            $sbl_corner = @$params->sbl_corner ? $params->sbl_corner : 0;
            $sbr_corner = @$params->sbr_corner ? $params->sbr_corner : 0;
            if (($border2[0] != 0) && ($border2[1] != 'none')) {
                $str .= "if (jQuery.browser.msie) {\n";
                $str .= "}\n";
                $str .= "jQuery('.ddmx .section .item2').wrap('<div></div>');\n";
                $str .= "jQuery('.ddmx .section').css('border','0');";
                $str .= "jQuery('.ddmx .section .item2').parent().css('padding-right', '" . $border2[0] . "px');\n";
                $str .= "jQuery('.ddmx .section .item2').parent().css('padding-left', '" . $border2[0] . "px');\n";
                $str .= "jQuery('.ddmx .section .item2.first').parent().css('padding-top', '" . $border2[0] . "px');\n";
                $str .= "jQuery('.ddmx .section .item2.last').parent().css('padding-bottom', '" . $border2[0] . "px');\n";
                $str .= "jQuery('.ddmx .section .item2' ).parent().css('background-color', '" . $border2[2] . "');\n";
                $str .= "jQuery('.ddmx .section .item2.first').parent().corner('" . $s_corner_style . " " . ($stl_corner ? 'tl' : '') . " " . ($str_corner ? 'tr' : '') . " " . ($s_corner_size + $border2[0]) . "px');\n";
                $str .= "jQuery('.ddmx .section .item2.last').parent().corner('" . $s_corner_style . " " . ($sbl_corner ? 'bl' : '') . " " . ($sbr_corner ? 'br' : '') . " " . ($s_corner_size + $border2[0]) . "px');\n";
                $str .= "jQuery('#menu .section .item2.first').corner('" . $s_corner_style . " " . ($stl_corner ? 'tl' : '') . " " . ($str_corner ? 'tr' : '') . " " . $s_corner_size . "px');\n";
                $str .= "jQuery('#menu .section .item2.last').corner('" . $s_corner_style . " " . ($sbl_corner ? 'bl' : '') . " " . ($sbr_corner ? 'br' : '') . " " . $s_corner_size . "px');\n";
            } else {
                if ($stl_corner + $str_corner != 0) {
                    $str .= "jQuery('#menu .section .item2.first').corner('" . $s_corner_style . " " . ($stl_corner ? 'tl' : '') . " " . ($str_corner ? 'tr' : '') . " " . $s_corner_size . "px');\n";
                }
                if ($sbl_corner + $sbr_corner != 0) {
                    $str .= "jQuery('#menu .section .item2.last').corner('" . $s_corner_style . " " . ($sbl_corner ? 'bl' : '') . " " . ($sbr_corner ? 'br' : '') . " " . $s_corner_size . "px');\n";
                }
            }
        }
    }

    if ($overlay_hack) {
        $str .= "jQuery('#menu').parents().css('overflow','visible');\n";
        $str .= "jQuery('html').css('overflow','auto');\n";
        $str .= "jQuery('#menu').parents().css('z-index','100');\n";
        $str .= "jQuery('#menu').css('z-index','101');\n";
    }
    if ($swmenufree['top_ttf']) {
        $registry = new JRegistry();
        $registry->loadINI($swmenufree['top_ttf']);
        $params = $registry->toObject();
        $fontfile = @$params->fontFile ? $params->fontFile : '';
        $fontface = @$params->fontFace ? $params->fontFace : '';
        $str .= "Cufon.replace('.ddmx .item1',{hover: true, fontFamily: '" . $fontface . "' });\n";
    }
    if ($swmenufree['sub_ttf']) {
        $registry = new JRegistry();
        $registry->loadINI($swmenufree['sub_ttf']);
        $params = $registry->toObject();
        $fontfile = @$params->fontFile ? $params->fontFile : '';
        $fontface = @$params->fontFace ? $params->fontFace : '';
        $str .= "Cufon.replace('.ddmx .item2',{hover: true, fontFamily: '" . $fontface . "' });\n";
    }
    $str .= "//-->\n";
    $str .= "</script>\n";
    return $str;
}

function SuperfishMenuFree($ordered, $swmenufree, $active_menu, $selectbox_hack, $overlay_hack) {
    global $mainframe, $Itemid;
    $absolute_path = JPATH_ROOT;
    $live_site = $mainframe->isAdmin() ? $mainframe->getSiteURL() : JURI::base();

    $name = "";
    $counter = 0;
    $doMenu = 1;
    //$uniqueID = $swmenufree['id'];
    $number = count($ordered);
    $activesub = -1;
    $activesub2 = -1;
    $topcount = 0;
    $subcounter = 0;

    $registry = new JRegistry();
    $registry->loadINI($swmenufree['sub_indicator']);
    $sub_indicator = $registry->toObject();
    $top_sub_indicator = $sub_indicator->top_sub_indicator ? $sub_indicator->top_sub_indicator : '';
    $sub_sub_indicator = $sub_indicator->sub_sub_indicator ? $sub_indicator->sub_sub_indicator : '';
    $sub_indicator_align = $sub_indicator->sub_indicator_align ? $sub_indicator->sub_indicator_align : 'left';
    $sub_indicator_top = $sub_indicator->sub_indicator_top ? $sub_indicator->sub_indicator_top : 0;
    $sub_indicator_left = $sub_indicator->sub_indicator_left ? $sub_indicator->sub_indicator_left : 0;

    $str = "<div id=\"sfmenu\" align=\"" . $swmenufree['position'] . "\" >\n";
    if ($swmenufree['orientation'] == "horizontal") {
        $str.= "<ul  id=\"menu\" class=\"sw-sf\"  > \n";
    } else {

        $str.= "<ul  id=\"menu\" class=\"sw-sf sf-vertical\"  > \n";
    }


    //if ($swmenufree['orientation']=="horizontal"){$str.= "<tr> \n";}

    while ($doMenu) {

        if ($ordered[$counter]['indent'] == 0) {
            $ordered[$counter]['URL'] = str_replace('&', '&amp;', $ordered[$counter]['URL']);
            $name = $ordered[$counter]['TITLE'];

            if (($counter + 1 != $number) && ($ordered[$counter + 1]['indent'] > $ordered[$counter]['indent']) && $top_sub_indicator) {

                $name = "<img src='" . $live_site . $top_sub_indicator . "' align='" . $sub_indicator_align . "' style='position:relative;left:" . $sub_indicator_left . "px;top:" . $sub_indicator_top . "px;' alt=''  />" . $name;
            }

            if ($swmenufree['orientation'] == "vertical") {
                //	$str.= "<tr> \n";
            }
            $act = 0;
            if (islastFree($ordered, $counter)) {
                if (($ordered[$counter]['ID'] == $active_menu)) {
                    $str.= "<li id='sf-" . $ordered[$counter]['ID'] . "' class='current'> \n";
                    $act = 1;
                    $activesub = $topcount;
                } else {
                    $str.= "<li id='sf-" . $ordered[$counter]['ID'] . "' > \n";
                }
            } else {
                if (($ordered[$counter]['ID'] == $active_menu)) {
                    $str.= "<li id='sf-" . $ordered[$counter]['ID'] . "' class='current'> \n";
                    $act = 1;
                    $activesub = $topcount;
                } else {
                    $str.= "<li id='sf-" . $ordered[$counter]['ID'] . "' > \n";
                }
            }
            $topcount++;
            //echo $ordered[$counter]['URL']."<br />";


            switch ($ordered[$counter]['TARGET']) {
                // cases are slightly different
                case 1:
                    // open in a new window
                    if (islastFree($ordered, $counter)) {
                        $str.= '<a href="' . $ordered[$counter]['URL'] . '" target="_blank" class="item1 last" >' . $name . '</a>';
                    } else {
                        $str.= '<a href="' . $ordered[$counter]['URL'] . '" target="_blank" class="item1" >' . $name . '</a>';
                    }

                    break;

                case 2:
                    // open in a popup window
                    if (islastFree($ordered, $counter)) {
                        $str.= "<a href=\"#\" onclick=\"javascript: window.open('" . $ordered[$counter]['URL'] . "', '', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=780,height=550'); return false\" class=\"item1 last\">" . $name . "</a>\n";
                    } else {
                        $str.= "<a href=\"#\" onclick=\"javascript: window.open('" . $ordered[$counter]['URL'] . "', '', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=780,height=550'); return false\" class=\"item1\">" . $name . "</a>\n";
                    }

                    break;

                case 3:
                    // don't link it
                    if (islastFree($ordered, $counter)) {
                        $str.= '<a class="item1 last" >' . $name . '</a>';
                    } else {
                        $str.= '<a class="item1" >' . $name . '</a>';
                    }

                    break;

                default: // formerly case 2
                    // open in parent window
                    if (islastFree($ordered, $counter)) {
                        $str.= "<a href='" . $ordered[$counter]['URL'] . "' class='item1 last'>" . $name . "</a>\n";
                    } else {
                        $str.= "<a href='" . $ordered[$counter]['URL'] . "' class='item1'>" . $name . "</a>\n";
                    }
                    break;
            }


            if ($counter + 1 == $number) {
                $doSubMenu = 0;
                $doMenu = 0;
                //$str.= "<div class=\"section\" style=\"border:0 !important;\"></div> \n";
            } elseif ($ordered[$counter + 1]['indent'] == 0) {
                $doSubMenu = 0;
                //$str.= "<div class=\"section\" style=\"border:0 !important;\"></div> \n";
            } else {
                $doSubMenu = 1;
            }


            $counter++;
            if ($activesub2 == -1) {
                $subcounter = 0;
            }

            while ($doSubMenu) {
                if ($ordered[$counter]['indent'] != 0) {
                    if ($ordered[$counter]['indent'] > $ordered[$counter - 1]['indent']) {
                        $str.= "<ul class='sf-section' >\n";
                    }
                    $ordered[$counter]['URL'] = str_replace('&', '&amp;', $ordered[$counter]['URL']);
                    $name = $ordered[$counter]['TITLE'];

                    if (($counter + 1 == $number) || ($ordered[$counter + 1]['indent'] == 0)) {
                        $doSubMenu = 0;
                        //$str.= "</li> \n";
                    }
                    //$style=" style=\"";
                    $li_class = "";
                    $a_class = "item2";

                    if (($counter + 1 == $number) || islastFree($ordered, $counter)) {
                        $a_class.=" last";
                    }
                    if ($ordered[$counter]['indent'] > $ordered[$counter - 1]['indent']) {
                        $a_class.=" first";
                    }

                    if (($counter + 1 != $number) && ($ordered[$counter + 1]['indent'] > $ordered[$counter]['indent']) && $sub_sub_indicator) {

                        $name = "<img src='" . $live_site . $sub_sub_indicator . "' align='" . $sub_indicator_align . "' style='position:relative;left:" . $sub_indicator_left . "px;top:" . $sub_indicator_top . "px;' alt=''   />" . $name;
                    }

                    if (($ordered[$counter]['ID'] == $Itemid)) {
                        $li_class = "sf-" . $ordered[$counter]['ID'] . "";
                    } else {
                        $li_class = "sf-" . $ordered[$counter]['ID'] . "";
                    }


                    $str.="<li id=\"" . $li_class . "\">";


                    switch ($ordered[$counter]['TARGET']) {
                        // cases are slightly different
                        case 1:
                            // open in a new window
                            $str.= '<a href="' . $ordered[$counter]['URL'] . '" target="_blank" class="' . $a_class . '" >' . $name . '</a>';
                            break;

                        case 2:
                            // open in a popup window
                            $str.= "<a href=\"#\" onclick=\"javascript: window.open('" . $ordered[$counter]['URL'] . "', '', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=780,height=550'); return false\" class=\"" . $a_class . "\">" . $name . "</a>\n";
                            break;

                        case 3:
                            // don't link it
                            $str.= '<a class="' . $a_class . '" >' . $name . '</a>';
                            break;

                        default: // formerly case 2
                            // open in parent window
                            $str.= "<a href=\"" . $ordered[$counter]['URL'] . "\" class=\"" . $a_class . "\" >" . $name . "</a>\n";
                            break;
                    }




                    if (($counter + 1 == $number) || ($ordered[$counter + 1]['indent'] < $ordered[$counter]['indent'])) {
                        $str .= str_repeat("</li></ul>\n", (($ordered[$counter]['indent']) - (@$ordered[$counter + 1]['indent'])));
                        if (($ordered[$counter + 1]['indent'] > 0)) {
                            $str .= "</li> \n";
                        }
                    } else if (($ordered[$counter + 1]['indent'] <= $ordered[$counter]['indent'])) {
                        $str .= "</li> \n";
                    }
                    $counter++;
                }
            }
            $str.= "</li> \n";
        }

        //$str.= "</li> \n";

        if ($swmenufree['orientation'] == "vertical") {
            //$str.= "</tr> \n";
        }
        if ($counter == ($number)) {
            $doMenu = 0;
        }
    }
    //if ($swmenufree['orientation']=="horizontal"){$str.= "</tr> \n";}
    $str .= "</ul><hr style=\"display:block;clear:left;margin:-0.66em 0;visibility:hidden;\" /></div> \n";


    if ($swmenufree['sub_width'] > 0) {
        $str.="<script type=\"text/javascript\">\n";
        $str.="<!--\n";
        $str.="jQuery.noConflict();\n";
        $str.="jQuery(document).ready(function($){\n";
        $str.="$('.sw-sf').superfish({\n";
        switch ($swmenufree['extra']) {
            // cases are slightly different
            case "fade":
                $str.="animation:   {opacity:'show'},";
                $str.="speed:  " . $swmenufree['specialB'] . ",";
                break;

            case "slide-down":
                $str.="animation:   {height:'show'},";
                $str.="speed:  " . $swmenufree['specialB'] . ",";
                break;

            case "slide-right":
                $str.="animation:   {width:'show'},";
                $str.="speed:  " . $swmenufree['specialB'] . ",";
                break;

            default:
                //	$str.="animation:   {opacity:'show'},";
                $str.="speed:   1,";
                break;
        }
        // $str.="animation:   {opacity:'show',width:'show'},";

        $str.="autoArrows:  false\n";

        //$str.="dropShadows: true\n";
        //$str.="pathClass:  'current' \n";
        $str.="});\n";

        if ($overlay_hack) {
            //$str.="alert($.topZIndex());\n";
            //  $str.="$('#left_container').topZIndex();\n";
            $str.="$('.sw-sf').parents().css('overflow','visible');\n";
            $str.="$('html').css('overflow','auto');\n";
            $str.="$('.sw-sf').parents().css('z-index','100');\n";
            $str.="$('.sw-sf').css('z-index','101');\n";
        }
        /// $str.="$('#menu".$uniqueID." ).dropShadow();\n";
        $str.="});\n";
    } else {

        $str.="<script type=\"text/javascript\">\n";
        $str.="<!--\n";
        $str.="jQuery.noConflict();\n";
        //$str.="alert($.topZIndex());\n";
        $str.="jQuery(document).ready(function($){\n";
        $str.="$('.sw-sf').supersubs({ \n";
        $str.="minWidth:8,\n";
        $str.="maxWidth:80,\n";
        $str.="extraWidth:2\n";
        $str.="}).superfish({\n";
        switch ($swmenufree['extra']) {
            // cases are slightly different
            case "fade":
                $str.="animation:   {opacity:'show'},";
                $str.="speed:  " . $swmenufree['specialB'] . ",";
                break;

            case "slide-down":
                $str.="animation:   {height:'show'},";
                $str.="speed:  " . $swmenufree['specialB'] . ",";
                break;

            case "slide-right":
                $str.="animation:   {width:'show'},";
                $str.="speed:  " . $swmenufree['specialB'] . ",";
                break;

            default:
                //	$str.="animation:   {opacity:'show'},";
                $str.="speed:   1,";
                break;
        }

        //$str.="animation:   {opacity:'show',width:'show'},";

        $str.="autoArrows:  false\n";

        //$str.="dropShadows: true\n";
        //$str.="pathClass:  'current' \n";
        $str.="});\n";

        //$str.="$.fx.off=true;\n";

        if ($overlay_hack) {
            //$str.="alert($.topZIndex());\n";
            //  $str.="$('#left_container').topZIndex();\n";
            $str.="$('.sw-sf').parents().css('overflow','visible');\n";
            $str.="$('html').css('overflow','auto');\n";
            $str.="$('.sw-sf').parents().css('z-index','100');\n";
            $str.="$('.sw-sf').css('z-index','101');\n";
        }
        $str.="});\n";
    }


    $str.= "//--> \n";
    $str.= "</script>  \n";
    $str .= "<script type=\"text/javascript\">\n";
    $str .= "<!--\n";
    $border1 = explode(" ", $swmenufree['main_border']);
    $border2 = explode(" ", $swmenufree['sub_border']);
    $border1[0] = rtrim(trim($border1[0]), 'px');
    $border2[0] = rtrim(trim($border2[0]), 'px');
    $border1[1] = trim($border1[1]);
    $border2[1] = trim($border2[1]);
    $border1[2] = trim($border1[2]);
    $border2[2] = trim($border2[2]);
    $border3 = explode(" ", $swmenufree['main_border_over']);
    $border4 = explode(" ", $swmenufree['sub_border_over']);
    $border3[0] = rtrim(trim($border3[0]), 'px');
    $border4[0] = rtrim(trim($border4[0]), 'px');
    $border3[1] = trim($border3[1]);
    $border4[1] = trim($border4[1]);
    $border3[2] = trim($border3[2]);
    $border4[2] = trim($border4[2]);
    if ($swmenufree['corners']) {
        $registry = new JRegistry();
        $registry->loadINI($swmenufree['corners']);
        $params = $registry->toObject();
        $c_corner_style = @$params->c_corner_style ? $params->c_corner_style : 0;
        if (($c_corner_style != 'none') && $c_corner_style) {
            $c_corner_size = @$params->c_corner_size ? $params->c_corner_size : 10;
            $ctl_corner = @$params->ctl_corner ? $params->ctl_corner : 0;
            $ctr_corner = @$params->ctr_corner ? $params->ctr_corner : 0;
            $cbl_corner = @$params->cbl_corner ? $params->cbl_corner : 0;
            $cbr_corner = @$params->cbr_corner ? $params->cbr_corner : 0;

            if (($border1[0] > 0) && ($border1[1] != 'none')) {
                $str .= "if (jQuery.browser.msie) {\n";
                $str .= "jQuery('#sfmenu').css('z-index','-1');\n";
                //  $str .= "jQuery('#td_menu_wrap').css('z-index','-1');\n";
                //    $str .= "jQuery('#td_menu_wrap').css('position','relative');\n";
                $str .= "}\n";
                // $str .= "jQuery('#outertable').css('border', '0');\n";
                $str .= "jQuery('#sfmenu').css('border', '0 !important');\n";
                $str .= "jQuery('#sfmenu').wrap('<div></div>');\n";
                $str .= "jQuery('#sfmenu').parent().css('background-color', '" . $border1[2] . "');\n";

                //$str .= "jQuery('#menu_wrap').parent().css('position', 'relative');\n";
                //    $str .= "jQuery('#menu_wrap').parent().position({left:" . $swmenupro['main_left'] . "});\n";
                $str .= "jQuery('#sfmenu').parent().css('padding', '" . $border1[0] . "px');\n";
                $str .= "jQuery('#sfmenu').parent().corner('" . $c_corner_style . " " . ($ctl_corner ? 'tl' : '') . " " . ($ctr_corner ? 'tr' : '') . " " . ($cbl_corner ? 'bl' : '') . " " . ($cbr_corner ? 'br' : '') . " " . ($c_corner_size + $border1[0]) . "px');\n";
                //$str .= "jQuery('#td_menu_wrap').css('z-index','1');\n";
            }



            //$str .= "jQuery('#wrap').wrap('<div></div>');\n";
            //$str .= "jQuery('#td_menu_wrap').css('z-index','-1');\n";
            $str .= "jQuery('#sfmenu ').corner('" . $c_corner_style . " " . ($ctl_corner ? 'tl' : '') . " " . ($ctr_corner ? 'tr' : '') . " " . ($cbl_corner ? 'bl' : '') . " " . ($cbr_corner ? 'br' : '') . " " . $c_corner_size . "px');\n";
            //  $str .= "jQuery('div#wrap').css('position','absolute');\n";
        }
        $t_corner_style = @$params->t_corner_style ? $params->t_corner_style : 0;
        if (($t_corner_style != 'none') && $t_corner_style) {
            $t_corner_size = @$params->t_corner_size ? $params->t_corner_size : 10;
            $ttl_corner = @$params->ttl_corner ? $params->ttl_corner : 0;
            $ttr_corner = @$params->ttr_corner ? $params->ttr_corner : 0;
            $tbl_corner = @$params->tbl_corner ? $params->tbl_corner : 0;
            $tbr_corner = @$params->tbr_corner ? $params->tbr_corner : 0;
            $str .= "jQuery('.sw-sf a.item1').corner('" . $t_corner_style . " " . ($ttl_corner ? 'tl' : '') . " " . ($ttr_corner ? 'tr' : '') . " " . ($tbl_corner ? 'bl' : '') . " " . ($tbr_corner ? 'br' : '') . " " . $t_corner_size . "px');\n";
        }
        $s_corner_style = @$params->s_corner_style ? $params->s_corner_style : 0;
        if (($s_corner_style != 'none') && $s_corner_style) {
            $s_corner_size = @$params->s_corner_size ? $params->s_corner_size : 10;
            $stl_corner = @$params->stl_corner ? $params->stl_corner : 0;
            $str_corner = @$params->str_corner ? $params->str_corner : 0;
            $sbl_corner = @$params->sbl_corner ? $params->sbl_corner : 0;
            $sbr_corner = @$params->sbr_corner ? $params->sbr_corner : 0;
            $str .= "jQuery('.sf-section a.item2.last').corner('" . $s_corner_style . " " . ($sbl_corner ? 'bl' : '') . " " . ($sbr_corner ? 'br' : '') . " " . $s_corner_size . "px');\n";
            $str .= "jQuery('.sf-section a.item2.first').corner('" . $s_corner_style . " " . ($stl_corner ? 'tl' : '') . " " . ($str_corner ? 'tr' : '') . " " . $s_corner_size . "px');\n";
        }
    }



    if ($swmenufree['top_ttf']) {
        $registry = new JRegistry();
        $registry->loadINI($swmenufree['top_ttf']);
        $params = $registry->toObject();
        $fontfile = @$params->fontFile ? $params->fontFile : '';
        $fontface = @$params->fontFace ? $params->fontFace : '';
        $str .= "Cufon.replace('.sw-sf .item1',{hover: true, fontFamily: '" . $fontface . "' });\n";
    }
    if ($swmenufree['sub_ttf']) {
        $registry = new JRegistry();
        $registry->loadINI($swmenufree['sub_ttf']);
        $params = $registry->toObject();
        $fontfile = @$params->fontFile ? $params->fontFile : '';
        $fontface = @$params->fontFace ? $params->fontFace : '';
        $str .= "Cufon.replace('.sw-sf .item2',{hover: true, fontFamily: '" . $fontface . "' });\n";
    }
    $str .= "//-->\n";
    $str .= "</script>\n";

    return $str;
}

function islastFree($array, $id) {

    $this_level = $array[$id]['indent'];
    $last = 0;
    $i = $id + 1;
    $do = 1;
    while ($do) {

        if (@$array[$i]['indent'] < $this_level || $i == count($array)) {
            $last = 1;
        }
        if (@$array[$i]['indent'] <= $this_level) {
            $do = 0;
        }
        $i++;
    }
    return $last;
}

function swmenuGetBrowserFree() {

    $br = new swBrowserFree;
    // echo substr($br->Name.$br->Version,0,5);


    return($br->Name . $br->Version);
}

function inAgentFree($agent) {
    global $HTTP_USER_AGENT;
    $notAgent = strpos($HTTP_USER_AGENT, $agent) === false;
    return !$notAgent;
}

function fixPaddingFree(&$swmenufree) {

    $padding1 = explode("px", $swmenufree['main_padding']);
    $padding2 = explode("px", $swmenufree['sub_padding']);
    for ($i = 0; $i < 4; $i++) {
        $padding1[$i] = trim(@$padding1[$i]);
        $padding2[$i] = trim(@$padding2[$i]);
    }
    if ($swmenufree['main_width'] != 0) {
        $swmenufree['main_width'] = ($swmenufree['main_width'] - ($padding1[1] + $padding1[3]));
    }
    if ($swmenufree['main_height'] != 0) {
        $swmenufree['main_height'] = ($swmenufree['main_height'] - ($padding1[0] + $padding1[2]));
    }
    if ($swmenufree['sub_width'] != 0) {
        $swmenufree['sub_width'] = ($swmenufree['sub_width'] - ($padding2[1] + $padding2[3]));
    }
    if (@$swmenufree['sub_width'] != 0) {
        $swmenufree['sub_height'] = ($swmenufree['sub_height'] - ($padding2[0] + $padding2[2]));
    }
    return($swmenufree);
}

function sw_getactiveFree($ordered) {
    $current_itemid = trim(JRequest::getVar('Itemid', 0));
    $current_id = trim(JRequest::getVar('id', 0));
    $current_task = trim(JRequest::getVar('task', 0));
    
      $cur_option = trim(JRequest::getVar('option', 0));
      
     if (($cur_option == "com_virtuemart")) {
        
            $prod_id = trim(JRequest::getVar('virtuemart_product_id', 0));
            $cat_id  = trim(JRequest::getVar('virtuemart_category_id', 0));
            if ($prod_id) {
                $current_itemid = $prod_id + 100000;
            } else {
                $current_itemid = $cat_id + 10000;
            }
        
    }

    if (!$current_itemid && $current_id) {

        if (preg_match("/category/i", $current_task)) {
            $current_itemid = $current_id + 1000;
        } elseif (preg_match("/section/i", $current_task)) {
            $current_itemid = $current_id;
        } elseif (preg_match("/view/i", $current_task)) {
            $current_itemid = $current_id + 10000;
        }
    }
    $indent = 0;
    $parent_value = $current_itemid;
    $parent = 1;
    $id = 0;
    while ($parent) {
        for ($i = 0; $i < count($ordered); $i++) {
            $row = $ordered[$i];
            if ($row['ID'] == $parent_value) {
                $parent_value = $row['PARENT'];
                $indent = $row['indent'];
                $id = $row['ID'];
            }
        }
        if (!$indent) {
            $parent = 0;
        }
    }
    return ($id);
}

class swBrowserFree {

    var $Name = "Unknown";
    var $Version = "Unknown";
    var $Platform = "Unknown";
    var $UserAgent = "Not reported";
    var $AOL = false;

    function swBrowserFree() {
        $agent = $_SERVER['HTTP_USER_AGENT'];

        // initialize properties
        $bd['platform'] = "Unknown";
        $bd['swBrowserFree'] = "Unknown";
        $bd['version'] = "Unknown";
        $this->UserAgent = $agent;

        // find operating system
        if (preg_match("/win/i", $agent))
            $bd['platform'] = "Windows";
        elseif (preg_match("/mac/i", $agent))
            $bd['platform'] = "MacIntosh";
        elseif (preg_match("/linux/i", $agent))
            $bd['platform'] = "Linux";
        elseif (preg_match("/OS2/i", $agent))
            $bd['platform'] = "OS/2";
        elseif (preg_match("/BeOS/i", $agent))
            $bd['platform'] = "BeOS";

        // test for Opera        
        if (preg_match("/opera/i", $agent)) {
            $val = stristr($agent, "opera");
            if (preg_match("//i", $val)) {
                $val = explode("/", $val);
                $bd['swBrowserFree'] = $val[0];
                $val = explode(" ", $val[1]);
                $bd['version'] = $val[0];
            } else {
                $val = explode(" ", stristr($val, "opera"));
                $bd['swBrowserFree'] = $val[0];
                $bd['version'] = $val[1];
            }

            // test for WebTV
        } elseif (preg_match("/webtv/i", $agent)) {
            $val = explode("/", stristr($agent, "webtv"));
            $bd['swBrowserFree'] = $val[0];
            $bd['version'] = $val[1];

            // test for MS Internet Explorer version 1
        } elseif (preg_match("/microsoft internet explorer/i", $agent)) {
            $bd['swBrowserFree'] = "MSIE";
            $bd['version'] = "1.0";
            $var = stristr($agent, "/");
            if (preg("/308|425|426|474|0b1/", $var)) {
                $bd['version'] = "1.5";
            }

            // test for NetPositive
        } elseif (preg_match("/NetPositive/i", $agent)) {
            $val = explode("/", stristr($agent, "NetPositive"));
            $bd['platform'] = "BeOS";
            $bd['swBrowserFree'] = $val[0];
            $bd['version'] = $val[1];

            // test for MS Internet Explorer
        } elseif (preg_match("/msie/i", $agent) && !preg_match("/opera/i", $agent)) {
            $val = explode(" ", stristr($agent, "msie"));
            $bd['swBrowserFree'] = $val[0];
            $bd['version'] = $val[1];

            // test for MS Pocket Internet Explorer
        } elseif (preg_match("/mspie/i", $agent) || preg_match('/pocket/i', $agent)) {
            $val = explode(" ", stristr($agent, "mspie"));
            $bd['swBrowserFree'] = "MSPIE";
            $bd['platform'] = "WindowsCE";
            if (preg_match("/mspie/i", $agent))
                $bd['version'] = $val[1];
            else {
                $val = explode("/", $agent);
                $bd['version'] = $val[1];
            }

            // test for Galeon
        } elseif (preg_match("/galeon/i", $agent)) {
            $val = explode(" ", stristr($agent, "galeon"));
            $val = explode("/", $val[0]);
            $bd['swBrowserFree'] = $val[0];
            $bd['version'] = $val[1];

            // test for Konqueror
        } elseif (preg_match("/Konqueror/i", $agent)) {
            $val = explode(" ", stristr($agent, "Konqueror"));
            $val = explode("/", $val[0]);
            $bd['swBrowserFree'] = $val[0];
            $bd['version'] = $val[1];

            // test for iCab
        } elseif (preg_match("/icab/i", $agent)) {
            $val = explode(" ", stristr($agent, "icab"));
            $bd['swBrowserFree'] = $val[0];
            $bd['version'] = $val[1];

            // test for OmniWeb
        } elseif (preg_match("/omniweb/i", $agent)) {
            $val = explode("/", stristr($agent, "omniweb"));
            $bd['swBrowserFree'] = $val[0];
            $bd['version'] = $val[1];

            // test for Phoenix
        } elseif (preg_match("/Phoenix/i", $agent)) {
            $bd['swBrowserFree'] = "Phoenix";
            $val = explode("/", stristr($agent, "Phoenix/"));
            $bd['version'] = $val[1];

            // test for Firebird
        } elseif (preg_match("/firebird/i", $agent)) {
            $bd['swBrowserFree'] = "Firebird";
            $val = stristr($agent, "Firebird");
            $val = explode("/", $val);
            $bd['version'] = $val[1];

            // test for Firefox
        } elseif (preg_match("/Firefox/i", $agent)) {
            $bd['swBrowserFree'] = "Firefox";
            $val = stristr($agent, "Firefox");
            $val = explode("/", $val);
            $bd['version'] = $val[1];

            // test for Mozilla Alpha/Beta Versions
        } elseif (preg_match("/mozilla/i", $agent) &&
                preg_match("/rv:[0-9].[0-9][a-b]/i", $agent) && !preg_match("/netscape/i", $agent)) {
            $bd['swBrowserFree'] = "Mozilla";
            $val = explode(" ", stristr($agent, "rv:"));
            preg_match("/rv:[0-9].[0-9][a-b]/i", $agent, $val);
            $bd['version'] = str_replace("rv:", "", $val[0]);

            // test for Mozilla Stable Versions
        } elseif (preg_match("/mozilla/i", $agent) &&
                preg_match("/rv:[0-9]\.[0-9]/i", $agent) && !preg_match("/netscape/i", $agent)) {
            $bd['swBrowserFree'] = "Mozilla";
            $val = explode(" ", stristr($agent, "rv:"));
            preg_match("/rv:[0-9]\.[0-9]\.[0-9]/i", $agent, $val);
            $bd['version'] = str_replace("rv:", "", $val[0]);

            // test for Lynx & Amaya
        } elseif (preg_match("/libwww/i", $agent)) {
            if (preg_match("/amaya/i", $agent)) {
                $val = explode("/", stristr($agent, "amaya"));
                $bd['swBrowserFree'] = "Amaya";
                $val = explode(" ", $val[1]);
                $bd['version'] = $val[0];
            } else {
                $val = explode("/", $agent);
                $bd['swBrowserFree'] = "Lynx";
                $bd['version'] = $val[1];
            }

            // test for Safari
        } elseif (preg_match("/safari/i", $agent)) {
            $bd['swBrowserFree'] = "Safari";
            $bd['version'] = "";

            // remaining two tests are for Netscape
        } elseif (preg_match("/netscape/i", $agent)) {
            $val = explode(" ", stristr($agent, "netscape"));
            $val = explode("/", $val[0]);
            $bd['swBrowserFree'] = $val[0];
            $bd['version'] = $val[1];
        } elseif (preg_match("/mozilla/i", $agent) && !preg_match("/rv:[0-9]\.[0-9]\.[0-9]/i", $agent)) {
            $val = explode(" ", stristr($agent, "mozilla"));
            $val = explode("/", $val[0]);
            $bd['swBrowserFree'] = "Netscape";
            $bd['version'] = $val[1];
        }

        // clean up extraneous garbage that may be in the name
        $bd['swBrowserFree'] = preg_replace("[^a-z,A-Z]", "", $bd['swBrowserFree']);
        // clean up extraneous garbage that may be in the version        
        $bd['version'] = preg_replace("[^0-9,.,a-z,A-Z]", "", $bd['version']);

        // check for AOL
        if (preg_match("/AOL/i", $agent)) {
            $var = stristr($agent, "AOL");
            $var = explode(" ", $var);
            $bd['aol'] = preg_replace("[^0-9,.,a-z,A-Z]", "", $var[1]);
        }

        // finally assign our properties
        $this->Name = $bd['swBrowserFree'];
        $this->Version = $bd['version'];
        $this->Platform = $bd['platform'];
        // $this->AOL = $bd['aol'];
        //echo $this->Name;
    }

}

?>
