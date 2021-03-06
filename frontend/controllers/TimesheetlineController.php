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
use frontend\models\Overtimeline;
use frontend\models\Storerequisitionline;
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

use frontend\models\Leave;
use frontend\models\Timesheetline;
use yii\web\Response;
use kartik\mpdf\Pdf;

class TimesheetlineController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup', 'index', 'create', 'update', 'delete', 'view'],
                'rules' => [
                    [
                        'actions' => ['signup', 'index'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'index', 'create', 'update', 'delete', 'view'],
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
            'contentNegotiator' => [
                'class' => ContentNegotiator::class,
                'only' => ['setquantity', 'setitem', 'setstarttime', 'setendtime'],
                'formatParam' => '_format',
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                    //'application/xml' => Response::FORMAT_XML,
                ],
            ]
        ];
    }

    public function actionIndex()
    {

        return $this->render('index');
    }

    public function actionCreate($No, $Period_Month)
    {
        $service = Yii::$app->params['ServiceName']['OvertimeLine'];
        $model = new Timesheetline();
        $model->Date = date('Y-m-d', strtotime($Period_Month));

        if (Yii::$app->request->get('No') && !Yii::$app->request->post()) {

            $model->Application_No = $No;
            //$model->Date = date('Y-m-d');
            $model->Employee_No = Yii::$app->user->identity->{'Employee No_'};
            $model->Line_No = time();
            $result = Yii::$app->navhelper->postData($service, $model);
            //Yii::$app->recruitment->printrr($result);
            if (is_string($result)) {
                Yii::$app->recruitment->printrr($result);
            }

            Yii::$app->navhelper->loadmodel($result, $model);
        }



        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('create', [
                'model' => $model,
                'grants' => Yii::$app->navhelper->dropdown('PayrollChargeGrants', 'Grant_Code', 'Grant_Name', ['Emp_Code' => Yii::$app->user->identity->{'Employee No_'}, 'Payroll_Period' => $model->Date], []), //$this->getGrants()

            ]);
        }
    }


    public function actionUpdate($Key)
    {
        $model = new Timesheetline();
        $model->isNewRecord = false;
        $service = Yii::$app->params['ServiceName']['OvertimeLine'];


        $result = Yii::$app->navhelper->readByKey($service, $Key);

        if (is_object($result)) {
            //load nav result to model
            Yii::$app->navhelper->loadmodel($result, $model);
        } else {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            //Yii::$app->recruitment->printrr($result);
            return ['note' => '<div class="alert alert-danger">Error Updating Line: ' . $result . '</div>'];
        }


        if (Yii::$app->request->post() && Yii::$app->navhelper->loadpost(Yii::$app->request->post()['Timesheetline'], $model)) {



            //Yii::$app->recruitment->printrr($model);

            $result = Yii::$app->navhelper->updateData($service, $model);

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if (!is_string($result)) {

                return ['note' => '<div class="alert alert-success"> Line Updated Successfully. </div>'];
            } else {

                return ['note' => '<div class="alert alert-danger">Error Updating Line: ' . $result . '</div>'];
            }
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('update', [
                'model' => $model,
                'grants' => Yii::$app->navhelper->dropdown('PayrollChargeGrants', 'Grant_Code', 'Grant_Name', ['Emp_Code' => Yii::$app->user->identity->{'Employee No_'}, 'Payroll_Period' => $model->Date], []), //$this->getGrants()
            ]);
        }

        return $this->render('update', [
            'model' => $model,
            'grants' => Yii::$app->navhelper->dropdown('PayrollChargeGrants', 'Grant_Code', 'Grant_Name', ['Emp_Code' => Yii::$app->user->identity->{'Employee No_'}, 'Payroll_Period' => $model->Date], []),
        ]);
    }

    public function actionDelete()
    {
        $service = Yii::$app->params['ServiceName']['OvertimeLine'];
        $result = Yii::$app->navhelper->deleteData($service, Yii::$app->request->get('Key'));
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (!is_string($result)) {
            return ['note' => '<div class="alert alert-success">Record Purged Successfully</div>'];
        } else {
            return ['note' => '<div class="alert alert-danger">Error Purging Record: ' . $result . '</div>'];
        }
    }


    public function actionSetstarttime()
    {
        $model = new Timesheetline();
        $service = Yii::$app->params['ServiceName']['OvertimeLine'];

        $filter = [
            'Line_No' => Yii::$app->request->post('Line_No')
        ];
        $line = Yii::$app->navhelper->getData($service, $filter);
        // Yii::$app->recruitment->printrr($line);
        if (is_array($line)) {
            Yii::$app->navhelper->loadmodel($line[0], $model);
            $model->Key = $line[0]->Key;
            $model->Start_Time = Yii::$app->request->post('Start_Time');
            $model->Date = Yii::$app->request->post('Date');
        }


        $result = Yii::$app->navhelper->updateData($service, $model);

        return $result;
    }


    public function actionSetendtime()
    {
        $model = new Timesheetline();
        $service = Yii::$app->params['ServiceName']['OvertimeLine'];

        $filter = [
            'Line_No' => Yii::$app->request->post('Line_No')
        ];
        $line = Yii::$app->navhelper->getData($service, $filter);
        // Yii::$app->recruitment->printrr($line);
        if (is_array($line)) {
            Yii::$app->navhelper->loadmodel($line[0], $model);
            $model->Key = $line[0]->Key;
            $model->End_Time = Yii::$app->request->post('End_Time');
        }


        $result = Yii::$app->navhelper->updateData($service, $model);

        return $result;
    }

    // Set Location

    public function actionSetlocation()
    {
        $model = new Storerequisitionline();
        $service = Yii::$app->params['ServiceName']['StoreRequisitionLine'];

        $filter = [
            'Line_No' => Yii::$app->request->post('Line_No')
        ];
        $line = Yii::$app->navhelper->getData($service, $filter);
        // Yii::$app->recruitment->printrr($line);
        if (is_array($line)) {
            Yii::$app->navhelper->loadmodel($line[0], $model);
            $model->Key = $line[0]->Key;
            $model->Location = Yii::$app->request->post('Location');
        }


        $result = Yii::$app->navhelper->updateData($service, $model);

        return $result;
    }

    public function actionSetitem()
    {
        $model = new Storerequisitionline();
        $service = Yii::$app->params['ServiceName']['StoreRequisitionLine'];

        $filter = [
            'Line_No' => Yii::$app->request->post('Line_No')
        ];
        $line = Yii::$app->navhelper->getData($service, $filter);
        // Yii::$app->recruitment->printrr($line);
        if (is_array($line)) {
            Yii::$app->navhelper->loadmodel($line[0], $model);
            $model->Key = $line[0]->Key;
            $model->No = Yii::$app->request->post('No');
        }

        $result = Yii::$app->navhelper->updateData($service, $model);

        return $result;
    }


    /*Get Locations*/

    public function getLocations()
    {
        $service = Yii::$app->params['ServiceName']['Locations'];
        $filter = [];
        $result = \Yii::$app->navhelper->getData($service, $filter);
        // return ArrayHelper::map($result,'Code','Name');

        return Yii::$app->navhelper->refactorArray($result, 'Code', 'Name');
    }



    /*Get Items*/

    public function getItems()
    {
        $service = Yii::$app->params['ServiceName']['Items'];
        $filter = [];
        $result = \Yii::$app->navhelper->getData($service, $filter);

        return Yii::$app->navhelper->refactorArray($result, 'No', 'Description');
    }




    public function actionView($ApplicationNo)
    {
        $service = Yii::$app->params['ServiceName']['leaveApplicationCard'];


        $filter = [
            'Application_No' => $ApplicationNo
        ];

        $leave = Yii::$app->navhelper->getData($service, $filter);

        //load nav result to model
        $leaveModel = new Leave();
        $model = $this->loadtomodel($leave[0], $leaveModel);


        return $this->render('view', [
            'model' => $model,
        ]);
    }


    /*Get Grants */
    public function getGrants()
    {
        $service = Yii::$app->params['ServiceName']['DonorList'];

        $result = \Yii::$app->navhelper->getData($service, []);
        $data = Yii::$app->navhelper->refactorArray($result, 'Donor_Code', 'Donor_Name');

        return $data;
    }


    // Donors
    public function getJob()
    {
        $service = Yii::$app->params['ServiceName']['Jobs'];
        $filter = [];
        $result = \Yii::$app->navhelper->getData($service, $filter);
        return Yii::$app->navhelper->refactorArray($result, 'No', 'Description');
    }


    /** Updates a single field */
    public function actionSetfield($field)
    {
        $service = 'OvertimeLine';
        $value = Yii::$app->request->post('fieldValue');

        $result = Yii::$app->navhelper->Commit($service, [$field => $value], Yii::$app->request->post('Key'));
        Yii::$app->response->format = \yii\web\response::FORMAT_JSON;
        return $result;
    }
}
