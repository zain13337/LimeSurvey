<?php
/**
* Add token entry
*/
?>
<div class='container-fluid <?php if (!isset($ajax) || $ajax = false):?> col-12 side-body <?php echo getSideBodyClass(false); ?> <?php endif; ?>'>

<?php if (!isset($ajax) || $ajax = false) { ?>
  <h3>
  <?php

    if ($token_subaction == "edit") {
        eT("Edit survey participant");
    } else {
        eT("Add survey participant");
    }

    ?>
  </h3>
<?php } ?>
<?php
foreach ($tokendata as $Key => $Value) {
    $$Key = $Value;
}
?>

<div id="edittoken-error-container" class="row" style="display: none;">
    <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" aria-label="Close" onclick="$('#edittoken-error-container').hide();"><span>×</span></button>
        <span class="alert-content"></span>
    </div>
</div>
<div class="row">
  <div class="col-md-12 content-right">
    <?php echo CHtml::form(array("admin/tokens/sa/{$token_subaction}/surveyid/{$surveyid}/tokenid/{$tokenid}"), 'post', array(
        'id' => 'edittoken',
        'class' => '',
        'data-validation-error' => gT("Some mandatory additional attributes were left blank. Please review them.")
    )); ?>
      <!-- Tabs -->
      <?php if( count($attrfieldnames) > 0 ):?>
        <nav>
            <div class="nav nav-tabs" id="edit-survey-text-element-language-selection">

          <!-- Common  -->
          <li role="presentation" class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#general" aria-expanded="true">
              <?php eT('General'); ?>
            </a>
          </li>

          <!-- Custom attibutes -->
          <li role="presentation" class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#custom" aria-expanded="false">
              <?php eT('Additional attributes'); ?>
            </a>
          </li>
        </ul>
      <?php endif; ?>

      <!-- Tabs content-->
      <div class="tab-content">
        <div id="general" class="tab-pane fade show active">
            <div class="ls-flex-column ls-space padding left-5 right-35 col-md-6">
            <!-- General -->
                <div class="form-group">
                <!-- ID  -->
                <label class="form-label">ID:</label>
                <div class="">
                    <p class="form-control-static">
                    <?php
                    if ($token_subaction == "edit") {
                        echo $tokenid;
                    } else {
                        eT("Auto");
                    }
                    ?>
                    </p>
                </div>
                </div>
                <div class="form-group">
                <!--
                TODO:
                To take in account the anonomyzed survey case (completed field contain no date, but a {Y,N}), the code become more complexe
                It will need a refactorisation .
                maybe a widget? At least, a lot of variable should be set in the controller (classes etc)
                -->
                <?php $sCointainerClass = ($oSurvey->anonymized != 'Y') ? 'yes-no-date-container' : 'yes-no-container'; ?>
                <!-- Completed -->
                <div id="completed-container" class="">
                    <label class="form-label" for='completed'>
                        <?php eT("Completed?"); ?>
                    </label>
                    <div class="selector__yesNoContainer <?php echo $sCointainerClass; ?>"
                         id="completed-yes-no-date-container"
                         data-locale="<?php echo convertLStoDateTimePickerLocale(Yii::app()->session['adminlang']); ?>">
                        <div class="row">
                        <?php if ($oSurvey->anonymized != 'Y'):?>

                           <?php
                            $bCompletedValue = "0";
                            if (isset($completed) && $completed != 'N')
                            {
                                $completedDBFormat     = $completed;
                                $bCompletedValue       = "1";
                                $completed             = convertToGlobalSettingFormat($completed, true);
                            }
                            ?>

                            <div class="btn-group btn-group-sm" role="group" aria-label="Completed radio toggle button group">
                                <input type="radio" class="btn-check" name="btnradio" id="completed-switch-yes" autocomplete="off" checked>
                                <label class="btn btn-outline-success" for="completed-switch-yes">Yes</label>
                                <input type="radio" class="btn-check" name="btnradio" id="completed-switch-no" autocomplete="off">
                                <label class="btn btn-outline-danger" for="completed-switch-no">No</label>
                            <!-- <?php
                              //  $this->widget('yiiwheels.widgets.switch.WhSwitch', array(
                              //  'name' => "completed-switch",
                              //  'id'=>"completed-switch",
                              //  'htmlOptions'=>array('class'=>"YesNoDateSwitch action_toggle_bootstrap_switch"),
                              //  'value' => $bCompletedValue,
                              //  'onLabel'=>gT('Yes'),
                              //  'offLabel' => gT('No')));
                            ?> -->
                            </div>
                        <?php else: ?>
                            <div class="">
                            <?php
                            $completedDBFormat = $completed;
                            $bCompletedValue   = (isset($completed) && $completed != 'N') ? "1" : "0";
                            $completed         = (isset($completed) && $completed != 'N') ? 'Y' : 'N';
                            ?>
                            <!--<?php
                                //$this->widget('yiiwheels.widgets.switch.WhSwitch', array(
                                //'name' => "completed-switch",
                                //'id'=>"completed-switch",
                                //'htmlOptions'=>array('class'=>"YesNoSwitch action_toggle_bootstrap_switch"),
                                //'value' => $bCompletedValue,
                                //'onLabel'=>gT('Yes'),
                                //'offLabel' => gT('No')));
                            ?> -->
                            </div>
                        <?php endif; ?>

                        <?php if ($oSurvey->anonymized != 'Y'):?>
                            <div class="">
                                <div id="sent-date-container" class="date-container"  <?php if (!$bCompletedValue):?>style="display: none;"<?php endif; ?>>
                                <div id="completed-date_datetimepicker" class="input-group date">
                                    <input class="YesNoDatePicker form-control" id="completed-date" type="text" value="<?php echo isset($completed) ? $completed : ''?>" name="completed-date" data-date-format="<?php echo $dateformatdetails['jsdate']; ?> HH:mm">
                                    <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                       <!-- <input class='form-control d-none YesNoDateHidden' type='text' size='20' id='completed' name='completed' value="<?php if (isset($completed)) {echo $completed; } else {echo "N"; }?>" /> -->
                    </div>
                </div>
            </div>

            <!-- First name, Last name -->            
            <div class="form-group">
                <label class="form-label" for='firstname'>
                <?php eT("First name:"); ?>
                </label>
                <div class="">
                    <?=TbHtml::textField('firstname', $firstname, [
                        'class' => 'form-control',
                        'size' => '30',
                    ]);?>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label" for='lastname'>
                <?php eT("Last name:"); ?>
                </label>
                <div class="">
                    <?=TbHtml::textField('lastname', $lastname, [
                        'class' => 'form-control',
                        'size' => '30',
                    ]);?>
                </div>
            </div>

            <!-- Token, language -->
            <div class="form-group">
                <label class="form-label" for='token'>
                <?php eT("Access code:"); ?>
                </label>
                <div class="">
                <?=TbHtml::textField('token',(isset($token) ? $token : ""), [
                    'class' => 'form-control',
                    'size' => '20',
                    'maxlength' => $iTokenLength
                ]);?>
                <?php if ($token_subaction == "addnew"): ?>
                    <span id="helpBlock" class="help-block"><?php eT("You can leave this blank, and automatically generate access codes using 'Generate access codes'"); ?></span>
                <?php endif; ?>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label" for='language'>
                <?php eT("Language:"); ?>
                </label>
                <div class="">
                <?php if (isset($language)) {echo languageDropdownClean($surveyid, $language); } else {echo languageDropdownClean($surveyid, Survey::model()->findByPk($surveyid)->language); }?>
                </div>
            </div>
        </div>
        <div class="ls-flex-column ls-space padding left-5 right-35 col-md-6">

            <!-- Email, Email Status  -->
            <div class="form-group">
            <label class="form-label" for='email'>
                <?php eT("Email:"); ?>
            </label>
            <div class="">
                <?=TbHtml::emailField('email', $email, [
                        'class' => 'form-control',
                        'size' => '50',
                        'maxlength' => '320',
                        'multiple' => true
                ]);?>
            </div>
            </div>

            <!-- Email Status -->
            <div class="form-group">
            <label class="form-label" for='emailstatus'>
                <?php eT("Email status:"); ?>
            </label>
            <div class="">
                <?=TbHtml::textField('emailstatus', $emailstatus, [
                        'class' => 'form-control',
                        'size' => '50',
                        'maxlength' => '320',
                        'placeholder' => 'OK'
                ]);?>
            </div>
            </div>

            <!-- Invitation sent, Reminder sent -->
            <div class="form-group">
            <!-- Invitation sent -->
            <label class="form-label" for='sent'>
                <?php eT("Invitation sent?"); ?>
            </label>
            <div class="selector__yesNoContainer <?php echo $sCointainerClass; ?>" id="sent-yes-no-date-container" data-locale="<?php echo convertLStoDateTimePickerLocale(Yii::app()->session['adminlang']); ?>">
                <div class="row">
                <div class="">
                    <?php if ($oSurvey->anonymized != 'Y'):?>
                    <?php
                        // TODO: move to controller
                        $bSwitchValue       = (isset($sent) && $sent != 'N') ? "1" : "0";
                        $bRemindSwitchValue = (isset($remindersent) && $remindersent != 'N') ? "1" : "0";

                        $bSwitchValue = "0";
                        if (isset($sent) && $sent != 'N')
                        {
                            $bSwitchValue     = "1";
                            $sentDBValue      = $sent;
                            $sent             = convertToGlobalSettingFormat($sent, true);
                        }

                        $bRemindSwitchValue = "0";
                        if (isset($remindersent) && $remindersent != 'N')
                        {
                            $bRemindSwitchValue       = "1";
                            $remindersentDBValue      = $remindersent;
                            $remindersent             = convertToGlobalSettingFormat($remindersent, true);
                        }
                    ?>
                    <div class="btn-group btn-group-sm" role="group" aria-label="Invitation sent radio toggle button group">
                        <input type="radio" class="btn-check" name="btnradio" id="invitation-sent-yes" autocomplete="off" checked>
                        <label class="btn btn-outline-success" for="invitation-sent-yes">Yes</label>
                        <input type="radio" class="btn-check" name="btnradio" id="invitation-sent-no" autocomplete="off">
                        <label class="btn btn-outline-danger" for="invitation-sent-no">No</label>
                    </div>
                   <!-- <?php
                      //  $this->widget('yiiwheels.widgets.switch.WhSwitch', array(
                      //  'name' => "sent-switch",
                      //  'id'=>"sent-switch",
                      //  'htmlOptions'=>array('class'=>"YesNoDateSwitch action_toggle_bootstrap_switch"),
                      //  'value' => $bSwitchValue,
                      //  'onLabel'=>gT('Yes'),
                      //  'offLabel' => gT('No')));
                    ?> -->
                    <?php else:?>
                        <?php
                            $sentDBValue = $sent;
                            $remindersentDBValue = $remindersent;
                            $bSwitchValue       = (isset($sent) && $sent != 'N') ? "1" : "0";
                            $bRemindSwitchValue = (isset($remindersent) && $remindersent != 'N') ? "1" : "0";
                        ?>
                            <?php
                            $this->widget('yiiwheels.widgets.switch.WhSwitch', array(
                                'name' => "sent-switch",
                                'id'=>"sent-switch",
                                'htmlOptions'=>array('class'=>"YesNoSwitch action_toggle_bootstrap_switch"),
                                'value' => $bSwitchValue,
                                'onLabel'=>gT('Yes'),
                                'offLabel' => gT('No')));
                            ?>
                    <?php endif; ?>
                </div>

                <div class="">
                    <div id="sent-date-container" data-parent="#sent-switch" class="selector__date-container_hidden date-container" <?php if (!$bSwitchValue){ echo "style='display:none;'"; }?> >
                        <!-- Sent Date -->
                        <div id="sent-date_datetimepicker" class="input-group date">
                        <input class="YesNoDatePicker form-control" id="sent-date" type="text" value="<?php echo isset($sent) ? $sent : ''?>" name="sent-date" data-date-format="<?php echo $dateformatdetails['jsdate']; ?> HH:mm">
                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                        </div>
                    </div>
                </div>
                </div>
                <!-- <input class='form-control d-none YesNoDateHidden' type='text' size='20' id='sent' name='sent' value="<?php if (isset($sent)) {echo $sent; } else {echo "N"; }?>" /> -->
            </div>
            </div>
            <div class="form-group">
            <!-- Reminder sent -->
            <label class="form-label" for='remindersent'>
                <?php eT("Reminder sent?"); ?>
            </label>
            <div class="selector__yesNoContainer <?php echo $sCointainerClass; ?>" id="remind-yes-no-date-container" data-locale="<?php echo convertLStoDateTimePickerLocale(Yii::app()->session['adminlang']); ?>">

                <div class="row">
                <div class="">
                    <?php if ($oSurvey->anonymized != 'Y') {
                        ?>

                        <div class="btn-group btn-group-sm" role="group" aria-label="Reminder sent radio toggle button group" id="remind-switch">
                            <input type="radio" class="btn-check" name="btnradio" id="reminder-sent-yes" autocomplete="off" checked>
                            <label class="btn btn-outline-success" for="reminder-sent-yes">Yes</label>
                            <input type="radio" class="btn-check" name="btnradio" id="reminder-sent-no" autocomplete="off">
                            <label class="btn btn-outline-danger" for="reminder-sent-no">No</label>
                        </div>
                   <!-- <?php
                        //$this->widget('yiiwheels.widgets.switch.WhSwitch', array(
                        //'name' => "remind-switch",
                        //'id'=>"remind-switch",
                        //'htmlOptions'=>array('class'=>"YesNoDateSwitch action_toggle_bootstrap_switch"),
                        //'value' => $bRemindSwitchValue,
                        //'onLabel'=>gT('Yes'),
                        //'offLabel' => gT('No')));
                    ?> -->
                    <?php } else { ?>
                        <?php
                            $this->widget('yiiwheels.widgets.switch.WhSwitch', array(
                            'name' => "remind-switch",
                            'id'=>"remind-switch",
                            'htmlOptions'=>array('class'=>"YesNoSwitch action_toggle_bootstrap_switch"),
                            'value' => $bRemindSwitchValue,
                            'onLabel'=>gT('Yes'),
                            'offLabel' => gT('No')));
                        ?>
                    <?php } ?>
                </div>

                <div class="">
                    <div id="remind-date-container" data-parent="#remind-switch" class="selector__date-container_hidden date-container" <?php if (!$bRemindSwitchValue){ echo "style='display:none;'"; }?> >

                        <div id="remind-date_datetimepicker" class="input-group date">
                        <input class="YesNoDatePicker form-control" id="remind-date" type="text" value="<?php echo isset($remindersent) && $remindersent!='N' ? $remindersent : ''?>" name="remind-date" data-date-format="<?php echo $dateformatdetails['jsdate']; ?> HH:mm">
                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                        </div>
                    </div>
                </div>
                </div>
                <!-- <input class='form-control d-none YesNoDateHidden' type='text' size='20' id='remindersent' name='remindersent' value="<?php if (isset($remindersent) && $remindersent!='N') {echo $remindersent; } else {echo "N"; }?>" /> -->
            </div>
                
            <!-- Reminder count, Uses left -->
            <div class="form-group">
                <!-- Reminder count -->
                <?php if ($token_subaction == "edit"): ?>
                <label class="form-label" for='remindercount'>
                    <?php eT("Reminder count:"); ?>
                </label>
                <input class='form-control' type='number' size='6' id='remindercount' name='remindercount' value="<?php echo $remindercount; ?>" />
                <?php endif; ?>
            </div>

            <!-- Uses left -->
            <div class="form-group">
                <label class="form-label" for='usesleft'>
                <?php eT("Uses left:"); ?>
                </label>
                <input class='form-control' type='number' size='20' id='usesleft' name='usesleft' value="<?php if (isset($usesleft)) {echo $usesleft; } else {echo " 1 "; }?>" />
            </div>
            </div>
        </div>

        <div class="ls-flex-column ls-space padding left-5 right-35 col-md-12">
            <!-- Valid from to  -->
            <div class="form-group">
            <?php
                if (isset($validfrom) && $validfrom != 'N')
                {
                    $validfrom = convertToGlobalSettingFormat($validfrom, true);
                }

                if (isset($validuntil) && $validuntil != 'N')
                {
                    $validuntil = convertToGlobalSettingFormat($validuntil, true);
                }
            ?>

            <!-- From -->
            <label class="form-label" for='validfrom'>
                <?php eT("Valid from"); ?>:</label>
            <div class=" has-feedback">
                <div id="validfrom_datetimepicker" class="input-group date">
                <input class="YesNoDatePicker form-control" id="validfrom" type="text" value="<?php echo isset($validfrom) ? $validfrom : ''?>" name="validfrom" data-date-format="<?php echo $dateformatdetails['jsdate']; ?> HH:mm" data-locale="<?php echo convertLStoDateTimePickerLocale(Yii::app()->session['adminlang']); ?>">
                <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                </div>
            </div>
            </div>

            <div class="form-group">
            <!-- To -->
            <label class="form-label" for='validuntil'>
                <?php eT('Until:'); ?>
            </label>
            <div class="has-feedback">
                <div id="validuntil_datetimepicker" class="input-group date">
                <input class="YesNoDatePicker form-control" id="validuntil" type="text" value="<?php echo isset($validuntil) ? $validuntil : ''?>" name="validuntil" data-date-format="<?php echo $dateformatdetails['jsdate']; ?> HH:mm" data-locale="<?php echo convertLStoDateTimePickerLocale(Yii::app()->session['adminlang']); ?>">
                <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                </div>
            </div>
            </div>
        </div>
    </div>

    <!-- Custom attibutes -->
    <div id="custom" class="tab-pane fade">
        <!-- Attributes -->
        <?php foreach ($attrfieldnames as $attr_name => $attr_description): ?>
            <div class="form-group">
                <label class="form-label" for='<?php echo $attr_name; ?>'>
                    <?php echo $attr_description['description'].($attr_description['mandatory'] == 'Y' ? '*' : '') ?>:
                </label>
                <div class="">
                    <input
                        class='form-control<?= $attr_description['mandatory'] == 'Y' ? ' mandatory-attribute' : '' ?>'
                        type='text'
                        size='55'
                        id='<?php echo $attr_name; ?>'
                        name='<?php echo $attr_name; ?>'
                        value='<?php if (isset($$attr_name)){echo htmlspecialchars($$attr_name, ENT_QUOTES, 'utf-8');}?>' 
                    />
                </div>
            </div>
            <?php endforeach; ?>
        </div>
</div>

  <!-- Buttons -->
  <p>
    <?php
    switch ($token_subaction)
    {
        case "edit":?>
          <input type='submit' class="d-none" value='<?php eT("Update participant entry"); ?>' />
          <input type='hidden' name='subaction' value='updatetoken' />
          <input type='hidden' name='tid' value='<?php echo $tokenid; ?>' />
        <?php break;
        case "addnew": ?>
          <input type='submit' class="d-none" value='<?php eT("Add participant entry"); ?>' />
          <input type='hidden' name='subaction' value='inserttoken' />
        <?php break;
    } ?>
          <input type='hidden' name='sid' value='<?php echo $surveyid; ?>' />
  </p>
  </form>
</div>
</div>


  <div style="display: none;">
    <?php
        Yii::app()->getController()->widget('yiiwheels.widgets.datetimepicker.WhDateTimePicker', array(
        'name' => "no",
        'id'   => "no",
        'value' => '',

        ));
    ?>
  </div>
</div>
<?php
App()->getClientScript()->registerScript('TokenformViewBSSwitcher', "
LS.renderBootstrapSwitch();
", LSYii_ClientScript::POS_POSTSCRIPT);
?>

<?php if ($token_subaction == "addnew"): ?>
    <!-- Empty Token Confirmation Modal -->
    <div class="modal fade" tabindex="-1" role="dialog" id="emptyTokenConfirmationModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><?= gT('Create empty participant') ?></h4>
                </div>
                <div class="modal-body">
                    <?= gT("You are about to create a participant without the basic details. Are you sure you want to proceed?") ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php eT("Cancel");?></button>
                    <button role="button" type="button" class="btn btn-primary" id="save-empty-token">
                        <?php eT("Save");?>
                    </button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
<?php endif; ?>
