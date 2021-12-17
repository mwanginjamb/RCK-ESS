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
                                <?= $form->field($model, 'Account_No')->dropDownList($accounts,['prompt' => 'select ...','required'=> true]) ?>
                                <?= $form->field($model, 'Account_Name')->textInput(['readonly' => true,'disabled' => true])->label() ?>
                                <?= $form->field($model, 'Request_No')->hiddenInput(['readonly' => true])->label(false) ?>
                                <?= $form->field($model, 'PD_Transaction_Code')->
                                dropDownList($transactionTypes,['prompt' => 'Select Transaction Type ..','required' => true]) ?>
                                <?= $form->field($model, 'Description')->textarea(['rows' => 3,'required' => true]) ?>

                                <?= $form->field($model, 'Key')->textInput(['readonly'=> true]) ?>
                               
                            </div>

                            <div class="col-md-6">
                                <?= $form->field($model, 'Employee_Name')->textInput(['readonly' => true,'disabled' => true])->label() ?>
                                <?= $form->field($model, 'No_of_Days')->textInput(['type' => 'number']) ?>
                                <?= $form->field($model, 'Global_Dimension_1_Code')->dropDownList($subOffices, ['prompt' => 'Select Program...']) ?>
                                <?= $form->field($model, 'Global_Dimension_2_Code')->dropDownList($programCodes, ['prompt' => 'Select Sub office...']) ?>
                                <?= $form->field($model, 'Job_No')->dropDownList($jobs, ['prompt' => 'Select...']) ?>
                                <?= $form->field($model, 'Job_Task_No')->dropDownList($jobTasks, [
                                    'prompt' => 'Select ...',
                                    'onchange' => '$.post("../fundsrequisitionline/planning-dd?task_no="+$(this).val()+"&job_no="+$("#fundsrequisitionline-job_no").val(), (data) => {
                        
                                        $("select#fundsrequisitionline-job_planning_line_no").html( data );
                                        
                                    })'
                                    ]) ?>
                                <?= $form->field($model, 'Job_Planning_Line_No')->dropDownList([], ['prompt' => 'Select Item...']) ?>
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
            globalFieldUpdate('fundsrequisitionline',false,'Employee_No', e, ['Employee_Name']);
        });

        // set No of days

        $('#fundsrequisitionline-no_of_days').change((e) => {
            console.log('No of days touched....');
            globalFieldUpdate('fundsrequisitionline',false,'No_of_Days', e);
        });

       // $('#fundsrequisitionline-account_no').select2();

        $('#fundsrequisitionline-account_no').change((e) => {
            globalFieldUpdate('fundsrequisitionline',false,'Account_No', e);
        });


        // Se Transaction Code

        $('#fundsrequisitionline-pd_transaction_code').change((e) => {
            globalFieldUpdate('fundsrequisitionline',false,'PD_Transaction_Code', e);
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

        
         
         
JS;

$this->registerJs($script);
