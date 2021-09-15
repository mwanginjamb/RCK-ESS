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


if(Yii::$app->session->hasFlash('success')){
    print ' <div class="alert alert-success alert-dismissable">
                             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5><i class="icon fas fa-check"></i> Success!</h5>
 ';
    echo Yii::$app->session->getFlash('success');
    print '</div>';
}else if(Yii::$app->session->hasFlash('error')){
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

        <?= ($model->Status == 'New')?Html::a('<i class="fas fa-paper-plane"></i> Send Approval Req',['send-for-approval','employeeNo' => Yii::$app->user->identity->{'Employee_No'}],['class' => 'btn btn-app submitforapproval',
            'data' => [
                'confirm' => 'Are you sure you want to send imprest request for approval?',
                'params'=>[
                    'No'=> $model->No,
                    'employeeNo' => Yii::$app->user->identity->Employee[0]->No,
                ],
                'method' => 'get',
        ],
            'title' => 'Submit Imprest Approval'

        ]):'' ?>


        <?= ($model->Status == 'Pending_Approval')?Html::a('<i class="fas fa-times"></i> Cancel Approval Req.',['cancel-request'],['class' => 'btn btn-app submitforapproval',
            'data' => [
            'confirm' => 'Are you sure you want to cancel imprest approval request?',
            'params'=>[
                'No'=> $model->No,
            ],
            'method' => 'get',
        ],
            'title' => 'Cancel Imprest Approval Request'

        ]):'' ?>


        <?= Html::a('<i class="fas fa-file-pdf"></i> Print Imprest',['print-imprest'],['class' => 'btn btn-app ',
            'data' => [
                'confirm' => 'Print Imprest?',
                'params'=>[
                    'No'=> $model->No,
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

                            <?= $form->field($model, 'No')->textInput(['readonly'=> true]) ?>
                            <?= $form->field($model, 'Key')->hiddenInput()->label(false) ?>

                            <?php if($model->Request_For == 'Self'): ?>
                                <?= $form->field($model, 'Employee_No')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?php else: ?>
                                <?= $form->field($model, 'Employee_No')->dropDownList($employees,['prompt'=> 'Select Employee']) ?>
                            <?php endif; ?>
                            <?= $form->field($model, 'Employee_Name')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Purpose')->textInput() ?>
                            <?= '<p><span>Employee Balance</span> '.Html::a($model->Employee_Balance,'#'); '</p>' ?>
                            <?= '<p><span>Imprest Amount</span> '.Html::a($model->Imprest_Amount,'#'); '</p>'?>



                        </div>

                        <div class="col-md-6">
                            <?= '<p><span> Amount LCY</span> '.Html::a($model->Amount_LCY,'#'); '</p>'?>
                            <?= $form->field($model, 'Status')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Global_Dimension_1_Code')->dropDownList($programs,['prompt' => 'Select ..']) ?>
                            <?= $form->field($model, 'Global_Dimension_2_Code')->dropDownList($departments, ['prompt' => 'select ...']) ?>
                            <?= $form->field($model, 'Expected_Date_of_Surrender')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Imprest_Type')->dropDownList(['Local' => 'Local', 'International' => 'International'],['prompt' => 'Select ...']) ?>


                            <?php if($model->Imprest_Type == 'International'): ?>

                                <?= $form->field($model, 'Currency_Code')->dropDownList($currencies,['prompt' => 'Select ...','required' => true]) ?>
                                <?= $form->field($model, 'Exchange_Rate')->textInput(['type'=> 'number','required' => true]) ?>

                            <?php endif; ?>

<!--                            <p class="parent"><span>+</span>-->



                            </p>



                        </div>



                    </div>




                </div>












               <!-- <div class="row">

                    <div class="form-group">
                        <?php Html::submitButton(($model->isNewRecord)?'Save':'Update', ['class' => 'btn btn-success']) ?>
                    </div>


                </div>-->
                <?php ActiveForm::end(); ?>
            </div>
        </div>


        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <?= ($model->Status == 'New')?Html::a('<i class="fa fa-plus-square"></i> New Imprest Line',['imprestline/create','Request_No'=>$model->No],['class' => 'add-line btn btn-outline-info',
                    ]):'' ?>
                </div>
            </div>

            <div class="card-body">





                <?php if(is_array($model->getLines($model->No))){ //show Lines ?>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <td><b>Transaction Type</b></td>
                            <td><b>Account No</b></td>
                            <td><b>Account Name</b></td>
                            <td><b>Description</b></td>
                            <td><b>Amount</b></td>
                            <td><b>Amount LCY</b></td>
                            <td><b>Budgeted Amount</b></td>
                            <td><b>Commited Amount</b></td>
                            <td><b>Total_Expenditure</b></td>
                            <td><b>Available Amount</b></td>
                            <td><b>Unbudgeted?</b></td>
                            <td><b>Actions</b></td>


                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        // print '<pre>'; print_r($model->getObjectives()); exit;

                        foreach($model->getLines($model->No) as $obj):
                            $updateLink = Html::a('<i class="fa fa-edit"></i>',['imprestline/update','Key'=> $obj->Key],['class' => 'update-objective btn btn-outline-info btn-xs']);
                            $deleteLink = Html::a('<i class="fa fa-trash"></i>',['imprestline/delete','Key'=> $obj->Key ],['class'=>'delete btn btn-outline-danger btn-xs']);
                            ?>
                            <tr>

                                <td><?= !empty($obj->Transaction_Type)?$obj->Transaction_Type:'Not Set' ?></td>
                                <td><?= !empty($obj->Account_No)?$obj->Account_No:'Not Set' ?></td>
                                <td><?= !empty($obj->Account_Name)?$obj->Account_Name:'Not Set' ?></td>
                                <td><?= !empty($obj->Description)?$obj->Description:'Not Set' ?></td>
                                <td><?= !empty($obj->Amount)?$obj->Amount:'Not Set' ?></td>
                                <td><?= !empty($obj->Amount_LCY)?$obj->Amount_LCY:'Not Set' ?></td>
                                <td><?= !empty($obj->Budgeted_Amount)?$obj->Budgeted_Amount:'Not Set' ?></td>
                                <td><?= !empty($obj->Commited_Amount)?$obj->Commited_Amount:'Not Set' ?></td>
                                <td><?= !empty($obj->Total_Expenditure)?$obj->Total_Expenditure:'Not Set' ?></td>
                                <td><?= !empty($obj->Available_Amount)?$obj->Available_Amount:'Not Set' ?></td>
                                <td><?= Html::checkbox('Unbudgeted',$obj->Unbudgeted) ?></td>
                                <td><?= $updateLink.'|'.$deleteLink ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
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

        // Set other Employee
  
     $('#imprestcard-employee_no').change((e) => {
        updateField('Imprestcard','Employee_No', e);
    });


     
     /*Set Program  */
    

     $('#imprestcard-global_dimension_1_code').change((e) => {
        updateField('Imprestcard','Global_Dimension_1_Code', e);
    });
     
     
     /* set department */
     
     $('#imprestcard-global_dimension_2_code').change((e) => {
        updateField('Imprestcard','Global_Dimension_2_Code', e);
    });

    /**Update Purpose */

    $('#imprestcard-purpose').change((e) => {
        updateField('Imprestcard','Purpose', e);
    }); 
     
     
     /*Set Imprest Type*/
     

     $('#imprestcard-imprest_type').change((e) => {
        updateField('Imprestcard','Imprest_Type', e);
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
                    const url = $('input[name=url]').val()+'imprest'+'/setfield?field='+fieldName;
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
     
     
     /* Add Line */
     $('.add-line, .update-objective').on('click', function(e){
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
