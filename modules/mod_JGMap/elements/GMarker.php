<?php
/**
 * 	Copyright 2011
 *  This file is part of mod_GMap.
 *
 *  mod_GMap is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  mod_GMap is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with mod_GMap.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Created on Nov, 2008
 * @author James Hansen(Kermode Bear Software)
 *
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
require_once(JPATH_SITE . '/modules/mod_JGMap/elements/GElement.php');
 class JElementGMarker extends JElement{
	var $_name = "GMarker";

	/**
	 * Javascript map initialization
	 * Javascript variable 'marker' must be defined.
	 */
	function addJavascript(&$params){
		$document = JFactory::getDocument();
		$lat = $params->get('lat');
		$long = $params->get('longitude');

		$js =<<<EOL

			function updateMarker(element){
				var lat = $('paramslat').value;
				var long = $('paramslong').value;
				var opts = new Object;

				if(element.checked == true){
						opts.title = $('markertitle').value;
						opts.map = map;
						opts.position = new google.maps.LatLng( lat, long);
						marker = new google.maps.Marker(opts);
						
			  	}else{
			  		if(marker){
			  			marker.setVisible(false);
			  			marker = null
			  		}
			  	}
			}
EOL;

		$document->addScriptDeclaration($js);
	}

	function fetchElement  ( $name,  $value,  &$xmlElement,  $control_name){
		$params =& JElementGMarker::getParameters();

		$this->addJavascript($params);
		$marker = ($params->get('marker', '')) ? 'checked' : '' ;
		$html = '<table>';
		$html .= '<tr><td>Enable:</td><td><input type="checkbox" id="paramsmarker" name="params[marker]" ' . $marker .
				' onclick="updateMarker(this);" /></td></tr>';

		$html .= '<tr><td>Title:</td><td><input class="text_area" type="text" value="' . $params->get('marker_title', '') .
				'" name="params[marker_title]" id="markertitle"/></td></tr>';
		$html .= '</table>';
		return $html;
	}

	function getParameters($mod = 'mod_GMap'){
		return GElement::getParameters();
	}

 }
?>