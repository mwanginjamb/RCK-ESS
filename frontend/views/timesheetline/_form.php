<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 2/24/2020
 * Time: 12:13 PM
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\TimePicker;

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
                    $form = ActiveForm::begin(); ?>
                <div class="row">


                            <div class="col-md-12">
                                    <?= $form->field($model, 'Date')->textInput(['type' => 'date'])?>
                                    <?= $form->field($model, 'Start_Time')->textInput(['type' => 'time']) ?>
                                    <?= $form->field($model, 'End_Time')->textInput(['type' => 'time']) ?>
                                    <?= $form->field($model, 'Hours_Worked')->textInput(['readonly' => true]) ?>
                                    <?= $form->field($model, 'Activity_Description')->textarea(['rows' => 2,'maxlength' => 250]) ?>
                                    <?= $form->field($model, 'Shared_Task')->dropDownList([
                                        'No' => 'No',
                                        'Yes' => 'Yes'
                                    ],['prompt' => 'Select ...']) ?>
                                    <?= $form->field($model, 'Grant')->dropDownList($grants, ['prompt' => 'Select ...','required' => true]) ?>

                                    <?= $form->field($model, 'Application_No')->hiddenInput(['readonly' => true])->label(false); ?>
                                    <?= $form->field($model, 'Key')->hiddenInput(['readonly' => true])->label(false); ?>
                                    <?= $form->field($model, 'Line_No')->hiddenInput(['readonly'=> true])->label(false) ?>

                            </div>







                </div>












                <!--<div class="row">

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
    

   // $('.timepicker').timepicker();

    //Submit form and get results in json    
        $('form').on('submit', function(e){
            e.preventDefault()
            const data = $(this).serialize();
            const url = $(this).attr('action');
            $.post(url,data).done(function(msg){
                    $('.modal').modal('show')
                    .find('.modal-body')
                    .html(msg.note);
        
                },'json');
        });

        $('#timesheetline-date').blur((e) => {
            globalFieldUpdate('Timesheetline',false,'Date', e);
        });

        $('#timesheetline-start_time').change((e) => {
            globalFieldUpdate('timesheetline',false,'Start_Time', e);
        });

        $('#timesheetline-end_time').change((e) => {
            globalFieldUpdate('Timesheetline',false,'End_Time',e, ['Hours_Worked']);
        }); 

        $('#timesheetline-activity_description').change((e) => {
            globalFieldUpdate('Timesheetline',false,'Activity_Description', e);
        });


        $('#timesheetline-grant').change((e) => {
            globalFieldUpdate('Timesheetline',false,'Grant', e);
        });

        $('#timesheetline-shared_task').change((e) => {
            globalFieldUpdate('Timesheetline',false,'Shared_Task', e);
        });

        
         
         
         
         
         function disableSubmit(){
             document.getElementById('submit').setAttribute("disabled", "true");
        }
        
        function enableSubmit(){
            document.getElementById('submit').removeAttribute("disabled");
        
        }


JS;

$this->registerJs($script);
