<?php 
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="cmProfile"><?php
	if ($this->enableAvatars) : ?>
	<div class="cmProfileAvatar"><div class="cmProfileAvatarImage"><?php
		$avatarFile = $this->joocmAvatar->getAvatarFile($this->joocmUserView->get('id'));
		if ($avatarFile != '') : ?>
		<img src="<?php echo $avatarFile; ?>" width="<?php echo $this->joocmAvatar->avatarWidth; ?>" height="<?php echo $this->joocmAvatar->avatarHeight; ?>" title="<?php echo $this->joocmUserView->get('name'); ?>" alt="<?php echo $this->joocmUserView->get('name'); ?>" /><?php
		else :
			echo JText::_('COM_JOOCM_NOAVATAR');
		endif; ?>
	</div></div><?php
	endif; ?>
    <div class="cmProfileView">
        <h3><?php echo $this->joocmUserView->get('name'); ?></h3>
        <ul class="cmProfileDetails">
            <li class="title"><?php echo JTEXT::_('COM_JOOCM_EMAIL'); ?></li><li><?php echo $this->joocmUserView->get('email'); ?></li>
            <li class="title"><?php echo JTEXT::_('COM_JOOCM_USERGROUP'); ?></li><li><?php echo $this->joocmUserView->get('usertype'); ?></li>
            <li class="title"><?php echo JTEXT::_('COM_JOOCM_MEMBERSINCE'); ?></li><li><?php echo JoocmHelper::Date($this->joocmUserView->get('registerDate')); ?></li>
            <li class="title"><?php echo JTEXT::_('COM_JOOCM_LASTVISIT'); ?></li><li><?php echo JoocmHelper::Date($this->joocmUserView->get('lastvisitDate')); ?></li>
            <li class="title"><?php echo JTEXT::_('COM_JOOCM_PROFILEVIEWS'); ?></li><li><?php echo $this->joocmUserView->get('views_count'); ?></li>
        </ul>
    </div>
    <br clear="all" />
    <fieldset><?php 
		$sendMessageLink = JoocmHelper::getLink('sendmessage', '&id='.$this->joocmUserView->get('id'));
		if ($sendMessageLink != '') :?>
        <a href="<?php echo $sendMessageLink; ?>">
        <?php echo JText::_('COM_JOOCM_SENDPRIVMESSAGE'); ?>
        </a><?php 
		endif; ?>
    </fieldset>
    <br clear="all" />
    <input type="button" name="Cancel" class="button" onclick="history.back();" value="<?php echo JText::_('COM_JOOCM_BACK'); ?>" />
</div> 
<div class="cmProfileBox">
    <h3><?php echo JText::_('COM_JOOCM_ABOUTME'); ?></h3><?php
    for ($i=0, $n=count($this->profilefieldsets); $i < $n; $i++) :
        $fieldset =& $this->profilefieldsets[$i]; ?>
        <ul class="cmProfileBox">
            <li class="cmFieldSetName"><?php echo JText::_($fieldset->name); ?></li><?php
			for ($j=0, $m=count($this->profilefields); $j < $m; $j++) :
				$field 	=& $this->profilefields[$j];
				if ($fieldset->id == $field->id_profile_field_set && $field->value != '') : ?>						
					<li class="cmFieldTitle"><?php echo JText::_($field->title); ?></li>
					<li class="cmFieldValue"><?php echo JText::_($field->value); ?></li><?php
				endif;
			endfor; ?>
        </ul><?php
    endfor; ?>
</div>
<br clear="all" />