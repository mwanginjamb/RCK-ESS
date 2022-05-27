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

<?php if ($model->Imprest_No && property_exists($surrender->Imprest_Surrender_Line, 'Imprest_Surrender_Line')) : ?>

    <div class="row">
        <div class="col-md-4">

            <?= ($model->Status == 'New') ? Html::a('<i class="fas fa-paper-plane"></i> Send Approval Req', ['send-for-approval', 'employeeNo' => Yii::$app->user->identity->{'Employee_No'}], [
                'class' => 'btn btn-app submitforapproval',
                'data' => [
                    'confirm' => 'Are you sure you want to send imprest request for approval?',
                    'params' => [
                        'No' => $_GET['No'],
                        'employeeNo' => Yii::$app->user->identity->{'Employee_No'},
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
                        'No' => $_GET['No'],
                    ],
                    'method' => 'get',
                ],
                'title' => 'Cancel Imprest Approval Request'

            ]) : '' ?>


            <?= Html::a('<i class="fas fa-file-pdf"></i> Print Surrender', ['print-surrender'], [
                'class' => 'btn btn-app ',
                'data' => [
                    'confirm' => 'Print Surrender?',
                    'params' => [
                        'No' => $model->No,
                    ],
                    'method' => 'get',
                ],
                'title' => 'Print Surrender.'

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

                            <?= $form->field($model, 'No')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= ($model->Request_For == 'Other') ? $form->field($model, 'Employee_No')->dropDownList($employees, ['prompt' => 'Select']) : '' ?>
                            <?= $form->field($model, 'Employee_Name')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Imprest_No')->dropDownList($imprests, ['prompt' => 'select..']) ?>
                            <?= $form->field($model, 'Purpose')->textInput(['readonly' => true, 'disabled' => true])
                            ?>
                            <?= $form->field($model, 'attachment')->fileInput(['accept' => 'application/pdf']) ?>
                            <?= '<p><span>Employee Balance</span> ' . Html::a($model->Employee_Balance, '#');
                            '</p>' ?>
                            <?= '<p><span>Imprest Amount</span> ' . Html::a($model->Surrender_Amount, '#');
                            '</p>' ?>
                            <?= '<p><span> Amount LCY</span> ' . Html::a($model->Claim_Amount, '#');
                            '</p>' ?>



                        </div>

                        <div class="col-md-6">
                            <?= $form->field($model, 'Status')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Global_Dimension_1_Code')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Global_Dimension_2_Code')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Posting_Date')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Receipt_No')->dropDownList($receipts, ['prompt' => 'Select ... ']) ?>
                            <?= $form->field($model, 'Receipt_Amount')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Key')->hiddenInput()->label(false) ?>
                            <?= '<p><span> Approval Entries </span> ' . Html::a($model->Approval_Entries, '#');
                            '</p>' ?>




                            <!--                            <p class="parent"><span>+</span>-->



                            </p>



                        </div>



                    </div>




                </div>












                <div class="row">

                    <div class="form-group">
                        <?php Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                    </div>


                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>

        <!-- Lines -->

        <div class="card">
            <div class="card-header">
                <div class="card-title">Imprest Surrender Lines Lines</div>
                <div class="card-tools">
                    <span class="text text-info border border-info p-2 rounded">To Update <b>Actual Spend</b> double click on it's cell to update.</span>
                </div>
            </div>

            <div class="card-body">
                <?php if (property_exists($surrender->Imprest_Surrender_Line, 'Imprest_Surrender_Line')) : ?>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <td class="text-center text-bold">Account_Name</td>
                                <td class="text-center text-bold">Description</td>
                                <td class="text-center text-bold border border-info">Actual Spend</td>
                                <td class="text-center text-bold ">Imprest Amount</td>
                                <td class="text-center text-bold">Request No</td>
                                <td class="text-center text-bold">Surrendered </td>

                                <td class="text-center text-bold border border-info">Donor Code</td>
                                <td class="text-center text-bold">Donor Name</td>
                                <td class="text-center text-bold border border-info">Grant Name</td>
                                <td class="text-center text-bold border border-info"><b>Objective Code</b></td>
                                <td class="text-center text-bold border border-info"><b>Output Code</b></td>
                                <td class="text-center text-bold border border-info"><b>Outcome Code</b></td>
                                <td class="text-center text-bold border border-info"><b>Activity Code</b></td>
                                <td class="text-center text-bold border border-info"><b>Partner Code</b></td>

                            </thead>
                            <tbody>
                                <?php foreach ($surrender->Imprest_Surrender_Line->Imprest_Surrender_Line as $line) : ?>
                                    <tr>
                                        <td class="text-center"><?= !empty($line->Account_Name) ? $line->Account_Name : '' ?></td>
                                        <td class="text-center"><?= !empty($line->Description) ? $line->Description : '' ?></td>
                                        <td data-key="<?= $line->Key ?>" data-name="Amount" data-service="ImprestSurrenderLine" ondblclick="addInput(this,'number')"><?= !empty($line->Amount) ? $line->Amount : '' ?></td>
                                        <td class="text-center amount"><?= !empty($line->Imprest_Amount) ? $line->Imprest_Amount : '' ?></td>
                                        <td class="text-center"><?= !empty($line->Request_No) ? $line->Request_No : '' ?></td>
                                        <td class="text-center"><?= Html::checkbox('Surrender', $line->Surrender) ?></td>

                                        <td data-key="<?= $line->Key ?>" data-name="Donor_No" data-service="ImprestSurrenderLine" ondblclick="addDropDown(this,'donors',{'Grant_No': 'grant','Amount':'amount'})" data-validate="Donor_Name" class="text-center"><?= !empty($line->Donor_No) ? $line->Donor_No : '' ?></td>
                                        <td class="text-center" id="Donor_Name"><?= !empty($line->Donor_Name) ? $line->Donor_Name : '' ?></td>
                                        <td data-key="<?= $line->Key ?>" data-name="Grant_No" data-service="ImprestSurrenderLine" ondblclick="addDropDown(this,'grants')" class="text-center grant"><?= !empty($line->Grant_No) ? $line->Grant_No : '' ?></td>
                                        <td data-key="<?= $line->Key ?>" data-name="Objective_Code" data-service="ImprestSurrenderLine" ondblclick="addDropDown(this,'objectives',{'Grant_No': 'grant'})"><?= !empty($line->Objective_Code) ? $line->Objective_Code : '' ?></td>
                                        <td data-key="<?= $line->Key ?>" data-name="Output_Code" data-service="ImprestSurrenderLine" ondblclick="addDropDown(this,'outputs',{'Grant_No': 'grant'})"><?= !empty($line->Output_Code) ? $line->Output_Code : '' ?></td>
                                        <td data-key="<?= $line->Key ?>" data-name="Outcome_Code" data-service="ImprestSurrenderLine" ondblclick="addDropDown(this,'outcome',{'Grant_No': 'grant'})"><?= !empty($line->Outcome_Code) ? $line->Outcome_Code : '' ?></td>
                                        <td data-key="<?= $line->Key ?>" data-name="Activity_Code" data-service="ImprestSurrenderLine" ondblclick="addDropDown(this,'activities',{'Grant_No': 'grant'})"><?= !empty($line->Activity_Code) ? $line->Activity_Code : '' ?></td>
                                        <td data-key="<?= $line->Key ?>" data-name="Partner_Code" data-service="ImprestSurrenderLine" ondblclick="addDropDown(this,'partners',{'Grant_No': 'grant'})"><?= !empty($line->Partner_Code) ? $line->Partner_Code : '' ?></td>

                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>



                <?php endif; ?>
            </div>



        </div>



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
<input type="hidden" name="absolute" value="<?= $absoluteUrl ?>">
<?php
$script = <<<JS
 //Submit Rejection form and get results in json    
       /* $('form').on('submit', function(e){
            e.preventDefault()
            const data = $(this).serialize();
            const url = $(this).attr('action');
            $.post(url,data).done(function(msg){
                    $('.modal').modal('show')
                    .find('.modal-body')
                    .html(msg.note);
        
                },'json');
        });*/

        //Upload Damn File

    $('#imprestsurrendercard-attachment').change(function(e){
          globalUpload('LeaveAttachments','Imprestsurrendercard','attachment','ImprestSurrenderCard');
          setTimeout(()=>{ location.reload(true)}, 3000)
    });

    $('#imprestsurrendercard-imprest_no').change((e) => {
        globalFieldUpdate('Imprestsurrendercard','imprest','Imprest_No', e,[],'ImprestSurrenderCard');
        setTimeout(()=>{ location.reload(true)}, 3000)
    });

   
    /**Update Purpose */

    $('#imprestsurrendercard-purpose').change((e) => {
        globalFieldUpdate('Imprestsurrendercard','imprest','Purpose', e,[],'ImprestSurrenderCard');
    }); 
     
     /*Set Program and Department dimension */
     
     $('#imprestcard-global_dimension_1_code').change(function(e){
        const dimension = e.target.value;
        const No = $('#imprestcard-no').val();
        if(No.length){
            const url = $('input[name=url]').val()+'imprest/setdimension?dimension=Global_Dimension_1_Code';
            $.post(url,{'dimension': dimension,'No': No}).done(function(msg){
                   //populate empty form fields with new data
                    
                    
                    if((typeof msg) === 'string') { // A string is an error
                        const parent = document.querySelector('.field-imprestcard-global_dimension_1_code');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = msg;
                        
                    }else{ // An object represents correct details
                        const parent = document.querySelector('.field-imprestcard-global_dimension_1_code');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = ''; 
                        
                    }
                    
                },'json');
        }
     });
     
     
     /* set department */
     
     $('#imprestcard-global_dimension_2_code').change(function(e){
        const dimension = e.target.value;
        const No = $('#imprestcard-no').val();
        if(No.length){
            const url = $('input[name=url]').val()+'imprest/setdimension?dimension=Global_Dimension_2_Code';
            $.post(url,{'dimension': dimension,'No': No}).done(function(msg){
                   //populate empty form fields with new data
                  
                    if((typeof msg) === 'string') { // A string is an error
                        const parent = document.querySelector('.field-imprestcard-global_dimension_2_code');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = msg;
                        
                    }else{ // An object represents correct details
                        const parent = document.querySelector('.field-imprestcard-global_dimension_2_code');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = ''; 
                        
                    }
                    
                },'json');
        }
     });
     
     
     /*Set Imprest Type*/
     
     $('#imprestcard-imprest_type').change(function(e){
        const Imprest_Type = e.target.value;
        const No = $('#imprestcard-no').val();
        if(No.length){
            const url = $('input[name=url]').val()+'imprest/setimpresttype';
            $.post(url,{'Imprest_Type': Imprest_Type,'No': No}).done(function(msg){
                   //populate empty form fields with new data
                   
                    if((typeof msg) === 'string') { // A string is an error
                        const parent = document.querySelector('.field-imprestcard-imprest_type');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = msg;
                        
                    }else{ // An object represents correct details
                        const parent = document.querySelector('.field-imprestcard-imprest_type');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = '';
                        
                         $('.modal').modal('show')
                        .find('.modal-body')
                        .html('<div class="alert alert-success">Imprest Type Update Successfully.</div>');
                        
                    }
                    
                },'json');
        }
     });
     
     
     /* Add Line */
     $('.add-line').on('click', function(e){
             e.preventDefault();
            var url = $(this).attr('href');
          
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
