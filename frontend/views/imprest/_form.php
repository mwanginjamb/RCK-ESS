<?php

/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 2/24/2020
 * Time: 12:13 PM
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;


$this->title = 'Imprest - ' . $model->No;


$absoluteUrl = \yii\helpers\Url::home(true);
//Yii::$app->recruitment->printrr($document);

if (Yii::$app->session->hasFlash('success')) {
    print ' <div class="alert alert-success alert-dismissable">
                             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5><i class="icon fas fa-check"></i> Success!</h5>
 ';
    echo Yii::$app->session->getFlash('success');
    print '</div>';
} else if (Yii::$app->session->hasFlash('error')) {
    print ' <div class="alert alert-danger alert-dismissable">
                                 <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5><i class="icon fas fa-check"></i> Error!</h5>
                                ';
    echo Yii::$app->session->getFlash('error');
    print '</div>';
}
?>

<div class="row">
    <div class="col-md-4">

        <?= ($model->Status == 'New') ? Html::a('<i class="fas fa-paper-plane"></i> Send Approval Req', ['send-for-approval', 'employeeNo' => Yii::$app->user->identity->{'Employee_No'}], [
            'class' => 'btn btn-app submitforapproval',
            'data' => [
                'confirm' => 'Are you sure you want to send imprest request for approval?',
                'params' => [
                    'No' => $model->No,
                    'employeeNo' => Yii::$app->user->identity->Employee[0]->No,
                ],
                'method' => 'get',
            ],
            'title' => 'Submit Imprest Approval'

        ]) : '' ?>


        <?= ($model->Status == 'Pending_Approval') ? Html::a('<i class="fas fa-times"></i> Cancel Approval Req.', ['cancel-request'], [
            'class' => 'btn btn-app submitforapproval',
            'data' => [
                'confirm' => 'Are you sure you want to cancel imprest approval request?',
                'params' => [
                    'No' => $model->No,
                ],
                'method' => 'get',
            ],
            'title' => 'Cancel Imprest Approval Request'

        ]) : '' ?>


        <?= Html::a('<i class="fas fa-file-pdf"></i> Print Imprest', ['print-imprest'], [
            'class' => 'btn btn-app ',
            'data' => [
                'confirm' => 'Print Imprest?',
                'params' => [
                    'No' => $model->No,
                    'Key' => $model->Key
                ],
                'method' => 'get',
            ],
            'title' => 'Print Imprest.'

        ]) ?>
    </div>
</div>


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
                    <div class="row col-md-12">



                        <div class="col-md-6">

                            <?= $form->field($model, 'No')->textInput(['readonly' => true]) ?>
                            <?= $form->field($model, 'Key')->hiddenInput()->label(false) ?>
                            <?= $form->field($model, 'Request_For')->dropDownList(['Self' => 'Self', 'Other' => 'Other'], ['prompt' => 'Select ...']) ?>


                            <?= $form->field($model, 'Employee_No')->dropDownList($employees, ['prompt' => 'Select Employee']) ?>

                            <?= $form->field($model, 'Employee_Name')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Purpose')->textInput() ?>
                            <?= '<p><span>Employee Balance</span> ' . Html::a($model->Employee_Balance, '#');
                            '</p>' ?>
                            <?= '<p><span>Imprest Amount</span> ' . Html::a($model->Imprest_Amount, '#');
                            '</p>' ?>


                        </div>

                        <div class="col-md-6">
                            <?= $form->field($model, 'attachment')->fileInput(['accept' => 'application/pdf']) ?>


                            <?= $form->field($model, 'attachment_multiple[]')->fileInput(['accept' => 'application/pdf', 'id' => 'select_multiple', 'multiple' => true]) ?>

                            <div id="upload-note"></div>
                            <div class="progress" id="progress_bar" style="display:none">
                                <div class="progress-bar" id="progress_bar_process" role="progressbar" style="width:0%"></div>
                            </div>

                            <?= '<p><span> Amount LCY</span> ' . Html::a($model->Amount_LCY, '#');
                            '</p>' ?>
                            <?= $form->field($model, 'Status')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Global_Dimension_1_Code')->dropDownList($programs, ['prompt' => 'Select ..']) ?>
                            <?= $form->field($model, 'Global_Dimension_2_Code')->dropDownList($departments, ['prompt' => 'select ...']) ?>
                            <?= $form->field($model, 'Expected_Date_of_Surrender')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Imprest_Type')->dropDownList(['Local' => 'Local', 'International' => 'International'], ['prompt' => 'Select ...']) ?>




                            <?= $form->field($model, 'Currency_Code')->dropDownList($currencies, ['prompt' => 'Select ...', 'required' => true]) ?>
                            <?php $form->field($model, 'Exchange_Rate')->textInput(['type' => 'number', 'required' => true]) ?>



                            <!--                            <p class="parent"><span>+</span>-->



                            </p>



                        </div>



                    </div>




                </div>












                <!-- <div class="row">

                    <div class="form-group">
                        <?php Html::submitButton(($model->isNewRecord) ? 'Save' : 'Update', ['class' => 'btn btn-success']) ?>
                    </div>


                </div>-->
                <?php ActiveForm::end(); ?>
            </div>
        </div>


        <div class="card">
            <div class="card-header">

                <?= ($model->Status == 'New') ? Html::a(
                    '<i class="fa fa-plus-square"></i> New Imprest Line',
                    ['add-line'],
                    [
                        'class' => 'add btn btn-outline-info',
                        'data-no' => $model->No,
                        'data-service' => 'ImprestRequestLine'
                    ]
                ) : '' ?>


                <div class="card-tools my-2 px-3">
                    <span class="text text-info border border-info p-2 rounded">To Update line values, double click on cells whose column headers are colored blue.</span>
                </div>
            </div>

            <div class="card-body">

                <?php if (property_exists($document->Imprest_Request_Line, 'Imprest_Request_Line')) { //show Lines 
                ?>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <td><b>Line No</b></td>
                                    <td class="text-info"><b>Transaction_Type</b></td>

                                    <td><b>Account_Name</b></td>
                                    <td class="text-info"><b>Description</b></td>
                                    <td class="text-info"><b>Amount</b></td>
                                    <td><b>Amount_LCY</b></td>
                                    <td class="text-info"><b>Program</b></td>
                                    <td class="text-info"><b>Department</b></td>

                                    <td class="text-info text-center text-bold border border-info">Donor_Code</td>
                                    <td class="text-center text-bold">Donor Name</td>
                                    <td class="text-info text-center text-bold border border-info">Grant_Name</td>
                                    <td class="text-info text-center text-bold border border-info"><b>Objective_Code</b></td>
                                    <td class="text-info text-center text-bold border border-info"><b>Output_Code</b></td>
                                    <td class="text-info text-center text-bold border border-info"><b>Outcome_Code</b></td>
                                    <td class="text-info text-center text-bold border border-info"><b>Activity_Code</b></td>
                                    <td class="text-info text-center text-bold border border-info"><b>Partner_Code</b></td>

                                    <td><b>Actions</b></td>



                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // print '<pre>'; print_r($model->getObjectives()); exit;

                                foreach ($document->Imprest_Request_Line->Imprest_Request_Line   as $obj) :
                                    $updateLink = Html::a('<i class="fa fa-edit"></i>', ['imprestline/update', 'Key' => $obj->Key], ['class' => 'update-objective btn btn-outline-info btn-xs']);
                                    $deleteLink = Html::a('<i class="fa fa-trash"></i>', ['imprestline/delete', 'Key' => $obj->Key], ['class' => 'delete btn btn-outline-danger btn-xs']);
                                ?>
                                    <tr>

                                        <td><?= !empty($obj->Line_No) ? $obj->Line_No : '' ?></td>
                                        <td data-key="<?= $obj->Key ?>" data-name="Transaction_Type" data-service="ImprestRequestLine" ondblclick="addDropDown(this,'transactiontypes')" data-validate="Account_Name"><?= !empty($obj->Transaction_Type) ? $obj->Transaction_Type : '' ?></td>
                                        <td class="Account_Name"><?= !empty($obj->Account_Name) ? $obj->Account_Name : '' ?></td>
                                        <td data-key="<?= $obj->Key ?>" data-name="Description" data-service="ImprestRequestLine" ondblclick="addTextarea(this)"><?= !empty($obj->Description) ? $obj->Description : '' ?></td>
                                        <td data-key="<?= $obj->Key ?>" data-name="Amount" data-service="ImprestRequestLine" ondblclick="addInput(this,'number')" data-validate="Amount_LCY"><?= !empty($obj->Amount) ? $obj->Amount : '' ?></td>
                                        <td class="Amount_LCY"><?= !empty($obj->Amount_LCY) ? $obj->Amount_LCY : '' ?></td>
                                        <td data-key="<?= $obj->Key ?>" data-name="Global_Dimension_1_Code" data-service="ImprestRequestLine" ondblclick="addDropDown(this,'dimension1')"><?= !empty($obj->Global_Dimension_1_Code) ? $obj->Global_Dimension_1_Code : '' ?></td>
                                        <td data-key="<?= $obj->Key ?>" data-name="Global_Dimension_2_Code" data-service="ImprestRequestLine" ondblclick="addDropDown(this,'dimension2')"><?= !empty($obj->Global_Dimension_2_Code) ? $obj->Global_Dimension_2_Code : '' ?></td>

                                        <td data-key="<?= $obj->Key ?>" data-name="Donor_No" data-service="ImprestRequestLine" ondblclick="addDropDown(this,'donors',{'Grant_No': 'grant'})" data-validate="Donor_Name" class="text-center"><?= !empty($obj->Donor_No) ? $obj->Donor_No : '' ?></td>
                                        <td class="text-center Donor_Name"><?= !empty($obj->Donor_Name) ? $obj->Donor_Name : '' ?></td>
                                        <td data-key="<?= $obj->Key ?>" data-name="Grant_No" data-service="ImprestRequestLine" ondblclick="addDropDown(this,'grants')" class="text-center grant"><?= !empty($obj->Grant_No) ? $obj->Grant_No : '' ?></td>
                                        <td data-key="<?= $obj->Key ?>" data-name="Objective_Code" data-service="ImprestRequestLine" ondblclick="addDropDown(this,'objectives',{'Grant_No': 'grant'})"><?= !empty($obj->Objective_Code) ? $obj->Objective_Code : '' ?></td>
                                        <td data-key="<?= $obj->Key ?>" data-name="Output_Code" data-service="ImprestRequestLine" ondblclick="addDropDown(this,'outputs',{'Grant_No': 'grant'})"><?= !empty($obj->Output_Code) ? $obj->Output_Code : '' ?></td>
                                        <td data-key="<?= $obj->Key ?>" data-name="Outcome_Code" data-service="ImprestRequestLine" ondblclick="addDropDown(this,'outcome',{'Grant_No': 'grant'})"><?= !empty($obj->Outcome_Code) ? $obj->Outcome_Code : '' ?></td>
                                        <td data-key="<?= $obj->Key ?>" data-name="Activity_Code" data-service="ImprestRequestLine" ondblclick="addDropDown(this,'activities',{'Grant_No': 'grant'})"><?= !empty($obj->Activity_Code) ? $obj->Activity_Code : '' ?></td>
                                        <td data-key="<?= $obj->Key ?>" data-name="Partner_Code" data-service="ImprestRequestLine" ondblclick="addDropDown(this,'partners',{'Grant_No': 'grant'})"><?= !empty($obj->Partner_Code) ? $obj->Partner_Code : '' ?></td>
                                        <td><?= $deleteLink ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                    </div>
                <?php } ?>
            </div>
        </div>






    </div>
</div>



<!--My Bs Modal template  --->

<div class="modal fade bs-example-modal-lg bs-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel" style="position: absolute">Imprest Management</h4>
            </div>
            <div class="modal-body">
                <div class="spinner-border mr-auto" role="status">
                    <span class="sr-only">Loading</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <!--<button type="button" class="btn btn-primary">Save changes</button>-->
            </div>

        </div>
    </div>
</div>
<input type="hidden" name="absolute" id="absolute" value="<?= $absoluteUrl ?>">
<?php
$script = <<<JS
  // Trigger Creation of a line
  $('.add').on('click',function(e){
            e.preventDefault();
            let url = $(this).attr('href');
           
            let data = $(this).data();
            const payload = {
                'Document_No': data.no,
                'Service': data.service
            };
            //console.log(payload);
            //return;
            $('a.add').text('Inserting...');
            $('a.add').attr('disabled', true);
            $.get(url, payload).done((msg) => {
                console.log(msg);
                setTimeout(() => {
                    location.reload(true);
                },1500);
            });
        });
    
     
    
  
    $('.del').on('click',function(e){
            e.preventDefault();
            if(confirm('Are you sure about deleting this record?'))
            {
                let data = $(this).data();
                let url = $(this).attr('href');
                let Key = data.key;
                let Service = data.service;
                const payload = {
                    'Key': Key,
                    'Service': Service
                };
                $(this).text('Deleting...');
                $(this).attr('disabled', true);
                $.get(url, payload).done((msg) => {
                    console.log(msg);
                    setTimeout(() => {
                        location.reload(true);
                    },3000);
                });
            }
            
    });


    $('#imprestcard-employee_no').select2();

    // Initially Hide Employee DD

    $('.field-imprestcard-employee_no').hide();

    // Check Req for state
    let ReqFor = $('#imprestcard-request_for').val();
    if(ReqFor == 'Other'){
        $('.field-imprestcard-employee_no').show();
    }else {
        $('.field-imprestcard-employee_no').hide();
    }

    // Display Employee No Field based on a change event on Req For

    $('#imprestcard-request_for').change((e) => {
        let selected = e.target.value;
        if(selected == 'Self'){
            $('.field-imprestcard-employee_no').hide();
        }else if(selected == 'Other') {
            $('.field-imprestcard-employee_no').show();
        }
        console.log(selected);
    });

    // Set Req_For

    $('#imprestcard-request_for').blur((e) => {
        globalFieldUpdate('Imprestcard','imprest','Request_For', e,[],'ImprestRequestCardPortal');
    });
   
    // Set other Employee
  
     $('#imprestcard-employee_no').change((e) => {
        globalFieldUpdate('Imprestcard','imprest','Employee_No', e,[],'ImprestRequestCardPortal');
    });


    // Set Currency

    $('#imprestcard-currency_code').change((e) => {
        globalFieldUpdate('Imprestcard','imprest','Currency_Code', e,[],'ImprestRequestCardPortal');
    });


     
     /*Set Program  */
    

     $('#imprestcard-global_dimension_1_code').change((e) => {
        globalFieldUpdate('Imprestcard','imprest','Global_Dimension_1_Code', e,[],'ImprestRequestCardPortal');
    });
     
     
     /* set department */
     
     $('#imprestcard-global_dimension_2_code').change((e) => {
        globalFieldUpdate('Imprestcard','imprest','Global_Dimension_2_Code', e,[],'ImprestRequestCardPortal');
    });

    /**Update Purpose */

    $('#imprestcard-purpose').change((e) => {
        globalFieldUpdate('Imprestcard','imprest','Purpose', e,[],'ImprestRequestCardPortal');
    }); 
     
     
     /*Set Imprest Type*/
     

     $('#imprestcard-imprest_type').change((e) => {
        globalFieldUpdate('Imprestcard','imprest','Imprest_Type', e,[],'ImprestRequestCardPortal');
    });

    //Upload Damn File

    $('#imprestcard-attachment').change(function(e){
          globalUpload('LeaveAttachments','Imprestcard','attachment','ImprestRequestCard');
    });

    $('#select_multiple').change(function(e){
          globalUploadMultiple('LeaveAttachments','Imprestcard','imprest','ImprestRequestCard');
    });
    
      /* Add Line */
      $('.add-line, .update-objective').on('click', function(e){
             e.preventDefault();
            var url = $(this).attr('href');
            console.log(url);
            $('.modal').modal('show')
                            .find('.modal-body')
                            .load(url); 

        });

        $('.delete, .delete-objective').on('click',function(e){
         e.preventDefault();
           var secondThought = confirm("Are you sure you want to delete this record ?");
           if(!secondThought){//if user says no, kill code execution
                return;
           }
           
         var url = $(this).attr('href');
         $.get(url).done(function(msg){
             $('.modal').modal('show')
                    .find('.modal-body')
                    .html(msg.note);
         },'json');
     });
     
     
     /*Handle modal dismissal event  */
    $('.modal').on('hidden.bs.modal',function(){
        var reld = location.reload(true);
        setTimeout(reld,1000);
    }); 
     
     
     
JS;

$this->registerJs($script);
