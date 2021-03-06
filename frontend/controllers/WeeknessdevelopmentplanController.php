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

class WeeknessdevelopmentplanController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup','index'],
                'rules' => [
                    [
                        'actions' => ['signup','index'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout','index'],
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
                'only' => [''],
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

    public function actionCreate(){

        $model = new Weeknessdevelopmentplan() ;
        $service = Yii::$app->params['ServiceName']['WeeknessDevPlan'];


        if(Yii::$app->request->post() && Yii::$app->navhelper->loadpost(Yii::$app->request->post()['Weeknessdevelopmentplan'],$model)  ){


            $result = Yii::$app->navhelper->postData($service,$model);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if(is_object($result)){

                return ['note' => '<div class="alert alert-success">Training Need Added Successfully. </div>'];

            }else{

                return ['note' => '<div class="alert alert-danger">Error  : '.$result.'</div>' ];

            }

        }//End Saving experience

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('create', [
                'model' => $model,
                'categories' =>  $this->getTcategories(),
                'trainers' =>  $this->getTrainers(),
                'trainingneeds' => $this->getTrainingneeds()
            ]);
        }

        return $this->render('create',[
            'model' => $model,
            'categories' =>  $this->getTcategories(),
            'trainers' =>  $this->getTrainers(),
            'trainingneeds' => $this->getTrainingneeds()
        ]);
    }


    public function actionUpdate(){
        $model = new Weeknessdevelopmentplan() ;
        $model->isNewRecord = false;
        $service = Yii::$app->params['ServiceName']['WeeknessDevPlan'];
        $filter = [
            'Line_No' => Yii::$app->request->get('Line_No'),
            'Employee_No' => Yii::$app->request->get('Employee_No'),
            'Appraisal_No' => Yii::$app->request->get('Appraisal_No')
        ];
        $result = Yii::$app->navhelper->getData($service,$filter);

        if(is_array($result)){
            //load nav result to model
            $model = Yii::$app->navhelper->loadmodel($result[0],$model) ;//$this->loadtomodeEmployee_Nol($result[0],$Expmodel);
        }else{
            Yii::$app->recruitment->printrr($result);
        }


        if(Yii::$app->request->post() && Yii::$app->navhelper->loadpost(Yii::$app->request->post()['Weeknessdevelopmentplan'],$model) ){
            $result = Yii::$app->navhelper->updateData($service,$model);

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if(!is_string($result)){

                return ['note' => '<div class="alert alert-success">Training Need Updated Successfully. </div>' ];
            }else{

                return ['note' => '<div class="alert alert-danger">Error Updating Training Need: '.$result.'</div>'];
            }

        }

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('update', [
                'model' => $model,
                'categories' =>  $this->getTcategories(),
                'trainers' =>  $this->getTrainers(),
                'trainingneeds' => $this->getTrainingneeds()

            ]);
        }

        return $this->render('update',[
            'model' => $model,
            'categories' =>  $this->getTcategories(),
            'trainers' =>  $this->getTrainers(),
            'trainingneeds' => $this->getTrainingneeds()
        ]);
    }

    public function actionDelete(){
        $service = Yii::$app->params['ServiceName']['WeeknessDevPlan'];
        $result = Yii::$app->navhelper->deleteData($service,Yii::$app->request->get('Key'));
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if(!is_string($result)){
            return ['note' => '<div class="alert alert-success">Record Purged Successfully</div>'];
        }else{
            return ['note' => '<div class="alert alert-danger">Error Purging Record: '.$result.'</div>' ];
        }
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


    public function getTcategories()
    {
        $service = Yii::$app->params['ServiceName']['Qualifications'];
        $result = Yii::$app->navhelper->getData($service);
        return Yii::$app->navhelper->refactorArray($result,'Description','Description');
    }

    public function getTrainers()
    {
        $service = Yii::$app->params['ServiceName']['HRTrainers'];
        $result = Yii::$app->navhelper->getData($service);
        return Yii::$app->navhelper->refactorArray($result,'No','Name');
    }

     public function getTrainingneeds()
    {
        $service = Yii::$app->params['ServiceName']['AppraisalTrainingNeed'];
        $result = Yii::$app->navhelper->getData($service);
        return Yii::$app->navhelper->refactorArray($result,'Need_Description','Need_Description');
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