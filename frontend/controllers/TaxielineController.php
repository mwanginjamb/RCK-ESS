<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 3/9/2020
 * Time: 4:21 PM
 */

namespace frontend\controllers;
use frontend\models\Employeeappraisalkra;
use frontend\models\Experience;
use frontend\models\Leaveplanline;
use frontend\models\Vehiclerequisitionline;
use frontend\models\Weeknessdevelopmentplan;
use Yii;
use yii\filters\AccessControl;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\BadRequestHttpException;

use frontend\models\Taxieline;
use yii\web\Response;
use kartik\mpdf\Pdf;

class TaxielineController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup','index','create','update','delete','view'],
                'rules' => [
                    [
                        'actions' => ['signup','index'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout','index','create','update','delete','view'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
            'contentNegotiator' =>[
                'class' => ContentNegotiator::class,
                'only' => ['uu'],
                'formatParam' => '_format',
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                    //'application/xml' => Response::FORMAT_XML,
                ],
            ]
        ];
    }

    public function actionIndex(){

        return $this->render('index');

    }

    public function actionCreate($No){
       $service = Yii::$app->params['ServiceName']['TaxieLines'];
       $model = new Taxieline();

        if(Yii::$app->request->get('No') && !Yii::$app->request->post()){

                $model->Document_No = $No;
                $result = Yii::$app->navhelper->postData($service, $model);
                //Yii::$app->recruitment->printrr($result);

                Yii::$app->navhelper->loadmodel($result,$model);
        }
        

        if(Yii::$app->request->post() && Yii::$app->navhelper->loadpost(Yii::$app->request->post()['Taxieline'],$model) ){

            $filter = [
                'Document_No' => Yii::$app->request->get('No'),
                'Key' => $model->Key
            ];

            $refresh = Yii::$app->navhelper->readByKey($service,$model->Key);
            if(is_array($refresh))
            {
                $model->Key = $refresh[0]->Key;
            }

            // Yii::$app->recruitment->printrr($data);

            $result = Yii::$app->navhelper->updateData($service,$model);

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            // return $model;
            if(!is_string($result)){

                return ['note' => '<div class="alert alert-success">Requisition Line Created Successfully. </div>' ];
            }else{

                return ['note' => '<div class="alert alert-danger">Error Creating Requisition Line: '.$result.'</div>'];
            }

        }

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('create', [
                'model' => $model,
                'vehicles' => $this->getVehicles(),
                'jobs' => $this->getJob(),
                'jobTasks' => $this->getJobTask(),
                'glAccounts' =>  $this->getGlaccounts(),
                'requestTypes' =>  $this->getRequestTypes(),
                'vendors' =>  $this->getVendors(),
            ]);
        }

        return $this->render('create', [
            'model' => $model,
            'vehicles' => $this->getVehicles(),
            'jobs' => $this->getJob(),
            'jobTasks' => $this->getJobTask(),
            'glAccounts' =>  $this->getGlaccounts(),
            'requestTypes' =>  $this->getRequestTypes(),
            'vendors' =>  $this->getVendors(),
        ]);

    }


    public function actionUpdate(){
        $model = new Taxieline() ;
        $model->isNewRecord = false;
        $service = Yii::$app->params['ServiceName']['TaxieLines'];
       
        $result = Yii::$app->navhelper->readByKey($service,Yii::$app->request->get('No'));

        if(is_object($result)){
            //load nav result to model
            Yii::$app->navhelper->loadmodel($result,$model) ;
        }else{
            Yii::$app->recruitment->printrr($result);
        }


        if(Yii::$app->request->post() && Yii::$app->navhelper->loadpost(Yii::$app->request->post()['Taxieline'],$model) ){

           
            $refresh = Yii::$app->navhelper->readByKey($service, $model->Key);
            $model->Key = $refresh->Key;

            //Yii::$app->recruitment->printrr($model);

            $result = Yii::$app->navhelper->updateData($service,$model);

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if(!is_string($result)){

                return ['note' => '<div class="alert alert-success"> Line Updated Successfully. </div>' ];
            }else{

                return ['note' => '<div class="alert alert-danger">Error Updating Line: '.$result.'</div>'];
            }

        }

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('update', [
                'model' => $model,
                'vehicles' => $this->getVehicles(),
                'jobs' => $this->getJob(),
                'jobTasks' => $this->getJobTask(),
                'glAccounts' =>  $this->getGlaccounts(),
                'requestTypes' =>  $this->getRequestTypes(),
                'vendors' =>  $this->getVendors(),
            ]);
        }

        return $this->render('update',[
            'model' => $model,
                'vehicles' => $this->getVehicles(),
                'jobs' => $this->getJob(),
                'jobTasks' => $this->getJobTask(),
                'glAccounts' =>  $this->getGlaccounts(),
                'requestTypes' =>  $this->getRequestTypes(),
                'vendors' =>  $this->getVendors(),
        ]);
    }

    public function getVendors() 
    {
        $service = Yii::$app->params['ServiceName']['Vendors'];
        $result = Yii::$app->navhelper->getData($service);
        return Yii::$app->navhelper->refactorArray($result,'No','Name');
    }

    public function actionDelete(){
        $service = Yii::$app->params['ServiceName']['TaxieLines'];
        $result = Yii::$app->navhelper->deleteData($service,Yii::$app->request->get('Key'));
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if(!is_string($result)){
            return ['note' => '<div class="alert alert-success">Record Purged Successfully</div>'];
        }else{
            return ['note' => '<div class="alert alert-danger">Error Purging Record: '.$result.'</div>' ];
        }
    }

    // Get Taxie Request Types

    public function getRequestTypes()
     {
         $service = Yii::$app->params['ServiceName']['TaxieRequestTypes'];
         $result = \Yii::$app->navhelper->getData($service);
         return Yii::$app->navhelper->refactorArray($result,'Code','Code');
     }

     /*Get a list of all GL Accounts*/
     public function getGlaccounts()
     {
         $service = Yii::$app->params['ServiceName']['GLAccountList'];
         $filter = [];
         $result = \Yii::$app->navhelper->getData($service, $filter);
 
         return Yii::$app->navhelper->refactorArray($result,'No','Name');
     }

     // Get Job

     public function getJob()
     {
         $service = Yii::$app->params['ServiceName']['Jobs'];
         $filter = [];
         $result = \Yii::$app->navhelper->getData($service, $filter);
         return Yii::$app->navhelper->refactorArray($result,'No','Description');
     }
 
     // Get Job Task
 
     public function getJobTask()
     {
         $service = Yii::$app->params['ServiceName']['JobTaskLines'];
         $filter = [];
         $result = \Yii::$app->navhelper->getData($service, $filter);
         return Yii::$app->navhelper->refactorArray($result,'Job_Task_No','Description');
     }
 
 
     // Get Job Planning Lines
 
     public function actionPlanningDd($task_no,$job_no)
     {
         
             $service = Yii::$app->params['ServiceName']['JobPlanningLines'];
             $filter = [
                 'Job_No' => $job_no,
                 'Job_Task_No' => $task_no
             ];
             $result = \Yii::$app->navhelper->getData($service, $filter);
             $data =  Yii::$app->navhelper->refactorArray($result,'Line_No','Description');
 
         if(count($data) )
         {
             foreach($data  as $k => $v )
             {
                 echo "<option value='$k'>".$v."</option>";
             }
         }else{
             echo "<option value=''>No data Available</option>";
         }
     }
 

    public function actionSetstartdate(){
        $model = new Leaveplanline();
        $service = Yii::$app->params['ServiceName']['LeavePlanLine'];

        $filter = [
            'Line_No' => Yii::$app->request->post('Line_No')
        ];
        $line = Yii::$app->navhelper->getData($service, $filter);
        // Yii::$app->recruitment->printrr($line);
        if(is_array($line)){
            Yii::$app->navhelper->loadmodel($line[0],$model);
            $model->Key = $line[0]->Key;
            $model->Start_Date = date('Y-m-d',strtotime(Yii::$app->request->post('Start_Date')));

        }


        $result = Yii::$app->navhelper->updateData($service,$model);

        Yii::$app->response->format = \yii\web\response::FORMAT_JSON;

        return $result;

    }

    public function actionSetenddate(){
        $model = new Leaveplanline();
        $service = Yii::$app->params['ServiceName']['LeavePlanLine'];

        $filter = [
            'Line_No' => Yii::$app->request->post('Line_No')
        ];
        $line = Yii::$app->navhelper->getData($service, $filter);

        if(is_array($line)){
            Yii::$app->navhelper->loadmodel($line[0],$model);
            $model->Key = $line[0]->Key;
            $model->End_Date = date('Y-m-d',strtotime(Yii::$app->request->post('End_Date')));
        }


        $result = Yii::$app->navhelper->updateData($service,$model);

        Yii::$app->response->format = \yii\web\response::FORMAT_JSON;

        return $result;
    }

    public function actionView($ApplicationNo){
       
    }


    /*Get Vehicles */
    public function getVehicles(){
        $service = Yii::$app->params['ServiceName']['AvailableVehicleLookUp'];

        $result = \Yii::$app->navhelper->getData($service, []);
        $arr = [];
        $i = 0;
        foreach($result as $res){
            if(!empty($res->Vehicle_Registration_No) && !empty($res->Make_Model)){
                ++$i;
                $arr[$i] = [
                    'Code' => $res->Vehicle_Registration_No,
                    'Description' => $res->Make_Model
                ];
            }
        }

        return ArrayHelper::map($arr,'Code','Description');
    }

    /*  Students Dim 3*/
    public function getStudents(){
        $service = Yii::$app->params['ServiceName']['DimensionValueList'];

        $filter = [
            'Global_Dimension_No' => 3
        ];
        $result = \Yii::$app->navhelper->getData($service, $filter);
        return ArrayHelper::map($result,'Code','Name');
    }


    /* Get Shades 4 */

    public function getShades(){
        $service = Yii::$app->params['ServiceName']['DimensionValueList'];

        $filter = [
            'Global_Dimension_No' => 4
        ];
        $result = \Yii::$app->navhelper->getData($service, $filter);
        return ArrayHelper::map($result,'Code','Name');
    }

    /* Get Animals 5*/

    public function getAnimals(){
        $service = Yii::$app->params['ServiceName']['DimensionValueList'];

        $filter = [
            'Global_Dimension_No' => 5
        ];
        $result = \Yii::$app->navhelper->getData($service, $filter);
        return ArrayHelper::map($result,'Code','Name');
    }


    /*Dim 6*/

    public function getInstitutions(){
        $service = Yii::$app->params['ServiceName']['DimensionValueList'];

        $filter = [
            'Global_Dimension_No' => 6
        ];
        $result = \Yii::$app->navhelper->getData($service, $filter);
        return ArrayHelper::map($result,'Code','Name');
    }









    public function loadtomodel($obj,$model){

        if(!is_object($obj)){
            return false;
        }
        $modeldata = (get_object_vars($obj)) ;
        foreach($modeldata as $key => $val){
            if(is_object($val)) continue;
            $model->$key = $val;
        }

        return $model;
    }
}