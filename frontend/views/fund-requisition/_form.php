<?php

/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 2/24/2020
 * Time: 12:13 PM
 */

use yii\bootstrap4\Html as Bootstrap4Html;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$absoluteUrl = \yii\helpers\Url::home(true);
?>

<?php if (property_exists($document->Allowance_Request_Line, 'Allowance_Request_Line')  && isset($model->Purpose)) : ?>

    <div class="row">
        <div class="col-md-4">

            <?= ($model->Status == 'New') ? Html::a('<i class="fas fa-paper-plane"></i> Send Approval Req', ['send-for-approval', 'employeeNo' => Yii::$app->user->identity->{'Employee No_'}], [
                'class' => 'btn btn-app submitforapproval',
                'data' => [
                    'confirm' => 'Are you sure you want to send Fund Requisition request for approval?',
                    'params' => [
                        'No' => $model->No,
                        'employeeNo' => Yii::$app->user->identity->{'Employee No_'},
                    ],
                    'method' => 'get',
                ],
                'title' => 'Submit Fund Requisition Approval'

            ]) : '' ?>


            <?= ($model->Status == 'Pending_Approval') ? Html::a('<i class="fas fa-times"></i> Cancel Approval Req.', ['cancel-request'], [
                'class' => 'btn btn-app submitforapproval',
                'data' => [
                    'confirm' => 'Are you sure you want to cancel Fund Requisition approval request?',
                    'params' => [
                        'No' => $model->No,
                    ],
                    'method' => 'get',
                ],
                'title' => 'Cancel Fund Requisition Approval Request'

            ]) : '' ?>



            <?= Html::a('<i class="fas fa-file-pdf"></i> Print Requisition', ['print-requisition'], [
                'class' => 'btn btn-app ',
                'data' => [
                    'confirm' => 'Print Requisition?',
                    'params' => [
                        'No' => $model->No,
                    ],
                    'method' => 'get',
                ],
                'title' => 'Print Requisition.'

            ]) ?>


        </div>
    </div>

<?php endif; ?>

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
                            <?= $form->field($model, 'Key')->hiddenInput(['readonly' => true])->label(false) ?>
                            <?= $form->field($model, 'Employee_No')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Employee_Name')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Purpose')->textInput(['required' => true]) ?>
                            <?= $form->field($model, 'attachment')->fileInput(['accept' => 'application/pdf']) ?>
                            <?= '<p><span>Gross Allowance</span> ' . Html::a($model->Gross_Amount, '#', ['id' => 'Gross_Allowance']);
                            '</p>' ?>
                            <?= '<p><span>Net Allowance LCY</span> ' . Html::a($model->Net_Allowance_LCY, '#', ['id' => 'Net_Allowance_LCY']);
                            '</p>' ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'Status')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Global_Dimension_1_Code')->dropDownList($programs, ['prompt' => 'Select ..', 'required' => true]) ?>
                            <?= $form->field($model, 'Global_Dimension_2_Code')->dropDownList($departments, ['prompt' => 'select ...', 'required' => true]) ?>
                            <?= $form->field($model, 'Expected_Date_of_Surrender')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Currency_Code')->dropDownList($currencies, ['prompt' => 'Select ...']) ?>
                            <?= $form->field($model, 'Exchange_Rate')->textInput(['type' => 'number', 'readonly' => true]) ?>

                        </div>
                    </div>

                </div>

                <div class="row">

                    <div class="form-group">
                        <input type="hidden" name="absolute" value="<?= $absoluteUrl ?>">
                        <?php Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                    </div>


                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <!--End Header Section  -->


        <!-- Begin Lines Section -->


        <!-- Lines-->
        <div class="card">
            <div class="card-header">
                <div class="card-title"> <?= Html::a(
                                                '<i class="fa fa-plus-square"></i> New Funds Requisition Line',
                                                ['add-line'],
                                                [
                                                    'class' => 'add btn btn-outline-info',
                                                    'data-no' => $model->No,
                                                    'data-service' => 'AllowanceRequestLine'
                                                ]
                                            ) ?></div>


                <div class="card-tools my-2 px-3">
                    <span class="text text-info border border-info p-2 rounded">To Update line values, double click on cells whose column headers are colored blue.</span>
                </div>


            </div>
            <div class="card-body">
                <?php
                if (property_exists($document->Allowance_Request_Line, 'Allowance_Request_Line')) { //show Lines 
                ?>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <td class="text-info text-center text-bold">Employee_No</td>
                                    <td><b>Employee Name</b></td>
                                    <td class="text-info"><b>Transaction Type</b></td>
                                    <td><b>Account No</b></td>
                                    <td><b>Account Name</b></td>
                                    <td><b>Description</b></td>
                                    <td class="text-info"><b>Daily_Rate</b></td>
                                    <td class="text-info"><b>No_of_Days</b></td>
                                    <td><b>Amount</b></td>
                                    <td><b>Amount LCY</b></td>

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



                                    <?php if ($model->Status == 'New') : ?>
                                        <td><b>Actions</b></td>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($document->Allowance_Request_Line->Allowance_Request_Line as $obj) :
                                    $updateLink = Html::a('<i class="fa fa-edit"></i>', ['fundsrequisitionline/update', 'Key' => $obj->Key, 'Request_No' => $model->No], ['class' => 'update-objective btn btn-outline-info btn-xs']);
                                    $deleteLink = Html::a('<i class="fa fa-trash"></i>', ['fundsrequisitionline/delete', 'Key' => $obj->Key], ['class' => 'delete btn btn-outline-danger btn-xs']);
                                ?>
                                    <tr>
                                        <td data-key="<?= $obj->Key ?>" data-name="Employee_No" data-service="AllowanceRequestLine" ondblclick="addDropDown(this,'employees')" data-validate="Employee_Name"><?= !empty($obj->Employee_No) ? $obj->Employee_No : '' ?></td>
                                        <td class="Employee_Name"><?= !empty($obj->Employee_Name) ? $obj->Employee_Name : 'Not Set' ?></td>
                                        <td data-key="<?= $obj->Key ?>" data-name="PD_Transaction_Code" data-service="AllowanceRequestLine" ondblclick="addDropDown(this,'rates')" data-validate="Account_Name"><?= !empty($obj->PD_Transaction_Code) ? $obj->PD_Transaction_Code : 'Not Set' ?></td>
                                        <td data-key="<?= $obj->Key ?>"><?= !empty($obj->Account_No) ? $obj->Account_No : 'Not Set' ?></td>
                                        <td class="Account_Name"><?= !empty($obj->Account_Name) ? $obj->Account_Name : 'Not Set' ?></td>
                                        <td><?= !empty($obj->Description) ? $obj->Description : 'Not Set' ?></td>

                                        <td data-key="<?= $obj->Key ?>" data-name="Daily_Rate" data-service="AllowanceRequestLine" ondblclick="addInput(this,'number')"><?= !empty($obj->Daily_Rate) ? $obj->Daily_Rate : '' ?></td>
                                        <td data-key="<?= $obj->Key ?>" data-name="No_of_Days" data-service="AllowanceRequestLine" ondblclick="addInput(this,'number')" data-validate="Amount"><?= !empty($obj->No_of_Days) ? $obj->No_of_Days : '' ?></td>
                                        <td class="Amount" data-key="<?= $obj->Key ?>" data-name="Amount" data-service="AllowanceRequestLine"><?= !empty($obj->Amount) ? $obj->Amount : '' ?></td>
                                        <td class="Amount_LCY"><?= !empty($obj->Amount_LCY) ? $obj->Amount_LCY : '' ?></td>
                                        <td data-key="<?= $obj->Key ?>" data-name="Global_Dimension_1_Code" data-service="AllowanceRequestLine" ondblclick="addDropDown(this,'dimension1')" data-validate="Amount_LCY"><?= !empty($obj->Global_Dimension_1_Code) ? $obj->Global_Dimension_1_Code : '' ?></td>
                                        <td data-key="<?= $obj->Key ?>" data-name="Global_Dimension_2_Code" data-service="AllowanceRequestLine" ondblclick="addDropDown(this,'dimension2')"><?= !empty($obj->Global_Dimension_2_Code) ? $obj->Global_Dimension_2_Code : '' ?></td>


                                        <td data-key="<?= $obj->Key ?>" data-name="Donor_No" data-service="AllowanceRequestLine" ondblclick="addDropDown(this,'donors',{'Grant_No': 'grant'})" data-validate="Donor_Name" class="text-center"><?= !empty($obj->Donor_No) ? $obj->Donor_No : '' ?></td>
                                        <td class="text-center Donor_Name"><?= !empty($obj->Donor_Name) ? $obj->Donor_Name : '' ?></td>
                                        <td data-key="<?= $obj->Key ?>" data-name="Grant_No" data-service="AllowanceRequestLine" ondblclick="addDropDown(this,'grants')" class="text-center grant"><?= !empty($obj->Grant_No) ? $obj->Grant_No : '' ?></td>
                                        <td data-key="<?= $obj->Key ?>" data-name="Objective_Code" data-service="AllowanceRequestLine" ondblclick="addDropDown(this,'objectives',{'Grant_No': 'grant'})"><?= !empty($obj->Objective_Code) ? $obj->Objective_Code : '' ?></td>
                                        <td data-key="<?= $obj->Key ?>" data-name="Output_Code" data-service="AllowanceRequestLine" ondblclick="addDropDown(this,'outputs',{'Grant_No': 'grant'})"><?= !empty($obj->Output_Code) ? $obj->Output_Code : '' ?></td>
                                        <td data-key="<?= $obj->Key ?>" data-name="Outcome_Code" data-service="AllowanceRequestLine" ondblclick="addDropDown(this,'outcome',{'Grant_No': 'grant'})"><?= !empty($obj->Outcome_Code) ? $obj->Outcome_Code : '' ?></td>
                                        <td data-key="<?= $obj->Key ?>" data-name="Activity_Code" data-service="AllowanceRequestLine" ondblclick="addDropDown(this,'activities',{'Grant_No': 'grant'})"><?= !empty($obj->Activity_Code) ? $obj->Activity_Code : '' ?></td>
                                        <td data-key="<?= $obj->Key ?>" data-name="Partner_Code" data-service="AllowanceRequestLine" ondblclick="addDropDown(this,'partners',{'Grant_No': 'grant'})"><?= !empty($obj->Partner_Code) ? $obj->Partner_Code : '' ?></td>

                                        <?php if ($model->Status == 'New') : ?>
                                            <td><?= $updateLink . '|' . $deleteLink ?></td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                    </div>
                <?php } ?>
            </div>








        </div>

        <!--objectives card -->


        <!-- \End Lines Section -->
        <!-- Attachments -->
        <?php if (is_array($attachments) && count($attachments)) :  //Yii::$app->recruitment->printrr($attachments); 
        ?>
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Files Attachments</h3>
                </div>
                <div class="card-body">
                    <?php $i = 0;
                    foreach ($attachments as $file) : ++$i; ?>


                        <div class="my-2 file border border-info d-flex justify-content-around align-items-center rounded p-3">
                            <p class="my-auto border rounded border-info bg-info p-2">Attachment <?= $i ?></p>
                            <?= Bootstrap4Html::a('<i class="fas fa-file"></i> Open', ['read'], [
                                'class' => 'btn btn-info',
                                'data' => [
                                    'params' => [
                                        'path' => $file->File_path,
                                        'No' => $model->No
                                    ],
                                    'method' => 'POST'
                                ]
                            ]) ?>
                        </div>


                    <?php endforeach; ?>
                </div>

            </div>
        <?php endif; ?>
        <!-- / Attachments -->

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

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <!--<button type="button" class="btn btn-primary">Save changes</button>-->
            </div>

        </div>
    </div>
</div>
<input type="hidden" name="url" value="<?= $absoluteUrl ?>">
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
    /*Deleting Records*/
     
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

     //Upload Damn File

    $('#fundrequisition-attachment').change(function(e){
          globalUpload('LeaveAttachments','fundrequisition','attachment','AllowanceRequestCard');
          setTimeout(()=>{ location.reload(true)}, 1500);
    });
      


    // Set Purpose


    $('#fundrequisition-purpose').change((e) => {
        globalFieldUpdate('fundrequisition','fund-requisition','Purpose', e);
    });

         
        
    
    // Set Currency

    $('#fundrequisition-currency_code').change((e) => {
        globalFieldUpdate('fundrequisition','fund-requisition','Currency_Code', e,['Exchange_Rate']);
    });
    
     
     
    
     
     
    
     
     /* Add Line */
     $('.add-line').on('click', function(e){
             e.preventDefault();
            var url = $(this).attr('href');
            console.log(url);
            $('.modal').modal('show')
                            .find('.modal-body')
                            .load(url); 

        });
     
     /*Handle modal dismissal event  */
    $('.modal').on('hidden.bs.modal',function(){
        var reld = location.reload(true);
        setTimeout(reld,1000);
    }); 
     
     
     
JS;

$this->registerJs($script);
