<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 2/24/2020
 * Time: 12:13 PM
 */
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;

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
                    $form = ActiveForm::begin(['id' => 'imprestLine']); ?>
                <div class="row">
                        <div class=" row col-md-12">
                            <div class="col-md-6">
                                <?= $form->field($model, 'Line_No')->hiddenInput(['readonly' => true])->label(false) ?>
                                
                                <?= $form->field($model, 'Request_No')->hiddenInput(['readonly' => true,'disabled'=>true])->label(false) ?>
                                <?= $form->field($model, 'Key')->hiddenInput(['readonly'=> true])->label(false) ?>
                                <?= $form->field($model, 'Transaction_Type')->
                                dropDownList($transactionTypes,['prompt' => 'Select Transaction Type ..',
                                    'required'=> true, 'required' => true]) ?>

                                    <?= $form->field($model, 'Description')->textarea(['rows' => 1,'required' => true]) ?>
                                    <?= $form->field($model, 'Amount')->textInput(['type' => 'number','required' => true]) ?>


                                    <?= $form->field($model, 'Objective_Code')->dropDownList(
                                    $objectiveCode,
                                    [
                                        'prompt' => 'Select...',
                                        'onchange' => '
                                            $.post( "' . Yii::$app->urlManager->createUrl('imprestline/outputs?Grant_No=') . '"+$("#imprestline-grant_no").val(), function( data ) {

                                                $( "select#imprestline-output_code" ).html(data);
                                            });
                                        '
                                        ]) ?>

                                <?= $form->field($model, 'Outcome_Code')->dropDownList(
                                    $outcomeCode,
                                    [
                                        'prompt' => 'Select...',
                                        'onchange' => '
                                            $.post( "' . Yii::$app->urlManager->createUrl('imprestline/activities?Grant_No=') . '"+$("#imprestline-grant_no").val(), function( data ) {

                                                $( "select#imprestline-activity_code" ).html(data);
                                            });
                                        '
                                        ]) ?>



                                    <?= $form->field($model, 'Activity_Code')->dropDownList(
                                    $activityCode,[
                                        'prompt' => 'Select...',
                                        'onchange' => '
                                                $.post( "' . Yii::$app->urlManager->createUrl('imprestline/partners?Grant_No=') . '"+$("#imprestline-grant_no").val(), function( data ) {

                                                    $( "select#imprestline-partner_code" ).html(data);
                                                });
                                        '
                                        ]) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'Global_Dimension_1_Code')->dropDownList($subOffices, ['prompt' => 'Select Program...']) ?>
                                <?= $form->field($model, 'Global_Dimension_2_Code')->dropDownList($programCodes, ['prompt' => 'Select Sub office...']) ?>
                                <?= $form->field($model, 'Donor_No')->dropDownList($donors,[
                                    'prompt' => 'Select...'
                                    ]) ?>
                                <?= $form->field($model, 'Grant_No')->dropDownList($grants,[
                                    'prompt' => 'Select...',
                                    'onchange' => '
                                            $.post( "' . Yii::$app->urlManager->createUrl('imprestline/objectives?Grant_No=') . '"+$(this).val(), function( data ) {

                                                $( "select#imprestline-objective_code" ).html( data );
                                            });
                                   '
                                    ]) ?>
                                
                                <?= $form->field($model, 'Output_Code')->dropDownList(
                                    $outputCode,
                                    [
                                        'prompt' => 'Select...',
                                        'onchange' => '
                                            $.post( "' . Yii::$app->urlManager->createUrl('imprestline/outcome?Grant_No=') . '"+$("#imprestline-grant_no").val(), function( data ) {

                                                $( "select#imprestline-outcome_code" ).html(data);
                                            });
                                        '
                                        ]) ?>
                               
                                
                               
                                <?= $form->field($model, 'Partner_Code')->dropDownList($partnerCode,['prompt' => 'Select...']) ?>
                                

                               
                            </div>

                        </div>

                </div>

               <!-- <div class="row">

                    <div class="form-group">
                        <?php Html::submitButton(($model->isNewRecord)?'Save':'Update', ['class' => 'btn btn-success','id'=>'submit']) ?>
                    </div>


                </div>-->
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

        
     /*Set Transaction Type*/
     
     $('#imprestline-transaction_type').change((e) => {
        updateField('Imprestline','Transaction_Type', e);
    });

    /*Set Description */
     
    $('#imprestline-description').change((e) => {
        updateField('Imprestline','Description', e);
    });

    /*Set Amount */
     
    $('#imprestline-amount').change((e) => {
        updateField('Imprestline','Amount', e);
    });

    // Update Task No


    $('#imprestline-job_task_no').on('blur',(e) => {
        globalFieldUpdate("Imprestline",false,"Job_Task_No", e);
    });

    
    // Set Job No
  
    $('#imprestline-job_no').change((e) => {
        globalFieldUpdate('Imprestline',false,'Job_No', e);
    });


    // set Job Planning Line No

    $('#imprestline-job_planning_line_no').change((e) => {
        globalFieldUpdate('Imprestline',false,'Job_Planning_Line_No', e);
    });

    // Set Sub Office

    $('#imprestline-global_dimension_1_code').change((e) => {
        globalFieldUpdate('Imprestline',false,'Global_Dimension_1_Code', e);
    });

    // set Program

    $('#imprestline-global_dimension_2_code').change((e) => {
        globalFieldUpdate('Imprestline',false,'Global_Dimension_2_Code', e);
    });

    $('#imprestline-donor_no').change((e) => {
        globalFieldUpdate('Imprestline',false,'Donor_No', e,['Donor_No']);
    });


    $('#imprestline-objective_code').change((e) => {
        globalFieldUpdate('Imprestline',false,'Objective_Code', e);
    });

    $('#imprestline-outcome_code').change((e) => {
        globalFieldUpdate('Imprestline',false,'Outcome_Code', e);
    });

    $('#imprestline-activity_code').change((e) => {
        globalFieldUpdate('Imprestline',false,'Activity_Code', e);
    });

    $('#imprestline-output_code').change((e) => {
        globalFieldUpdate('Imprestline',false,'Output_Code', e);
    });

    $('#imprestline-partner_code').change((e) => {
        globalFieldUpdate('Imprestline',false,'Partner_Code', e);
    });

    $('#imprestline-grant_no').change((e) => {
        globalFieldUpdate('Imprestline',false,'Grant_No', e);
    });



     function updateField(entity,fieldName, ev) {
                const model = entity.toLowerCase();
                const field = fieldName.toLowerCase();
                const formField = '.field-'+model+'-'+fieldName.toLowerCase();
                const keyField ='#'+model+'-key'; 
                const targetField = '#'+model+'-'.field;
                const tget = '#'+model+'-'+field;


                const fieldValue = ev.target.value;
                const Key = $(keyField).val();
                //alert(Key);
                if(Key.length){
                    const url = $('input[name=absolute]').val()+model+'/setfield?field='+fieldName;
                    $.post(url,{ fieldValue:fieldValue,'Key': Key}).done(function(msg){
                        
                            // Populate relevant Fields
                                                       
                            $(keyField).val(msg.Key);
                            $(targetField).val(msg[fieldName]);

                           
                            if((typeof msg) === 'string') { // A string is an error
                                console.log(formField);
                                const parent = document.querySelector(formField);
                                const helpbBlock = parent.children[2];
                                helpbBlock.innerText = msg;
                                
                            }else{ // An object represents correct details

                                const parent = document.querySelector(formField);
                                const helpbBlock = parent.children[2];
                                helpbBlock.innerText = '';
                                
                            }   
                        },'json');
                }
            
     }         
         function disableSubmit(){
             document.getElementById('submit').setAttribute("disabled", "true");
        }
        
        function enableSubmit(){
            document.getElementById('submit').removeAttribute("disabled");
        
        }
JS;

$this->registerJs($script);
