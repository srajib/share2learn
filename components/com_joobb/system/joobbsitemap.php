<?php
/**
 * @version $Id: joobbsitemap.php 135 2010-08-13 10:03:14Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!BB Sitemap ( Experimental Version )
 *
 * @package Joo!BB
 */
class JoobbSitemap
{
		
	/**
	 * router
	 *
	 * @var object
	 */
	var $router = null;
	
	/**
	 * priority
	 *
	 * @var string
	 */
	var $priority = array(0=>'0.0', 1=>'0.1', 2=>'0.2', 3=>'0.3', 4=>'0.4', 5=>'0.5', 6=>'0.6', 7=>'0.7', 8=>'0.8', 9=>'0.9', 10=>'1.0');
	
	/**
	 * get sitemap object
	 *
	 * @access public
	 * @return object of JoobbSitemap
	 */		
	function JoobbSitemap() {
		$config =& JFactory::getConfig();
		$options['mode'] = $config->getValue('config.sef');

		jimport('joomla.application.router');
		$this->router =& JRouter::getInstance('site', $options);
		
		if (!class_exists('JSite')) {
			require_once(JPATH_SITE.DS.'includes'.DS.'application.php');
		}
				
		$this->Itemid = JoocmHelper::getMenuId('com_joobb');
	
	}

	/**
	 * get a joobb sitemap object
	 *
	 * @access public
	 * @return object of JoobbAuth
	 */
	function &getInstance() {
	
		static $joobbSitemap;

		if (!is_object($joobbSitemap)) {
			$joobbSitemap = new JoobbSitemap();
		}

		return $joobbSitemap;
	}

	function createSitemap($fileName = 'joobb_sitemap.xml', $priority = 'none', $changeFreq = 'none', $limit = 50000) {
		$joobbConfig =& JoobbConfig::getInstance();
		$sitemapRootURL = JURI::root();
		
        // we need a router!
        if (!$this->router) {
            return false;
        }
		
		$this->limit = $limit;

		$sitemap = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$sitemap .= "<urlset\n";
		$sitemap .= "      xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"\n";
		$sitemap .= "      xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"\n";
		$sitemap .= "      xsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9\n";
		$sitemap .= "            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">\n";
		$sitemap .= "<!-- created with Joo!BB - Joomla! Bulletin Board Sitemap Generator www.joobb.org -->\n\n";
	
		$sitemap .= "<url>\n";
		$sitemap .= "  <loc>".$this->createURL('index.php?option=com_joobb&view=board&Itemid='.$this->Itemid)."</loc>\n";
		if ($priority != 'none') {
			$sitemap .= "  <priority>1.0</priority>\n";
		}
		$sitemap .= "  <lastmod>".gmdate("c", time())."</lastmod>\n";
		if ($changeFreq != 'none') {
			$changeFreqVal = $changeFreq;
			if ($changeFreq == 'autodetect') {
				$changeFreqVal = $this->getFreq(gmdate("Y-m-d H:i:s"));
			}
			$sitemap .= "  <changefreq>".$changeFreqVal."</changefreq>\n";
		}
		$sitemap .= "</url>\n";
		$this->limit--;

		// get forum items
		$items = $this->getForumItems();
		if (count($items)) {
			foreach ($items as $item) {
				$sitemap .= "<url>\n";
				$sitemap .= "  <loc>".$this->createURL('index.php?option=com_joobb&view=forum&forum='.$item->id.'&Itemid='.$this->Itemid)."</loc>\n";
				if ($priority != 'none') {
					$sitemap .= "  <priority>".$this->calcForumPrio($item->id)."</priority>\n";
				}
				$sitemap .= "  <lastmod>".gmdate("c", strtotime($item->date_post))."</lastmod>\n";
				if ($changeFreq != 'none') {
					$changeFreqVal = $changeFreq;
					if ($changeFreq == 'autodetect') {
						$changeFreqVal = $this->getFreq($item->date_post);
					}
					$sitemap .= "  <changefreq>".$changeFreqVal."</changefreq>\n";
				}
				$sitemap .= "</url>\n";
				$this->limit--;
			}
		}

		// get topic items
		$items = $this->getTopicItems();
		if (count($items)) {
			foreach ($items as $item) {
				$sitemap .= "<url>\n";
				$sitemap .= "  <loc>".$this->createURL('index.php?option=com_joobb&view=topic&topic='.$item->id_topic.'&Itemid='.$this->Itemid)."</loc>\n";
				if ($priority != 'none') {
					$sitemap .= "  <priority>".$this->calcTopicPrio($item->views, $item->id_forum)."</priority>\n";
				}
				$sitemap .= "  <lastmod>".gmdate("c", strtotime($item->date_post))."</lastmod>\n";
				if ($changeFreq != 'none') {
					$changeFreqVal = $changeFreq;
					if ($changeFreq == 'autodetect') {
						$changeFreqVal = $this->getFreq($item->date_post);
					}
					$sitemap .= "  <changefreq>".$changeFreqVal."</changefreq>\n";
				}
				$sitemap .= "</url>\n";
				$this->limit--;
			}
		}
		$sitemap .= "</urlset>";

		return $this->saveSitemap(JPATH_SITE.DS.$fileName, $sitemap);
	}

	/**
	 * create URL 
	 * 
	 * relies on the logic of Joomla! and using parts of code from it
	 * 
	 * @access public
	 * @return string
	 */
    function createURL($url, $xhtml = true, $ssl = null) {

        if ((strpos($url, '&') !== 0 ) && (strpos($url, 'index.php') !== 0)) {
            return $url;
        }

        // build route
        $uri = &$this->router->build($url);
        $url = $uri->toString(array('path', 'query', 'fragment'));
		
        // replace spaces
        $url = preg_replace('/\s/', '%20', $url);
		
		// remove unnessesary strings from the url
		$url = preg_replace('/administrator\//', '', $url);
		$url = preg_replace('/component\//', '', $url);
		
        // get the secure/unsecure URLs.
        $ssl = (int) $ssl;
		$uri =& JURI::getInstance();
		
		// get additional parts
		static $prefix;
		if (!$prefix) {
			$prefix = $uri->toString(array('host', 'port'));
		}
		
		// determine which scheme we want
		$scheme = ($ssl === 1) ? 'https' : 'http';
		
		// make sure our url path begins with a slash
		if (!preg_match('#^/#', $url)) {
			$url    = '/' . $url;
		}
		
		// build the URL
		$url = $scheme . '://' . $prefix . $url;

        if($xhtml) {
            $url = str_replace('&', '&amp;', $url);
        }
		
        return $url;
    }
		
	/**
	 * save sitemap
	 * 
	 * @access public
	 * @return boolean
	 */
	function saveSitemap($fileName = "", $content = "") {
	
		if ($fileName == "" || $content == "") {
			return false;
		}
		
		$sitemapFile = fopen($fileName, "w+");
		if ($sitemapFile) {
			fputs($sitemapFile, $content);
		} else {
			return false; // error... check write permissions
		}
		
		fclose($sitemapFile);
		
		return true;
	}
		
	/**
	 * get forum items
	 * 
	 * @access public
	 * @return array
	 */
	function getForumItems() {
	
		// initialize variables
		$db				=& JFactory::getDBO();

		$query = "SELECT f.*, lp.date_post"
				. "\n FROM #__joobb_forums AS f"
				. "\n LEFT JOIN #__joobb_posts AS lp ON f.id_last_post = lp.id"
				. "\n WHERE f.status = 1"
				. "\n AND f.auth_view <= 0"
				. "\n ORDER BY f.ordering"
				. "\n LIMIT 0, ".$this->limit
				;
		$db->setQuery($query);

		return $db->loadObjectList();
	}
		
	/**
	 * get topic items
	 * 
	 * @access public
	 * @return array
	 */
	function getTopicItems() {
	
		// initialize variables
		$db				=& JFactory::getDBO();
	
		$query = "SELECT p.*, t.views"
				. "\n FROM #__joobb_posts AS p"
				. "\n INNER JOIN #__joobb_topics AS t ON t.id_first_post = p.id"
				. "\n INNER JOIN #__joobb_forums AS f ON f.id = p.id_forum"
				. "\n INNER JOIN #__joobb_categories AS c ON c.id = f.id_cat"
				. "\n WHERE f.auth_read <= 0"
				. "\n ORDER BY p.date_post DESC"
				. "\n LIMIT 0, ".$this->limit
				;
		$db->setQuery($query);

		return $db->loadObjectList();
	}

	function calcForumPrio($forumId) {

		// initialize variables
		$db				=& JFactory::getDBO();
	
		$query = "SELECT IFNULL(MAX(m.sum_views), 0)"
				. "FROM (SELECT SUM(t.views) AS sum_views"
				. "\n FROM #__joobb_topics AS t" 
				. "\n GROUP BY t.id_forum)m"
				;
		$db->setQuery($query);

		$views = $db->loadResult();
		
		$query = "SELECT SUM(t.views)"
				. "\n FROM #__joobb_topics AS t"
				. "\n WHERE t.id_forum = $forumId"
				;
		$db->setQuery($query);

		$forumViews = $db->loadResult();
		
		$prio = (int) ($forumViews / $views * 10);		
		
		return $this->priority[$prio];
	}

	function calcTopicPrio($topicViews, $forumId) {

		// initialize variables
		$db				=& JFactory::getDBO();
	
		$query = "SELECT IFNULL(MAX(t.views), 0)"
				. "\n FROM #__joobb_topics AS t" 
				. "\n WHERE t.id_forum = $forumId"
				;
		$db->setQuery($query);

		$views = $db->loadResult();
		
		$prio = (int) ($topicViews / $views * 10);		
		
		return $this->priority[$prio];
	}

	function getFreq($date) {

		$time = strtotime(gmdate("Y-m-d H:i:s")) - strtotime($date);
			
		switch (true) {
			case ($time <= 60):
				$changeFreq = 'always';
				break;
			case ($time <= 3600):
				$changeFreq = 'hourly';
				break;
			case ($time <= 86400):
				$changeFreq = 'daily';
				break;
			case ($time <= 604800):
				$changeFreq = 'weekly';
				break;
			case ($time <= 18144000):
				$changeFreq = 'monthly';
				break;
			case ($time <= 31536000):
				$changeFreq = 'yearly';
				break;
			default:
				$changeFreq = 'never';
				break;
		}

		return $changeFreq;
	}
}
?>