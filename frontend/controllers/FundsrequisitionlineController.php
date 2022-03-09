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
use frontend\models\Fundrequisition;
use frontend\models\Fundsrequisitionline;
use frontend\models\Imprestline;
use frontend\models\Leaveplanline;
use frontend\models\Weeknessdevelopmentplan;
use Yii;
use yii\filters\AccessControl;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\BadRequestHttpException;

use frontend\models\Leave;
use yii\web\Response;
use kartik\mpdf\Pdf;

class FundsrequisitionlineController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup','index','create','update','delete','view'],
                'rules' => [
                    [
                        'actions' => ['signup'],
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

    public function actionCreate($Request_No){
       $service = Yii::$app->params['ServiceName']['AllowanceRequestLine'];
       $model = new Fundsrequisitionline() ;


        if($Request_No && !Yii::$app->request->post()){
                $model->Request_No = $Request_No;
                $model->Line_No = time();
                $res = Yii::$app->navhelper->postData($service, $model);
                if(!is_string($res)){
                    Yii::$app->navhelper->loadmodel($res, $model);
                }else{
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return ['note' => '<div class="alert alert-danger">Error Creating Fund Requisition Line: '.$res.'</div>'];
                }

            return $this->renderAjax('create', [
                'model' => $model,
                'transactionTypes' => $this->getRates(),
                'subOffices' => $this->getDimension(1),
                'programCodes' => $this->getDimension(2),
                'jobs' =>  $this->getJob(),
                'jobTasks' => $this->getJobTask(),
                'accounts' => $this->getGlaccounts(),
                'employees' => $this->getEmployees()
            ]);

        }
        // Yii::$app->navhelper->loadpost(Yii::$app->request->post()['Fundsrequisitionline'],$model)

        if(Yii::$app->request->post()  && $model->load(Yii::$app->request->post()) ){


             $refresh = Yii::$app->navhelper->getData($service,[
                'Line_No' => Yii::$app->request->post()['Fundsrequisitionline']['Line_No']
                ]);
            $model->Key = $refresh[0]->Key;
            $result = Yii::$app->navhelper->updateData($service,$model);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            // return $model;
            if(!is_string($result)){
                return ['note' => '<div class="alert alert-success">Fund Requisition Line Created Successfully. </div>' ];
            }else{
                return ['note' => '<div class="alert alert-danger">Error Creating Fund Requisition Line: '.$result.'</div>'];
            }

        }

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('create', [
                'model' => $model,
                'transactionTypes' => $this->getRates(),
                'subOffices' => $this->getDimension(1),
                'programCodes' => $this->getDimension(2),
                'jobs' =>  $this->getJob(),
                'jobTasks' => $this->getJobTask(),
                'accounts' => $this->getGlaccounts(),
                'employees' => $this->getEmployees()
            ]);
        }
    }


    public function actionUpdate(){
        $model = new Fundsrequisitionline() ;
        $model->isNewRecord = false;
        $service = Yii::$app->params['ServiceName']['AllowanceRequestLine'];
        $result = Yii::$app->navhelper->readByKey($service,Yii::$app->request->get('Key'));

        if(is_object($result)){
            //load nav result to model
            $model = Yii::$app->navhelper->loadmodel($result,$model) ;
        }else{
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ['note' => '<div class="alert alert-danger">Error Updating Fund Requisition Line: '.$result.'</div>'];
        }

        if(Yii::$app->request->post() && Yii::$app->navhelper->loadpost(Yii::$app->request->post()['Fundsrequisitionline'],$model) ){

            $refresh = Yii::$app->navhelper->readByKey($service,Yii::$app->request->post()['Fundsrequisitionline']['Key']);
            $model->Key = $refresh->Key;

            $result = Yii::$app->navhelper->updateData($service,$model);

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if(!is_string($result)){
                return ['note' => '<div class="alert alert-success">Fund Requisition Line Updated Successfully. </div>' ];
            }else{
                return ['note' => '<div class="alert alert-danger">Error Updating Fund Requisition Line: '.$result.'</div>'];
            }

        }

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('update', [
                'model' => $model,
                'subOffices' => $this->getDimension(1),
                'programCodes' => $this->getDimension(2),
                'jobs' =>  $this->getJob(),
                'jobTasks' => $this->getJobTask(),
                'accounts' => $this->getGlaccounts(),
                'transactionTypes' => $this->getRates(),
                'employees' => $this->getEmployees(),
                'donors' => Yii::$app->navhelper->dropdown('CustomerLookup','No','Name'),
                'grants' => Yii::$app->navhelper->dropdown('GrantLookUp','No','Title'),
                'objectiveCode' => Yii::$app->navhelper->dropdown('GrantLinesLookUp','Code','Description',['Line_Type' => 'Objective']),
                'outputCode' => Yii::$app->navhelper->dropdown('GrantLinesLookUp','Code','Description',['Line_Type' => 'Output']),
                'outcomeCode' => Yii::$app->navhelper->dropdown('GrantLinesLookUp','Code','Description',['Line_Type' => 'Outcome']),
                'activityCode' => Yii::$app->navhelper->dropdown('GrantLinesLookUp','Code','Description',['Line_Type' => 'Activity']),
                'partnerCode' => Yii::$app->navhelper->dropdown('GrantDetailLines','G_L_Account_No','Activity_Description'),
            ]);
        }

        return $this->render('update',[
            'model' => $model,
            'transactionTypes' => $this->getRates(),
        ]);
    }

    public function actionDelete(){
        $service = Yii::$app->params['ServiceName']['AllowanceRequestLine'];
        $result = Yii::$app->navhelper->deleteData($service,Yii::$app->request->get('Key'));
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if(!is_string($result)){
            return ['note' => '<div class="alert alert-success">Record Purged Successfully</div>'];
        }else{
            return ['note' => '<div class="alert alert-danger">Error Purging Record: '.$result.'</div>' ];
        }
    }


    public function actionView(){
        
    }

    public function getEmployees(){        
        $service = Yii::$app->params['ServiceName']['EmployeesUnfiltered'];
        $filter = [];
        $employees = \Yii::$app->navhelper->getData($service, $filter);
        return Yii::$app->navhelper->refactorArray($employees,'No','Full_Name');
    }

     /*Get a list of all GL Accounts*/

     public function getGlaccounts()
     {
         $service = Yii::$app->params['ServiceName']['GLAccountList'];
         $filter = [];
         $result = \Yii::$app->navhelper->getData($service, $filter);
 
         return Yii::$app->navhelper->refactorArray($result,'No','Name');
     }

    /*Get Transaction Types */

    public function getRates(){
        $service = Yii::$app->params['ServiceName']['RequisitionRates'];

        $result = \Yii::$app->navhelper->getData($service, []);
        return ArrayHelper::map($result,'Code','Description');

    }

        // get SUb offices

        public function getDimension($value)
        {
            $service = Yii::$app->params['ServiceName']['DimensionValueList'];
            $filter = ['Global_Dimension_No' => $value];
            $result = \Yii::$app->navhelper->getData($service, $filter);
            return Yii::$app->navhelper->refactorArray($result,'Code','Name');
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

        /** Updates a single field */
    public function actionSetfield($field){
        $service = 'AllowanceRequestLine';
        $value = Yii::$app->request->post('fieldValue');
       
        $result = Yii::$app->navhelper->Commit($service,[$field => $value],Yii::$app->request->post('Key'));
        Yii::$app->response->format = \yii\web\response::FORMAT_JSON;
        return $result;
          
    }
    



}