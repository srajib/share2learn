<?php
/**
 * @version $Id: view.html.php 210 2012-02-20 20:37:58Z sterob $
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
 * Joo!BB Subscriptions View
 *
 * @package Joo!BB
 */
class JoobbViewSubscriptions extends JView
{

	function display($tpl = null) {

		if ($this->joobbUser->get('id') < 1) {
			$this->messageQueue->addMessage(JText::_('COM_JOOBB_MSGNOPERMISSIONVIEWSUBSCRIPTIONS'));
			$this->app->redirect(JRoute::_('index.php?option=com_joobb&view=board&Itemid='. $this->Itemid, false));
		}

		// request variables
		$limit = JRequest::getVar('limit', $this->joobbConfig->getBoardSettings('topics_per_page'), '', 'int');
		$limitstart	= JRequest::getVar('limitstart', 0, '', 'int');
		
		$model	=& $this->getModel();
		$subscriptions = $model->getUserSubscriptions($this->joobbUser->get('id'), $limitstart, $limit);
		$this->assignRef('subscriptions', $subscriptions);
		$this->assignRef('total', $model->getTotal());

		// handle page title
		$this->document->setTitle(JText::_('COM_JOOBB_MYSUBSCRIPTIONS'));
		
		// handle bread crumb
		$this->breadCrumbs->addBreadCrumb(JText::_('COM_JOOBB_MYSUBSCRIPTIONS'));
		
		// handle pagination
		$showPagination = false;
		if ($this->total > $limit) {
			jimport('joomla.html.pagination');
			$this->pagination = new JPagination($this->total, $limitstart, $limit);
			$showPagination = true;
		}
		$this->assign('showPagination', $showPagination);
		
		// assign icon set
		$joobbIconSet = new JoobbIconSet($this->joobbConfig->getIconSetFile());
		$this->assignRef('joobbIconSet', $joobbIconSet);
		
		parent::display($tpl);
	}
	
	function &getSubscription($index = 0) {
		$subscription =& $this->subscriptions[$index];
		
		$subscription->subscriptionLink = JRoute::_('index.php?option=com_joobb&task=joobbunsubscribetopic&topic='.$subscription->id.'&Itemid='. $this->Itemid, true);
		
		// topic link
		$subscription->href = JRoute::_('index.php?option=com_joobb&view=topic&topic='.$subscription->id.'&Itemid='.$this->Itemid);
		
		$topicInfoIcons = array();
		switch ($subscription->type) {
			case 1:		// sticky
				$topicInfoIcons[] = $this->joobbIconSet->iconByFunction['topicSticky'];				
				break;
			case 2:		// announcement
				$topicInfoIcons[] = $this->joobbIconSet->iconByFunction['topicAnnouncement'];
				break;										
		}		
		switch ($subscription->status) {
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
		
		$subscription->topicInfoIcons = $topicInfoIcons;
		
		// get the topic icon
		$subscription->postIcon = $this->joobbIconSet->iconByFunction[$subscription->icon_function];
		
		// convert dates to required format
		$subscription->date_topic = JoocmHelper::Date($subscription->date_topic);
		$subscription->date_last_post = JoocmHelper::Date($subscription->date_last_post);

		$subscription->lastPostLink = 'index.php?option=com_joobb&view=topic&topic='.$subscription->id.'&Itemid='.$this->Itemid.'#p'.$subscription->id_last_post;

		$subscription->authorLink = '';
		if ($subscription->author) {
			$subscription->authorLink = JoocmHelper::getLink('profile', '&id='.$subscription->id_author);
			
		} else {
			if ($subscription->guest_author) {
				$subscription->author = $subscription->guest_author;
			} else {
				$subscription->author = JText::_('COM_JOOBB_GUEST');
			}
		}
		
		$subscription->posterLink = '';
		if ($subscription->poster) {
			$subscription->posterLink = JoocmHelper::getLink('profile', '&id='.$subscription->id_poster);
		} else {
			if ($subscription->guest_poster) {
				$subscription->poster = $subscription->guest_poster;
			} else {
				$subscription->poster = JText::_('COM_JOOBB_GUEST');
			}
		}
		
		return $subscription;
	}
}
?>