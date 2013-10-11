<?php
/**
 * @version $Id: terms.php 206 2011-11-14 20:37:29Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!CM  directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!CM Terms View
 *
 * @package Joo!CM
 */
class ViewTerms {

	/**
	 * show terms
	 */	
	function showTerms(&$rows, $pageNav, &$lists) {

		// initialize variables
		$user	=& JFactory::getUser();
				
		$document =& JFactory::getDocument();
		$document->addStyleSheet(JOOCM_ADMINCSS_LIVE.DL.'icon.css');
		
		$task	= JRequest::getVar('task'); ?>
		<div id="submenu-box">
			<div class="t"><div class="t"><div class="t"></div></div></div>			
			<div class="m">
				<ul id="submenu"><li>
					<?php $class = ($task == 'joocm_timeformat_view') ? 'class="active"' : ''; ?>
					<a <?php echo $class; ?> href="index.php?option=com_joocm&task=joocm_timeformat_view"><?php echo JText::_('COM_JOOCM_TIMEFORMATS'); ?></a>
				</li><li>
					<?php $class = ($task == 'joocm_terms_view') ? 'class="active"' : ''; ?>
					<a <?php echo $class; ?> href="index.php?option=com_joocm&task=joocm_terms_view"><?php echo JText::_('COM_JOOCM_TERMS'); ?></a>
				</li></ul>
				<div class="clr"></div>
			</div>
			<div class="b"><div class="b"><div class="b"></div></div></div>
		</div>	
		<form action="index.php?option=com_joocm" method="post" name="adminForm">
			<table class="adminlist" cellspacing="1">
			<thead><tr>
				<th nowrap="nowrap" width="5">
					<?php echo JText::_('Num'); ?>
				</th>
				<th nowrap="nowrap" width="5">
					<input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count($rows); ?>);" />
				</th>
				<th nowrap="nowrap" width="88%">
					<?php echo JHTML::_('grid.sort', 'COM_JOOCM_TERMS', 't.terms', @$lists['filter_order_Dir'], @$lists['filter_order'], 'joocm_terms_view'); ?>
				</th>
				<th nowrap="nowrap" width="10%">
					<?php echo JHTML::_('grid.sort', 'COM_JOOCM_LOCALE', 't.locale', @$lists['filter_order_Dir'], @$lists['filter_order'], 'joocm_terms_view'); ?>
				</th>								
				<th nowrap="nowrap" width="1%">
					<?php echo JHTML::_('grid.sort', 'COM_JOOCM_PUBLISHED', 't.published', @$lists['filter_order_Dir'], @$lists['filter_order'], 'joocm_terms_view'); ?>
				</th>																													
				<th nowrap="nowrap" width="1%">
					<?php echo JHTML::_('grid.sort', 'COM_JOOCM_ID', 't.id', @$lists['filter_order_Dir'], @$lists['filter_order'], 'joocm_terms_view'); ?>
				</th>			
			</tr></thead>
			<tfoot><tr>
				<td colspan="6"><?php echo $pageNav->getListFooter(); ?></td>
			</tr></tfoot>			
			<tbody><?php
			$k = 0;
			for ($i=0, $n=count($rows); $i < $n; $i++) {
				$row 	=& $rows[$i];
				
				$img_published = $row->published ? 'tick.png' : 'publish_x.png';
				$task_published = $row->published ? 'joocm_terms_unpublish' : 'joocm_terms_publish';
				$alt_published = $row->published ? JText::_('COM_JOOCM_PUBLISHED') :  JText::_('COM_JOOCM_UNPUBLISHED');
								
				$link = 'index.php?option=com_joocm&task=joocm_terms_edit&hidemainmenu=1&cid[]='. $row->id;	?>
				<tr class="<?php echo "row$k"; ?>">
					<td><?php echo $i+1 ?></td>
					<td><?php echo JHTML::_('grid.id', $i, $row->id); ?></td>
					<td><?php
					if (JTable::isCheckedOut($user->get ('id'), $row->id)) {
						echo $row->name;
					} else { ?>
						<a href="<?php echo JRoute::_($link); ?>">
							<?php echo htmlspecialchars($row->terms, ENT_QUOTES); ?>
						</a><?php
					} ?>				
					</td>
					<td><?php echo $row->locale; ?></td>									
					<td align="center">
						<a href="javascript: void(0);" onClick="return listItemTask('cb<?php echo $i;?>','<?php echo $task_published; ?>')">
							<img src="images/<?php echo $img_published;?>" width="16" height="16" border="0" alt="<?php echo $alt_published; ?>" />
						</a>
					</td>																																																							
					<td><?php echo $row->id; ?></td>
				</tr><?php
				$k = 1 - $k;
			} ?>
			</tbody>
			</table>
			<input type="hidden" name="option" value="com_joocm" />
			<input type="hidden" name="task" value="joocm_terms_view" />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="hidemainmenu" value="0" />
			<input type="hidden" name="filter_order" value="<?php echo $lists['filter_order']; ?>" />
			<input type="hidden" name="filter_order_Dir" value="" />
		</form><?php
	}
	
	/**
	 * edit terms
	 */
	function editTerms(&$row, &$lists) {

		$document =& JFactory::getDocument();
		$document->addStyleSheet(JOOCM_ADMINCSS_LIVE.DL.'icon.css');
		
		$editor =& JFactory::getEditor(); ?>
		<script language="javascript" type="text/javascript">
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'joocm_terms_cancel') {
				submitform(pressbutton); return;
			}

			// do field validation
			if (trim(form.name.value) == "") {
				alert("<?php echo JText::sprintf('COM_JOOCM_MSGFIELDREQUIRED', JText::_('COM_JOOCM_NAME'), JText::_('COM_JOOCM_TERMS')); ?>");
			} else {
				<?php echo $editor->save(); ?>
				submitform(pressbutton);
			}
		}
		</script>
		<form action="index.php" method="post" name="adminForm" id="adminForm">
			<div class="col100">
				<fieldset class="adminform">
					<legend><?php echo JText::_('COM_JOOCM_TERMSOFAGREEMENT'); ?></legend>
					<table class="admintable" cellspacing="1"><tr>
						<td class="key">
							<label for="terms"><?php echo JText::_('COM_JOOCM_TERMS'); ?></label>
						</td><td>
							<input type="text" name="terms" id="terms" class="inputbox" size="40" value="<?php echo $row->terms; ?>" maxlength="255" />
						</td>
					</tr><tr>
						<td class="key">
							<label for="locale"><?php echo JText::_('COM_JOOCM_LOCALE'); ?></label>
						</td><td>
							<input type="text" name="locale" id="locale" class="inputbox" size="40" value="<?php echo $row->locale; ?>" maxlength="5" />
						</td>
					</tr><tr>
						<td class="key">
							<label for="published"><?php echo JText::_('COM_JOOCM_PUBLISHED'); ?></label>
						</td>
						<td><?php echo $lists['published']; ?></td>
					</tr><tr>
						<td colspan="2"><?php echo $editor->display('termstext',  $row->termstext , '100%', '450', '75', '20') ; ?></td>
					</tr></table>
				</fieldset>
				<fieldset class="adminform">
					<legend><?php echo JText::_('COM_JOOCM_AGREEMENT'); ?></legend>
					<table class="admintable" cellspacing="1"><tr>
						<td class="key">
							<label for="agreement"><?php echo JText::_('COM_JOOCM_AGREEMENT'); ?></label>
						</td><td>
							<input type="text" name="agreement" id="agreement" class="inputbox" size="40" value="<?php echo $row->agreement; ?>" maxlength="255" />
						</td>
					</tr><tr>
						<td colspan="2"><?php echo $editor->display('agreementtext',  $row->agreementtext, '100%', '450', '75', '20') ; ?></td>
					</tr></table>
				</fieldset>
 				<fieldset class="adminform">
					<legend><?php echo JText::_('COM_JOOCM_METAINFORMATION'); ?></legend>
					<table class="admintable" cellspacing="1"><tr>
						<td class="key">
							<label for="description"><?php echo JText::_('COM_JOOCM_DESCRIPTION'); ?></label>
						</td><td>
                            <textarea name="description" rows="5" cols="40" id="description" class="inputbox" ><?php echo $row->description; ?></textarea>
						</td>
					</tr><tr>
						<td class="key">
							<label for="keywords"><?php echo JText::_('COM_JOOCM_KEYWORDS'); ?></label>
						</td><td>
                            <textarea name="keywords" rows="5" cols="40" id="keywords" class="inputbox" ><?php echo $row->keywords; ?></textarea>
						</td>
					</tr></table>
				</fieldset>
			</div>
			<div class="clr"></div>				
			<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
			<input type="hidden" name="option" value="com_joocm" />
			<input type="hidden" name="task" value="" />
		</form><?php
	}
}
?>