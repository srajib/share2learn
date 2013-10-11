<?php
class JElementSections extends JElement
{
   var   $_name = 'Sections';

   function fetchElement($name, $value, &$node, $control_name)
   {
      $db =& JFactory::getDBO();
// added this for the size of the list
      $size = ( $node->attributes('size') ? $node->attributes('size') : 5 );
      $query = 'SELECT id, title FROM #__sections WHERE published = 1 AND scope = "content" ORDER BY title';
      $db->setQuery($query);
      $options = $db->loadObjectList();
      /*
      array_unshift($options, JHTML::_('select.option', '0', '- '.JText::_('Select Section').' -', 'id', 'title'));

      return JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.']', 'class="inputbox"', 'id', 'title', $value, $control_name.$name);
      */
// added
      return JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.'][]',  ' multiple="multiple" size="' . $size . '" class="inputbox"', 'id', 'title', $value, $control_name.$name);
   }
}
?>