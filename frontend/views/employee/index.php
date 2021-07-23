<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 2/26/2020
 * Time: 5:41 AM
 */




use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->title = 'KWTRP - Employee Profile'
?>
<h2 class="title">Employee : <?= $model->No.' - '. $model->First_Name. ' '. $model->Last_Name?></h2>

<?php


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
    echo Yii::$app->session->getFlash('success');
    print '</div>';
}


// Yii::$app->recruitment->printrr($model->beneficiaries);
?>
<div class="row">
    <div class="col-md-12">
        <?php $form = ActiveForm::begin(['action'=> ['leave/create']]); ?>
        <div class="card collapsed-card">
            <div class="card-header">
                <h3 class="card-title">General Details</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">

                <div class="row">
                    <div class=" row col-md-12">
                        <div class="col-md-6">
                            <?= $form->field($model, 'No')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'First_Name')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Middle_Name')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Last_Name')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Full_Name')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Gender')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'County_of_Origin')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Sub_County')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Location')->textInput(['readonly'=> true, 'disabled'=>true]) ?>




                        </div>
                        <div class="col-md-6">

                            <?= $form->field($model, 'Sub_Location')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Village')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'National_ID')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Passport_Number')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Marital_Status')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Ethnic_Origin')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Religion')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Driving_License')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Health_Conditions')->textInput(['readonly'=> true, 'disabled'=>true]) ?>


                        </div>
                    </div>
                </div>







            </div>
        </div>



        <div class="card collapsed-card">
            <div class="card-header">
                <h3 class="card-title">Communication</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">

                <div class="row">
                    <div class=" row col-md-12">
                        <div class="col-md-6">
                            <?= $form->field($model, 'Phone_No')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Alternative_Phone_No')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'E_Mail')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Company_E_Mail')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'City')->textInput(['readonly'=> true, 'disabled'=>true]) ?>


                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'Address')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Post_Code')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Address_2')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'ShowMap')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Alt_Address_Code')->textInput(['readonly'=> true, 'disabled'=>true]) ?>

                        </div>
                    </div>
                </div>







            </div>
        </div>



        <div class="card collapsed-card">
            <div class="card-header">
                <h3 class="card-title"> Important Dates</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">

                <div class="row">
                    <div class=" row col-md-12">
                        <div class="col-md-6">
                            <?= $form->field($model, 'Birth_Date')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Age')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Employment_Date')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Service_Period')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Period_To_Retirement')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'End_of_Probation_Period')->textInput(['readonly'=> true, 'disabled'=>true]) ?>



                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'Probabtion_Extended_By')->checkbox(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'New_Probation_Period_End_Date')->checkbox(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Reasons_For_Extension')->checkbox(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Contract_Start_Date')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Contract_End_Date')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Date_of_joining_Medical_Scheme')->textInput(['readonly'=> true, 'disabled'=>true]) ?>

                        </div>
                    </div>
                </div>







            </div>
        </div>

        <div class="card collapsed-card">
            <div class="card-header">
                <h3 class="card-title">Job Details</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">

                <div class="row">
                    <div class=" row col-md-12">
                        <div class="col-md-6">
                            <?= $form->field($model, 'Grade')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Pointer')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?php $form->field($model, 'Job_Title')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Job_Description')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Division_Name')->textInput(['readonly'=> true, 'disabled'=>true]) ?>

                            <?= $form->field($model, 'Department_Name')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Section_Name')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Unit_Name')->textInput(['readonly'=> true, 'disabled'=>true]) ?>



                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'Probation_Period_Extended')->checkbox(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Notice_Period')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Probation_Status')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Line_Manager_Name')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Overview_Manager_Name')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Grant_Approver_Name')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Long_Term')->checkbox(['readonly'=> true, 'disabled'=>true]) ?>

                        </div>
                    </div>
                </div>







            </div>
        </div>

        <div class="card collapsed-card">
            <div class="card-header">
                <h3 class="card-title">Termination Details</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">

                <div class="row">
                    <div class=" row col-md-12">
                        <div class="col-md-6">
                            <?= $form->field($model, 'Cause_of_Inactivity_Code')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Termination_Date')->textInput(['readonly'=> true, 'disabled'=>true]) ?>


                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'Grounds_for_Term_Code')->textInput(['readonly'=> true, 'disabled'=>true]) ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--Administration-->

        <div class="card collapsed-card">
            <div class="card-header">
                <h3 class="card-title">Administration Details</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">

                <div class="row">
                    <div class=" row col-md-12">
                        <div class="col-md-6">
                            <?= $form->field($model, 'Suspend_Leave_Application')->checkbox(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Nature_Of_Employment')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Disabled')->checkbox(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Line_Manager_Name')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Grant_Approver_Name')->textInput(['readonly'=> true, 'disabled'=>true]) ?>

                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'Disability_Id')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Status')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Covered_Medically')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Overview_Manager_Name')->textInput(['readonly'=> true, 'disabled'=>true]) ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!--Payments-->


        <div class="card collapsed-card">
            <div class="card-header">
                <h3 class="card-title">Payment Details</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">

                <div class="row">
                    <div class=" row col-md-12">
                        <div class="col-md-6">
                            <?= $form->field($model, 'Payment_Methods')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Currency')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'KRA_Number')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'NHIF_Number')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'NSSF_Number')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Employee_Posting_Group')->textInput(['readonly'=> true, 'disabled'=>true]) ?>

                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'Bank_Code')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Bank_Name')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Bank_Branch_No')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Branch_Name')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Bank_Account_No')->textInput(['readonly'=> true, 'disabled'=>true]) ?>


                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Dependants -->


        <!-- Emergency Contacts -->

       


        <!-- Beneficiaries -->

        


        <!-- Qualifications -->


        



        <!-- Qualifications -->


        <!-- Work Permits If Expertriate -->



        <!-- /Work Permit  -->











        <?php ActiveForm::end(); ?>
    </div>
</div>
