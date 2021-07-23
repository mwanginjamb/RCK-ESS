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



                    <?php  $form = ActiveForm::begin();      ?>
                <div class="row">
                   

                            <table class="table">
                                <tbody>

                             <div class="col-md-6">
                                     <?= $form->field($model, 'Body_Code')->dropDownList(
                                        $qualifications,
                                        ['prompt' => 'Select ...']
                                    ) ?>
                                    <?= $form->field($model, 'From_Date')->textInput(['type' => 'date']) ?>
                                   
                                   

                            </div>
                             <div class="col-md-6">
                                    <?= $form->field($model, 'Membership_No')->textInput(['maxlength' => 150]) ?>
                                    <?= $form->field($model, 'To_Date')->textInput(['type' => 'date']) ?>
                                   

                                    <?= $form->field($model, 'Action')->dropDownList(
                                        ['Retain' => 'Retain','Remove' => 'Remove','New_Addition' => 'New_Addition'],
                                        ['prompt' => 'Select ...']
                                    ) ?>
                            </div>
                                    <?= $form->field($model, 'Change_No')->hiddenInput(['readonly' => true])->label(false) ?>
                                    <?= $form->field($model, 'Key')->hiddenInput(['readonly'=> true])->label(false) ?>
                                    

                                </tbody>
                            </table>


                </div>


                <div class="row">

                    <div class="form-group">
                        <?= Html::submitButton(($model->isNewRecord)?'Save':'Update', ['class' => 'btn btn-success']) ?>
                    </div>


                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="url" value="<?= $absoluteUrl ?>">
<?php
$script = <<<JS
 //Submit Rejection form and get results in json    
        $('form').on('submit', function(e){
            e.preventDefault()
            const data = $(this).serialize();
            const url = $(this).attr('action');
            $.post(url,data).done(function(msg){
                    $('.modal').modal('show')
                    .find('.modal-body')
                    .html(msg.note);
        
                },'json');
        });




         $('#professionalchange-body_code').change(function(e){
         e.preventDefault();
          const Body_Code = e.target.value;
          const Change_No = $('#professionalchange-change_no').val();
          // Check if leave required an attachment or not
            const Vurl = $('input[name=url]').val()+'professionalchange/commit';
            $.get(Vurl,{"Body_Code": Body_Code, "Change_No": Change_No }).done(function(msg){
                console.log(msg);

                if(typeof msg == 'object') {
                    $('#professionalchange-key').val(msg.Key);
                }
                
            });
         
     });


JS;

$this->registerJs($script);
