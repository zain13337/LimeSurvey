<?php
    $sResponsesId = '';
    $aResponsesId = json_decode(Yii::app()->session['responsesid']);
    foreach($aResponsesId as $aResponseId){
        $sResponsesId .= $aResponseId.', ';
    }
?>

<div class="card" id="panel-2" <?php if ($SingleResponse) { echo 'style="display:none"';} ?> >
  <div class="card-header bg-primary">
    <?php eT("Selection");?>
  </div>
  <div class="card-body">
    <div class="form-group">
        <!-- From -->
        <label for='export_ids' class="col-sm-2 form-label">
          <?php eT("Selected answers"); ?>
        </label>

        <div class="col-sm-6">
          <input type="text" readonly value="<?php echo  $sResponsesId; ?>" class="form-control" name="responses_id" id="responses_id" />
        </div>
        <div class="col-sm-2">
          <a class="btn btn-outline-secondary" href="<?php echo Yii::app()->getController()->createUrl("responses/setSession/", ['unset' => 'true', 'surveyId' => $surveyid]); ?>" role="button">
            <?php eT("Reset");?>
          </a>
        </div>
        <input type="hidden" value='<?php echo json_encode($aResponsesId); ?>' name="export_ids" id="export_ids" />
    </div>
  </div>
</div>
