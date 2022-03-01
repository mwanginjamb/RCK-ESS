<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 3/9/2020
 * Time: 4:21 PM
 */

namespace frontend\controllers;
use frontend\models\Imprestline;
use Yii;
use yii\filters\AccessControl;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\BadRequestHttpException;

use frontend\models\Leave;
use yii\web\Response;

class ImprestlineController extends Controller
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

    public function actionCreate($Request_No){
       $service = Yii::$app->params['ServiceName']['ImprestRequestSubformPortal'];
       $model = new Imprestline() ;


        if($Request_No && !isset(Yii::$app->request->post()['Imprestline'])){

               $model->Request_No = $Request_No;
               $model->Line_No = time();
               $request = Yii::$app->navhelper->postData($service,$model);
               if(is_object($request) )
               {
                   $model = Yii::$app->navhelper->loadmodel($request,$model);
               }else{
                   //Yii::$app->recruitment->printrr($request);

                return ['note' => '<div class="alert alert-danger">Error Creating Imprest Line: '.$request.'</div>'];
            }
            return $this->renderAjax('create', [
                'model' => $model,
                'transactionTypes' => $this->getTransactiontypes(),
                'subOffices' => $this->getDimension(1),
                'programCodes' => $this->getDimension(2),
                'jobs' =>  $this->getJob(),
                'jobTasks' => $this->getJobTask(),
                'donors' => Yii::$app->navhelper->dropdown('CustomerLookup','No','Name')
            ]);

        }
        

        if(Yii::$app->request->post() && Yii::$app->navhelper->loadpost(Yii::$app->request->post()['Imprestline'],$model) ){


            $refresh = Yii::$app->navhelper->getData($service,['Line_No' => Yii::$app->request->post()['Imprestline']['Line_No']]);
            $model->Key = $refresh[0]->Key;
            $result = Yii::$app->navhelper->updateData($service,$model);

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
           // Yii::$app->recruitment->printrr($refresh);
            // return $model;
            if(is_object($result)){

                return ['note' => '<div class="alert alert-success">Imprest Line Created Successfully. </div>' ];
            }else{

                return ['note' => '<div class="alert alert-danger">Error Creating Imprest Line: '.$result.'</div>'];
            }

        }

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('create', [
                'model' => $model,
                'transactionTypes' => $this->getTransactiontypes(),
                'subOffices' => $this->getDimension(1),
                'programCodes' => $this->getDimension(2),
                'jobs' =>  $this->getJob(),
                'jobTasks' => $this->getJobTask(),
                'donors' => Yii::$app->navhelper->dropdown('CustomerLookup','No','Name')
            ]);
        }


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
 


    


    public function actionUpdate($Key){
        $model = new Imprestline() ;
        $model->isNewRecord = false;
        $service = Yii::$app->params['ServiceName']['ImprestRequestLine'];
       
        $result = Yii::$app->navhelper->readByKey($service,$Key);


        if(is_object($result)){
            //load nav result to model
            $model = Yii::$app->navhelper->loadmodel($result,$model) ;
            // Yii::$app->recruitment->printrr($model);
        }else{
            // Yii::$app->recruitment->printrr($result);
            return ['note' => '<div class="alert alert-danger">Error Updating Imprest Line: '.$result.'</div>'];
        }


        if(Yii::$app->request->post() && Yii::$app->navhelper->loadpost(Yii::$app->request->post()['Imprestline'],$model) ){

            $refresh = Yii::$app->navhelper->getData($service,['Line_No' => Yii::$app->request->post()['Imprestline']['Line_No']]);
            $model->Key = $refresh[0]->Key;

            $result = Yii::$app->navhelper->updateData($service,$model);

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if(!is_string($result)){
                return ['note' => '<div class="alert alert-success">Imprest Line Updated Successfully. </div>' ];
            }else{
                return ['note' => '<div class="alert alert-danger">Error Updating Imprest Line: '.$result.'</div>'];
            }

        }

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('update', [
                'model' => $model,
                'transactionTypes' => $this->getTransactiontypes(),
                'subOffices' => $this->getDimension(1),
                'programCodes' => $this->getDimension(2),
                'jobs' =>  $this->getJob(),
                'jobTasks' => $this->getJobTask(),
                'donors' => Yii::$app->navhelper->dropdown('CustomerLookup','No','Name')
            ]);
        }

        return $this->render('update',[
            'model' => $model,
            'transactionTypes' => $this->getTransactiontypes(),
            'students' => $this->getStudents()
        ]);
    }


    /* Get Dimension 3*/

    public function getStudents(){
        $service = Yii::$app->params['ServiceName']['DimensionValueList'];

        $filter = [
            'Global_Dimension_No' => 3
        ];
        $result = \Yii::$app->navhelper->getData($service, $filter);
        //return ArrayHelper::map($result,'Code','Name');
        //return Yii::$app->navhelper->refactorArray($result,'Code','Name');
          $arr = [];
        $i = 0;
        foreach($result as $res){
            if(!empty($res->Code) && !empty($res->Name)){
                ++$i;
                $arr[$i] = [
                    'Code' => $res->Code,
                    'Name' => $res->Name.' - '.$res->Code
                ];
            }
        }

        return ArrayHelper::map($arr,'Code','Name');
    }

    public function actionDelete(){
        $service = Yii::$app->params['ServiceName']['ImprestRequestLine'];
        $result = Yii::$app->navhelper->deleteData($service,Yii::$app->request->get('Key'));
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if(!is_string($result)){
            return ['note' => '<div class="alert alert-success">Record Purged Successfully</div>'];
        }else{
            return ['note' => '<div class="alert alert-danger">Error Purging Record: '.$result.'</div>' ];
        }
    }

    public function actionSettransactiontype(){
        $model = new Imprestline();
        $service = Yii::$app->params['ServiceName']['ImprestRequestSubformPortal'];

           $model->Transaction_Type = Yii::$app->request->post('Transaction_Type');
           $model->Request_No = Yii::$app->request->post('Request_No');
           $model->Employee_No = Yii::$app->user->identity->{'Employee_No'};
           $model->Line_No = time();

        $line = Yii::$app->navhelper->postData($service, $model);
        Yii::$app->response->format = \yii\web\response::FORMAT_JSON;
        return $line;

    }

    public function actionSetenddate(){
        $model = new Imprestline();
        $service = Yii::$app->params['ServiceName']['Leave__Plan__Line'];

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
        $service = Yii::$app->params['ServiceName']['leaveApplicationCard'];
        $leaveTypes = $this->getLeaveTypes();
        $employees = $this->getEmployees();

        $filter = [
            'Application_No' => $ApplicationNo
        ];

        $leave = Yii::$app->navhelper->getData($service, $filter);

        //load nav result to model
        $leaveModel = new Leave();
        $model = $this->loadtomodel($leave[0],$leaveModel);


        return $this->render('view',[
            'model' => $model,
            'leaveTypes' => ArrayHelper::map($leaveTypes,'Code','Description'),
            'relievers' => ArrayHelper::map($employees,'No','Full_Name'),
        ]);
    }



    /*Get Transaction Types */

    public function getTransactiontypes(){
        $service = Yii::$app->params['ServiceName']['PaymentTypes'];

        $filter = ['Source_Type' => 'Imprest'];

        $result = \Yii::$app->navhelper->getData($service, $filter);

        $arr = []; $i = 0;
        foreach($result as $p)
        {
            if(!empty($p->Description)){
                ++$i;
                $arr[$i] = [
                    'Code' => $p->Code,
                    'Description' => $p->Description

                ];
            }

        }
        return ArrayHelper::map($arr,'Code','Description');
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


    /** Updates a single field */
    public function actionSetfield($field){
        $service = 'ImprestRequestSubformPortal';
        $value = Yii::$app->request->post('fieldValue');
       
        $result = Yii::$app->navhelper->Commit($service,[$field => $value],Yii::$app->request->post('Key'));
        Yii::$app->response->format = \yii\web\response::FORMAT_JSON;
        return $result;
          
    }
}