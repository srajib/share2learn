<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
$this->document->addScript(JOOCM_BASEPATH_LIVE.DL.'assets'.DL.'js'.DL.'jquery.min.js');
$this->document->addScript(JOOCM_BASEPATH_LIVE.DL.'assets'.DL.'js'.DL.'jquery.jcarousel.min.js'); ?>
<script language="javascript" type="text/javascript">
	$j(document).ready(function(){try{
		$j("#jcarousel").jcarousel({
			scroll: 1,
			initCallback: carousel_initCallback
		});		
		$j("#myAvatars span").click(function () {
			$j("input[name='id_avatar']").val($j(this).attr("id"));
			$j("#cmMyAvatar").html($j(this).html()); 
		});
		$j("#standardAvatars li").click(function () {
			$j("input[name='id_avatar']").val($j(this).attr("id"));
			$j("#cmMyAvatar").html($j(this).html()); 
		});
	}catch(e){}});
	
	function carousel_initCallback(carousel) {
		$j('#jcarousel-next').bind('click', function() {
			carousel.next();
			return false;
		});
	
		$j('#jcarousel-prev').bind('click', function() {
			carousel.prev();
			return false;
		});
	};
</script><?php
if ($this->enableAvatars) : ?>
<form action="index.php" method="post" id="cmSaveAvatarForm" name="cmSaveAvatarForm" class="form-validate">
    <fieldset>
        <legend><?php echo JText::_('COM_JOOCM_MYCURRENTAVATAR') .' - '. $this->joocmUser->get('name'); ?></legend>
        <div class="cmProfileAvatar"><div class="cmProfileAvatarImage"><div id="cmMyAvatar"><?php
        $avatarFile = $this->joocmAvatar->getAvatarFile($this->joocmUser->get('id'));
        if ($avatarFile != '') : ?>
            <img src="<?php echo $avatarFile; ?>" width="<?php echo $this->joocmAvatar->avatarWidth; ?>" height="<?php echo $this->joocmAvatar->avatarHeight; ?>" title="<?php echo $this->joocmUser->get('name'); ?>" alt="<?php echo $this->joocmUser->get('name'); ?>" /><?php
        else :
            echo JText::_('COM_JOOCM_NOAVATAR');
        endif; ?>
        </div></div></div>
        <div class="cmAvatarDesc">
        <p><?php echo JText::_('COM_JOOCM_AVATARDESC1'); ?></p>
        <ul>
        	<li><?php echo JText::_('COM_JOOCM_AVATAROPTION1'); ?></li>
            <li><?php echo JText::_('COM_JOOCM_AVATAROPTION2'); ?></li>
            <li><?php echo JText::_('COM_JOOCM_AVATAROPTION3'); ?></li>
        </ul>
        <p><?php echo JText::_('COM_JOOCM_AVATARDESC2'); ?> </p>
        </div>
        <br class="clr" /><br />
        <div>
        <input type="submit" name="Submit" class="button validate" value="<?php echo JText::_('COM_JOOCM_SAVE'); ?>" />
        <input type="button" name="Cancel" class="button" onclick="document.location.href='<?php echo JoocmHelper::getLink('main'); ?>'" value="<?php echo JText::_('COM_JOOCM_CANCEL'); ?>" />
        </div>
    </fieldset>
    <input type="hidden" name="option" value="com_joocm" />
    <input type="hidden" name="task" value="joocmsaveavatar" />
    <input type="hidden" name="id_avatar" value="<?php echo $this->joocmUser->get('id_avatar'); ?>" />
    <input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
</form>
<form action="index.php" method="post" id="cmUploadAvatarForm" name="cmUploadAvatarForm" class="form-validate" enctype="multipart/form-data">
<fieldset>
    <legend><?php echo JText::_('COM_JOOCM_MYAVATARS'); ?></legend>
    <ul class="cmMyAvatars" id="myAvatars"><?php
    $userAvatarsCount = count($this->userAvatars);
    foreach ($this->userAvatars as $userAvatar) : ?>
        <li>
            <span id="<?php echo $userAvatar->id; ?>">
            <img src="<?php echo $this->joocmAvatar->getJoocmAvatarFile($userAvatar); ?>" width="<?php echo $this->joocmAvatar->avatarWidth; ?>" height="<?php echo $this->joocmAvatar->avatarHeight; ?>" alt="<?php echo $this->joocmUser->get('name'); ?>" />
            </span><br />
            <center><a href="<?php echo JRoute::_('index.php?option=com_joocm&task=joocmdeleteavatar&id_avatar='.$userAvatar->id.'&Itemid='. $this->Itemid, true); ?>"><?php echo JTEXT::_('COM_JOOCM_DELETE'); ?></a></center>
        </li><?php
    endforeach; ?>
    </ul>
	<br class="clr" /><br />
    <h3></h3>
    <table cellpadding="0" cellspacing="0" border="0" width="100%"><tr>
        <td width="20%" height="40"><label for="avatarfile"><?php echo JText::_('COM_JOOCM_UPLOADAVATARFILE'); ?></label></td>
        <td><input type="file" name="avatarfile" id="avatarfile" class="inputbox" size="40" value="" maxlength="512" />
        <input type="submit" name="Submit" class="button validate" value="<?php echo JText::_('COM_JOOCM_UPLOAD'); ?>" /></td>
    </tr><tr>
        <td width="20%" height="40"><label for="avatarurl"><?php echo JText::_('COM_JOOCM_SETAVATARURL'); ?></label></td>
        <td><textarea name="avatarurl" id="avatarurl" rows="5" cols="50" class="inputbox"></textarea></td>
    </tr></table>
</fieldset>
<input type="hidden" name="option" value="com_joocm" />
<input type="hidden" name="task" value="joocmuploadavatar" />
<input type="hidden" name="my_avatar_id" value="" />
<input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
</form>
<fieldset>
    <legend><?php echo JText::_('COM_JOOCM_SELECTSTANDARDAVATAR'); ?></legend>
    <div id="jcarousel" class="jcarousel-skin-cm"><ul id="standardAvatars"><?php
    $standardAvatarsCount = count($this->standardAvatars);
    foreach ($this->standardAvatars as $standardAvatar) : ?>
        <li id="<?php echo $standardAvatar->id; ?>"><img src="<?php echo $this->joocmAvatar->getJoocmAvatarFile($standardAvatar); ?>" width="<?php echo $this->joocmAvatar->avatarWidth; ?>" height="<?php echo $this->joocmAvatar->avatarHeight; ?>" alt="<?php echo $this->joocmUser->get('name'); ?>" /></li><?php
    endforeach; ?>
    </ul></div>
</fieldset><br /><?php
endif; ?>