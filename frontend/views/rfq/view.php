<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 2/24/2020
 * Time: 6:09 PM
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
$no = property_exists($model[0],'RFQ_No')?$model[0]->RFQ_No:'';
$this->title = 'RFQ Evalution - '.$no;
$this->params['breadcrumbs'][] = ['label' => 'Sent RFQ List   ', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'RFQ Commitee Evaluation ', 'url' => ['#']];
$absoluteUrl = \yii\helpers\Url::home(true);
/** Status Sessions */


/* Yii::$app->session->set('MY_Appraisal_Status',$model->MY_Appraisal_Status);
Yii::$app->session->set('EY_Appraisal_Status',$model->EY_Appraisal_Status);
Yii::$app->session->set('isSupervisor',false);*/
?>

<div class="row">
    <div class="col-md-4">

       


      
    </div>
</div>

    <div class="row">
        <div class="col-md-12">
            <div class="card-info">
                <div class="card-header">
                    <h3>RFQ Commitee Evaluation </h3>
                </div>



            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">




                    <h3 class="card-title">RFQ No : <?= $no ?></h3>



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
                        <div class="col-md-12">
                            <table class="table table-bordered w-100" >
                                <thead>
                                    <tr>
                                        <td class="font-weight-bold">Description</td>
                                        <td class="font-weight-bold">Vendor Name</td>
                                        <td class="font-weight-bold">Committee Member ID</td>
                                        <td class="font-weight-bold">Quoted Amount (Ksh.)</td>
                                        <td class="font-weight-bold">Award</td>
                                        <td class="font-weight-bold">Remarks</td>
                                        <td class="font-weight-bold">Action</td>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php if(is_string($model)): ?>
                                    <tr>
                                        <td colspan="6" align="center"><?= $model ?></td>
                                    </tr>

                                    <?php elseif(is_array($model)): 
                                    
                                    foreach($model as $m):
                                         ?>
                                    <tr>
                                        <td><?= $m->Description ?></td>
                                        <td><?= $m->Vendor_Name ?></td>
                                        <td><?=$m->Committee_Member_ID ?></td>
                                        <td><?= number_format($m->Quoted_Amount) ?></td>
                                        <td><?= Html::checkbox( 'Award', $m->Award, ['rev'=> $m->Key, 'id' => 'Award_'.$m->Vendor_No]) ?></td>
                                        <td><?= Html::textarea('Remarks',property_exists($m,'Remarks')?$m->Remarks:'',['rows' => 2,'id' => 'Remarks_'.$m->Vendor_No]) ?></td>
                                        <td><?= Html::button('<i class="fa fa-check"></i>Submit', ['class' => 'btn btn-success','id' => $m->Vendor_No, 'rel' => $m->Key ])?></td>
                                    </tr>

                                    <?php
                                    endforeach;
                                 endif; ?>r
                                </tbody>
                            </table>
                        </div>
                    </div>




                    <?php ActiveForm::end(); ?>



                </div>
            </div><!--end details card-->

            <!--Lines -->

           

            <!--End Lines -->

    </div>

    <!--My Bs Modal template  --->

    <div class="modal fade bs-example-modal-lg bs-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel" style="position: absolute">Procurement Management</h4>
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

    $(function(){
      

        // Vote to award vendor

        $('button').on('click',(e) => {
           if(confirm("Do you know what you are doing ?"))
           {
                let Key = e.target.getAttribute('rel');
                let No = e.target.getAttribute('id');
                let Award = $("#Award_"+No).prop("selected", false)[0].checked;
                let Remarks = $("#Remarks_"+No).val();
               

                // console.log(Key); 

                const Payload = {
                    Award,
                    Remarks,
                    Key,
                    No
                };

                console.log(Payload); 
                const url = $('input[name=url]').val()+'rfq/vote';
                $.post(url, Payload).done(function(msg){
                    $('.modal').modal('show')
                            .find('.modal-body')
                            .html(msg.note);
                },'json');


           }
        });
      
    
    /*Handle modal dismissal event  */
    $('.modal').on('hidden.bs.modal',function(){
        var reld = location.reload(true);
        setTimeout(reld,1000);
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
    
    /* Table Media Queries 
    
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
    }*/
CSS;

$this->registerCss($style);
