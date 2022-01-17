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
                    $form = ActiveForm::begin(); ?>
                <div class="row">


                            <div class="col-md-6">
                                    <?= $form->field($model, 'Request_Type')->dropDownList($requestTypes, ['prompt' => 'Select ...']) ?>
                                    <?= $form->field($model, 'Vendor_No')->dropDownList($vendors, ['prompt' => 'Select ...']) ?>
                                    
                                    <?= $form->field($model, 'Departure_Location')->textInput(['maxlength' => 200]) ?>
                                    <?= $form->field($model, 'Travel_Date')->textInput(['type' => 'date']) ?>
                                    <?= $form->field($model, 'Departure_Time')->textInput(['type' => 'time']) ?>
                                    <?= $form->field($model, 'Destination_Location')->textInput(['maxlength' => 200]) ?>
                                    <?= $form->field($model, 'Reason_For_Request')->textarea(['rows' => 2,'maxlength' => 250]) ?>
                                    <?= $form->field($model, 'No_of_Person_s_Travelling')->textInput(['type' => 'number']) ?>
                                   
                                    
                            </div>
                            <div class="col-md-6">
                            <?= $form->field($model, 'Job_No')->dropDownList($jobs, ['prompt' => 'Select Sub office...']) ?>
                                <?= $form->field($model, 'Job_Task_No')->dropDownList($jobTasks, [
                                    'prompt' => 'Select ...',
                                    'onchange' => '$.post("../taxieline/planning-dd?task_no="+$(this).val()+"&job_no="+$("#taxieline-job_no").val(), (data) => {
                                        $("select#taxieline-job_planning_line_no").html( data );
                                    })'
                                    ]) ?>
                                <?= $form->field($model, 'Job_Planning_Line_No')->dropDownList([], ['prompt' => 'Select ...']) ?>

                                <?= $form->field($model, 'G_L_Account_No')->dropDownList($glAccounts, ['prompt' => 'Select ...']) ?>
                                <?= $form->field($model, 'Key')->textInput(['readonly'=> true]) ?>
                                <?php $form->field($model, 'Document_No')->textInput(['readonly' => true]) ?>
                            </div>



                </div>












                <div class="row">

                    <div class="form-group">
                        <?= Html::submitButton(($model->isNewRecord)?'Save':'Update', ['class' => 'btn btn-success','id'=>'submit']) ?>
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



// Make some fields searchable
$('#taxieline-job_no').select2();
$('#taxieline-job_task_no').select2();
$('#taxieline-g_l_account_no').select2();
$('#taxieline-vendor_no').select2();


 //Submit Rejection form and get results in json    
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

         $('#leaveplanline-start_date').on('change', function(e){
            e.preventDefault();
                  
            const Line_No = $('#leaveplanline-line_no').val();
            
            
            const url = $('input[name="absolute"]').val()+'leaveplanline/setstartdate';
            $.post(url,{'Line_No': Line_No,'Start_Date': $(this).val()}).done(function(msg){
                   //populate empty form fields with new data
                    console.log(typeof msg);
                    console.table(msg);
                    if((typeof msg) === 'string') { // A string is an error
                        const parent = document.querySelector('.field-leaveplanline-start_date');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = msg;
                        disableSubmit();
                    }else{ // An object represents correct details
                        const parent = document.querySelector('.field-leaveplanline-start_date');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = ''; 
                        enableSubmit();
                    }
                    $('#leaveplanline-days_planned').val(msg.Days_Planned);
                    $('#leaveplanline-holidays').val(msg.Holidays);
                    $('#leaveplanline-weekend_days').val(msg.Weekend_Days);
                    $('#leaveplanline-total_no_of_days').val(msg.Total_No_Of_Days);
                    
                },'json');
        });
         
         $('#leaveplanline-end_date').on('change', function(e){
            e.preventDefault();
                  
            const Line_No = $('#leaveplanline-line_no').val();
            
            
            const url = $('input[name="absolute"]').val()+'leaveplanline/setenddate';
            $.post(url,{'Line_No': Line_No,'End_Date': $(this).val()}).done(function(msg){
                   //populate empty form fields with new data
                    console.log(typeof msg);
                    console.table(msg);
                    if((typeof msg) === 'string'){ // A string is an error
                        const parent = document.querySelector('.field-leaveplanline-end_date');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = msg;
                        disableSubmit();
                    }else{ // An object represents correct details
                        const parent = document.querySelector('.field-leaveplanline-end_date');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = ''; 
                        enableSubmit();
                    }
                    $('#leaveplanline-days_planned').val(msg.Days_Planned);
                    // $('#leaveplanline-start_date').val(msg.Start_Date);
                    $('#leaveplanline-holidays').val(msg.Holidays);
                    $('#leaveplanline-weekend_days').val(msg.Weekend_Days);
                    $('#leaveplanline-total_no_of_days').val(msg.Total_No_Of_Days);
                    
                },'json');
        });
         
         function disableSubmit(){
             document.getElementById('submit').setAttribute("disabled", "true");
        }
        
        function enableSubmit(){
            document.getElementById('submit').removeAttribute("disabled");
        
        }
JS;

$this->registerJs($script);
