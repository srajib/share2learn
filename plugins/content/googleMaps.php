<?php
/**
* googleMaps plugin
* allows you to include one or more google maps
* right inside Joomla content item or article
* Author: kksou
* Copyright (C) 2006-2008. kksou.com. All Rights Reserved
* Website: http://www.kksou.com/php-gtk2
* v1.5 April 16, 2009
* v1.51 April 30, 2009 added support for mod_googleMaps
* v1.52 May 3, 2009 improved javascript
*/

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

@session_start();

jimport( 'joomla.event.plugin' );

$lib = dirname(__FILE__).'/googleMaps/googleMaps.lib.php';
require_once($lib);

class plgContentgoogleMaps extends JPlugin {

	function plgContentgoogleMaps( &$subject, $params ) {
		parent::JPlugin( $subject, $params );
 	}

	function onPrepareContent( &$row, &$params, $limitstart=0 ) {

		$plugin =& JPluginHelper::getPlugin('content', 'googleMaps');
		$pluginParams = new JParameter( $plugin->params );

		/*if ( !$pluginParams->get( 'enabled', 1 ) ) {
			$row->text = preg_replace( $regex, '', $row->text );
			return true;
		}*/

		$param = new stdClass;
		$param->api_key = $pluginParams->get('api_key');
		$param->width = $pluginParams->get('width');
		$param->height = $pluginParams->get('height');
		$param->zoom = $pluginParams->get('zoom');

		$is_mod = 0;
		if (isset($params->is_mod)) $is_mod = 1;

		$plugin = new Plugin_googleMaps($row, $param, $is_mod);
		return true;
	}
}

?>
