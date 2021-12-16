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

<?php if($model->Imprest_No && property_exists($surrender->Imprest_Surrender_Line, 'Imprest_Surrender_Line')): ?>

<div class="row">
    <div class="col-md-4">

        <?= ($model->Status == 'New')?Html::a('<i class="fas fa-paper-plane"></i> Send Approval Req',['send-for-approval','employeeNo' => Yii::$app->user->identity->{'Employee_No'}],['class' => 'btn btn-app submitforapproval',
            'data' => [
                'confirm' => 'Are you sure you want to send imprest request for approval?',
                'params'=>[
                    'No'=> $_GET['No'],
                    'employeeNo' => Yii::$app->user->identity->{'Employee_No'},
                ],
                'method' => 'get',
        ],
            'title' => 'Submit Imprest Approval'

        ]):'' ?>


        <?= ($model->Status == 'Pending_Approval')?Html::a('<i class="fas fa-times"></i> Cancel Approval Req.',['cancel-request'],['class' => 'btn btn-app submitforapproval',
            'data' => [
            'confirm' => 'Are you sure you want to cancel imprest approval request?',
            'params'=>[
                'No'=> $_GET['No'],
            ],
            'method' => 'get',
        ],
            'title' => 'Cancel Imprest Approval Request'

        ]):'' ?>


        <?= Html::a('<i class="fas fa-file-pdf"></i> Print Surrender',['print-surrender'],['class' => 'btn btn-app ',
            'data' => [
                'confirm' => 'Print Surrender?',
                'params'=>[
                    'No'=> $model->No,
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

                            <?= $form->field($model, 'No')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= ($model->Request_For == 'Other')?$form->field($model, 'Employee_No')->dropDownList($employees,['prompt' => 'Select']):'' ?>
                            <?= $form->field($model, 'Employee_Name')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Imprest_No')->dropDownList($imprests,['prompt' => 'select..']) ?>
                            <?= $form->field($model, 'Purpose')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= '<p><span>Employee Balance</span> '.Html::a($model->Employee_Balance,'#'); '</p>' ?>
                            <?= '<p><span>Imprest Amount</span> '.Html::a($model->Surrender_Amount,'#'); '</p>'?>
                            <?= '<p><span> Amount LCY</span> '.Html::a($model->Claim_Amount,'#'); '</p>'?>



                        </div>

                        <div class="col-md-6">
                            <?= $form->field($model, 'Status')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Global_Dimension_1_Code')->textInput(['readonly' => true,'disabled' => true]) ?>
                            <?= $form->field($model, 'Global_Dimension_2_Code')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Posting_Date')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Receipt_No')->dropDownList($receipts,['prompt' => 'Select ... ']) ?>
                            <?= $form->field($model, 'Receipt_Amount')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Key')->hiddenInput()->label(false) ?>
                            <?= '<p><span> Approval Entries </span> '.Html::a($model->Approval_Entries,'#'); '</p>'?>




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
                <div class="card-title">Imprest Lines</div>
            </div>

            <div class="card-body">
                <?php if(property_exists($surrender->Imprest_Surrender_Line, 'Imprest_Surrender_Line')): ?>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <td class="text-center text-bold">Account_Name</td>
                                <td class="text-center text-bold">Description</td>
                                <td class="text-center text-bold">Imprest_Amount</td>
                                <td class="text-center text-bold">Request_No</td>
                                <td class="text-center text-bold">Surrendered </td>
                                <td class="text-center text-bold">Donor_Code</td>
                                <td class="text-center text-bold">Job_No</td>
                                <td class="text-center text-bold">Job_Task_No</td>
                                <td class="text-center text-bold">Job_Planning_Line_No"</td>
                                <td class="text-center text-bold">Budgeted_Amount</td>
                                <td class="text-center text-bold">Commited_Amount</td>
                                <td class="text-center text-bold">Total_Expenditure</td>
                                <td class="text-center text-bold">Available_Amount</td>
                                <td class="text-center text-bold">Unbudgeted</td>
                                
                            </thead>
                            <tbody>
                                <?php foreach($surrender->Imprest_Surrender_Line->Imprest_Surrender_Line as $line) :?>
                                    <tr>
                                        <td class="text-center"><?= !empty($line->Account_Name)? $line->Account_Name : '' ?></td>
                                        <td class="text-center"><?= !empty($line->Description)? $line->Description : '' ?></td>
                                        <td class="text-center"><?= !empty($line->Imprest_Amount)? $line->Imprest_Amount : '' ?></td>
                                        <td class="text-center"><?= !empty($line->Request_No)? $line->Request_No : '' ?></td>
                                        <td class="text-center"><?= Html::checkbox('Surrender',$line->Surrender) ?></td>
                                        <td class="text-center"><?= !empty($line->Donor_Code)? $line->Donor_Code : '' ?></td>
                                        <td class="text-center"><?= !empty($line->Job_No)? $line->Job_No : '' ?></td>
                                        <td class="text-center"><?= !empty($line->Job_Task_No)? $line->Job_Task_No : '' ?></td>
                                        <td class="text-center"><?= !empty($line->Job_Planning_Line_No)? $line->Job_Planning_Line_No : '' ?></td>
                                        <td class="text-center"><?= !empty($line->Budgeted_Amount)? $line->Budgeted_Amount : '' ?></td>
                                        <td class="text-center"><?= !empty($line->Commited_Amount)? $line->Commited_Amount : '' ?></td>
                                        <td class="text-center"><?= !empty($line->Total_Expenditure)? $line->Total_Expenditure : '' ?></td>
                                        <td class="text-center"><?= !empty($line->Available_Amount)? $line->Available_Amount : '' ?></td>
                                        <td class="text-center"><?= Html::checkbox('Unbudgeted',$line->Unbudgeted) ?></td>
                                       
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                        

                <?php endif; ?>
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

        // Set Imprest No to surrender
        
     $('#imprestsurrendercard-imprest_no').change(function(e){
        const No = $('#imprestsurrendercard-no').val();
        const Imprest_No = e.target.value;
        if(No.length){
            const url = $('input[name=url]').val()+'imprest/setimpresttosurrender';
            $.post(url,{'Imprest_No': Imprest_No,'No': No}).done(function(msg){
                   //populate empty form fields with new data
                    $('#imprestsurrendercard-global_dimension_1_code').val(msg.Global_Dimension_1_Code);
                    $('#imprestsurrendercard-global_dimension_2_code').val(msg.Global_Dimension_2_Code);
                    $('#imprestsurrendercard-purpose').val(msg.Purpose);
                    if((typeof msg) === 'string') { // A string is an error
                        console.error(msg);
                        const parent = document.querySelector('.field-imprestsurrendercard-imprest_no');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = msg;
                        return;
                    }else{ // An object represents correct details
                        console.log('Found...');
                        console.log(msg.Global_Dimension_1_Code);
                        const parent = document.querySelector('.field-imprestsurrendercard-imprest_no');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = ''; 
                        
                        // Trigger a page reload to display lines and workflow buttons

                        location.reload(true);

                        console.log('got to reloading....');
                       
                        
                    }
                    
                },'json');
        }
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
