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
                                    <?= $form->field($model, 'Type')->dropDownList([
                                            'G_L_Account' => 'G_L_Account',
                                            'Fixed_Asset' => 'Fixed_Asset',
                                            'Item' => 'Item'
                                        ],
                                        [
                                                'prompt' => 'Select Type ...',
                                                'onchange' => '$.post("../purchase-requisitionline/no-dd?type="+$(this).val(), (data) => {
                                                $("select#purchaserequisitionline-no").html( data );
                                                    
                                            })'
                                         ]); ?>
                                    <?= $form->field($model, 'No')->dropDownList([], ['prompt' => 'Select Item...']) ?>
                                    <?= $form->field($model, 'Name')->textInput(['disabled' => true, 'readonly' => true]) ?>

                                    <?= $form->field($model, 'Quantity')->textInput(['type' => 'number']) ?>
                                    <?= $form->field($model, 'Location')->dropDownList($locations, ['prompt' => 'Select Location...']) ?>
                                    
                                    <?= $form->field($model, 'Estimate_Unit_Price')->textInput() ?>
                                   
                                   

                                    
                                    
                                </div>
                                
                                <div class="col-md-6">
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'Global_Dimension_1_Code')->dropDownList($subOffices, ['prompt' => 'Select ...']) ?>
                                            <?= $form->field($model, 'Global_Dimension_2_Code')->dropDownList($programCodes, ['prompt' => 'Select ...']) ?>
                                            
                                            <?= $form->field($model, 'Estimate_Total_Amount')->textInput(['readonly' => true, 'disabled' =>  true]) ?>
                                            <?= $form->field($model, 'Procurement_Method')->dropDownList([
                                                '_blank_' => '_blank_',
                                                'Tender' => 'Tender',
                                                'RFQ' => 'RFQ',
                                                'Direct_Procurement' => 'Direct_Procurement',
                                                'RFP' => 'RFP',
                                            ],['prompt' => 'Select ...']) ?>
                                    <?= $form->field($model, 'Description')->textarea(['rows' => 2, 'required'=> true]) ?>
                                    <?= $form->field($model, 'Requisition_No')->textInput(['readonly' => true]) ?>
                                </div>
                                        <div class="col-md-6">

                                                    <?= $form->field($model, 'Donor_No')->dropDownList($donors,[
                                                    'prompt' => 'Select...'
                                                    ]) ?>

                                                    <?= $form->field($model, 'Grant_No')->dropDownList($grants,[
                                                        'prompt' => 'Select...',
                                                        'onchange' => '
                                                                $.post( "' . Yii::$app->urlManager->createUrl('imprestline/objectives?Grant_No=') . '"+$(this).val(), function( data ) {

                                                                    $( "select#purchaserequisitionline-objective_code" ).html( data );
                                                                });
                                                    '
                                                        ]) ?>

                                                    <?= $form->field($model, 'Objective_Code')->dropDownList(
                                                        $objectiveCode,
                                                        [
                                                            'prompt' => 'Select...',
                                                            'onchange' => '
                                                                $.post( "' . Yii::$app->urlManager->createUrl('imprestline/outputs?Grant_No=') . '"+$("#purchaserequisitionline-grant_no").val(), function( data ) {

                                                                    $( "select#purchaserequisitionline-output_code" ).html(data);
                                                                });
                                                            '
                                                            ]) ?>



                                                            <?= $form->field($model, 'Output_Code')->dropDownList(
                                                                                                $outputCode,
                                                                                                [
                                                                                                    'prompt' => 'Select...',
                                                                                                    'onchange' => '
                                                                                                        $.post( "' . Yii::$app->urlManager->createUrl('imprestline/outcome?Grant_No=') . '"+$("#purchaserequisitionline-grant_no").val(), function( data ) {

                                                                                                            $( "select#purchaserequisitionline-outcome_code" ).html(data);
                                                                                                        });
                                                                                                    '
                                                                ]) ?>


                                                            <?= $form->field($model, 'Outcome_Code')->dropDownList(
                                                                $outcomeCode,
                                                                [
                                                                    'prompt' => 'Select...',
                                                                    'onchange' => '
                                                                        $.post( "' . Yii::$app->urlManager->createUrl('imprestline/activities?Grant_No=') . '"+$("#purchaserequisitionline-grant_no").val(), function( data ) {

                                                                            $( "select#purchaserequisitionline-activity_code" ).html(data);
                                                                        });
                                                                    '
                                                            ]) ?>


                                                    <?= $form->field($model, 'Activity_Code')->dropDownList(
                                                        $activityCode,[
                                                            'prompt' => 'Select...',
                                                            'onchange' => '
                                                                    $.post( "' . Yii::$app->urlManager->createUrl('imprestline/partners?Grant_No=') . '"+$("#purchaserequisitionline-grant_no").val(), function( data ) {

                                                                        $( "select#purchaserequisitionline-partner_code" ).html(data);
                                                                    });
                                                            '
                                                    ]) ?>


                                                    <?= $form->field($model, 'Partner_Code')->dropDownList($partnerCode,['prompt' => 'Select...']) ?>

                                        </div>
                                    </div>
                                

                            </div>

                                <?= $form->field($model, 'Key')->hiddenInput(['readonly'=> true])->label(false) ?>
                                <?= $form->field($model, 'Line_No')->hiddenInput(['readonly'=> true,'disabled' => true])->label(false) ?>
                                <?= $form->field($model, 'Requisition_No')->hiddenInput(['readonly'=> true])->label(false) ?>



                </div>

                <div class="row">

                    <div class="form-group">
                        <?php Html::submitButton(($model->isNewRecord)?'Save':'Update', ['class' => 'btn btn-success','id'=>'submit']) ?>
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

    $('#purchaserequisitionline-type').blur((e) => {
        globalFieldUpdate('purchaserequisitionline','purchase-requisitionline','Type', e);
    }); 
    
    $('#purchaserequisitionline-no').change((e) => {
        globalFieldUpdate('purchaserequisitionline','purchase-requisitionline','No', e,['Name']);
    });  
    
    $('#purchaserequisitionline-location').change((e) => {
        globalFieldUpdate('purchaserequisitionline','purchase-requisitionline','Location', e);
    });

    $('#purchaserequisitionline-quantity').change((e) => {
        globalFieldUpdate('purchaserequisitionline','purchase-requisitionline','Quantity', e);
    });

    $('#purchaserequisitionline-estimate_unit_price').change((e) => {
        globalFieldUpdate('purchaserequisitionline','purchase-requisitionline','Estimate_Unit_Price', e,['Estimate_Total_Amount']);
    });

    $('#purchaserequisitionline-procurement_method').change((e) => {
        globalFieldUpdate('purchaserequisitionline','purchase-requisitionline','Procurement_Method', e);
    });

    $('#purchaserequisitionline-description').change((e) => {
        globalFieldUpdate('purchaserequisitionline','purchase-requisitionline','Description', e);
    });

    $('#purchaserequisitionline-quantity').change((e) => {
        globalFieldUpdate('purchaserequisitionline','purchase-requisitionline','Quantity', e);
    });

    $('#purchaserequisitionline-global_dimension_1_code').change((e) => {
        globalFieldUpdate('purchaserequisitionline','purchase-requisitionline','Global_Dimension_1_Code', e);
    });

    $('#purchaserequisitionline-global_dimension_2_code').change((e) => {
        globalFieldUpdate('purchaserequisitionline','purchase-requisitionline','Global_Dimension_2_Code', e);
    });

  /**Grants Fields */

  $('#purchaserequisitionline-donor_no').change((e) => {
        globalFieldUpdate('purchaserequisitionline','purchase-requisitionline','Donor_No', e);
    });


    $('#purchaserequisitionline-objective_code').change((e) => {
        globalFieldUpdate('purchaserequisitionline','purchase-requisitionline','Objective_Code', e);
    });

    $('#purchaserequisitionline-outcome_code').change((e) => {
        globalFieldUpdate('purchaserequisitionline','purchase-requisitionline','Outcome_Code', e);
    });

    $('#purchaserequisitionline-activity_code').change((e) => {
        globalFieldUpdate('purchaserequisitionline','purchase-requisitionline','Activity_Code', e);
    });

    $('#purchaserequisitionline-output_code').change((e) => {
        globalFieldUpdate('purchaserequisitionline','purchase-requisitionline','Output_Code', e);
    });

    $('#purchaserequisitionline-partner_code').change((e) => {
        globalFieldUpdate('purchaserequisitionline','purchase-requisitionline','Partner_Code', e);
    });

    $('#purchaserequisitionline-grant_no').change((e) => {
        globalFieldUpdate('purchaserequisitionline','purchase-requisitionline','Grant_No', e);
    });
JS;

$this->registerJs($script);
