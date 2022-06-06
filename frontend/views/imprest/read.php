<?php

/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 2/26/2020
 * Time: 10:59 PM
 */



/* @var $this yii\web\View */

$this->title = 'HRMIS - Document Reader ';
$this->params['breadcrumbs'][] = ['label' => 'imprest', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Attachment', 'url' => ['print-imprest', 'DocNo' => $No]];
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Document Attachment <?= \yii\helpers\Html::a('<i class="fa fa-backward"></i> Back to Document', ['view', 'DocNo' => $No], ['class' => 'btn btn-outline-warning mx-4']) ?></h3>

            </div>
            <div class="card-body">

                <!--<iframe src="data:application/pdf;base64,<?/*= $content; */ ?>" height="950px" width="100%"></iframe>-->
                <?php

                if ($content) { ?>

                    <iframe src="<?= $content; ?>" height="950px" width="100%"></iframe>
                <?php } ?>






            </div>
        </div>
    </div>
</div>

<?php
