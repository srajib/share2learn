<?php
/**
 * copyright (C) 2009 GWE Systems Ltd - All rights reserved
 */
// TODO - when saving form if not a repeating event reset the "each repeat" options to "all repeats"!!!
// no direct access
defined('_JEXEC') or die('Restricted access');
JLoader::register('JevRsvpInvitees', JPATH_ADMINISTRATOR . "/components/com_rsvppro/libraries/jevrinvitees.php");

class JevRsvpAttendance {

    private $params;
    private $jomsocial = false;

    public function __construct($params) {
        $this->params = $params;
		jimport('joomla.filesystem.file');
		if (JFile::exists(JPATH_SITE.'/components/com_community/community.php')){
			if (JComponentHelper::isEnabled("com_community")) {
				$this->jomsocial = true;
			}
		}
		
    }

    public function editAttendance(&$extraTabs, &$row, &$params) {
        $customfields = array();
        // Only setup when editing an event (not a repeat)
        if (JRequest::getString("jevtask", "") != "icalevent.edit" && JRequest::getString("jevtask", "") != "icalevent.editcopy")
            return true;
/*
	jimport('joomla.application.component.view');

	$theme = JEV_CommonFunctions::getJEventsViewName ();
	if(JVersion::isCompatible("1.6.0")){
		$this->_basepath = JPATH_SITE . "/plugins/jevents/jevrsvppro/rsvppro/";
	}
	else {
		$this->_basepath = JPATH_SITE . "/plugins/jevents/rsvppro/";
	}
	$this->view = new JView(array('base_path' => $this->_basepath, "template_path" => $this->_basepath . "tmpl/default", "name" => $theme));

	$this->view->addTemplatePath($this->_basepath . "tmpl/" . $theme);
	$this->view->addTemplatePath(JPATH_SITE . DS . 'templates' . DS . JFactory::getApplication ()->getTemplate() . DS . 'html' . DS . "plg_rsvppro" . DS . "default");
	$this->view->addTemplatePath(JPATH_SITE . DS . 'templates' . DS . JFactory::getApplication ()->getTemplate() . DS . 'html' . DS . "plg_rsvppro" . DS . $theme);
	$this->view->setLayout("edit");
*/	
		
        $db = & JFactory::getDBO();

        $editor = & JFactory::getEditor();

		if(JVersion::isCompatible("1.6.0")){
			JHTML::script('rsvp.js', 'plugins/jevents/jevrsvppro/rsvppro/' );
			JHTML::stylesheet('rsvp.css', 'plugins/jevents/jevrsvppro/rsvppro/' );
		}
		else {
			JHTML::script('rsvp.js', 'plugins/jevents/rsvppro/' );
			JHTML::stylesheet('rsvp.css', 'plugins/jevents/rsvppro/' );
		}

        $eventid = intval($row->ev_id());
        $user = JFactory::getUser();
        if (!($user->id == $row->created_by() || JEVHelper::isAdminUser($user) || JEVHelper::canDeleteEvent($row))) {
            $label = JText::_( 'JEV_INVITATIONS' );
            $customfield = array("label" => $label, "input" => JText::_( 'JEV_CREATOR_ONLY' ));
            $customfields["rsvp"] = $customfield;
            return true;
        }
        if ($eventid > 0) {

            $sql = "SELECT * FROM #__jev_attendance WHERE ev_id=" . $eventid;
            $db->setQuery($sql);
            $rsvpdata = $db->loadObject();
            if (!$rsvpdata) {
                $rsvpdata = $this->newRSVP();
            }
        } else {
            $rsvpdata = $this->newRSVP();
        }

        // If this is a copy reset the event id
        if (JRequest::getString("jevtask", "") == "icalevent.editcopy") {
            $eventid = 0;
        }

        if ($rsvpdata->message == "") {
            $rsvpdata->message = JText::_( 'JEV_DEFAULT_MESSAGE' );
        }
        if ($rsvpdata->subject == "") {
            $rsvpdata->subject = JText::_( 'JEV_DEFAULT_SUBJECT' );
        }
        if ($rsvpdata->remindermessage == "") {
            $rsvpdata->remindermessage = JText::_( 'JEV_DEFAULT_REMINDER_MESSAGE' );
        }
        if ($rsvpdata->remindersubject == "") {
            $rsvpdata->remindersubject = JText::_( 'JEV_DEFAULT_REMINDER_SUBJECT' );
        }
        $script = "JevRsvpLanguage.strings['JEV_HIDE_REGISTRATION_RSVP']='" . JText::_("JEV_HIDE_REGISTRATION_RSVP", true) . "';";
        $document = JFactory::getDocument();
        $document->addScriptDeclaration($script);

	$style = <<<STYLE
div#jevrsvpattend div , div#jevrsvpinvite div, div#jevrsvpremind div{
	clear:left;
	line-height:1.6em;
}
div#jevrsvpattend .label, div#jevrsvpinvite .label, div#jevrsvpremind .label{
	width:240px;
	margin-right:10px;
	display:block;
	float:left;
}

STYLE;
        $document->addStyleDeclaration($style);

        if ($this->params->get("attendance", 1)) {

            ob_start();
?>
            <input type="hidden" name="custom_rsvp_evid" value="<?php echo $eventid; ?>" />
            <div id="jevrsvpattend">
                <fieldset>
                    <legend><?php echo JText::_( 'JEV_ATTENDANCE' ); ?></legend>
                    <div class="rsvp_allowregistration">
                        <div class='label'><?php echo JText::_( 'JEV_ALLOW_REGISTRATION' ); ?></div>
                        <label for="custom_rsvp_allowregistration1"><?php echo JText::_( 'JEV_YES' ); ?></label>
                        <input type="radio" name="custom_rsvp_allowregistration" id="custom_rsvp_allowregistration1" value="1" <?php echo $rsvpdata->allowregistration == 1 ? "checked='checked'" : ""; ?> onclick="enableattendance();" />
                        <label for="custom_rsvp_allowregistration0"><?php echo JText::_( 'JEV_NO' ); ?></label>
                        <input type="radio" name="custom_rsvp_allowregistration" id="custom_rsvp_allowregistration0" value="0" <?php echo $rsvpdata->allowregistration ? "" : "checked='checked'"; ?> onclick="disableattendance();"/>
		     <?php if ($this->params->get("invites", 0)) { ?>
                        <label for="custom_rsvp_allowregistration2"><?php echo JText::_( 'JEV_BY_INVITATION' ); ?></label>
                        <input type="radio" name="custom_rsvp_allowregistration" id="custom_rsvp_allowregistration2" value="2" <?php echo $rsvpdata->allowregistration == 2 ? "checked='checked'" : ""; ?> onclick="enableattendance();"/>
		     <?php } ?>
                        <input type="hidden" name="custom_rsvp_params" id="custom_rsvp_params" value="<?php echo $rsvpdata->params; ?>"  />

                    </div>
					
                    <div id="jevattendance" <?php if (!$rsvpdata->allowregistration)
                echo "style='display:none'"; ?> >
		<div class="rsvp_sessionaccess">
			<label for="custom_rsvp_sessionaccess"  class='label'><?php echo JText::_( 'JEV_SESSION_ACCESSLEVEL' ); ?></label>
			<?php 
			$accesslist = JEventsHTML::buildAccessSelect(  intval($rsvpdata->sessionaccess ), 'class="inputbox" size="1" onchange="toggleSessionAccessMessage()" ', JText::_("JEV_ACCESSLEVEL_MATCHES_EVENT"), "custom_rsvp_sessionaccess"); 
			if ($rsvpdata->sessionaccess<0){
				$accesslist = str_replace('<option value="">','<option value="-1" selected="selected">', $accesslist );
			}
			else {
				$accesslist = str_replace('<option value="">','<option value="-1" >', $accesslist );
			}
			echo $accesslist;
			?>
		</div>
		<div id="rsvp_sessionaccessmessage" <?php if ($rsvpdata->sessionaccess<0) 
                echo "style='display:none'"; ?>>
			<div class='label'><?php echo JText::_( 'JEV_SESSION_NOACCESS_MESSAGE' ); ?></div>		
			<input type="text" size="60" maxlength="250" value="<?php echo $rsvpdata->sessionaccessmessage ;?>" id="custom_rsvp_sessionaccessmessage" name="custom_rsvp_sessionaccessmessage">
		</div>
<?php if ($this->params->get("capacity", 0)) { ?>
                            <div   class="rsvp_capacity">
                                <label for="custom_rsvp_capacity"  class='label'><?php echo JText::_( 'JEV_CAPACITY' ); ?></label>
                    <input type="text" name="custom_rsvp_capacity" id="custom_rsvp_capacity" value="<?php echo $rsvpdata->capacity; ?>" size="6" />
                </div>
<?php if ($this->params->get("waitinglist", 0)) { ?>
                    <div  class="rsvp_waitingcapacity">
                        <label for="custom_rsvp_waitingcapacity"  class='label'><?php echo JText::_( 'JEV_WAITING_CAPACITY' ); ?></label>
                        <input type="text" name="custom_rsvp_waitingcapacity" id="custom_rsvp_waitingcapacity" value="<?php echo $rsvpdata->waitingcapacity; ?>" size="6" />
                    </div>
<?php
                }
            }
            if ($this->params->get("allowpending", 0)) {
?>
                <div  class="rsvp_initialstate">
                    <div class='label'><?php echo JText::_( 'JEV_INITIAL_REGISTRATION_STATE' ); ?></div>
                    <label for="custom_rsvp_initialstate0"><?php echo JText::_( 'JEV_INITIAL_STATE_PENDING' ); ?></label>
                    <input type="radio" name="custom_rsvp_initialstate" id="custom_rsvp_initialstate0" value="0" <?php echo $rsvpdata->initialstate == 0 ? "checked='checked'" : ""; ?> />
                    <label for="custom_rsvp_initialstate1"><?php echo JText::_( 'JEV_INITIAL_STATE_APPROVED' ); ?></label>
                    <input type="radio" name="custom_rsvp_initialstate" id="custom_rsvp_initialstate1" value="1" <?php echo $rsvpdata->initialstate == 1 ? "checked='checked'" : ""; ?> />
                </div>
<?php
            }
            jimport("joomla.utilities.file");
            $templates = JFolder::files(dirname(__FILE__) . "/params/", ".xml");
            // only offer extra fields templates if there is more than one available
            if ($rsvpdata->template == "") {
                $rsvpdata->template = $this->params->get("defaultcf");
			}
			// And the new db versions
			$db = JFactory::getDBO();
			$user = JFactory::getUser();
			$db->setQuery("SELECT * FROM #__jev_rsvp_templates where (global=1 OR created_by=".$user->id." OR id=".intval($rsvpdata->template).") AND ((istemplate=1  AND published =1  ) OR id=".intval($rsvpdata->template).")" );
			$dbtemplates = $db->loadObjectList();
			echo $db->getErrorMsg();

            if (count($templates) > 1 || (count($templates) == 1 && $templates[0] == "fieldssample.xml") ) {
?>
                <fieldset  class="rsvp_extrafields">
                    <legend><?php echo JText::_( 'JEV_EXTRA_FIELDS' ); ?></legend>
                    <div>
                        <label for="custom_rsvp_template" class='label'><?php echo JText::_( 'JEV_EXTRA_FIELDS_TEMPLATE' ); ?></label>
<?php
                $options = array();
                $options[] = JHTML::_('select.option', "", JText::_( 'JEV_SELECT_TEMPLATE' ), 'var', 'text');
				$options[] = JHTML::_('select.option', -1, JText::_( 'JEV_BLANK_TEMPLATE' ), 'var', 'text');
				foreach ($dbtemplates as $template) {
					$options[] = JHTML::_('select.option', $template->id, $template->title, 'var', 'text');
				}

                foreach ($templates as $template) {
                    if ($template == "fieldssample.xml"){
                        continue;
					}
                    $options[] = JHTML::_('select.option', $template, ucfirst(str_replace(".xml", "", $template)), 'var', 'text');
                }
				// if only one choice then no need to ask - this is not good since it forces you to use the custom fields
                if (count($options) == 2){
                //    array_shift($options);
				}
                echo JHTML::_('select.genericlist', $options, "custom_rsvp_template", 'onchange=changeTemplateSelection()', 'var', 'text', $rsvpdata->template);

				JHTML::_('behavior.modal');
				$link =JRoute::_("index.php?option=com_rsvppro&task=templates.edit&tmpl=component&cid[0]=xxGGxx&customise=1");
				if (intval($rsvpdata->template)>0){
					$style="";
				}
				else {
					$style="style='display:none' ";
				}
				if (JevTemplateHelper::canCreateOwn() || JevTemplateHelper::canCreateGlobal()){
				?>
                <a <?php echo $style;?> href='#<?php  echo JText::_( 'JEV_CUSTOMISE_EXTRA_FIELDS_TEMPLATE' ); ?>' onclick="customiseTemplate('<?php echo $link;?>');return false;"  id="custom_rsvp_template_link" ><?php  echo JText::_( 'JEV_CUSTOMISE_EXTRA_FIELDS_TEMPLATE' ); ?></a>
				<?php } 
				else {
					?>
				<span id="custom_rsvp_template_link"></span>
				<?php
				}
?>
				</div>
            </fieldset>
            <?php }
            ?>
            <div class="rsvp_allowcancellation">
                <div class='label'><?php echo JText::_( 'JEV_ALLOW_CANCELLATION' ); ?></div>
                <label for="custom_rsvp_allowcancellation1"><?php echo JText::_( 'JEV_YES' ); ?></label>
                <input type="radio" name="custom_rsvp_allowcancellation" id="custom_rsvp_allowcancellation1" value="1"  <?php echo $rsvpdata->allowcancellation ? "checked='checked'" : ""; ?> onclick="updateCancelClose(1);"/>
                <label for="custom_rsvp_allowcancellation0"><?php echo JText::_( 'JEV_NO' ); ?></label>
                <input type="radio" name="custom_rsvp_allowcancellation" id="custom_rsvp_allowcancellation0" value="0" <?php echo $rsvpdata->allowcancellation ? "" : "checked='checked'"; ?>  onclick="updateCancelClose(0);"/>
            </div>
                
                <?php
                // handle legacy events before allowchanges was introduced
                if ($rsvpdata->allowchanges==-1){
                    $rsvpdata->allowchanges=$rsvpdata->allowcancellation;
                }
                ?>
            <div class="rsvp_allowchanges">
                <div class='label'"><?php echo JText::_( 'JEV_ALLOW_CHANGES' ); ?></div>
                <label for="custom_rsvp_allowchanges1"><?php echo JText::_( 'JEV_YES' ); ?></label>
                <input type="radio" name="custom_rsvp_allowchanges" id="custom_rsvp_allowchanges1" value="1"  <?php echo $rsvpdata->allowchanges ? "checked='checked'" : ""; ?> onclick="updateCancelClose(1);"/>
                <label for="custom_rsvp_allowchanges0"><?php echo JText::_( 'JEV_NO' ); ?></label>
                <input type="radio" name="custom_rsvp_allowchanges" id="custom_rsvp_allowchanges0" value="0" <?php echo $rsvpdata->allowchanges ? "" : "checked='checked'"; ?>  onclick="updateCancelClose(0);"/>
            </div>
                
            <div class="rsvp_allrepeats">
                <div  class='label'><?php echo JText::_( 'JEV_REGISTER_TRACK_ATTENDANCE' ); ?></div>
                <label for="custom_rsvp_allrepeats1"><?php echo JText::_( 'JEV_ALL_REPEATS' ); ?></label>
                <input type="radio" name="custom_rsvp_allrepeats" id="custom_rsvp_allrepeats1" value="1" <?php echo $rsvpdata->allrepeats ? "checked='checked'" : ""; ?>/>
                <label for="custom_rsvp_allrepeats0"><?php echo JText::_( 'JEV_SPECIFIC_REPEATS' ); ?></label>
                <input type="radio" name="custom_rsvp_allrepeats" id="custom_rsvp_allrepeats0" value="0" <?php echo $rsvpdata->allrepeats ? "" : "checked='checked'"; ?>/>
            </div>
            <div class="rsvp_showattendees">
                <div  class='label'><?php echo JText::_( 'JEV_SHOW_ATTENDEES' ); ?></div>
                <label for="custom_rsvp_showattendees1"><?php echo JText::_( 'JEV_YES' ); ?></label>
                <input type="radio" name="custom_rsvp_showattendees" id="custom_rsvp_showattendees1" value="1" <?php echo $rsvpdata->showattendees == 1 ? "checked='checked'" : ""; ?>/>
                <label for="custom_rsvp_showattendees0"><?php echo JText::_( 'JEV_NO' ); ?></label>
                <input type="radio" name="custom_rsvp_showattendees" id="custom_rsvp_showattendees0" value="0" <?php echo $rsvpdata->showattendees ? "" : "checked='checked'"; ?>/>
		<?php if ($this->params->get("invites", 0)) { ?>
                <label for="custom_rsvp_showattendees2"><?php echo JText::_( 'JEV_BY_INVITATION' ); ?></label>
                <input type="radio" name="custom_rsvp_showattendees" id="custom_rsvp_showattendees2" value="2" <?php echo $rsvpdata->showattendees == 2 ? "checked='checked'" : ""; ?>/>
		<?php } ?>
            </div>
            <div class="rsvp_attendintro">
                <div style="font-weight:bold"><?php echo JText::_( 'JEV_ATTEND_INTRO' ); ?></div>
                <?php
                // parameters : areaname, content, hidden field, width, height, rows, cols
                echo $editor->display('custom_rsvp_attendintro', $rsvpdata->attendintro, "100%", 250, '50', '10', false);
                ?>
            </div>
            <?php
                echo $this->openCloseDates($rsvpdata, $row);
            ?>
            </div>
        </fieldset>
    </div>
<?php
                $input = ob_get_clean();

                $label = JText::_( 'JEV_ATTENDANCE' );
                $extraTabs[] = array("title" => $label, "paneid" => 'jev_attend_pane', "content" => $input);
            }

            if ($this->params->get("invites", 0)) {
                $rsvpdata->invites = ($rsvpdata->invites || $this->params->get("autoinvite", "") != "" || $this->params->get("defaultallowinvites", 0));
                ob_start();
?>
                <div id="jevrsvpinvite">
                    <fieldset>
                        <legend><?php echo JText::_( 'JEV_INVITATION_OPTIONS' ); ?></legend>
                        <div>
                            <div class='label'><?php echo JText::_( 'JEV_CREATE_INVITES' ); ?></div>
                            <label for="custom_rsvp_invites1"><?php echo JText::_( 'JEV_YES' ); ?></label>
                            <input type="radio" name="custom_rsvp_invites" id="custom_rsvp_invites1" value="1" onclick="enableinvites()" <?php echo $rsvpdata->invites ? "checked='checked'" : ""; ?>/>
                            <label for="custom_rsvp_invites0"><?php echo JText::_( 'JEV_NO' ); ?></label>
                            <input type="radio" name="custom_rsvp_invites" id="custom_rsvp_invites0" value="0" onclick="disableinvites()"  <?php echo $rsvpdata->invites ? "" : "checked='checked'"; ?>/>
                            <div id="jev_allinvites" <?php echo $rsvpdata->invites ? "" : "style='display:none;'"; ?>>
                                <div class="rsvp_allinvites">
                                    <div class='label'><?php echo JText::_( 'JEV_ALL_INVITES' ); ?></div>
                                    <label for="custom_rsvp_allinvites1"><?php echo JText::_( 'JEV_ALL_REPEATS' ); ?></label>
                                    <input type="radio" name="custom_rsvp_allinvites" id="custom_rsvp_allinvites1" value="1" <?php echo $rsvpdata->allinvites ? "checked='checked'" : ""; ?>/>
                                    <label for="custom_rsvp_allinvites0"><?php echo JText::_( 'JEV_SPECIFIC_REPEATS' ); ?></label>
                                    <input type="radio" name="custom_rsvp_allinvites" id="custom_rsvp_allinvites0" value="0" <?php echo $rsvpdata->allinvites ? "" : "checked='checked'"; ?>/>
                                </div>
                                <div>
                                    <div class='label'><?php echo JText::_( 'JEV_HIDE_NONE_INVITEES' ); ?></div>
                                    <label for="custom_rsvp_hidenoninvitees1"><?php echo JText::_( 'JEV_YES' ); ?></label>
                                    <input type="radio" name="custom_rsvp_hidenoninvitees" id="custom_rsvp_hidenoninvitees1" value="1" <?php echo $rsvpdata->hidenoninvitees ? "checked='checked'" : ""; ?>/>
                                    <label for="custom_rsvp_hidenoninvitees0"><?php echo JText::_( 'JEV_NO' ); ?></label>
                                    <input type="radio" name="custom_rsvp_hidenoninvitees" id="custom_rsvp_hidenoninvitees0" value="0" <?php echo $rsvpdata->hidenoninvitees ? "" : "checked='checked'"; ?>/>
                                </div>
                            </div>
                            <div id="jev_invites" <?php echo $rsvpdata->invites ? "" : "style='display:none;'"; ?>>
                                <em><?php echo JText::_( 'JEV_ADD_INVITES_MESSAGE' ); ?></em>
                            </div>
                        </div>
                        <div id="jevmessage" <?php if (!$rsvpdata->invites)
                    echo "style='display:none'"; ?>>
                            <div style="font-weight:bold"><?php
                echo JText::_( 'JEV_EMAIL_MESSAGE' );
                JHTML::_('behavior.tooltip');
                echo " " . JHTML::_('tooltip', JText::_( 'JEV_DEFAULT_MESSAGE_DESC' ), null, 'tooltip.png', null, null, 0);
?></div>
            <div  class='label'><?php echo JText::_( 'JEV_EMAIL_SUBJECT' ); ?></div>
            <input type="text" name="custom_rsvp_subject" value="<?php echo $rsvpdata->subject; ?>" size="50" maxlength="255" />
            <?php
                // parameters : areaname, content, hidden field, width, height, rows, cols
		if ($rsvpdata->message == strip_tags($rsvpdata->message)){
			$rsvpdata->message = htmlspecialchars(nl2br($rsvpdata->message));
		}
                echo $editor->display('custom_rsvp_message', $rsvpdata->message, "100%", 250, '50', '10', false);
            ?>
            </div>
        </fieldset>
    </div>
<?php
                $input = ob_get_clean();
                $label = JText::_( 'JEV_INVITATION_OPTIONS' );
                $extraTabs[] = array("title" => $label, "paneid" => 'jev_invite_pane', "content" => $input);
            }
            if ($this->params->get("reminders", 0)) {
		JPluginHelper::importPlugin("rsvppro");
		$dispatcher =& JDispatcher::getInstance();
		$extrareminders = array();
		$results = $dispatcher->trigger( 'onEditReminders' , array(&$extrareminders));
				
                ob_start();
?>
                <div id="jevrsvpremind">
                    <fieldset>
                        <legend><?php echo JText::_( 'JEV_REMINDER_OPTIONS' ); ?></legend>
                        <div style="clear:both" >
                            <div class='label'><?php echo JText::_( 'JEV_ALLOW_REMINDERS' ); ?></div>
                            <label for="custom_rsvp_allowreminders1"><?php echo JText::_( 'JEV_YES' ); ?></label>
                            <input type="radio" name="custom_rsvp_allowreminders" id="custom_rsvp_allowreminders1" value="1" onclick="enablereminders()" <?php echo $rsvpdata->allowreminders==1 ? "checked='checked'" : ""; ?>/>
                            <label for="custom_rsvp_allowreminders0"><?php echo JText::_( 'JEV_NO' ); ?></label>
                            <input type="radio" name="custom_rsvp_allowreminders" id="custom_rsvp_allowreminders0" value="0" onclick="disablereminders()"  <?php echo $rsvpdata->allowreminders ? "" : "checked='checked'"; ?>/>
			<?php
				foreach ($extrareminders as $k=>$v){
					?>
                            <label for="custom_rsvp_allowreminders<?php echo $k; ?>"><?php echo $v; ?></label>
                            <input type="radio" name="custom_rsvp_allowreminders" id="custom_rsvp_allowreminders<?php echo $k; ?>" value="<?php echo $k; ?>" onclick="enablereminders()"  <?php echo $rsvpdata->allowreminders==$k ?  "checked='checked'" : ""; ?>/>
					<?php
				}
			?>
                        </div>
                        <div id="jevreminder" <?php if (!$rsvpdata->allowreminders)
                    echo "style='display:none'"; ?>>
                            <div class="rsvp_allreminders">
                                <div class='label'><?php echo JText::_( 'JEV_REMIND_ALL_REPEATS' ); ?></div>
                                <label for="custom_rsvp_remindallrepeats1"><?php echo JText::_( 'JEV_FIRST_REPEAT' ); ?></label>
                                <input type="radio" name="custom_rsvp_remindallrepeats" id="custom_rsvp_remindallrepeats1" value="1" <?php echo $rsvpdata->remindallrepeats ? "checked='checked'" : ""; ?>/>
                                <label for="custom_rsvp_remindallrepeats0"><?php echo JText::_( 'JEV_SPECIFIC_REPEATS' ); ?></label>
                                <input type="radio" name="custom_rsvp_remindallrepeats" id="custom_rsvp_remindallrepeats0" value="0" <?php echo $rsvpdata->remindallrepeats ? "" : "checked='checked'"; ?>/>
                            </div>
                            <div style="font-weight:bold"><?php
                echo JText::_( 'JEV_REMINDER_EMAIL_MESSAGE' );
                JHTML::_('behavior.tooltip');
                echo " " . JHTML::_('tooltip', JText::_( 'JEV_DEFAULT_MESSAGE_DESC' ), null, 'tooltip.png', null, null, 0);
?></div>
            <div  class='label'><?php echo JText::_( 'JEV_EMAIL_SUBJECT' ); ?></div>
                <input type="text" name="custom_rsvp_remindersubject" value="<?php echo $rsvpdata->remindersubject; ?>" size="50" maxlength="255" />
            <?php
                // parameters : areaname, content, hidden field, width, height, rows, cols
		if ($rsvpdata->remindermessage == strip_tags($rsvpdata->remindermessage)){
			$rsvpdata->remindermessage = htmlspecialchars(nl2br($rsvpdata->remindermessage));
		}
                echo $editor->display('custom_rsvp_remindermessage', $rsvpdata->remindermessage, "100%", 250, '50', '10', false);
            ?>
            </div>
        </fieldset>
    </div>
<?php
                $input = ob_get_clean();

                JHTML::_('behavior.modal');

                $label = JText::_( 'JEV_REMINDER_OPTIONS' );
                $customfield = array("label" => $label, "input" => $input);
                $customfields["rsvp"] = $customfield;

                $extraTabs[] = array("title" => $label, "paneid" => 'jev_remind_pane', "content" => $input);
            }

            return true;
        }

	private function openCloseDates($rsvpdata, $row) {
		ob_start();
?>
		<div>
			<fieldset><legend><?php echo JText::_('JEV_STARTREGISTRATION'); ?></legend>
				<strong><?php echo JText::_("JEV_REGISTRATION_TIME_INTRO"); ?> </strong><br/>
				<div style="float:left" class="regopendate">
		<?php
		echo JText::_('JEV_STARTREGISTRATION_DATE') . "&nbsp;";
		$params = & JComponentHelper::getParams(JEV_COM_COMPONENT);
		$minyear = $params->get("com_earliestyear", 1970);
		$maxyear = $params->get("com_latestyear", 2150);
		if ($rsvpdata->regopen == "0000-00-00 00:00:00")
			$rsvpdata->regopen = strftime("%Y-%m-%d %H:00:00");
		?>
		<input type="text" id="custom_rsvp_regopen" name="custom_rsvp_regopen"  value="<?php echo $rsvpdata->regopen; ?>" style="display:none" />
		<?php
		if (method_exists("JEVHelper", "loadCalendar11")) {
			JEVHelper::loadCalendar11("regopen", "regopen", substr($rsvpdata->regopen, 0, 10), $minyear, $maxyear, 'checkRegDates(\'regopentime\');', 'checkRegDates(\'regopentime\');', 'Y-m-d');
		} else {
			JevRsvpAttendance::loadCalendar11("regopen", "regopen", substr($rsvpdata->regopen, 0, 10), $minyear, $maxyear, 'checkRegDates(\'regopentime\');', 'checkRegDates(\'regopentime\');', 'Y-m-d');
		}

		if (strlen($rsvpdata->regopen) > 10) {
			$regopentime = strtotime($rsvpdata->regopen);
			$hiddenregopentime = strftime("%H:%M", $regopentime);
			list($h, $m) = explode(":", $hiddenregopentime);
			$format = JUtility::isWinOS() ? "%I:%M" : "%l:%M";
			if ($h > 11) {
				$regopentime = strftime($format, $regopentime) . " " . JText::_("JEV_PM");
			} else {
				$regopentime = strftime($format, $regopentime) . " " . JText::_("JEV_AM");
			}
		} else {
			$regopentime = "";
			$hiddenregopentime = "00:00";
		}
		?>
	</div>
	<div style="position:relative" class="regopentime">
<?php echo JText::_('JEV_STARTREGISTRATION_TIME') . "&nbsp;"; ?>
		<input class="inputbox" type="text"  id="regopentime" size="8" value="<?php echo $regopentime; ?>" />
		<input class="inputbox" type="hidden" id="hiddenregopentime" size="8" value="<?php echo $hiddenregopentime; ?>" />
	</div>
</fieldset>
</div>
<div>
<fieldset><legend><?php echo JText::_('JEV_ENDREGISTRATION'); ?></legend>
	<strong><?php echo JText::_("JEV_REGISTRATION_TIME_INTRO"); ?> </strong><br/>
	<div style="float:left" class="regclosedate">
<?php
		echo JText::_('JEV_ENDREGISTRATION_DATE') . "&nbsp;";
		$params = & JComponentHelper::getParams(JEV_COM_COMPONENT);
		$minyear = $params->get("com_earliestyear", 1970);
		$maxyear = $params->get("com_latestyear", 2150);
		if ($rsvpdata->regclose == "0000-00-00 00:00:00")
			$rsvpdata->regclose = $row->publish_up();
?>
		<input type="text" id="custom_rsvp_regclose"  name="custom_rsvp_regclose" value="<?php echo $rsvpdata->regclose; ?>" style="display:none" />
		<?php
		if (method_exists("JEVHelper", "loadCalendar11")) {
			JEVHelper::loadCalendar11("regclose", "regclose", substr($rsvpdata->regclose, 0, 10), $minyear, $maxyear, 'checkRegDates(\'regclosetime\');', 'checkRegDates(\'regclosetime\');', 'Y-m-d');
		} else {
			JevRsvpAttendance::loadCalendar11("regclose", "regclose", substr($rsvpdata->regclose, 0, 10), $minyear, $maxyear, 'checkRegDates(\'regclosetime\');', 'checkRegDates(\'regclosetime\');', 'Y-m-d');
		}


		if (strlen($rsvpdata->regclose) > 10) {
			$regclosetime = strtotime($rsvpdata->regclose);
			$hiddenregclosetime = strftime("%H:%M", $regclosetime);
			list($h, $m) = explode(":", $hiddenregclosetime);
			$format = JUtility::isWinOS() ? "%I:%M" : "%l:%M";
			if ($h > 11) {
				$regclosetime = strftime($format, $regclosetime) . " " . JText::_("JEV_PM");
			} else {
				$regclosetime = strftime($format, $regclosetime) . " " . JText::_("JEV_AM");
			}
		} else {
			$regclosetime = "";
			$hiddenregclosetime = "00:00";
		}
		?>
	</div>
	<div style="position:relative" class="regclosetime"> 
		<?php echo JText::_('JEV_ENDREGISTRATION_TIME') . "&nbsp;"; ?>
		<input class="inputbox" type="text" id="regclosetime" size="8" value="<?php echo $regclosetime; ?>" />
		<input class="inputbox" type="hidden" id="hiddenregclosetime" size="8" value="<?php echo $hiddenregclosetime; ?>" />
	</div>
</fieldset>
</div>
<div id='jevendcancel' style="display:<?php echo $rsvpdata->allowcancellation ? 'block' : 'none'; ?>">
<fieldset><legend><?php echo JText::_('JEV_ENDCANCELLATIONS'); ?></legend>
	<strong><?php echo JText::_("JEV_ENDCANCELLATIONS_INTRO"); ?> </strong><br/>
	<div style="float:left">
<?php
		echo JText::_('JEV_ENDCANCELLATION_DATE') . "&nbsp;";
		$params = & JComponentHelper::getParams(JEV_COM_COMPONENT);
		$minyear = $params->get("com_earliestyear", 1970);
		$maxyear = $params->get("com_latestyear", 2150);
		if ($rsvpdata->cancelclose == "0000-00-00 00:00:00")
			$rsvpdata->cancelclose = $row->publish_up();
?>
		<input type="hidden" id="custom_rsvp_cancelclose"  name="custom_rsvp_cancelclose" value="<?php echo $rsvpdata->cancelclose; ?>" />
		<?php
		if (method_exists("JEVHelper", "loadCalendar11")) {
			JEVHelper::loadCalendar11("cancelclose", "cancelclose", substr($rsvpdata->cancelclose, 0, 10), $minyear, $maxyear, 'checkRegDates(\'cancelclosetime\');', 'checkRegDates(\'cancelclosetime\');', 'Y-m-d');
		} else {
			JevRsvpAttendance::loadCalendar11("cancelclose", "cancelclose", substr($rsvpdata->cancelclose, 0, 10), $minyear, $maxyear, 'checkRegDates(\'cancelclosetime\');', 'checkRegDates(\'cancelclosetime\');', 'Y-m-d');
		}

		if (strlen($rsvpdata->cancelclose) > 10) {
			$cancelclosetime = strtotime($rsvpdata->cancelclose);
			$hiddencancelclosetime = strftime("%H:%M", $cancelclosetime);
			list($h, $m) = explode(":", $hiddencancelclosetime);
			$format = JUtility::isWinOS() ? "%I:%M" : "%l:%M";
			if ($h > 11) {
				$cancelclosetime = strftime($format, $cancelclosetime) . " " . JText::_("JEV_PM");
			} else {
				$cancelclosetime = strftime($format, $cancelclosetime) . " " . JText::_("JEV_AM");
			}
		} else {
			$cancelclosetime = "";
			$hiddencancelclosetime = "00:00";
		}
		if (JVersion::isCompatible("1.6.0")){
			$pluginpath = 'plugins/jevents/jevrsvppro/rsvppro/';
		}
		else {
			$pluginpath = 'plugins/jevents/rsvppro/';
		}

		?>
	</div>
	<div style="position:relative">
		<?php echo JText::_('JEV_ENDCANCELLATION_TIME') . "&nbsp;"; ?>
		<input class="inputbox" type="text" id="cancelclosetime" size="8" value="<?php echo $cancelclosetime; ?>" />
		<input class="inputbox" type="hidden" id="hiddencancelclosetime" size="8" value="<?php echo $hiddencancelclosetime; ?>" />
	</div>
</fieldset>
</div>
<div id="rsvpspacer"></div>
<script language="javascript" type="text/javascript" >
window.addEvent("domready",function(){

	myStartPick = new HoverPickTime('regopentime', {'img':	'<?php echo JURI::root(); ?><?php echo $pluginpath;?>clock_red.png','amPm': ['<?php echo JText::_("JEV_AM"); ?>', '<?php echo JText::_("JEV_PM"); ?>']});

	myEndPick = new HoverPickTime('regclosetime', {'img':	'<?php echo JURI::root(); ?><?php echo $pluginpath;?>clock_red.png','amPm': ['<?php echo JText::_("JEV_AM"); ?>', '<?php echo JText::_("JEV_PM"); ?>']});

	myCancelPick = new HoverPickTime('cancelclosetime', {'img':	'<?php echo JURI::root(); ?><?php echo $pluginpath;?>clock_red.png','amPm': ['<?php echo JText::_("JEV_AM"); ?>', '<?php echo JText::_("JEV_PM"); ?>']});
});
</script>
<?php
		$html = ob_get_clean();
		return $html;
	}

	public function storeAttendance($event) {
		$evdetail = $event->_detail;
		if (!array_key_exists("rsvp_allowregistration", $evdetail->_customFields))
			return;

		$db = & JFactory::getDBO();

		$eventid = intval($evdetail->_customFields["rsvp_evid"]);
		if ($eventid == 0) {
			$eventid = $event->ev_id;
		}
		if ($eventid > 0) {

			$sql = "SELECT * FROM #__jev_attendance WHERE ev_id=" . $eventid;
			$db->setQuery($sql);
			$rsvpdata = $db->loadObject();

			// Store details in registry - will need them for waiting lists!
			$registry = & JRegistry::getInstance("jevents");
			$registry->setValue("rsvpdata", $rsvpdata);
			$registry->setValue("event", $event);

			JTable::addIncludePath(JPATH_ADMINISTRATOR."/components/com_rsvppro/tables/");
			$rsvpitem =& JTable::getInstance('jev_attendance');
			//$rsvpitem = new JTable("#__jev_attendance", "id", $db);

			// ensure picks up default values
			foreach ($this->newRSVP() as $k => $v) {
				$rsvpitem->$k = $v;
			}
			$rsvpitem->id = 0;
			foreach ($evdetail->_customFields as $key => $value) {
				if (strpos($key, "rsvp_") === 0) {
					$key = str_replace("rsvp_", "", $key);
					$rsvpitem->$key = $value;
				}
			}
			unset($rsvpitem->evid);
			$rsvpitem->ev_id = intval($eventid);

			if (JRequest::getString("freq")=="none"){
				$rsvpitem->allrepeats = 1;
				$rsvpitem->allinvites = 1;
				$rsvpitem->remindallrepeats = 1;
			}
			if ($rsvpdata && $rsvpdata->id > 0) {
				$rsvpitem->id = intval($rsvpdata->id);
				$success = $rsvpitem->store();

				// Also clear out defunct attendance and invitation records
				// if !registration allows then remove all attendance records
				if (!$rsvpitem->allowregistration) {
					/*
					 * Keep the attendees in case someone wants to disable registrations temporarily and then reinstate them
					$sql = "DELETE FROM #__jev_attendees WHERE at_id=" . $rsvpdata->id;
					$db->setQuery($sql);
					$db->query();
					 */
				}

				// if attendance is recorded once for all repeats then remove repeat specific attendance records
				if ($rsvpitem->allrepeats) {
					$sql = "DELETE FROM #__jev_attendees WHERE at_id=" . $rsvpdata->id . " AND rp_id>0";
					$db->setQuery($sql);
					$db->query();
					//$sql = "DELETE FROM #__jev_attendance WHERE ev_id=" . $rsvpdata->ev_id . " AND allrepeats=0";
					//$db->setQuery($sql);
					//$db->query();
				} else {
					// if attendance is recorded separately for each repeats then remove general attendance records
					$sql = "DELETE FROM #__jev_attendees WHERE at_id=" . $rsvpdata->id . " AND rp_id=0";
					$db->setQuery($sql);
					$db->query();
					//$sql = "DELETE FROM #__jev_attendance WHERE ev_id=" . $rsvpdata->ev_id . " AND allrepeats=1";
					//$db->setQuery($sql);
					//$db->query();
				}

				// if no invites for this event then remove all invites
				if (!$rsvpitem->invites) {
					$sql = "DELETE FROM #__jev_invitees WHERE at_id=" . $rsvpdata->id;
					$db->setQuery($sql);
					$db->query();
				}
				// if invites cover all repeats then remove repeat specific attendance records
				if ($rsvpitem->allinvites) {
					$sql = "DELETE FROM #__jev_invitees WHERE at_id=" . $rsvpdata->id . " AND rp_id>0";
					$db->setQuery($sql);
					$db->query();
				} else {
					// if attendance is recorded separately for each repeats then remove general attendance records
					$sql = "DELETE FROM #__jev_invitees WHERE at_id=" . $rsvpdata->id . " AND rp_id=0";
					$db->setQuery($sql);
					$db->query();
				}


				// TODO clean up reminders too
			} else {
				$success = $rsvpitem->store();
			}

			if ($success) {
				// Make sure the waiting list reflects any change in capacity
				if (isset($rsvpdata) && $rsvpdata->id) {
					JLoader::register('JevRsvpAttendees',JPATH_ADMINISTRATOR."/components/com_rsvppro/libraries/jevrattendees.php");
					$jevrDisplayAttendees = new JevRsvpAttendees($this->params, $this->jomsocial, $rsvpdata);
					$jevrDisplayAttendees->updateWaitingList($rsvpdata,$rsvpdata->id);
					//$jevrDisplayAttendance = new JevRsvpDisplayAttendance($this->params);
					//$jevrDisplayAttendance->updateWaitingList($rsvpdata->id);
				}
			}

			return $success;
		}
		return false;
	}

	public function deleteAttendance($idlist) {
		$ids = explode(",", $idlist);
		JArrayHelper::toInteger($ids);
		$idlist = implode(",", $ids);

		// fetch the attendance records
		$db = JFactory::getDBO();
		$sql = "SELECT id FROM #__jev_attendance WHERE ev_id IN (" . $idlist . ")";
		$db->setQuery($sql);
		$atids = $db->loadResultArray();

		$sql = "DELETE FROM #__jev_attendance WHERE ev_id IN (" . $idlist . ")";
		$db->setQuery($sql);
		$db->query();

		if ($atids && count($atids) > 0) {
			$atids = implode(",", $atids);

			$sql = "DELETE FROM #__jev_attendees WHERE at_id IN (" . $atids . ")";
			$db->setQuery($sql);
			$db->query();

			$sql = "DELETE FROM #__jev_invitees WHERE at_id IN (" . $atids . ")";
			$db->setQuery($sql);
			$db->query();

			$sql = "DELETE FROM #__jev_reminders WHERE at_id IN (" . $atids . ")";
			$db->setQuery($sql);
			$db->query();
			
		}

		return true;
	}

	private function newRSVP() {
		$rsvpdata = new stdClass();
		$rsvpdata->id = 0;
		$rsvpdata->allowregistration = $this->params->get("defaultallow",0);
		// Add these as defaults from the params!!!
		if ($this->params->get("allowpending", 0)) {
			$rsvpdata->initialstate = $this->params->get("defaultinitialstate", 1);
		}
		else {
			$rsvpdata->initialstate = 1;
		}
		$rsvpdata->allowcancellation = $this->params->get("defaultcancellation",0);
		$rsvpdata->allowchanges = $this->params->get("defaultchanges",0);
		$rsvpdata->allinvites = 1;
		$rsvpdata->allrepeats = 1;
		$rsvpdata->showattendees = $this->params->get("defaultshowattendees",0);
		$rsvpdata->hidenoninvitees = 0;
		$rsvpdata->capacity = 0;
		$rsvpdata->waitingcapacity = 0;
		$rsvpdata->invites = intval($this->params->get("defaultallowinvites",0));
		$rsvpdata->template = "";
		$rsvpdata->attendintro = $this->params->get("defintro", "");
		
		$rsvpdata->message = $this->params->get("message", JText::_( 'JEV_DEFAULT_MESSAGE' ));
		$rsvpdata->subject = $this->params->get("subject", JText::_( 'JEV_DEFAULT_SUBJECT' ));
		$rsvpdata->allowreminders  = $this->params->get("defaultallowreminders",0);
		$rsvpdata->remindermessage = $this->params->get("remindermessage", JText::_( 'JEV_DEFAULT_REMINDER_MESSAGE' ));
		$rsvpdata->remindersubject = $this->params->get("remindersubject", JText::_( 'JEV_DEFAULT_REMINDER_SUBJECT' ));
		$rsvpdata->remindernotice = intval($this->params->get("reminderinterval", 24)) * 3600;
		$rsvpdata->remindallrepeats = 1;
		$rsvpdata->sessionaccess = -1;
		$rsvpdata->sessionaccessmessage = "";
		$rsvpdata->params = "";
		$rsvpdata->regclose = "";
		$rsvpdata->cancelclose = "";
		$rsvpdata->regopen = "";
		$rsvpdata->params = "";

		return $rsvpdata;
	}

	public function autoInvite($event) {
		if ($this->params->get("autoinvite", "") == "")
			return;

		$sql = "SELECT * FROM #__jev_attendance WHERE ev_id=" . $event->ev_id;
		$db = JFactory::getDBO();
		$db->setQuery($sql);
		$rsvpitem = $db->loadObject();

		if (!$rsvpitem) return;
		
		// check if this has already been processed.
		$db = JFactory::getDBO();
		$db->setQuery("SELECT count(at_id) FROM #__jev_invitees WHERE at_id=" . intval($rsvpitem->id));
		if (intval($db->loadResult()) != 0) {
			return;
		}

		$datamodel = new JEventsDataModel();
		$row = $datamodel->queryModel->getEventById($event->ev_id, 0, "icaldb");

		JRequest::setVar("jevattend_hiddeninitees", 1);
		JRequest::setVar("jevinvitee", explode(",", $this->params->get("autoinvite", "")));
		JRequest::setVar("rsvp_email", "email");

		$this->jevrinvitees = new JevRsvpInvitees($this->params, $this->jomsocial);
		$this->jevrinvitees->updateInvitees($rsvpitem, $row, false);

		/*
		  if (is_callable("curl_exec")){
		  // I need the repeat id
		  $query = "SELECT  rpt.* FROM #__jevents_vevent as ev "
		  . "\n LEFT JOIN #__jevents_repetition as rpt ON rpt.eventid = ev.ev_id"
		  . "\n LEFT JOIN #__jevents_vevdetail as det ON det.evdet_id = rpt.eventdetail_id"
		  . "\n LEFT JOIN #__jevents_rrule as rr ON rr.eventid = ev.ev_id"
		  . "\n WHERE ev.ev_id = '".intval($rsvpitem->ev_id)."' ORDER BY rpt.startrepeat asc LIMIT 1" ;

		  $db->setQuery( $query );
		  $repeat = $db->loadObject();

		  if (is_null($repeat)) return;

		  $ch = curl_init();
		  $Itemid=JRequest::getInt("Itemid");
		  $url = JURI::root()."index.php?option=com_jevents&task=icalrepeat.detail&Itemid=$Itemid&tmpl=component&evid=".intval($repeat->rp_id);

		  $url .= '&start_debug=1&debug_host=127.0.0.1&debug_port=10000&debug_stop=1';

		  curl_setopt($ch, CURLOPT_URL,$url);
		  curl_setopt($ch, CURLOPT_VERBOSE, 1);
		  curl_setopt($ch, CURLOPT_POST, 1);
		  curl_setopt($ch, CURLOPT_POSTFIELDS, 	"jevattend_hiddeninvitees=1&jevinvitee=".$this->params->get("autoinvite","") ."&rsvp_email=email");
		  curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		  $this->rawData = curl_exec($ch);
		  curl_close ($ch);
		  }
		 */
	}

	public function autoRemind($event) {
		if ($this->params->get("autoremind", 0)  <3)
			return;

		JLoader::register('JevRsvpReminders', JPATH_ADMINISTRATOR . "/components/com_rsvppro/libraries/jevrreminders.php");
		$this->jevrreminders = new JevRsvpReminders($this->params, 0);

		$sql = "SELECT * FROM #__jev_attendance WHERE ev_id=" . $event->ev_id;
		$db = JFactory::getDBO();
		$db->setQuery($sql);
		$rsvpitem = $db->loadObject();

		if (!$rsvpitem) return;
		
		$this->jevrreminders->remindUsers($rsvpitem, $event, $this->params->get("autoremind", 0));

	}
	
	public function deleteReminders($event) {
		$sql = "SELECT * FROM #__jev_attendance WHERE ev_id=" . $event->ev_id;
		$db = JFactory::getDBO();
		$db->setQuery($sql);
		$rsvpdata = $db->loadObject();

		$sql = "DELETE FROM #__jev_reminders WHERE at_id=".$rsvpdata->id;
		if (!$rsvpdata->remindallrepeats){
			$sql .= " AND rp_id=".$event->rp_id();
		}
		$db->setQuery($sql);
		$db->query();

	}


	/**
	 * Loads all necessary files for and creats popup calendar link
	 *
	 * @static
	 */
	static function loadCalendar11($fieldname, $fieldid, $value, $minyear, $maxyear, $onhidestart="", $onchange="", $format='Y-m-d') {
		$document = & JFactory::getDocument();
		$component = "com_jevents";
		$params = & JComponentHelper::getParams($component);
		$forcepopupcalendar = $params->get("forcepopupcalendar", 1);
		$offset = $params->get("com_starday", 1);
		$calendar = (JVersion::isCompatible("1.6.0")) ? 'calendar12.js' : 'calendar11.js'; // RSH 9/28/10 - need to make the calendar a variable to be compatible with both mootools1.1 and 1.2
		JEVHelper::script($calendar, "components/" . $component . "/assets/js/", true);
		JHTML::stylesheet("dashboard.css", "components/" . $component . "/assets/css/", true);
		$script = '
			var field' . $fieldid . '=false;
			window.addEvent(\'domready\', function() {
			if (field' . $fieldid . ') return;
			field' . $fieldid . '=true;
			new NewCalendar(
				{ ' . $fieldid . ' :  "' . $format . '"},
				{
				direction:0,
				classes: ["dashboard"],
				draggable:true,
				navigation:2,
				tweak:{x:0,y:-75},
				offset:' . $offset . ',
				range:{min:' . $minyear . ',max:' . $maxyear . '},
				readonly:' . $forcepopupcalendar . ',
				months:["' . JText::_("JEV_JANUARY") . '",
				"' . JText::_("JEV_FEBRUARY") . '",
				"' . JText::_("JEV_MARCH") . '",
				"' . JText::_("JEV_APRIL") . '",
				"' . JText::_("JEV_MAY") . '",
				"' . JText::_("JEV_JUNE") . '",
				"' . JText::_("JEV_JULY") . '",
				"' . JText::_("JEV_AUGUST") . '",
				"' . JText::_("JEV_SEPTEMBER") . '",
				"' . JText::_("JEV_OCTOBER") . '",
				"' . JText::_("JEV_NOVEMBER") . '",
				"' . JText::_("JEV_DECEMBER") . '"
				],
				days :["' . JText::_("JEV_SUNDAY") . '",
				"' . JText::_("JEV_MONDAY") . '",
				"' . JText::_("JEV_TUESDAY") . '",
				"' . JText::_("JEV_WEDNESDAY") . '",
				"' . JText::_("JEV_THURSDAY") . '",
				"' . JText::_("JEV_FRIDAY") . '",
				"' . JText::_("JEV_SATURDAY") . '"
				]
				';
		if ($onhidestart != "") {
			$script.=',
				onHideStart : function () { ' . $onhidestart . '; },
				onHideComplete :function () { ' . $onchange . '; }';
		}
		$script.='}
			);
		});';
		$document->addScriptDeclaration($script);
		if ($onchange != "") {
			$onchange = 'onchange="' . $onchange . '"';
		}
		echo '<input type="text" name="' . $fieldname . '" id="' . $fieldid . '" value="' . htmlspecialchars($value, ENT_COMPAT, 'UTF-8') . '" maxlength="10" ' . $onchange . ' size="12"  />';
	}

	
}