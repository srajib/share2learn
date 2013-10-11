<?php
class JElementAccess extends JElement
{
   var   $_name = 'Access';

   function fetchElement($name, $value, &$node, $control_name)
   {

      $size = ( $node->attributes('size') ? $node->attributes('size') : 3 );

	  $db =& JFactory::getDBO();

		$query = 'SELECT id AS value, name AS text'
		. ' FROM #__groups'
		. ' ORDER BY id'
		;
		$db->setQuery( $query );
		$groups = $db->loadObjectList();
		// $access = JHTML::_('select.genericlist',  $groups, 'access',                           'class="inputbox" size="3"', 'value', 'text', intval( $row->access ), '', 1 );
        $access =  JHTML::_('select.genericlist',  $groups, ''.$control_name.'['.$name.'][]',  ' multiple="multiple" size="' . $size . '" class="inputbox"', 'value', 'text', $value, $control_name.$name);
		return $access;
  }
}
?>