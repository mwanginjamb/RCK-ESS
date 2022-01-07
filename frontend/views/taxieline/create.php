<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 2/24/2020
 * Time: 12:29 PM
 */

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\AgendaDocument */

$this->title = 'Add Line';

$this->params['breadcrumbs'][] = $this->title;


$model->isNewRecord = true;
//$model->Booking_Date = date('Y-m-d');
//$model->End_Date = date('m-d-Y');
$model->Travel_Date = (date('Y',strtotime($model->Travel_Date)) == '0001')?$model->Travel_Date = date('Y-m-d'):$model->Travel_Date;
?>




<div class="card">
    <div class="card-header">
        <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="card-body">
        <?= $this->render('_form', [
            'model' => $model,
            'vehicles' => $vehicles,
            'jobs' => $jobs,
            'jobTasks' => $jobTasks,
            'glAccounts' => $glAccounts,
            'requestTypes' => $requestTypes,
            'vendors' =>  $vendors
        ]) ?>
    </div>
</div>



