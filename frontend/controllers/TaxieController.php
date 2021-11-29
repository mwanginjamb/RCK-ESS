<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 3/9/2020
 * Time: 4:21 PM
 */

namespace frontend\controllers;

use frontend\models\Taxie;
use Yii;
use yii\filters\AccessControl;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\BadRequestHttpException;


use yii\web\Response;


class TaxieController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout','signup','index','advance-list','create','update','delete','view'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout','index','advance-list','create','update','delete','view'],
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
                'only' => ['list','availability-list','approved-list'],
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

    public function actionVehicleAvailability(){

        return $this->render('vehicle-availability');

    }

    public function actionApprovedRequisitions(){

        return $this->render('approved');

    }


    public function actionCreate(){

        $model = new Taxie();
        $service = Yii::$app->params['ServiceName']['TaxieCard'];

        /*Do initial request */
        if(!isset(Yii::$app->request->post()['Taxie'])){
            $model->Employee_No = Yii::$app->user->identity->{'Employee No_'};;
            $request = Yii::$app->navhelper->postData($service, $model);
            if(!is_string($request) )
            {
               // Yii::$app->recruitment->printrr($model);
                Yii::$app->navhelper->loadmodel($request,$model);
                return $this->redirect(['update','No' => $model->No]);
            }else{
                Yii::$app->session->setFlash('error',$request);
                return $this->redirect(['index']);
            }
        }

        if(Yii::$app->request->post() && Yii::$app->navhelper->loadpost(Yii::$app->request->post()['Taxie'],$model) ){

            $filter = [
                'No' => $model->No,
            ];
            /*Read the card again to refresh Key in case it changed*/
            $refresh = Yii::$app->navhelper->getData($service,$filter);
            $model = Yii::$app->navhelper->loadmodel($refresh[0],$model);
            $result = Yii::$app->navhelper->updateData($service,$model);
            if(!is_string($result)){

                Yii::$app->session->setFlash('success','Request Created Successfully.' );
                return $this->redirect(['view','No' => $result->Booking_Requisition_No]);

            }else{
                Yii::$app->session->setFlash('error','Error Creating Request '.$result );
                return $this->redirect(['index']);

            }

        }


        //Yii::$app->recruitment->printrr($model);

        return $this->render('create',[
            'model' => $model,
            'vendors' =>  $this->getVendors(),
        ]);
    }




    public function actionUpdate($No){
        $model = new Taxie();
        $service = Yii::$app->params['ServiceName']['TaxieCard'];
        $model->isNewRecord = false;

        $filter = [
            'Booking_Requisition_No' => $No,
        ];
        $result = Yii::$app->navhelper->findOne($service,'','No',$No);

        if(is_object($result)){
            //load nav result to model
            $model = Yii::$app->navhelper->loadmodel($result,$model) ;//$this->loadtomodeEmployee_Plan_Nol($result[0],$Expmodel);
        }else{
            // Yii::$app->recruitment->printrr($result);

            Yii::$app->session->setFlash('error',$result);
            return $this->redirect(['index']);
        }


        if(Yii::$app->request->post() && Yii::$app->navhelper->loadpost(Yii::$app->request->post()['Taxie'],$model) ){
            $filter = [
                'No' => $model->No,
            ];
            /*Read the card again to refresh Key in case it changed*/
            $refresh = Yii::$app->navhelper->findOne($service,'','No',$No);
            Yii::$app->navhelper->loadmodel($refresh,$model);

            $result = Yii::$app->navhelper->updateData($service,$model);

            if(!is_string($result)){

                Yii::$app->session->setFlash('success','Document Updated Successfully.' );

                return $this->redirect(['view','No' => $result->No]);

            }else{
                Yii::$app->session->setFlash('success','Error Updating Document '.$result );
                return $this->render('update',[
                    'model' => $model,
                    'vendors' =>  $this->getVendors(),
                ]);

            }

        }


        // Yii::$app->recruitment->printrr($model);
        if(Yii::$app->request->isAjax){
            return $this->renderAjax('update', [
                'model' => $model,
                'vendors' =>  $this->getVendors(),

            ]);
        }

        return $this->render('update',[
            'model' => $model,
            'vendors' =>  $this->getVendors(),
        ]);
    }


    public function actionDelete(){
        $service = Yii::$app->params['ServiceName']['TaxieCard'];
        $result = Yii::$app->navhelper->deleteData($service,Yii::$app->request->get('Key'));
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if(!is_string($result)){

            return ['note' => '<div class="alert alert-success">Record Purged Successfully</div>'];
        }else{
            return ['note' => '<div class="alert alert-danger">Error Purging Record: '.$result.'</div>' ];
        }
    }

    public function actionView($No){
        $model = new Taxie();
        $service = Yii::$app->params['ServiceName']['TaxieCard'];

        $result = Yii::$app->navhelper->findOne($service,'','No',$No);

        //load nav result to model
        $model = $this->loadtomodel($result , $model);

        //Yii::$app->recruitment->printrr($model);

        return $this->render('view',[
            'model' => $model,
        ]);
    }

   // Get Vehicle Requisition list

    public function actionList(){
        $service = Yii::$app->params['ServiceName']['TaxieList'];
        $filter = [
            'Employee_No' => Yii::$app->user->identity->Employee[0]->No,
        ];

        $results = \Yii::$app->navhelper->getData($service,$filter);
        $result = [];
        foreach($results as $item){

            if(!empty($item->No ))
            {
                $link = $updateLink = $deleteLink =  '';
                $Viewlink = Html::a('<i class="fas fa-eye"></i>',['view','No'=> $item->No ],['class'=>'btn btn-outline-primary btn-xs','title' => 'View Request.' ]);
                if($item->Status == 'New'){
                    $link = Html::a('<i class="fas fa-paper-plane"></i>',['send-for-approval','No'=> $item->No ],['title'=>'Send Approval Request','class'=>'btn btn-primary btn-xs']);
                    $updateLink = Html::a('<i class="far fa-edit"></i>',['update','No'=> $item->No ],['class'=>'btn btn-info btn-xs','title' => 'Update Request']);
                }else if($item->Status == 'Pending_Approval'){
                    $link = Html::a('<i class="fas fa-times"></i>',['cancel-request','No'=> $item->No ],['title'=>'Cancel Approval Request','class'=>'btn btn-warning btn-xs']);
                }

                $result['data'][] = [
                    'Key' => $item->Key,
                    'No' => $item->No,
                    'Employee_Name' => !empty($item->Employee_Name)?$item->Employee_Name:'',
                    'Employee_No' => !empty($item->Employee_No)?$item->Employee_No:'',
                    'Total_Amount' => !empty($item->Total_Amount)?$item->Total_Amount:'',
                    'Taxi_Company_Name' => !empty($item->Taxi_Company_Name)?$item->Taxi_Company_Name:'',
                    'Status' => $item->Status,
                    'Action' => $link.' '. $updateLink.' '.$Viewlink ,

                ];
            }

        }

        return $result;
    }

    // Vehicle Availability List
    public function actionAvailabilityList(){
        $service = Yii::$app->params['ServiceName']['VehicleAvailabilityStatus'];
        $filter = [];

        $results = \Yii::$app->navhelper->getData($service,$filter);
        $result = [];
        foreach($results as $item){

            if(!empty($item->Vehicle_Registration_No ))
            {
                $result['data'][] = [
                    'Key' => $item->Key,
                    'No' => !empty($item->Vehicle_Registration_No)?$item->Vehicle_Registration_No:'',
                    'Make_Model' => !empty($item->Make_Model)?$item->Make_Model:'',
                    'Availability_Status' => !empty($item->Availability_Status)?$item->Availability_Status:'',
                    'Repair_Status' => !empty($item->Repair_Status)?$item->Repair_Status:'',
                    'Booked_Status' => !empty($item->Booked_Status)?$item->Booked_Status:'',
                ];
            }

        }

        return $result;
    }

    // Approved Vehicle Requisitions List
    public function actionApprovedList(){
        $service = Yii::$app->params['ServiceName']['ApprovedBookingRequisition'];
        $filter = [
            // 'Employee_No' => Yii::$app->user->identity->Employee[0]->No,
        ];

        $results = \Yii::$app->navhelper->getData($service,$filter);
        $result = [];
        foreach($results as $item){

            if(!empty($item->Vehicle_Registration_No ))
            {
                $result['data'][] = [
                    'Key' => $item->Key,
                    'No' => !empty($item->Booking_Requisition_No)?$item->Booking_Requisition_No:'',
                    'Requisition_Date' => !empty($item->Requisition_Date)?$item->Requisition_Date:'',
                    'Vehicle_Registration_No' => !empty($item->Vehicle_Registration_No)?$item->Vehicle_Registration_No:'',
                    'Reason_For_Booking' => !empty($item->Reason_For_Booking)?$item->Reason_For_Booking:'',
                    'Requested_By' => !empty($item->Requested_By)?$item->Requested_By:'',
                    'Department' => !empty($item->Department)?$item->Department:'',
                    'Approved_By' => !empty($item->Approved_By)?$item->Approved_By:'',
                    'Status' => !empty($item->Status)?$item->Status:'',
                ];
            }

        }

        return $result;
    }

    public function getVendors() 
    {
        $service = Yii::$app->params['ServiceName']['Vendors'];
        $result = Yii::$app->navhelper->getData($service);
        return Yii::$app->navhelper->refactorArray($result,'No','Name');
    }

    public function getEmployees(){
        $service = Yii::$app->params['ServiceName']['Employees'];

        $employees = \Yii::$app->navhelper->getData($service);
        $data = [];
        $i = 0;
        if(is_array($employees)){

            foreach($employees as  $emp){
                $i++;
                if(!empty($emp->Full_Name) && !empty($emp->No)){
                    $data[$i] = [
                        'No' => $emp->No,
                        'Full_Name' => $emp->Full_Name
                    ];
                }

            }



        }

        return $data;
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

    /* Call Approval Workflow Methods */

    public function actionSendForApproval()
    {
        $service = Yii::$app->params['ServiceName']['PortalFactory'];
        $DocNo = Yii::$app->request->get('No');
        $data = [
            'applicationNo' => $DocNo,
            'sendMail' => true,
            'approvalUrl' => Yii::$app->urlManager->createAbsoluteUrl(['taxie/view','No' => $DocNo]),
        ];


        $result = Yii::$app->navhelper->PortalWorkFlows($service,$data,'IanSendTaxiRequisitionForApproval');

        if(!is_string($result)){
            Yii::$app->session->setFlash('success', 'Request Sent to Supervisor Successfully.', true);
            return $this->redirect(['index']);
        }else{

            Yii::$app->session->setFlash('error', 'Error Sending  Request for Approval  : '. $result);
            return $this->redirect(['index']);

        }
    }

    /*Cancel Approval Request */

    public function actionCancelRequest($No)
    {
        $service = Yii::$app->params['ServiceName']['PortalFactory'];

        $data = [
            'applicationNo' => $No,
        ];


        $result = Yii::$app->navhelper->PortalWorkFlows($service,$data,'IanCancelTaxiRequisitionApprovalRequest');

        if(!is_string($result)){
            Yii::$app->session->setFlash('success', 'Request Cancelled Successfully.', true);
            return $this->redirect(['index']);
        }else{

            Yii::$app->session->setFlash('error', 'Error Cancelling Approval Request.  : '. $result);
            return $this->redirect(['index']);

        }
    }



}