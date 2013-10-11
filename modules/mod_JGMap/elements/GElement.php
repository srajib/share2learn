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
class GElement extends JElement{
	
	static function getParameters($mod = 'mod_JGMap'){
		static $params;
		$file = JPATH_SITE . 'modules/' .DS . $mod . DS . $mod . '.xml';	
		if(!is_object($params) ){
			$id = JRequest::getVar('cid', 0, '', 'array');
			if(empty($id[0]))
				$id = JRequest::getInt('id', 0);
			else 
				$id = $id[0];
			
			if($id){	
				$db = JFactory::getDBO();
				$module =& new JTableModule($db);
				$sql = 'select * from #__modules where module = '. $db->Quote($mod);
				$sql .= ' and id = ' . $id;
				$module->_db->setQuery($sql);
				
				$results = $module->_db->loadAssoc();
				$module->bind($results);
				$params = new JParameter($module->params, $file);
					
			}else{
				$params = new JParameter(null, $file);
			}
			
		}
		
		return $params;
	}
}