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
window.addEvent("domready",function(){
	var data_sources = [$('paramscom_categories'), $('paramsk2_categories')];
	//
	data_sources.each(function(el,j){
		el.getParent().getParent().setStyle('display','none');
	});
	
	$('params'+$('paramscontent_source').value).getParent().getParent().setStyle('display','');
	
	$('paramscontent_source').addEvent("change", function(){
		data_sources.each(function(el,j){
			el.getParent().getParent().setStyle('display','none');
		});
	
		$('params'+$('paramscontent_source').value).getParent().getParent().setStyle('display','');	
	});

	$('paramscontent_source').addEvent("blur", function(){
		data_sources.each(function(el,j){
			el.getParent().getParent().setStyle('display','none');
		});
	
		$('params'+$('paramscontent_source').value).getParent().getParent().setStyle('display','');
	});
	
});