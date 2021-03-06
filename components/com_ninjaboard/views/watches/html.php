<?php defined( 'KOOWA' ) or die( 'Restricted access' );
/**
 * @category	Ninjaboard
 * @copyright	Copyright (C) 2007 - 2011 NinjaForge. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://ninjaforge.com
 */
 
class ComNinjaboardViewWatchesHtml extends ComNinjaboardViewHtml
{
	public function display()
	{
		$this->assign('params', KFactory::get('admin::com.ninjaboard.model.settings')->getParams());
		if(KFactory::get('lib.joomla.user')->guest)
		{
			$this->mixin(KFactory::get('admin::com.ninja.view.user.mixin'));

			$this->setLoginLayout();

			return parent::display();
		}
		
		$this->person = KFactory::get('admin::com.ninjaboard.model.people')->getMe();
		$title = $this->person->display_name;

		$this->_subtitle = $title;
		$this->assign('title', $title);
		
		
		$this->remove_selected_button = str_replace(array('$title', '$link'), array(JText::_('Remove Selected'), '#'), $this->params['tmpl']['create_topic_button']);
		
		$state			= $this->getModel()->getState();
		$this->total	= $this->getModel()->getTotal();

		$this->assign('pagination', 
			KFactory::get('site::com.ninjaboard.template.helper.paginator', array('name' => 'watches'))
				->pagination($this->total, $state->offset, $state->limit, 4)
		);
		
		return parent::display();
	}
}