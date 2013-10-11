<?php
/**
 * @version $Id: view.html.php 204 2011-11-13 18:27:41Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

/**
 * Joo!BB Forum View
 *
 * @package Joo!BB
 */
class JoobbViewForum extends JView
{

	function display($tpl = null) {
		
		// initialize variables
		$joobbAuth		=& JoobbAuth::getInstance();

		$forumId = JRequest::getVar('forum', 0, '', 'int');
		
		if (!$forumId) {
			$this->messageQueue->addMessage(JText::_('COM_JOOBB_MSGREQUESTNOTPERFORMED'));
			$this->app->redirect(JRoute::_('index.php?option=com_joobb&view=board&Itemid='. $this->Itemid, false));			
		}
		
		$model		=& $this->getModel();	
		$forum		= $model->getForum($forumId);
		$category	= $model->getCategory($forum->id_cat);

		// request variables
		$limit		= JRequest::getVar('limit', $this->joobbConfig->getBoardSettings('topics_per_page'), '', 'int');
		$limitstart	= JRequest::getVar('limitstart', 0, '', 'int');

		if (!$joobbAuth->getAuth('auth_read', $forum->id)) {
			$this->messageQueue->addMessage(sprintf(JText::_('COM_JOOBB_MSGNOPERMISSION'), JText::_('COM_JOOBB_MSGREADFORUM')));
			$this->app->redirect(JRoute::_('index.php?option=com_joobb&view=board&Itemid='.$this->Itemid, false));
		}

		// handle page title
		$this->document->setTitle($category->name.' - '.$forum->name);
		
		// handle metadata
		$this->document->setDescription(JoocmHtml::stripTags($forum->description));
		$this->document->setMetadata('keywords', str_replace(' ', ', ', $forum->name));
		
		// handle sort
		$sort = JRequest::getVar('sort', 'lastpost');
		$order = JRequest::getVar('order', 'desc');
		
		$header = array ( 'lastpost' => 'index.php?option=com_joobb&view=forum&forum='.$forum->id.'&Itemid='.$this->Itemid.'&sort=lastpost',
				  'views' => 'index.php?option=com_joobb&view=forum&forum='.$forum->id.'&Itemid='.$this->Itemid.'&sort=views',
				  'replies' => 'index.php?option=com_joobb&view=forum&forum='.$forum->id.'&Itemid='.$this->Itemid.'&sort=replies',
				  'author' => 'index.php?option=com_joobb&view=forum&forum='.$forum->id.'&Itemid='.$this->Itemid.'&sort=author',
				);
		
		if ($header[$sort] == '') {
		    $sort = 'lastpost';
		}
		
		foreach ($header as $key => &$value) {
		    if ($sort == $key) {
				if ($order == 'asc') { 
					$value .= '&order=desc';
				} else { 
					$value .= '&order=asc';
				}
		    } else { 
				$value .= '&order=desc';
			}
		}
		
		if ($order == 'asc')
		    $this->assign('sortImg',$this->joobbTemplate->themePathLive.DL.'images'.DL.'topicsSortAsc.png');
		else 
		    $this->assign('sortImg',$this->joobbTemplate->themePathLive.DL.'images'.DL.'topicsSortDesc.png');

		$this->assign('sortActive',$sort);
		$this->assignRef('header',$header);
		
		//set data model
		$topics =& $this->get('topics');
		$this->assignRef('topics', $topics);
		$announcements =& $this->get('announcements');
		$this->assignRef('announcements', $announcements);
		
		// handle bread crumb
		$this->breadCrumbs->addBreadCrumb($forum->name, '');
		
		// total topics
		$this->assignRef('total', $this->get('total'));
		
		$showPagination = false;
		if ($this->total > $limit) {
			$showPagination = true;
		}
		$this->assign('showPagination', $showPagination);
		
		jimport('joomla.html.pagination');
		$this->pagination = new JPagination($this->total, $limitstart, $limit);
		
		// post icons
		$joobbIconSet = new JoobbIconSet($this->joobbConfig->getIconSetFile());
		$this->assignRef('joobbIconSet', $joobbIconSet);

		$joobbButtonSet	=& JoobbButtonSet::getInstance();

		$buttonNewTopic = $joobbButtonSet->buttonByFunction['buttonNewTopic'];
		$buttonNewTopic->href = JRoute::_('index.php?option=com_joobb&view=edittopic&forum='.$forum->id.'&topic=0&Itemid='.$this->Itemid);
		$this->assign('buttonNewTopic', $buttonNewTopic);		

		$this->assignRef('searchInputBoxText', JText::_('COM_JOOBB_SEARCHTHISFORUM'));
		
		// search button
		$this->assignRef('buttonSearch', $joobbButtonSet->buttonByFunction['buttonSearch']);
		$this->assignRef('actionSearch', JRoute::_('index.php?option=com_joobb&view=search&forum='.$forumId.'&Itemid='.$this->Itemid));
		
		parent::display($tpl);
	}
	
	function &getAnnouncement($index = 0) {
		$announcement =& $this->announcements[$index];
		return $this->getExtData($announcement);
	}
	
	function &getTopic($index = 0) {
		$topic =& $this->topics[$index];
		return $this->getExtData($topic);
	}
	
	function &getExtData($topic) {
		
		// topic
		$topic->href = JRoute::_('index.php?option=com_joobb&view=topic&topic='.$topic->id.'&Itemid='.$this->Itemid);
		
		$topicInfoIcons = array();
		switch ($topic->type) {
			case 1:		// sticky
				$topicInfoIcons[] = $this->joobbIconSet->iconByFunction['topicSticky'];				
				break;
			case 2:		// announcement
				$topicInfoIcons[] = $this->joobbIconSet->iconByFunction['topicAnnouncement'];
				break;										
		}		
		switch ($topic->status) {
			case 1:		// locked
				$topicInfoIcons[] = $this->joobbIconSet->iconByFunction['topicLocked'];
				break;
			case 2:		// solved
				$topicInfoIcons[] = $this->joobbIconSet->iconByFunction['topicSolved'];
				break;
			case 3:		// trash
				$topicInfoIcons[] = $this->joobbIconSet->iconByFunction['topicTrash'];
				break;																
		}		
		
		$topic->topicInfoIcons = $topicInfoIcons;
		
		// get the topic icon
		$topic->postIcon = $this->joobbIconSet->iconByFunction[$topic->icon_function];
		
		$topic->date_topic = JoocmHelper::Date($topic->date_topic);
		$topic->date_last_post = JoocmHelper::Date($topic->date_last_post);

		$topic->lastPostLink = JRoute::_('index.php?option=com_joobb&view=topic&topic='.$topic->id.'&Itemid='.$this->Itemid).JoobbHelper::getLimitStart($topic->id, $topic->id_last_post).'#p'.$topic->id_last_post;

		$topic->authorLink = '';
		if ($topic->author) {
			$topic->authorLink = JoocmHelper::getLink('profile', '&id='.$topic->id_author);
		} else {
			if ($topic->guest_author) {
				$topic->author = $topic->guest_author;
			} else {
				$topic->author = JText::_('COM_JOOBB_GUEST');
			}
		}
		
		$topic->posterLink = '';
		if ($topic->poster) {
			$topic->posterLink = JoocmHelper::getLink('profile', '&id='.$topic->id_poster);
		} else {
			if ($topic->guest_poster) {
				$topic->poster = $topic->guest_poster;
			} else {
				$topic->poster = JText::_('COM_JOOBB_GUEST');
			}
		}
		
		return $topic;
	}
}
?>