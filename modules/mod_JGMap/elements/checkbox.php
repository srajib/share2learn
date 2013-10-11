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
 * Created on Oct 19, 2009
 * @author James Hansen(Kermode Bear Software)
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
 class JElementCheckbox extends JElement{
	var $_name = "Checkbox";

	function fetchElement  ( $name,  $value,  &$xmlElement,  $control_name){
		$onclick = $xmlElement->attributes('onclick');

		$html = '<input type="checkbox" name="params[' . $name . ']" id="' . $name . '"';

		if($onclick){
			$html .= ' onclick="'. $onclick . '"';
		}

		if($value == 1 || $xmlElement->attributes('default') == 'on'){
			$html .= ' checked ';
		}

		$html .= ' value="1" />';
		return $html;
	}
 }
?>