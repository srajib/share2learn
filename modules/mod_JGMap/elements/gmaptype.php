<?php
/**
 * 	Copyright 2011 2010
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
 * Created on Dec , 2010
 * @author James Hansen(Kermode Bear Software)
 *
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
class JElementGMapType extends JElement{
	var $_name = "gmaptype";

	function fetchElement  ( $name,  $value,  &$xmlElement,  $control_name){
		$mapVariable = $xmlElement->get('var', 'map');
		
		$options[] = array('value' => 'ROADMAP', 'text' => 'Map');
		$options[] = array('value' => 'SATELLITE', 'text' => 'Satellite');
		$options[] = array('value' => 'HYBRID', 'text' => 'Hybrid');
		$options[] = array('value' => 'TERRAIN', 'text' => 'Terrain');
		$onchange = 'onchange="'.$mapVariable.'.setMapTypeId(eval(\'google.maps.MapTypeId.\' + this.options[this.selectedIndex].value))"';
		return JHTML::_('select.genericlist', $options, 'params['.$name.']', $onchange,'value', 'text', $value);
	}	
}