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
 class JElementGMap extends JElement{
	var $_name = "GMap";

	/**
	 * Javascript map initialization
	 */
	function initMap( $lat, $long, $zoom, $marker, &$params){
		$document =& JFactory::getDocument();
		$js = "http://maps.google.com/maps/api/js?sensor=false";
		$mapType = $params->get('mapType', 'ROADMAP');
		$document->addScript($js);
		$markerScript = '';
		if($marker){
			$markerTitle = $params->get('marker_title');
			$markerScript =<<<EOL
			var opts = new Object;
			opts.title = "{$markerTitle}";
			opts.position = new google.maps.LatLng($lat, $long);
			opts.map = map
			marker = new google.maps.Marker(opts);
EOL;
		}

		$onload =<<<EOL
			var map;
			var marker;

			google.maps.event.addDomListener(window,'load', load );
		    function load() {
			 	var mapOptions = {
			 	    center : new google.maps.LatLng({$lat}, {$long}),
			 	    zoom : {$zoom}, 
			 	    mapTypeId: google.maps.MapTypeId.{$mapType},
      				navigationControl: true,  
      				mapTypeControl: false
  				}
		        map = new google.maps.Map(document.getElementById("map"), mapOptions);
		        
		        google.maps.event.addListener( map , 'dragend', updateLatLong);
				google.maps.event.addListener( map , 'zoom_changed', updateZoom);
				google.maps.event.addListener( map , 'dragend', updateMarkerMove);
				google.maps.event.addListener( map , 'drag', updateLatLong);
				google.maps.event.addListener( map , 'dragstart', updateMarkerStart);
				$markerScript

				$('paramszoom').addEvent('keyup', function(){
					map.setZoom(this.value);
				});
		      
		    }
			function updateZoom(){
				$('paramszoom').value = map.getZoom();
			}
			function updateMarkerStart(){
				if(marker){
					marker.setVisible(false);
				}
			}
			
			function updateMarkerMove(){
				if(marker){
					var center = map.getCenter();
					var lat = center.lat();
					var lng = center.lng();
					if(marker){
						marker.setPosition(map.getCenter());
						marker.setVisible(true);
					}
				}
			}

			function updateLatLong(){
				var center = map.getCenter();
				var lat = center.lat();
				var lng = center.lng();
				$('paramslat').value = lat;
				$('paramslong').value = lng;
			}
EOL;

		$document->addScriptDeclaration($onload);
	}

	function fetchElement  ( $name,  $value,  &$xmlElement,  $control_name){
		$params =& JElementGMap::getParameters();

		$lat = $params->get('lat', $xmlElement->attributes('lat'));
		$long = $params->get('longitude', $xmlElement->attributes('longitude'));
		$zoom = $params->get('zoom', 3);
		$width = $params->get('width', '200');
		$height = $params->get('height', '150');
		
		$marker = $params->get('marker');
		$markerTitle = $params->get('marker_info', '');
		$this->initMap($lat, $long, $zoom, $marker, $params);
		$elements =<<<EOL
		<table><tr><td>Latitude:</td><td><input class="text_area" type="text" id="paramslat" name="params[lat]" value="{$lat}" /></td></tr>
		<tr><td>Longitude:</td><td><input class="text_area" type="text" id="paramslong" name="params[longitude]" value="{$long}" /></td></tr>
		<tr><td>Zoom Level:</td><td><input class="text_area" type="text" id="paramszoom" name="params[zoom]" value="{$zoom}" maxlength="2" size="2" /></td></tr>
		</table>
		<div id="map" style="padding: 10px,margin:4px 4px 10px; width: {$width}px; height: {$height}px"> </div>
EOL;
		return $elements;
	}

	function getParameters($mod = 'mod_GMap'){
		return GElement::getParameters();
	}
 }
?>
<script type="text/javascript">
/**
 * Events for adjusting the size of the google map.
 */
window.addEvent('load', function(){
	var el = $('paramswidth');
	el.addEvent('keyup', function(){
		adjustDimensions(this);
	});
	el = $('paramsheight');
	el.addEvent('keyup' ,function(){
		adjustDimensions(this);
	});
});

/**
 * Adjust the google map size.
 * @param el Div of the Google map.
 */
function adjustDimensions(el){
	if(el.value.length > 2){
		var mapDiv = $('map');
		var width = parseInt($('paramswidth').value) || 100;
		var height = parseInt($('paramsheight').value)  || 100;
		mapDiv.style.width = width + 'px';
		mapDiv.style.height = height + 'px';
	}		
}
</script>