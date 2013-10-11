<?php
/*
# ------------------------------------------------------------------------
# Free Slide SP1 - Slideshow module for Joomla 1.5
# ------------------------------------------------------------------------
# Copyright (C) 2010 JoomShaper.com. All Rights Reserved.
# @license - GNU/GPL, see LICENSE.php,
# Author: JoomShaper.com
# Websites:  http://www.joomshaper.com -  http://www.joomxpert.com
# ------------------------------------------------------------------------
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once (JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');

class modFSSP1Helper
{
		
	function getList(&$params){
		
		global $mainframe;
		
		$cparams	    =& $mainframe->getParams('com_content');

		$db			    =& JFactory::getDBO();
		$user		    =& JFactory::getUser();
		$userId		    = (int) $user->get('id');
		
		$count		    = $params->get('max_article',3); 
		$catid		    = $params->get('com_categories');
		$show_fp	    = $params->get('show_front', 1);
		$aid		    = $user->get('aid', 0);
		$content_source = $params->get('content_source','com_categories');
		$ordering       = $params->get('itemsOrdering');
		$k2cid          = $params->get('k2_categories', NULL);
		$imgSource    	= $params->get('imgSource', 'M');			
		$user_id        = $params->get('user_id');
		$titleas		= $params->get('titleas');
		$desclimitas	= $params->get('desclimitas');
		$titlelimit		= (int) $params->get('titlelimit');
		$desclimit		= (int) $params->get('desclimit');
		
		$contentConfig  = &JComponentHelper::getParams( 'com_content' );
		$access		    = !$contentConfig->get('shownoauth');

		$nullDate	    = $db->getNullDate();

		$date =& JFactory::getDate();
		$now = $date->toMySQL();
		$where = '';
		
		// User Filter
		switch ($user_id)
		{
			case 'by_me':
				$where .= ' AND (a.created_by = ' . (int) $userId . ' OR a.modified_by = ' . (int) $userId . ')';
				break;
			case 'not_me':
				$where .= ' AND (a.created_by <> ' . (int) $userId . ' AND a.modified_by <> ' . (int) $userId . ')';
				break;
		}
		
		// ensure should be published
		$where .= " AND ( a.publish_up = ".$db->Quote($nullDate)." OR a.publish_up <= ".$db->Quote($now)." )";
		$where .= " AND ( a.publish_down = ".$db->Quote($nullDate)." OR a.publish_down >= ".$db->Quote($now)." )";
		
	    // ordering
		switch ($ordering) {
			case 'date' :
				$orderby = 'a.created ASC';
				break;
			case 'rdate' :
				$orderby = 'a.created DESC';
				break;
			case 'alpha' :
				$orderby = 'a.title';
				break;
			case 'ralpha' :
				$orderby = 'a.title DESC';
				break;
			case 'order' :
				$orderby = 'a.ordering';
				break;
			default :
				$orderby = 'a.id DESC';
				break;
		}
		
		// content specific stuff
        if ($content_source=='k2_categories') {
		    // start K2 specific
		    require_once (JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'helpers'.DS.'route.php');
		    
    		$query = "SELECT a.*, c.name as categoryname,c.id as categoryid, c.alias as categoryalias, c.params as categoryparams".
    		" FROM #__k2_items as a".
    		" LEFT JOIN #__k2_categories c ON c.id = a.catid";
	
    		$query .= " WHERE a.published = 1"
    		." AND a.access <= {$aid}"
    		." AND a.trash = 0"
    		." AND c.published = 1"
    		." AND c.access <= {$aid}"
    		." AND c.trash = 0"
    		;
	
			if (!is_null($k2cid)) {
			
				if (is_array($k2cid)) {
					$k2cids = implode($k2cid,',');	
					$ids = explode( ',', $k2cids );
				} else {
					$ids = explode( ',', $k2cid );
				}			
				
				JArrayHelper::toInteger( $ids );					
				$query .= ' AND (a.catid=' . implode( ' OR a.catid=', $ids ) . ')';
			}


    		if ($show_fp=='0')
    			$query.= " AND a.featured != 1";
	
    		if ($show_fp=='2')
    			$query.= " AND a.featured = 1";
    			
    		$query .= $where . ' ORDER BY ' . $orderby;

    		// end K2 specific		
	    } else {
            // start Joomla specific
            
            $catCondition = '';

            if ($show_fp != 2) {
				if ($content_source=='com_categories') {
					if ($catid)
					{
						if (is_array($catid)) {
							$jcids = implode($catid,',');	
							$ids = explode( ',', $jcids );
						} else {
							$ids = explode( ',', $catid );
						}
						
						JArrayHelper::toInteger( $ids );
						$catCondition = ' AND (cc.id=' . implode( ' OR cc.id=', $ids ) . ')';
					}
				}
        	}
		
    		// Content Items only
    		$query = 'SELECT a.*, ' .
    			' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END as slug,'.
    			' CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(":", cc.id, cc.alias) ELSE cc.id END as catslug'.
    			' FROM #__content AS a' .
    			($show_fp == '0' ? ' LEFT JOIN #__content_frontpage AS f ON f.content_id = a.id' : '') .
    			($show_fp == '2' ? ' INNER JOIN #__content_frontpage AS f ON f.content_id = a.id' : '') .
    			' INNER JOIN #__categories AS cc ON cc.id = a.catid' .
    			' INNER JOIN #__sections AS s ON s.id = a.sectionid' .
    			' WHERE a.state = 1'. $where .' AND s.id > 0' .
    			($access ? ' AND a.access <= ' .(int) $aid. ' AND cc.access <= ' .(int) $aid. ' AND s.access <= ' .(int) $aid : '').
    			($catid && $show_fp != 2 ? $catCondition : '').
    			($show_fp == '0' ? ' AND f.content_id IS NULL ' : '').
    			' AND s.published = 1' .
    			' AND cc.published = 1' .
    			' ORDER BY '. $orderby;
    		// end Joomla specific
		}	
		
			
		$db->setQuery($query, 0, $count);

		$rows = $db->loadObjectList();

        $i=0;
		$lists	= array();
		
		if (is_array($rows) && count($rows)>0) {
    		foreach ( $rows as $row )
    		{
    		    //process content plugins
    		    $text = JHTML::_('content.prepare',$row->introtext,$cparams);
    			$lists[$i]->id 			= $row->id;
    			$lists[$i]->created 	= $row->created;
    			$lists[$i]->modified 	= $row->modified;
				$lists[$i]->title 		= modFSSP1Helper::cText(htmlspecialchars($row->title),$titlelimit,$titleas);
				$lists[$i]->introtext 	= modFSSP1Helper::cText($text,$desclimit,$desclimitas);

    			if ($content_source=='k2_categories') {
    			    $lists[$i]->link 	= JRoute::_(K2HelperRoute::getItemRoute($row->id.':'.$row->alias, $row->catid.':'.$row->categoryalias));
    			    $lists[$i]->image 	= modFSSP1Helper::getK2Images($row->id,$imgSource);				
    			} else {
    			    $lists[$i]->link 	= JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catslug, $row->sectionid));
    			    $lists[$i]->image 	= modFSSP1Helper::getImages($row->introtext);
    			}
			
    			$i++;
    		}
        }
		return $lists;
	}
	
	function cText($text, $limit, $limitas) {
		
		switch ($limitas) {
			case 0 :
				$text = JFilterOutput::cleanText($text);
				$text = explode(' ',$text);
				$sep = (count($text)>$limit) ? '...' : '';
				$text=implode(' ', array_slice($text,0,$limit)) . $sep;
				break;
			case 1 :
				$text = JFilterOutput::cleanText($text);
				$sep  = (strlen($text)>$limit) ? '...' : '';
				$text =utf8_substr($text,0,$limit) . $sep;
				break;
			case 2 :
				$allowed_tags = '<b><i><a><small><h1><h2><h3><h4><h5><h6><sup><sub><em><strong><u><br>';
				$text = strip_tags( $text, $allowed_tags );
				$text = $text;
				break;
			default :
				$text = JFilterOutput::cleanText($text);
				$text = explode(' ',$text);
				$sep = (count($text)>$limit) ? '...' : '';
				$text=implode(' ', array_slice($text,0,$limit)) . $sep;
				break;
		}		
		
		return $text;
	}

	function getK2Images($id,$imgSource) {	  
		
		$imgsrc='';
		
		if (file_exists(JPATH_SITE.DS.'media'.DS.'k2'.DS.'items'.DS.'cache'.DS.md5("Image".$id).'_' . $imgSource . '.jpg')) {
		    $imgsrc = 'media/k2/items/cache/'.md5("Image".$id).'_' . $imgSource . '.jpg';
		}
		 
		return $imgsrc;
	}	
	
	function getImages($text) {  
		
		preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $text, $matches);
		
		//If no image found
		if (!isset($matches[1])) { 
			$matches[1]='modules/mod_freeslider_sp1/assets/images/no-image.jpg';
		}
		
		 if (!file_exists($matches[1])) {
			$matches[1]='modules/mod_freeslider_sp1/assets/images/no-image.jpg';
		 }		
		
		return $matches[1];
	}
	
}
 