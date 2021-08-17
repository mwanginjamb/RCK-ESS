<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 2/24/2020
 * Time: 12:31 PM
 */


use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\AgendaDocument */
$this->params['breadcrumbs'][] = ['label' => 'Work Tickets', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Update Work Ticket', 'url' => ['update','No' => $model->Work_Ticket_No]];
$this->title = 'Update Work Ticket';

?>
<div class="agenda-document-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form',[
        'model' => $model,
        'vehicles' =>  $vehicles,
        'departments' =>  $departments,
        'requisitions' => $requisitions,
        'fuel' => $fuel

    ]) ?>

</div>
