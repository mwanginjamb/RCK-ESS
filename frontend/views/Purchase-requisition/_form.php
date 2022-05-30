<?php

/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 2/24/2020
 * Time: 12:13 PM
 */

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html as Bootstrap4Html;

$absoluteUrl = \yii\helpers\Url::home(true);
?>


<div class="row">
    <div class="col-md-4">

        <?= ($model->Status == 'New') ? Html::a('<i class="fas fa-paper-plane"></i> Send Approval Req', ['send-for-approval'], [
            'class' => 'btn btn-app submitforapproval',
            'data' => [
                'confirm' => 'Are you sure you want to send this document for approval?',
                'params' => [
                    'No' => $model->No,
                    'employeeNo' => Yii::$app->user->identity->{'Employee_No'},
                ],
                'method' => 'get',
            ],
            'title' => 'Submit Document for Approval'

        ]) : '' ?>


        <?= ($model->Status == 'Pending_Approval') ? Html::a('<i class="fas fa-times"></i> Cancel Approval Req.', ['cancel-request'], [
            'class' => 'btn btn-app submitforapproval',
            'data' => [
                'confirm' => 'Are you sure you want to cancel document approval request?',
                'params' => [
                    'No' => $model->No,
                ],
                'method' => 'get',
            ],
            'title' => 'Cancel Document Approval Request'

        ]) : '' ?>
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

                $form = ActiveForm::begin([
                    // 'id' => $model->formName()
                ]);



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
                                    <h5><i class="icon fas fa-times"></i> Error!</h5>
                                ';
                    echo Yii::$app->session->getFlash('error');
                    print '</div>';
                }


                ?>
                <div class="row">
                    <div class="row col-md-12">



                        <div class="col-md-6">

                            <?= $form->field($model, 'No')->textInput(['readonly' => true]) ?>
                            <?= $form->field($model, 'Key')->hiddenInput()->label(false) ?>
                            <?= $form->field($model, 'Employee_No')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Employee_Name')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Title')->textInput(['maxlength' => 250]) ?>
                            <?= $form->field($model, 'Requested_Delivery_Date')->textInput(['type' => 'date']) ?>

                        </div>

                        <div class="col-md-6">
                            <?= $form->field($model, 'attachment')->fileInput(['accept' => 'application/pdf']) ?>


                            <?= $form->field($model, 'attachment_multiple[]')->fileInput(['accept' => 'application/pdf', 'id' => 'select_multiple', 'multiple' => true]) ?>

                            <div id="upload-note"></div>
                            <div class="progress" id="progress_bar" style="display:none">
                                <div class="progress-bar" id="progress_bar_process" role="progressbar" style="width:0%"></div>
                            </div>

                            <?= $form->field($model, 'Global_Dimension_2_Code')->dropDownList($departments, ['prompt' => 'Select Department']) ?>

                            <?= $form->field($model, 'Status')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Requisition_Date')->textInput(['type' => 'date']) ?>
                            <?= $form->field($model, 'Approval_Entries')->textInput(['readonly' => true, 'disabled' => true]) ?>

                        </div>

                    </div>

                </div>


                <div class="row">

                    <div class="form-group">
                        <?php Html::submitButton(($model->isNewRecord) ? 'Save' : 'Update', ['class' => 'btn btn-success']) ?>
                    </div>


                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>



        <!-- Document  Lines -->

        <div class="card">
            <div class="card-header">

                <?= ($model->Status == 'New') ? Html::a(
                    '<i class="fa fa-plus-square"></i> New Line',
                    ['add-line'],
                    [
                        'class' => 'add btn btn-outline-info',
                        'data-no' => $model->No,
                        'data-service' => 'PurchaseRequisitionLine'
                    ]
                ) : '' ?>


                <div class="card-tools my-2 px-3">
                    <span class="text text-info border border-info p-2 rounded">To Update line values, double click on cells whose column headers are colored blue.</span>
                </div>

            </div>
            <div class="card-body">
                <?php
                if (is_array($model->lines)) { //show Lines 
                ?>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <td class="text text-info"><b>Type</b></td>
                                    <td class="text text-info"><b>No</b></td>
                                    <td><b>Name</b></td>
                                    <td><b>Unit of Measure</b></td>

                                    <td class="text text-info"><b>Description</b></td>
                                    <td class="text text-info"><b>Quantity</b></td>
                                    <td class="text text-info"><b>Location</b></td>
                                    <td class="text text-info"><b>Estimate Unit Price</b></td>
                                    <td><b>Estimate Total Amount</b></td>
                                    <td class="text text-info"><b>Procurement Method</b></td>
                                    <td class="text text-info"><b>Sub Office</b></td>
                                    <td class="text text-info"><b>Program Code</b></td>

                                    <td class="text text-info"><b>Donor No</b></td>
                                    <!-- <td class=""><b>Donor Name</b></td> -->
                                    <td class="text text-info"><b>Grant_No</b></td>
                                    <td class="text text-info"><b>Objective Code</b></td>
                                    <td class="text text-info"><b>Output Code</b></td>
                                    <td class="text text-info"><b>Outcome Code</b></td>
                                    <td class="text text-info"><b>Activity Code</b></td>
                                    <td class="text text-info"><b>Partner Code</b></td>
                                    <td>Action</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // print '<pre>'; print_r($model->getObjectives()); exit;

                                foreach ($model->lines as $obj) :


                                    $delete = ($model->Status == 'New') ? Html::a(
                                        '<i class="fa fa-trash"></i> ',
                                        ['delete-line'],
                                        [
                                            'class' => 'del btn btn-outline-info',
                                            'title' => 'Delete this record.',
                                            'data-key' => $obj->Key,
                                            'data-service' => 'PurchaseRequisitionLine'
                                        ]
                                    ) : '';



                                ?>
                                    <tr>

                                        <td data-key="<?= $obj->Key ?>" data-reload="true" data-name="Type" data-service="PurchaseRequisitionLine" ondblclick="addDropDown(this,'types')" class="type"><?= !empty($obj->Type) ? $obj->Type : '' ?></td>
                                        <td data-key="<?= $obj->Key ?>" data-validate="Name" data-name="No" data-service="PurchaseRequisitionLine" ondblclick="addDropDown(this,'no',{'Type': 'type'})"><?= !empty($obj->No) ? $obj->No : '' ?></td>
                                        <td class="Name"><?= !empty($obj->Name) ? $obj->Name : '' ?></td>
                                        <td class="Unit_of_Measure"><?= !empty($obj->Unit_of_Measure) ? $obj->Unit_of_Measure : '' ?></td>
                                        <td data-key="<?= $obj->Key ?>" data-name="Description" data-service="PurchaseRequisitionLine" ondblclick="addTextarea(this)"><?= !empty($obj->Description) ? $obj->Description : '' ?></td>
                                        <td data-validate="Unit_of_Measure" data-key="<?= $obj->Key ?>" data-name="Quantity" data-service="PurchaseRequisitionLine" ondblclick="addInput(this,'number')"><?= !empty($obj->Quantity) ? $obj->Quantity : '' ?></td>
                                        <td data-key="<?= $obj->Key ?>" data-name="Location" data-service="PurchaseRequisitionLine" ondblclick="addDropDown(this,'locations')"><?= !empty($obj->Location) ? $obj->Location : '' ?></td>
                                        <td data-key="<?= $obj->Key ?>" data-validate="Estimate_Total_Amount" data-name="Estimate_Unit_Price" data-service="PurchaseRequisitionLine" ondblclick="addInput(this,'number')"><?= !empty($obj->Estimate_Unit_Price) ? $obj->Estimate_Unit_Price : '' ?></td>
                                        <td class="Estimate_Total_Amount"><?= !empty($obj->Estimate_Total_Amount) ? $obj->Estimate_Total_Amount : '' ?></td>
                                        <td class="Procurement_Method" data-key="<?= $obj->Key ?>" data-validate="Procurement_Method" data-name="Procurement_Method" data-service="PurchaseRequisitionLine" ondblclick="addDropDown(this,'procurement-methods')"><?= !empty($obj->Procurement_Method) ? $obj->Procurement_Method : '' ?></td>
                                        <td data-key="<?= $obj->Key ?>" data-name="Global_Dimension_1_Code" data-service="PurchaseRequisitionLine" ondblclick="addDropDown(this,'dimension1')"><?= !empty($obj->Global_Dimension_1_Code) ? $obj->Global_Dimension_1_Code : '' ?></td>
                                        <td data-key="<?= $obj->Key ?>" data-name="Global_Dimension_2_Code" data-service="PurchaseRequisitionLine" ondblclick="addDropDown(this,'dimension2')"><?= !empty($obj->Global_Dimension_2_Code) ? $obj->Global_Dimension_2_Code : '' ?></td>

                                        <td data-key="<?= $obj->Key ?>" data-name="Donor_Code" data-service="PurchaseRequisitionLine" ondblclick="addDropDown(this,'donors')" class="text-center"><?= !empty($obj->Donor_Code) ? $obj->Donor_Code : '' ?></td>
                                        <!-- <td class="Donor_Name"><?php //!empty($obj->Donor_Name)?$obj->Donor_Name:'' 
                                                                    ?></td> -->
                                        <td data-key="<?= $obj->Key ?>" data-name="Grant_No" data-service="PurchaseRequisitionLine" ondblclick="addDropDown(this,'grants')" class="text-center grant"><?= !empty($obj->Grant_No) ? $obj->Grant_No : '' ?></td>
                                        <td data-key="<?= $obj->Key ?>" data-name="Objective_Code" data-service="PurchaseRequisitionLine" ondblclick="addDropDown(this,'objectives',{'Grant_No': 'grant'})"><?= !empty($obj->Objective_Code) ? $obj->Objective_Code : '' ?></td>
                                        <td data-key="<?= $obj->Key ?>" data-name="Output_Code" data-service="PurchaseRequisitionLine" ondblclick="addDropDown(this,'outputs',{'Grant_No': 'grant'})"><?= !empty($obj->Output_Code) ? $obj->Output_Code : '' ?></td>
                                        <td data-key="<?= $obj->Key ?>" data-name="Outcome_Code" data-service="PurchaseRequisitionLine" ondblclick="addDropDown(this,'outcome',{'Grant_No': 'grant'})"><?= !empty($obj->Outcome_Code) ? $obj->Outcome_Code : '' ?></td>
                                        <td data-key="<?= $obj->Key ?>" data-name="Activity_Code" data-service="PurchaseRequisitionLine" ondblclick="addDropDown(this,'activities',{'Grant_No': 'grant'})"><?= !empty($obj->Activity_Code) ? $obj->Activity_Code : '' ?></td>
                                        <td data-key="<?= $obj->Key ?>" data-name="Partner_Code" data-service="PurchaseRequisitionLine" ondblclick="addDropDown(this,'partners',{'Grant_No': 'grant'})"><?= !empty($obj->Partner_Code) ? $obj->Partner_Code : '' ?></td>
                                        <td><?= $delete ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                    </div>
                <?php } ?>
            </div>
        </div>

        <!--/Lines card -->
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
                            <?= Html::a(
                                '<i class="fa fa-trash"></i> ',
                                ['delete-line'],
                                [
                                    'class' => 'delete btn btn-outline-danger',
                                    'title' => 'Delete this record.',
                                    'data-key' => $file->Key,
                                    'data-service' => 'LeaveAttachments',

                                ]
                            )
                            ?>
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
<input type="hidden" name="absolute" id="absolute" value="<?= $absoluteUrl ?>">
<?php
$script = <<<JS

    $('#purchaserequisition-title').change((e) => {
        globalFieldUpdate('purchaserequisition','purchase-requisition','Title', e);
    });
    $('#purchaserequisition-requested_delivery_date').blur((e) => {
        globalFieldUpdate('purchaserequisition','purchase-requisition','Requested_Delivery_Date', e);
    });
    $('#purchaserequisition-global_dimension_2_code').change((e) => {
        globalFieldUpdate('purchaserequisition','purchase-requisition','Global_Dimension_2_Code', e);
    });

    //Upload Damn File
    $('#purchaserequisition-attachment').change(function(e){
          globalUpload('LeaveAttachments','purchaserequisition','attachment','PurchaseRequisitionCard');    
          // setTimeout(()=>{ location.reload(true)}, 3000);
    });

    $('#select_multiple').change(function(e){
          globalUploadMultiple('LeaveAttachments','purchaserequisition','purchase-requisition','PurchaseRequisitionCard');
         // setTimeout(()=>{ location.reload(true)}, 1500);
    });

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
                },100);
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
                    console.log(typeof msg.result);
                    if(typeof msg.result === 'string')
                    {
                        alert(msg['result']);
                    }
                    setTimeout(() => {
                        location.reload(true);
                    },100);
                });
            }
            
    });
 
     /*Handle modal dismissal event  */
    $('.modal').on('hidden.bs.modal',function(){
        var reld = location.reload(true);
        setTimeout(reld,1000);
    }); 

    
     
     
     
JS;

$this->registerJs($script);
