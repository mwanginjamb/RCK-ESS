<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Request password reset';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-request-password-reset">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out your email. A link to reset password will be sent there.</p>

    <div class="row">
        <div class="col-lg-12">
            <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>

                <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

                <div class="form-group">
                    <?= Html::submitButton('Send', ['class' => 'btn btn-primary']) ?>
                </div>

            <?= Html::a('<i class="fa fa-arrow-left"></i> Back to Login Page',['login'],['class' => '']) ?>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<?php
$directory = Yii::getAlias('@frontend').'/web/background';
@chdir($directory);
$images = glob("*.{jpg,JPG,jpeg,JPEG,png,PNG}", GLOB_BRACE);


$random_img = $images[array_rand($images)];



$style = <<<CSS
    .login-page { 
          background: url("../../background/$random_img") no-repeat center center fixed; 
          -webkit-background-size: cover;
          -moz-background-size: cover;
          -o-background-size: cover;
          background-size: cover;
         

    }


    .top-logo {
        display: flex;
        margin-left: 10px;
       
    }
     .top-logo img { 
                width: 120px;
                height: auto;
                position: absolute;
                left: 15px;
                top:15px;
                
          
            }
     .login-logo a  {
        color: #ffffff!important;
        font-family: sans-serif, Verdana;
        font-size: larger;
        font-weight: 400;
        text-shadow: 2px 2px 8px #21baff;

     }

    input.form-control {
        border-left: 0!important;
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        border: 1px solid #f6c844;
    }
    
    span.input-group-text {
        border-right: 0;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        border: 1px solid #f6c844;
    }
    
   .card {
    background-color: rgba(0,0,0,.6);
   }
   
   .login-card-body {
     background-color: rgba(0,0,0,.1);
   }

    
    
CSS;

$this->registerCss($style);
