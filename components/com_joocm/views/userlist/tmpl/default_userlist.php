<?php
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="componentheading"><?php echo JText::_('COM_JOOCM_MEMBERS'); ?></div><?php
if ($this->showPagination) : ?>
<div class="cmMarginBottom10 cmLeft">
	<?php echo $this->pagination->getPagesCounter(); ?>
</div>
<div class="cmMarginBottom10 cmRight">
	<?php echo $this->pagination->getPagesLinks(); ?>
</div><br clear="all" /><?php
endif; ?>
<div class="cmUserListHeader">
	<div class="cmLeft">
    	<form action="<?php echo JoocmHelper::getLink('userlist'); ?>" method="post" id="josForm" name="josForm">
            <input type="text" name="searchUser" id="searchUser" class="" value="<?php echo $this->searchUser; ?>" />
            <button onclick="this.form.submit();"><?php echo JText::_('COM_JOOCM_SEARCH'); ?></button>
            <button onclick="getElementById('searchUser').value=''; this.form.submit();"><?php echo JText::_('COM_JOOCM_RESET'); ?></button>
            <input type="hidden" name="orderbydir" value="<?php echo $this->orderByDir; ?>" />
            <input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
        </form>
    </div>
    <div class="cmMarginBottom20 cmRight">
    	<?php $class = ($this->filter == 'all') ? 'class="cmActive"' : ''; ?>
        <a href="<?php echo JoocmHelper::getLink('userlist', '&filter=all'); ?>" <?php echo $class; ?>><?php echo JText::_('COM_JOOCM_ALLMEMBERS'); ?></a>
        <span class="cmMembersNavSpacer">|</span>
        <?php $class = ($this->filter == 'online') ? 'class="cmActive"' : ''; ?>
        <a href="<?php echo JoocmHelper::getLink('userlistonline'); ?>" <?php echo $class; ?>><?php echo JText::_('COM_JOOCM_ONLINE'); ?></a>
        <span class="cmMembersNavSpacer">|</span>
        <?php $class = ($this->filter == 'offline') ? 'class="cmActive"' : ''; ?>
        <a href="<?php echo JoocmHelper::getLink('userlist', '&filter=offline'); ?>" <?php echo $class; ?>><?php echo JText::_('COM_JOOCM_OFFLINE'); ?></a>
    </div>
</div>
<div class="cmUserListHeader">
	<div class="cmLeft">
    	<?php echo JText::_('COM_JOOCM_SORTBY'); ?>
		<?php $class = ($this->orderBy == 'name') ? 'class="cmActive"' : ''; ?>
        <a href="<?php echo JoocmHelper::getLink('userlist', '&orderby=name'); ?>" <?php echo $class; ?>><?php echo JText::_('COM_JOOCM_NAME'); ?></a>
        <span class="cmMembersNavSpacer">|</span>
        <?php $class = ($this->orderBy == 'registerDate') ? 'class="cmActive"' : ''; ?>
        <a href="<?php echo JoocmHelper::getLink('userlist', '&orderby=registerDate'); ?>" <?php echo $class; ?>><?php echo JText::_('COM_JOOCM_REGISTERDATE'); ?></a>
        <span class="cmMembersNavSpacer">|</span>
        <?php $class = ($this->orderBy == 'lastvisitDate') ? 'class="cmActive"' : ''; ?>
        <a href="<?php echo JoocmHelper::getLink('userlist', '&orderby=lastvisitDate'); ?>" <?php echo $class; ?>><?php echo JText::_('COM_JOOCM_LASTVISIT'); ?></a>
    </div>
    <div class="cmRight"><?php
	if ($this->orderByDir == 'DESC') : ?>
        <a href="<?php echo JoocmHelper::getLink('userlist', '&orderbydir=ASC'); ?>"><?php echo JText::_('COM_JOOCM_ASCENDING'); ?></a><?php
    else : ?>
		<a href="<?php echo JoocmHelper::getLink('userlist', '&orderbydir=DESC'); ?>"><?php echo JText::_('COM_JOOCM_DESCENDING'); ?></a><?php
    endif; ?>
    </div>
</div><?php
if ($this->total > 0) :
	$joocmUsersCount = count($this->joocmUsers);
	for ($i = 0; $i < $joocmUsersCount; $i++) :
		$joocmUser =& $this->getJoocmUser($i); ?>
        <div class="cmUserListProfile">
            <div class="cmUserListAvatar">
            <a href="<?php echo $joocmUser->userLink; ?>">
            	<div class="cmUserListAvatarImage"><img src="<?php echo $this->joocmAvatar->getAvatarFile($joocmUser->id); ?>" width="60" height="60" title="<?php echo $joocmUser->name; ?>" alt="<?php echo $joocmUser->name; ?>" /></div>
            </a>
            </div>
            <div class="cmUserListDetails">
            	<a href="<?php echo $joocmUser->userLink; ?>"><?php echo $joocmUser->name; ?></a>
                <div style="float:right;">
                <strong><?php echo JText::_('COM_JOOCM_REGISTERDATE').': '; ?></strong><?php echo $joocmUser->registerDate; ?><br />
                <strong><?php echo JText::_('COM_JOOCM_LASTVISITDATE').': '; ?></strong><?php echo $joocmUser->lastvisitDate; ?>
                </div>
				<div class="cmUserListLine"><?php
				if ($joocmUser->onlineState) : ?>
                <span class="cmUserOnline"><?php echo JText::_('COM_JOOCM_ONLINE'); ?></span><?php
				else : ?>
                <span class="cmUserOffline"><?php echo JText::_('COM_JOOCM_OFFLINE'); ?></span><?php
				endif; 
				$sendMessageLink = JoocmHelper::getLink('sendmessage', '&id='.$joocmUser->id);
				if ($sendMessageLink != '') :?>
                <a href="<?php echo $sendMessageLink; ?>"><?php echo JText::_('COM_JOOCM_SENDPRIVMESSAGE'); ?></a><?php 
				endif; ?>
                </div>
            </div>
            <br clear="all" />
        </div><?php
	endfor;
endif;
if ($this->showPagination) : ?>
<div class="cmMarginTop5 cmLeft">
	<?php echo $this->pagination->getPagesCounter(); ?>
</div>
<div class="cmMarginTop5 cmRight">
	<?php echo $this->pagination->getPagesLinks(); ?>
</div><br clear="all" /><?php
endif; ?>
<input type="button" name="back" class="button" onclick="document.location.href='<?php echo JoocmHelper::getLink('main'); ?>'" value="<?php echo JText::_('COM_JOOCM_BACK'); ?>" />