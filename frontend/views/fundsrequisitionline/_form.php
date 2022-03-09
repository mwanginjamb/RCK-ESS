<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 2/24/2020
 * Time: 12:13 PM
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$absoluteUrl = \yii\helpers\Url::home(true);
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="card-body">



                    <?php

                    $form = ActiveForm::begin([
                        'id' => $model->formName(),
                        //'enableAjaxValidation' => true,
                    ]);
                    //echo $form->errorSummary($model);
                    ?>
                <div class="row">

                       

                            <div class="col-md-6"> 
                                <?= $form->field($model, 'Employee_No')->dropDownList($employees,['prompt' => 'select ...','required'=> true]) ?> 
                                <?= $form->field($model, 'PD_Transaction_Code')->
                                dropDownList($transactionTypes,['prompt' => 'Select Transaction Type ..','required' => true]) ?>                          
                                <?php $form->field($model, 'Account_No')->dropDownList($accounts,['prompt' => 'select ...','required'=> true]) ?>
                                <?= $form->field($model, 'Amount')->textInput(['type' => 'number','readonly' => true]) ?>
                                <?= $form->field($model, 'Account_Name')->textInput(['readonly' => true,'disabled' => true])->label() ?>
                                <?= $form->field($model, 'Request_No')->hiddenInput(['readonly' => true])->label(false) ?>
                               
                                <?= $form->field($model, 'Description')->textarea(['rows' => 3,'required' => true]) ?>

                                <?= $form->field($model, 'Key')->hiddenInput(['readonly'=> true])->label(false) ?>
                                <?= $form->field($model, 'Daily_Tax_Relief')->textInput(['readonly' => true,'disabled' => true]) ?>
                                <?= $form->field($model, 'Tax_Relief')->textInput(['readonly' => true,'disabled' => true]) ?>
                                <?= $form->field($model, 'Taxable_Amount')->textInput(['readonly' => true,'disabled' => true]) ?>
                               
                            </div>

                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <?= $form->field($model, 'Employee_Name')->textInput(['readonly' => true,'disabled' => true]) ?>
                                        <?= $form->field($model, 'No_of_Days')->textInput(['type' => 'number']) ?>
                                        <?= $form->field($model, 'Daily_Rate')->textInput(['type' => 'number']) ?>
                                        <?= $form->field($model, 'Global_Dimension_1_Code')->dropDownList($subOffices, ['prompt' => 'Select Program...']) ?>
                                        <?= $form->field($model, 'Global_Dimension_2_Code')->dropDownList($programCodes, ['prompt' => 'Select Sub office...']) ?>

                                    </div>
                                    <div class="col-md-6">
                                       
                                       <?= $form->field($model, 'Donor_No')->dropDownList($donors,[
                                        'prompt' => 'Select...'
                                        ]) ?>

                                        <?= $form->field($model, 'Grant_No')->dropDownList($grants,[
                                            'prompt' => 'Select...',
                                            'onchange' => '
                                                    $.post( "' . Yii::$app->urlManager->createUrl('imprestline/objectives?Grant_No=') . '"+$(this).val(), function( data ) {

                                                        $( "select#fundsrequisitionline-objective_code" ).html( data );
                                                    });
                                        '
                                            ]) ?>

                                        <?= $form->field($model, 'Objective_Code')->dropDownList(
                                            $objectiveCode,
                                            [
                                                'prompt' => 'Select...',
                                                'onchange' => '
                                                    $.post( "' . Yii::$app->urlManager->createUrl('imprestline/outputs?Grant_No=') . '"+$("#fundsrequisitionline-grant_no").val(), function( data ) {

                                                        $( "select#fundsrequisitionline-output_code" ).html(data);
                                                    });
                                                '
                                                ]) ?>



                                                <?= $form->field($model, 'Output_Code')->dropDownList(
                                                                                    $outputCode,
                                                                                    [
                                                                                        'prompt' => 'Select...',
                                                                                        'onchange' => '
                                                                                            $.post( "' . Yii::$app->urlManager->createUrl('imprestline/outcome?Grant_No=') . '"+$("#fundsrequisitionline-grant_no").val(), function( data ) {

                                                                                                $( "select#fundsrequisitionline-outcome_code" ).html(data);
                                                                                            });
                                                                                        '
                                                    ]) ?>


                                                <?= $form->field($model, 'Outcome_Code')->dropDownList(
                                                    $outcomeCode,
                                                    [
                                                        'prompt' => 'Select...',
                                                        'onchange' => '
                                                            $.post( "' . Yii::$app->urlManager->createUrl('imprestline/activities?Grant_No=') . '"+$("#fundsrequisitionline-grant_no").val(), function( data ) {

                                                                $( "select#fundsrequisitionline-activity_code" ).html(data);
                                                            });
                                                        '
                                                ]) ?>


                                        <?= $form->field($model, 'Activity_Code')->dropDownList(
                                            $activityCode,[
                                                'prompt' => 'Select...',
                                                'onchange' => '
                                                        $.post( "' . Yii::$app->urlManager->createUrl('imprestline/partners?Grant_No=') . '"+$("#fundsrequisitionline-grant_no").val(), function( data ) {

                                                            $( "select#fundsrequisitionline-partner_code" ).html(data);
                                                        });
                                                '
                                         ]) ?>


                                        <?= $form->field($model, 'Partner_Code')->dropDownList($partnerCode,['prompt' => 'Select...']) ?>

                                    </div>
                                </div>
                               
                            </div>

                            

                    

                </div>


                <div class="row">

                    <div class="form-group">
                        <?php Html::submitButton(($model->isNewRecord)?'Save':'Update', ['class' => 'btn btn-success','id'=>'submit']) ?>
                    </div>


                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="absolute" value="<?= $absoluteUrl ?>">
<?php
$script = <<<JS
 //Submit Rejection form and get results in json    
        $('form').on('submit', function(e){
            e.preventDefault();
            const data = $(this).serialize();
            const url = $(this).attr('action');
            $.post(url,data).done(function(msg){
                    $('.modal').modal('show')
                    .find('.modal-body')
                    .html(msg.note);
        
                },'json');
        });

        // Set Employee No

        $('#fundsrequisitionline-employee_no').change((e) => {
            console.log('Emp no touched...');
            globalFieldUpdate('fundsrequisitionline',false,'Employee_No', e, ['Employee_Name','Daily_Rate']);
        });

        // set No of days

        $('#fundsrequisitionline-no_of_days').change((e) => {
            console.log('No of days touched....');
            globalFieldUpdate('fundsrequisitionline',false,'No_of_Days', e, ['Amount','Daily_Rate','Daily_Tax_Relief','Tax_Relief','Taxable_Amount']);
        });


        // set Daily Rate
        $('#fundsrequisitionline-daily_rate').change((e) => {
            globalFieldUpdate('fundsrequisitionline',false,'Daily_Rate', e, ['Amount','Daily_Rate','Daily_Tax_Relief','Tax_Relief','Taxable_Amount']);
        });

       // $('#fundsrequisitionline-account_no').select2();

        $('#fundsrequisitionline-account_no').change((e) => {
            globalFieldUpdate('fundsrequisitionline',false,'Account_No', e);
        });


        // Se Transaction Code

        $('#fundsrequisitionline-pd_transaction_code').change((e) => {
            globalFieldUpdate('fundsrequisitionline',false,'PD_Transaction_Code', e,['Account_Name']);
        });

        // Set Description

        $('#fundsrequisitionline-description').change((e) => {
            globalFieldUpdate('fundsrequisitionline',false,'Description', e);
        });

        // set Dim 1

        
        $('#fundsrequisitionline-global_dimension_1_code').on('change',(e) => {
             globalFieldUpdate("fundsrequisitionline",false,"global_dimension_1_code", e);
        });

        // set Dim 2

        $('#fundsrequisitionline-global_dimension_2_code').on('change',(e) => {
             globalFieldUpdate("fundsrequisitionline",false,"global_dimension_2_code", e);
        });

       
        // Set Task No

        $('#fundsrequisitionline-job_task_no').on('blur',(e) => {
             globalFieldUpdate("fundsrequisitionline",false,"Job_Task_No", e);
        });

         // Set Job No
  
        $('#fundsrequisitionline-job_no').change((e) => {
            globalFieldUpdate('fundsrequisitionline',false,'Job_No', e);
        });

        // Set Planning Line

        $('#fundsrequisitionline-job_planning_line_no').change((e) => {
            globalFieldUpdate('fundsrequisitionline',false,'job_planning_line_no', e);
        });

        /**Grants Fields */

        $('#fundsrequisitionline-donor_no').change((e) => {
        globalFieldUpdate('fundsrequisitionline',false,'Donor_No', e,['Donor_No']);
    });


    $('#fundsrequisitionline-objective_code').change((e) => {
        globalFieldUpdate('fundsrequisitionline',false,'Objective_Code', e);
    });

    $('#fundsrequisitionline-outcome_code').change((e) => {
        globalFieldUpdate('fundsrequisitionline',false,'Outcome_Code', e);
    });

    $('#fundsrequisitionline-activity_code').change((e) => {
        globalFieldUpdate('fundsrequisitionline',false,'Activity_Code', e);
    });

    $('#fundsrequisitionline-output_code').change((e) => {
        globalFieldUpdate('fundsrequisitionline',false,'Output_Code', e);
    });

    $('#fundsrequisitionline-partner_code').change((e) => {
        globalFieldUpdate('fundsrequisitionline',false,'Partner_Code', e);
    });

    $('#fundsrequisitionline-grant_no').change((e) => {
        globalFieldUpdate('fundsrequisitionline',false,'Grant_No', e);
    });


        
         
         
JS;

$this->registerJs($script);
