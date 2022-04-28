<?php
/** @var AdminController $this */
?>

<?php $form = $this->beginWidget('CActiveForm', array('id'=>'survey-group',)); ?>

<div id='change-surveygroup-modal' >
    <label class="" for='surveygroupid'><?php  eT("Survey group:"); ?></label>
        <div class=" ">
            <select id='surveygroupid' class="form-select custom-data"  name='surveygroupid' >
                <?php
                    $aSurveyGroupList = SurveysGroups::model()->findAll();
                    foreach ($aSurveyGroupList as $oSurveyGroup) { ?>
                        <option value='<?=$oSurveyGroup->gsid?>'>
                            <?php echo $oSurveyGroup->name; ?>
                        </option>
                    <?php } ?>
            </select>
        </div>
        <?php eT('This will update the survey group for all selected surveys.').' '.eT('Continue?'); ?>
</div>
<?php $this->endWidget(); ?>
