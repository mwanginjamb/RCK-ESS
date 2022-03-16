<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 2/24/2020
 * Time: 6:09 PM
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Imprest - '.$model->No;
$this->params['breadcrumbs'][] = ['label' => 'imprest Surrenders', 'url' => ['surrenderlist']];
$this->params['breadcrumbs'][] = ['label' => 'Imprest Surrender Card', 'url' => ['view-surrender','No'=> $model->No]];
/** Status Sessions */


/* Yii::$app->session->set('MY_Appraisal_Status',$model->MY_Appraisal_Status);
Yii::$app->session->set('EY_Appraisal_Status',$model->EY_Appraisal_Status);
Yii::$app->session->set('isSupervisor',false);*/
?>

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

    <div class="row">
        <div class="col-md-12">
            <div class="card-info">
                <div class="card-header">
                    <h3>Imprest Surrender Card </h3>
                </div>



            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">




                    <h3 class="card-title">Imprest No : <?= $model->No?></h3>



                    <?php
                    if(Yii::$app->session->hasFlash('success')){
                        print ' <div class="alert alert-success alert-dismissable">
                                 ';
                        echo Yii::$app->session->getFlash('success');
                        print '</div>';
                    }else if(Yii::$app->session->hasFlash('error')){
                        print ' <div class="alert alert-danger alert-dismissable">
                                 ';
                        echo Yii::$app->session->getFlash('error');
                        print '</div>';
                    }
                    ?>
                </div>
                <div class="card-body">


                    <?php $form = ActiveForm::begin(); ?>


                    <div class="row">
                        <div class=" row col-md-12">
                            <div class="col-md-6">

                                <?= $form->field($model, 'No')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                                <?= $form->field($model, 'Employee_No')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                                <?= $form->field($model, 'Employee_Name')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                                <?= $form->field($model, 'Imprest_No')->textInput(['readonly' => true]) ?>
                                <?= $form->field($model, 'Purpose')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                                <?= '<p><span>Employee Balance</span> '.Html::a($model->Employee_Balance,'#'); '</p>' ?>
                                <?= '<p><span>Imprest Amount</span> '.Html::a($model->Surrender_Amount,'#'); '</p>'?>
                                <?= '<p><span> Amount LCY</span> '.Html::a($model->Claim_Amount,'#'); '</p>'?>



                               <!-- <p class="parent"><span>+</span>-->




                                </p>


                            </div>
                            <div class="col-md-6">

                                <?= $form->field($model, 'Status')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                                <?= $form->field($model, 'Global_Dimension_1_Code')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                                <?= $form->field($model, 'Global_Dimension_2_Code')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                                <?= $form->field($model, 'Posting_Date')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                                <?= $form->field($model, 'Receipt_No')->dropDownList([],['prompt' => 'Select ... ','readonly'=> true, 'disabled'=>true]) ?>
                                <?= $form->field($model, 'Receipt_Amount')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                                <?= '<p><span> Approval Entries </span> '.Html::a($model->Approval_Entries,'#'); '</p>'?>

                               <!-- <p class="parent"><span>+</span></p>-->



                            </div>
                        </div>
                    </div>




                    <?php ActiveForm::end(); ?>



                </div>
            </div><!--end details card-->


            <!--Imprest Lines -->

            <div class="card">
                <div class="card-header">
                    <div class="card-title"> Imprest Surrender Lines  </div>
                </div>

                <div class="card-body">
                    <?php
                    if(property_exists($surrender->Imprest_Surrender_Line, 'Imprest_Surrender_Line')){ //show Lines ?>

                    <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                <tr>
                                                    <td class="text-center text-bold">Account_Name</td>
                                                    <td class="text-center text-bold">Description</td>
                                                    <td class="text-center text-bold">Actual Spend</td>
                                                    <td class="text-center text-bold">Imprest Amount</td>
                                                    <td class="text-center text-bold">Request No</td>
                                                    <td class="text-center text-bold">Surrender</td>
                                                    <td class="text-center text-bold ">Donor Code</td>
                                                    <td class="text-center text-bold">Donor Name</td>
                                                    <td class="text-center text-bold" >Grant Name</td>
                                                    <td class="text-center text-bold"><b>Objective Code</b></td>
                                                    <td class="text-center text-bold"><b>Output Code</b></td>
                                                    <td class="text-center text-bold"><b>Outcome Code</b></td>
                                                    <td class="text-center text-bold"><b>Activity Code</b></td>
                                                    <td class="text-center text-bold"><b>Partner Code</b></td>
                                                    


                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                // print '<pre>'; print_r($model->getObjectives()); exit;

                                                foreach($surrender->Imprest_Surrender_Line->Imprest_Surrender_Line as $line):
                                                ?>
                                                    <tr>

                                                            <td class="text-center"><?= !empty($line->Account_Name)? $line->Account_Name : '' ?></td>
                                                            <td class="text-center"><?= !empty($line->Description)? $line->Description : '' ?></td>
                                                            <td data-key="<?= $line->Key ?>" data-name="Amount" data-service="ImprestSurrenderLine"><?= !empty($line->Amount)?$line->Amount:'' ?></td>
                                                            <td class="text-center"><?= !empty($line->Imprest_Amount)? $line->Imprest_Amount : '' ?></td>
                                                            <td class="text-center"><?= !empty($line->Request_No)? $line->Request_No : '' ?></td>
                                                            <td class="text-center"><?= Html::checkbox('Surrender',$line->Surrender) ?></td>
                                                            <td data-key="<?= $line->Key ?>" data-name="Donor_No" data-service="ImprestSurrenderLine" ondblclick="addDropDown(this,'donors',{'Grant_No': 'grant','Amount':'amount'})"  class="text-center"><?= !empty($line->Donor_No)? $line->Donor_No : '' ?></td>
                                                            <td class="text-center"><?= !empty($line->Donor_Name)? $line->Donor_Name : '' ?></td>
                                                            <td data-key="<?= $line->Key ?>"   class="text-center grant"><?= !empty($line->Grant_No)? $line->Grant_No : '' ?></td>
                                                            <td data-key="<?= $line->Key ?>" ><?= !empty($line->Objective_Code)?$line->Objective_Code:'' ?></td>
                                                            <td data-key="<?= $line->Key ?>" ><?= !empty($line->Output_Code)?$line->Output_Code:'' ?></td>
                                                            <td data-key="<?= $line->Key ?>" ><?= !empty($line->Outcome_Code)?$line->Outcome_Code:'' ?></td>
                                                            <td data-key="<?= $line->Key ?>" ><?= !empty($line->Activity_Code)?$line->Activity_Code:'' ?></td>
                                                            <td data-key="<?= $line->Key ?>" ><?= !empty($line->Partner_Code)?$line->Partner_Code:'' ?></td>
                                                            
                                                    </tr>
                                                <?php endforeach; ?>
                                                </tbody>
                                            </table>
                    </div>
                    <?php } ?>
                </div>
            </div>

            <!--objectives card -->








        </>
    </div>

    <!--My Bs Modal template  --->

    <div class="modal fade bs-example-modal-lg bs-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel" style="position: absolute">Leave Plan</h4>
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


<?php

$script = <<<JS

    $(function(){
      
        
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
      
    
    /*Evaluate KRA*/
        $('.evalkra').on('click', function(e){
             e.preventDefault();
            var url = $(this).attr('href');
            console.log('clicking...');
            $('.modal').modal('show')
                            .find('.modal-body')
                            .load(url); 

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
     
     
     //Update a training plan
    
     $('.update-trainingplan').on('click',function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        console.log('clicking...');
        $('.modal').modal('show')
                        .find('.modal-body')
                        .load(url); 

     });
     
     
     //Update/ Evalute Employeeappraisal behaviour -- evalbehaviour
     
      $('.evalbehaviour').on('click',function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        console.log('clicking...');
        $('.modal').modal('show')
                        .find('.modal-body')
                        .load(url); 

     });
      
      /*Add learning assessment competence-----> add-learning-assessment */
      
      
      $('.add-learning-assessment').on('click',function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        console.log('clicking...');
        $('.modal').modal('show')
                        .find('.modal-body')
                        .load(url); 

     });
      
      
     
      
      
      
    
    /*Handle modal dismissal event  */
    $('.modal').on('hidden.bs.modal',function(){
        var reld = location.reload(true);
        setTimeout(reld,1000);
    }); 
        
    /*Parent-Children accordion*/ 
    
    $('tr.parent').find('span').text('+');
    $('tr.parent').find('span').css({"color":"red", "font-weight":"bolder"});    
    $('tr.parent').nextUntil('tr.parent').slideUp(1, function(){});    
    $('tr.parent').click(function(){
            $(this).find('span').text(function(_, value){return value=='-'?'+':'-'}); //to disregard an argument -event- on a function use an underscore in the parameter               
            $(this).nextUntil('tr.parent').slideToggle(100, function(){});
     });
    
    /*Divs parenting*/
    
     $('p.parent').find('span').text('+');
    $('p.parent').find('span').css({"color":"red", "font-weight":"bolder"});    
    $('p.parent').nextUntil('p.parent').slideUp(1, function(){});    
    $('p.parent').click(function(){
            $(this).find('span').text(function(_, value){return value=='-'?'+':'-'}); //to disregard an argument -event- on a function use an underscore in the parameter               
            $(this).nextUntil('p.parent').slideToggle(100, function(){});
     });
    
        //Add Career Development Plan
        
        $('.add-cdp').on('click',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
           
            
            console.log('clicking...');
            $('.modal').modal('show')
                            .find('.modal-body')
                            .load(url); 
            
         });//End Adding career development plan
         
         /*Add Career development Strength*/
         
         
        $('.add-cds').on('click',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            
            $('.modal').modal('show')
                            .find('.modal-body')
                            .load(url); 
            
         });
         
         /*End Add Career development Strength*/
         
         
         /* Add further development Areas */
         
            $('.add-fda').on('click',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
                       
            console.log('clicking...');
            $('.modal').modal('show')
                            .find('.modal-body')
                            .load(url); 
            
         });
         
         /* End Add further development Areas */
         
         /*Add Weakness Development Plan*/
             $('.add-wdp').on('click',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
                       
            console.log('clicking...');
            $('.modal').modal('show')
                            .find('.modal-body')
                            .load(url); 
            
         });
         /*End Add Weakness Development Plan*/

         //Change Action taken

         $('select#probation-action_taken').on('change',(e) => {

            const key = $('input[id=Key]').val();
            const Employee_No = $('input[id=Employee_No]').val();
            const Appraisal_No = $('input[id=Appraisal_No]').val();
            const Action_Taken = $('#probation-action_taken option:selected').val();
           
              

            /* var data = {
                "Action_Taken": Action_Taken,
                "Appraisal_No": Appraisal_No,
                "Employee_No": Employee_No,
                "Key": key

             } 
            */
            $.get('./takeaction', {"Key":key,"Appraisal_No":Appraisal_No, "Action_Taken": Action_Taken,"Employee_No": Employee_No}).done(function(msg){
                 $('.modal').modal('show')
                    .find('.modal-body')
                    .html(msg.note);
                });


            });
    
        
    });//end jquery

    

        
JS;

$this->registerJs($script);

$style = <<<CSS
    p span {
        margin-right: 50%;
        font-weight: bold;
    }

    table td:nth-child(11), td:nth-child(12) {
                text-align: center;
    }
    
    /* Table Media Queries */
    
     @media (max-width: 500px) {
          table td:nth-child(2),td:nth-child(3),td:nth-child(6),td:nth-child(7),td:nth-child(8),td:nth-child(9),td:nth-child(10), td:nth-child(11) {
                display: none;
        }
    }
    
     @media (max-width: 550px) {
          table td:nth-child(2),td:nth-child(6),td:nth-child(7),td:nth-child(8),td:nth-child(9),td:nth-child(10), td:nth-child(11) {
                display: none;
        }
    }
    
    @media (max-width: 650px) {
          table td:nth-child(2),td:nth-child(6),td:nth-child(7),td:nth-child(8),td:nth-child(9),td:nth-child(10), td:nth-child(11) {
                display: none;
        }
    }


    @media (max-width: 1500px) {
          table td:nth-child(2),td:nth-child(7),td:nth-child(8),td:nth-child(9),td:nth-child(10), td:nth-child(11) {
                display: none;
        }
    }
CSS;

$this->registerCss($style);
