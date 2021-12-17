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

<?php if(property_exists($document->Allowance_Request_Line,'Allowance_Request_Line')  && isset($model->Purpose)): ?>

<div class="row">
    <div class="col-md-4">

        <?= ($model->Status == 'New')?Html::a('<i class="fas fa-paper-plane"></i> Send Approval Req',['send-for-approval','employeeNo' => Yii::$app->user->identity->{'Employee No_'}],['class' => 'btn btn-app submitforapproval',
            'data' => [
                'confirm' => 'Are you sure you want to send Fund Requisition request for approval?',
                'params'=>[
                    'No'=> $model->No,
                    'employeeNo' => Yii::$app->user->identity->{'Employee No_'},
                ],
                'method' => 'get',
        ],
            'title' => 'Submit Fund Requisition Approval'

        ]):'' ?>


        <?= ($model->Status == 'Pending_Approval')?Html::a('<i class="fas fa-times"></i> Cancel Approval Req.',['cancel-request'],['class' => 'btn btn-app submitforapproval',
            'data' => [
            'confirm' => 'Are you sure you want to cancel Fund Requisition approval request?',
            'params'=>[
                'No'=> $model->No,
            ],
            'method' => 'get',
        ],
            'title' => 'Cancel Fund Requisition Approval Request'

        ]):'' ?>



        <?= Html::a('<i class="fas fa-file-pdf"></i> Print Requisition',['print-requisition'],['class' => 'btn btn-app ',
            'data' => [
                'confirm' => 'Print Requisition?',
                'params'=>[
                    'No'=> $model->No,
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
                                            <?= $form->field($model, 'No')->textInput(['readonly'=> true]) ?>
                                            <?= $form->field($model, 'Key')->hiddenInput(['readonly'=> true])->label(false) ?>
                                            <?= $form->field($model, 'Employee_No')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                                            <?= $form->field($model, 'Employee_Name')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                                            <?= $form->field($model, 'Purpose')->textInput(['required' => true]) ?>
                                            <?= '<p><span>Gross Allowance</span> '.Html::a($model->Gross_Amount,'#',['id'=>'Gross_Allowance']); '</p>' ?>
                                            <?= '<p><span>Net Allowance LCY</span> '.Html::a($model->Net_Allowance_LCY,'#',['id' => 'Net_Allowance_LCY']); '</p>'?>
                                        </div>
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'Status')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                                            <?= $form->field($model, 'Global_Dimension_1_Code')->dropDownList($programs,['prompt' => 'Select ..','required'=> true]) ?>
                                            <?= $form->field($model, 'Global_Dimension_2_Code')->dropDownList($departments, ['prompt' => 'select ...','required'=> true]) ?>
                                            <?= $form->field($model, 'Expected_Date_of_Surrender')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                                            <?= $form->field($model, 'Currency_Code')->dropDownList($currencies,['prompt' => 'Select ...']) ?>
                                            <?= $form->field($model, 'Exchange_Rate')->textInput(['type'=> 'number','readonly' => true]) ?>
                                            
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
                    <div class="card-title">   <?= Html::a('<i class="fa fa-plus-square"></i> New Funds Requisition Line',['fundsrequisitionline/create','Request_No'=>$model->No],['class' => 'add-objective btn btn-outline-info']) ?></div>
                </div>
                <div class="card-body">
                    <?php
                    if(property_exists($document->Allowance_Request_Line,'Allowance_Request_Line')){ //show Lines ?>
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <td><b>Employee Name</b></td>
                                <td><b>Transaction Type</b></td>
                                <td><b>Account No</b></td>
                                <td><b>Account Name</b></td>
                                <td><b>Description</b></td>
                                <td><b>Daily Rate</b></td>
                                <td><b>No_of_Days</b></td>
                                <td><b>Amount LCY</b></td>
                                <td><b>Unbudgeted?</b></td>
                            <?php if($model->Status == 'New'): ?>
                                <td><b>Actions</b></td>
                            <?php endif; ?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach($document->Allowance_Request_Line->Allowance_Request_Line as $obj):
                                $updateLink = Html::a('<i class="fa fa-edit"></i>',['fundsrequisitionline/update','Key'=> $obj->Key, 'Request_No' => $model->No],['class' => 'update-objective btn btn-outline-info btn-xs']);
                                $deleteLink = Html::a('<i class="fa fa-trash"></i>',['fundsrequisitionline/delete','Key'=> $obj->Key ],['class'=>'delete btn btn-outline-danger btn-xs']);
                                ?>
                                <tr>
                                    <td><?= !empty($obj->Employee_Name)?$obj->Employee_Name:'Not Set' ?></td>
                                    <td><?= !empty($obj->PD_Transaction_Code)?$obj->PD_Transaction_Code:'Not Set' ?></td>
                                    <td><?= !empty($obj->Account_No)?$obj->Account_No:'Not Set' ?></td>
                                    <td><?= !empty($obj->Account_Name)?$obj->Account_Name:'Not Set' ?></td>
                                    <td><?= !empty($obj->Description)?$obj->Description:'Not Set' ?></td>
                                    <td><?= !empty($obj->Daily_Rate)?$obj->Daily_Rate:'Not Set' ?></td>
                                    <td><?= !empty($obj->No_of_Days)?$obj->No_of_Days:'Not Set' ?></td>
                                    <td><?= !empty($obj->Amount)?$obj->Amount:'Not Set' ?></td>
                                    <td><?= Html::checkbox('Unbudgeted',$obj->Unbudgeted) ?></td>
                                    <?php if($model->Status == 'New'): ?>
                                    <td><?= $updateLink.'|'.$deleteLink ?></td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php } ?>
                </div>
            </div>

        <!--objectives card -->


       <!-- \End Lines Section -->
   
   
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
      

        //Add a training plan
    
     $('.add-objective, .update-objective').on('click',function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        console.log('clicking...');
        $('.modal').modal('show')
                        .find('.modal-body')
                        .load(url); 

     });

        // Set Purpose

         $('#fundrequisition-purpose').change((e) => {
            globalFieldUpdate('fundrequisition','fund-requisition','Purpose', e);
            location.reload(true);
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
