<?php

/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 3/9/2020
 * Time: 4:21 PM
 */

namespace frontend\controllers;

use frontend\models\Purchaserequisition;
use Yii;
use yii\filters\AccessControl;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use kartik\mpdf\Pdf;
use yii\helpers\FileHelper;

class PurchaseRequisitionController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup', 'index', 'list', 'create', 'update', 'delete', 'view', 'list-pending', 'list-approved', 'approved', 'pending', 'list-initiated', 'initiated'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'index', 'list', 'create', 'update', 'delete', 'view', 'list-pending', 'list-approved', 'approved', 'pending', 'list-initiated', 'initiated'],
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
                'only' => ['list', 'list-pending', 'list-approved', 'list-initiated'],
                'formatParam' => '_format',
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                    //'application/xml' => Response::FORMAT_XML,
                ],
            ]
        ];
    }

    public function beforeAction($action)
    {

        $ExceptedActions = [
            'dimension1', 'dimension2', 'types', 'no',
            'grants', 'objectives', 'outputs', 'outcome', 'procurement-methods',
            'activities', 'partners', 'donors', 'upload', 'locations', 'upload-multiple'
        ];

        if (in_array($action->id, $ExceptedActions)) {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    public function actionIndex()
    {

        return $this->render('index');
    }

    public function actionPending()
    {

        return $this->render('pending');
    }

    public function actionApproved()
    {

        return $this->render('approved');
    }

    public function actionInitiated()
    {

        return $this->render('initiated');
    }


    public function actionCreate()
    {

        $model = new Purchaserequisition();
        $service = Yii::$app->params['ServiceName']['PurchaseRequisitionCard'];

        /*Do initial request */
        if (!isset(Yii::$app->request->post()['Purchaserequisition'])) {
            $model->Employee_No = Yii::$app->user->identity->{'Employee No_'};
            $request = Yii::$app->navhelper->postData($service, $model);
            if (is_object($request)) {
                $model = Yii::$app->navhelper->loadmodel($request, $model);
                return $this->redirect(['update', 'No' => $request->No]);
            } else {
                Yii::$app->session->setFlash('error', $request);
                $this->redirect(['index']);
            }
        }

        $model->Requested_Delivery_Date = ($model->Requested_Delivery_Date == '0001-01-01') ? date('Y-m-d') : $model->Requested_Delivery_Date;
        if (Yii::$app->request->post() && Yii::$app->navhelper->loadpost(Yii::$app->request->post()['Purchaserequisition'], $model)) {


            $result = Yii::$app->navhelper->updateData($service, $model);
            if (!is_string($result)) {

                Yii::$app->session->setFlash('success', 'Request Created Successfully.');
                return $this->redirect(['view', 'No' => $result->No]);
            } else {
                Yii::$app->session->setFlash('error', 'Error Creating Request ' . $result);
                return $this->redirect(['index']);
            }
        }


        //Yii::$app->recruitment->printrr($model);

        return $this->render('create', [
            'model' => $model,
            'programs' => $this->getPrograms(),
            'departments' => $this->getDepartments(),
            'attachments' => Yii::$app->navhelper->getData(Yii::$app->params['ServiceName']['LeaveAttachments'], ['Document_No' => $model->No]),
        ]);
    }




    public function actionUpdate($No)
    {
        $model = new Purchaserequisition();
        $service = Yii::$app->params['ServiceName']['PurchaseRequisitionCard'];
        $model->isNewRecord = false;

        $filter = [
            'No' => $No,
        ];
        $result = Yii::$app->navhelper->getData($service, $filter);

        if (is_array($result)) {
            //load nav result to model
            $model = Yii::$app->navhelper->loadmodel($result[0], $model);
        } else {
            Yii::$app->recruitment->printrr($result);
        }


        if (Yii::$app->request->post() && Yii::$app->navhelper->loadpost(Yii::$app->request->post()['Purchaserequisition'], $model)) {

            $result = Yii::$app->navhelper->updateData($service, $model);

            if (!is_string($result)) {
                Yii::$app->session->setFlash('success', 'Document Updated Successfully.');
                return $this->redirect(['view', 'No' => $result->No]);
            } else {
                Yii::$app->session->setFlash('success', 'Error Updating Document' . $result);
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }


        // Yii::$app->recruitment->printrr($model);
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('update', [
                'model' => $model,
                'programs' => $this->getPrograms(),
                'departments' => $this->getDepartments(),


            ]);
        }

        return $this->render('update', [
            'model' => $model,
            'programs' => $this->getPrograms(),
            'departments' => $this->getDepartments(),
            'attachments' => Yii::$app->navhelper->getData(Yii::$app->params['ServiceName']['LeaveAttachments'], ['Document_No' => $model->No])
        ]);
    }

    public function actionDelete()
    {
        $service = Yii::$app->params['ServiceName']['PurchaseRequisitionCard'];
        $result = Yii::$app->navhelper->deleteData($service, Yii::$app->request->get('Key'));
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (!is_string($result)) {

            return ['note' => '<div class="alert alert-success">Record Purged Successfully</div>'];
        } else {
            return ['note' => '<div class="alert alert-danger">Error Purging Record: ' . $result . '</div>'];
        }
    }

    public function actionView($No)
    {
        $model = new Purchaserequisition();
        $service = Yii::$app->params['ServiceName']['PurchaseRequisitionCard'];

        $filter = [
            'No' => $No
        ];

        $result = Yii::$app->navhelper->getData($service, $filter);

        //load nav result to model
        $model = Yii::$app->navhelper->loadmodel($result[0], $model);

        //Yii::$app->recruitment->printrr($model);

        return $this->render('view', [
            'model' => $model,
            'attachments' => Yii::$app->navhelper->getData(Yii::$app->params['ServiceName']['LeaveAttachments'], ['Document_No' => $model->No]),
        ]);
    }

    public function actionRead()
    {
        $path = Yii::$app->request->post('path');
        $No = Yii::$app->request->post('No');
        $binary = file_get_contents($path);
        $content = chunk_split(base64_encode($binary));
        return $this->render('read', [
            'path' => $path,
            'No' => $No,
            'content' => $content
        ]);
    }

    // Get list

    public function actionList()
    {
        $service = Yii::$app->params['ServiceName']['PurchaseRequisitionList'];
        $filter = [
            'Employee_No' => Yii::$app->user->identity->{'Employee No_'},
        ];

        $results = \Yii::$app->navhelper->getData($service, $filter);
        //Yii::$app->recruitment->printrr($filter);
        $result = [];
        foreach ($results as $item) {

            if (!empty($item->No)) {
                $link = $updateLink = $deleteLink =  '';
                $Viewlink = Html::a('<i class="fas fa-eye"></i>', ['view', 'No' => $item->No], ['class' => 'btn btn-outline-primary btn-xs', 'title' => 'View Request.']);
                if ($item->Status == 'New') {
                    $link = Html::a('<i class="fas fa-paper-plane"></i>', ['send-for-approval', 'No' => $item->No], ['title' => 'Send Approval Request', 'class' => 'btn btn-primary btn-xs']);
                    $updateLink = Html::a('<i class="far fa-edit"></i>', ['update', 'No' => $item->No], ['class' => 'btn btn-info btn-xs', 'title' => 'Update Request']);
                } else if ($item->Status == 'Pending_Approval') {
                    $link = Html::a('<i class="fas fa-times"></i>', ['cancel-request', 'No' => $item->No], ['title' => 'Cancel Approval Request', 'class' => 'btn btn-warning btn-xs']);
                }

                $result['data'][] = [
                    'Key' => $item->Key,
                    'No' => $item->No,
                    'Title' => !empty($item->Title) ? $item->Title : '',
                    'Employee_No' => !empty($item->Employee_No) ? $item->Employee_No : '',
                    'Employee_Name' => !empty($item->Employee_Name) ? $item->Employee_Name : '',
                    'Global_Dimension_1_Code' => !empty($item->Global_Dimension_1_Code) ? $item->Global_Dimension_1_Code : '',
                    'Status' => $item->Status,
                    'Action' => $link . ' ' . $updateLink . ' ' . $Viewlink,

                ];
            }
        }

        return $result;
    }

    // Pending Requests

    public function actionListPending()
    {
        $service = Yii::$app->params['ServiceName']['PRPendingApproval'];
        $filter = [
            'Employee_No' => Yii::$app->user->identity->{'Employee No_'},
        ];

        $results = \Yii::$app->navhelper->getData($service, $filter);
        //Yii::$app->recruitment->printrr($results);
        $result = [];
        foreach ($results as $item) {

            if (!empty($item->No)) {
                $link = $updateLink = $deleteLink =  '';
                $Viewlink = Html::a('<i class="fas fa-eye"></i>', ['view', 'No' => $item->No], ['class' => 'btn btn-outline-primary btn-xs', 'title' => 'View Request.']);
                if ($item->Status == 'New') {
                    $link = Html::a('<i class="fas fa-paper-plane"></i>', ['send-for-approval', 'No' => $item->No], ['title' => 'Send Approval Request', 'class' => 'btn btn-primary btn-xs']);
                    $updateLink = Html::a('<i class="far fa-edit"></i>', ['update', 'No' => $item->No], ['class' => 'btn btn-info btn-xs', 'title' => 'Update Request']);
                } else if ($item->Status == 'Pending_Approval') {
                    $link = Html::a('<i class="fas fa-times"></i>', ['cancel-request', 'No' => $item->No], ['title' => 'Cancel Approval Request', 'class' => 'btn btn-warning btn-xs']);
                }

                $result['data'][] = [
                    'Key' => $item->Key,
                    'No' => $item->No,
                    'Title' => !empty($item->Title) ? $item->Title : '',
                    'Employee_No' => !empty($item->Employee_No) ? $item->Employee_No : '',
                    'Employee_Name' => !empty($item->Employee_Name) ? $item->Employee_Name : '',
                    'Global_Dimension_1_Code' => !empty($item->Global_Dimension_1_Code) ? $item->Global_Dimension_1_Code : '',
                    'Status' => $item->Status,
                    'Action' => $Viewlink,

                ];
            }
        }

        return $result;
    }

    public function actionListApproved()
    {
        $service = Yii::$app->params['ServiceName']['PRApproved'];
        $filter = [
            'Employee_No' => Yii::$app->user->identity->{'Employee No_'},
        ];

        $results = \Yii::$app->navhelper->getData($service, $filter);
        //Yii::$app->recruitment->printrr($results);
        $result = [];
        foreach ($results as $item) {

            if (!empty($item->No)) {
                $link = $updateLink = $deleteLink =  '';
                $Viewlink = Html::a('<i class="fas fa-eye"></i>', ['view', 'No' => $item->No], ['class' => 'btn btn-outline-primary btn-xs', 'title' => 'View Request.']);
                if ($item->Status == 'New') {
                    $link = Html::a('<i class="fas fa-paper-plane"></i>', ['send-for-approval', 'No' => $item->No], ['title' => 'Send Approval Request', 'class' => 'btn btn-primary btn-xs']);
                    $updateLink = Html::a('<i class="far fa-edit"></i>', ['update', 'No' => $item->No], ['class' => 'btn btn-info btn-xs', 'title' => 'Update Request']);
                } else if ($item->Status == 'Pending_Approval') {
                    $link = Html::a('<i class="fas fa-times"></i>', ['cancel-request', 'No' => $item->No], ['title' => 'Cancel Approval Request', 'class' => 'btn btn-warning btn-xs']);
                }

                $result['data'][] = [
                    'Key' => $item->Key,
                    'No' => $item->No,
                    'Title' => !empty($item->Title) ? $item->Title : '',
                    'Employee_No' => !empty($item->Employee_No) ? $item->Employee_No : '',
                    'Employee_Name' => !empty($item->Employee_Name) ? $item->Employee_Name : '',
                    'Global_Dimension_1_Code' => !empty($item->Global_Dimension_1_Code) ? $item->Global_Dimension_1_Code : '',
                    'Status' => $item->Status,
                    'Action' => $Viewlink,

                ];
            }
        }

        return $result;
    }


    public function actionListInitiated()
    {
        $service = Yii::$app->params['ServiceName']['ProcurementInitiated'];
        $filter = [
            'Employee_No' => Yii::$app->user->identity->{'Employee No_'},
        ];

        $results = \Yii::$app->navhelper->getData($service, $filter);
        //Yii::$app->recruitment->printrr($results);
        $result = [];
        foreach ($results as $item) {

            if (!empty($item->No)) {
                $link = $updateLink = $deleteLink =  '';
                $Viewlink = Html::a('<i class="fas fa-eye"></i>', ['view', 'No' => $item->No], ['class' => 'btn btn-outline-primary btn-xs', 'title' => 'View Request.']);
                if ($item->Status == 'New') {
                    $link = Html::a('<i class="fas fa-paper-plane"></i>', ['send-for-approval', 'No' => $item->No], ['title' => 'Send Approval Request', 'class' => 'btn btn-primary btn-xs']);
                    $updateLink = Html::a('<i class="far fa-edit"></i>', ['update', 'No' => $item->No], ['class' => 'btn btn-info btn-xs', 'title' => 'Update Request']);
                } else if ($item->Status == 'Pending_Approval') {
                    $link = Html::a('<i class="fas fa-times"></i>', ['cancel-request', 'No' => $item->No], ['title' => 'Cancel Approval Request', 'class' => 'btn btn-warning btn-xs']);
                }

                $result['data'][] = [
                    'Key' => $item->Key,
                    'No' => $item->No,
                    'Title' => !empty($item->Title) ? $item->Title : '',
                    'Employee_No' => !empty($item->Employee_No) ? $item->Employee_No : '',
                    'Employee_Name' => !empty($item->Employee_Name) ? $item->Employee_Name : '',
                    'Global_Dimension_1_Code' => !empty($item->Global_Dimension_1_Code) ? $item->Global_Dimension_1_Code : '',
                    'Global_Dimension_2_Code' => !empty($item->Global_Dimension_2_Code) ? $item->Global_Dimension_2_Code : '',
                    'Global_Dimension_3_Code' => !empty($item->Global_Dimension_3_Code) ? $item->Global_Dimension_3_Code : '',
                    'Created_On' => !empty($item->Created_On) ? $item->Created_On : '',
                    'Status' => $item->Status,
                    'Action' => $Viewlink,

                ];
            }
        }

        return $result;
    }

    /*Get Programs */

    public function getPrograms()
    {
        $service = Yii::$app->params['ServiceName']['DimensionValueList'];

        $filter = [
            'Global_Dimension_No' => 1
        ];

        $result = \Yii::$app->navhelper->getData($service, $filter);
        return ArrayHelper::map($result, 'Code', 'Name');
    }

    /* Get Department*/

    public function getDepartments()
    {
        $service = Yii::$app->params['ServiceName']['DimensionValueList'];

        $filter = [
            'Global_Dimension_No' => 2
        ];
        $result = \Yii::$app->navhelper->getData($service, $filter);
        return ArrayHelper::map($result, 'Code', 'Name');
    }

    /* Get Dimension 3*/

    public function getD3()
    {
        $service = Yii::$app->params['ServiceName']['DimensionValueList'];

        $filter = [
            'Global_Dimension_No' => 3
        ];
        $result = \Yii::$app->navhelper->getData($service, $filter);
        return ArrayHelper::map($result, 'Code', 'Name');
    }







    public function getEmployees()
    {
        $service = Yii::$app->params['ServiceName']['Employees'];

        $employees = \Yii::$app->navhelper->getData($service);
        $data = [];
        $i = 0;
        if (is_array($employees)) {

            foreach ($employees as  $emp) {
                $i++;
                if (!empty($emp->Full_Name) && !empty($emp->No)) {
                    $data[$i] = [
                        'No' => $emp->No,
                        'Full_Name' => $emp->Full_Name
                    ];
                }
            }
        }

        return $data;
    }









    /* Call Approval Workflow Methods */

    public function actionSendForApproval($No)
    {
        $service = Yii::$app->params['ServiceName']['PortalFactory'];

        $data = [
            'applicationNo' => $No,
            'sendMail' => 1,
            'approvalUrl' => '',
        ];


        $result = Yii::$app->navhelper->PortalWorkFlows($service, $data, 'IanSendRequisitionHeaderForApproval');

        if (!is_string($result)) {
            Yii::$app->session->setFlash('success', 'Approval Request Sent to Supervisor Successfully.', true);
            return $this->redirect(['index']);
        } else {

            Yii::$app->session->setFlash('error', 'Error Sending Approval Request for Approval  : ' . $result);
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


        $result = Yii::$app->navhelper->PortalWorkFlows($service, $data, 'IanCancelRequisitionHeaderApprovalRequest');

        if (!is_string($result)) {
            Yii::$app->session->setFlash('success', 'Approval Request Cancelled Successfully.', true);
            return $this->redirect(['index']);
        } else {

            Yii::$app->session->setFlash('error', 'Error Cancelling Approval Approval Request.  : ' . $result);
            return $this->redirect(['index']);
        }
    }


    /**
     * Some utility Functions for controllers
     */


    /** Updates a single field on a form */
    public function actionSetfield($field)
    {
        $service = 'PurchaseRequisitionCard';
        $value = Yii::$app->request->post('fieldValue');

        $result = Yii::$app->navhelper->Commit($service, [$field => $value], Yii::$app->request->post('Key'));
        Yii::$app->response->format = \yii\web\response::FORMAT_JSON;
        return $result;
    }


    public function actionCommit()
    {
        $commitService = Yii::$app->request->post('service');
        $key = Yii::$app->request->post('key');
        $name = Yii::$app->request->post('name');
        $value = Yii::$app->request->post('value');

        $service = Yii::$app->params['ServiceName'][$commitService];
        $request = Yii::$app->navhelper->readByKey($service, $key);
        $data = [];
        if (is_object($request)) {
            $data = [
                'Key' => $request->Key,
                $name => $value
            ];
        } else {
            Yii::$app->response->format = \yii\web\response::FORMAT_JSON;
            return ['error' => $request];
        }

        $result = Yii::$app->navhelper->updateData($service, $data);
        Yii::$app->response->format = \yii\web\response::FORMAT_JSON;
        return $result;
    }

    public function actionAddLine($Service, $Document_No)
    {
        $service = Yii::$app->params['ServiceName'][$Service];
        $data = [
            'Requisition_No' => $Document_No,
            'Line_No' => time()
        ];

        // Insert Record

        $result = Yii::$app->navhelper->postData($service, $data);

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (is_object($result)) {
            return [
                'note' => 'Record Created Successfully.',
                'result' => $result
            ];
        } else {
            return ['note' => $result];
        }
    }

    public function actionDeleteLine($Service, $Key)
    {
        $service = Yii::$app->params['ServiceName'][$Service];
        $result = Yii::$app->navhelper->deleteData($service, Yii::$app->request->get('Key'));
        Yii::$app->session->setFlash('success', 'Record Deleted Successfully.', true);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (!is_string($result)) {
            return [
                'note' => 'Record Deleted Successfully.',
                'result' => $result
            ];
        } else {
            return ['note' => $result];
        }
    }


    public function getDimension($value)
    {
        $service = Yii::$app->params['ServiceName']['DimensionValueList'];
        $filter = ['Global_Dimension_No' => $value];
        $result = \Yii::$app->navhelper->getData($service, $filter);

        return Yii::$app->navhelper->refactorArray($result, 'Code', 'Name');
    }

    public function actionDimension1()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $this->getDimension(1);
    }

    public function actionDimension2()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $this->getDimension(2);
    }


    // Get Transaction Types

    public function actionTypes()
    {
        $data = [
            'G_L_Account' => 'G_L_Account',
            'Fixed_Asset' => 'Fixed_Asset',
            'Item' => 'Item'
        ];
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
    }




    // Get Donors

    public function actionDonors()
    {
        $filter = [];
        $data = Yii::$app->navhelper->dropdown('CustomerLookup', 'No', 'Name', $filter, ['No']);
        ksort($data);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
    }


    // Get Grants

    public function actionGrants()
    {
        $filter = [];
        $data = Yii::$app->navhelper->dropdown('GrantLookUp', 'No', 'Title', $filter, ['No']);
        ksort($data);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
    }

    // Get Filtered Objectives

    public function actionObjectives()
    {
        $data = file_get_contents('php://input');
        $params = json_decode($data);
        $service = Yii::$app->params['ServiceName']['GrantLinesLookUp'];
        $filter = [
            'Grant_No' => $params->Grant_No,
            'Line_Type' => 'Objective'
        ];

        $data = Yii::$app->navhelper->dropdown('GrantLinesLookUp', 'Code', 'Description', $filter, ['Code']);
        ksort($data);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
    }

    // Get Filtered Outputs

    public function actionOutputs()
    {
        $data = file_get_contents('php://input');
        $params = json_decode($data);
        $service = Yii::$app->params['ServiceName']['GrantLinesLookUp'];
        $filter = [
            'Grant_No' => $params->Grant_No,
            'Line_Type' => 'Output'
        ];
        $data = Yii::$app->navhelper->dropdown('GrantLinesLookUp', 'Code', 'Description', $filter, ['Code']);
        ksort($data);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
    }


    // Get Filtered OutCome

    public function actionOutcome()
    {
        $data = file_get_contents('php://input');
        $params = json_decode($data);
        $filter = [
            'Grant_No' => $params->Grant_No,
            'Line_Type' => 'Outcome'
        ];
        $data = Yii::$app->navhelper->dropdown('GrantLinesLookUp', 'Code', 'Description', $filter, ['Code']);
        ksort($data);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
    }


    // Get Filterd Activities

    public function actionActivities()
    {

        $data = file_get_contents('php://input');
        $params = json_decode($data);
        $service = Yii::$app->params['ServiceName']['GrantLinesLookUp'];
        $filter = [
            'Grant_No' => $params->Grant_No,
            'Line_Type' => 'Activity'
        ];
        $data = Yii::$app->navhelper->dropdown('GrantLinesLookUp', 'Code', 'Description', $filter, ['Code']);
        ksort($data);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
    }

    public function actionPartners()
    {
        $data = file_get_contents('php://input');
        $jsonParams = json_decode($data);
        $filter = [
            'Grant_Code' => $jsonParams->Grant_No
        ];
        $data = Yii::$app->navhelper->dropdown('GrantDetailLines', 'G_L_Account_No', 'Activity_Description', $filter, ['G_L_Account_No']);
        ksort($data);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
    }

    public function actionNo()
    {
        $data = file_get_contents('php://input');
        $jsonParams = json_decode($data);
        $type = $jsonParams->Type;

        if ($type == 'G_L_Account') {
            $filter = [
                'Direct_Posting' => true,
                'Income_Balance' => 'Income_Statement'
            ];
            $data = Yii::$app->navhelper->dropdown('GLAccountList', 'No', 'Name', $filter, ['No']);
        } elseif ($type == 'Item') {
            $filter = [];
            $data = Yii::$app->navhelper->dropdown('Items', 'No', 'Description', $filter, ['No']);
            krsort($data);
        } elseif ($type == 'Fixed_Asset') {
            $data = Yii::$app->navhelper->dropDown('FixedAssets', 'No', 'Description', [], ['No']);
            krsort($data);
        }

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
    }

    public function actionLocations()
    {
        $data = Yii::$app->navhelper->dropdown('Locations', 'Code', 'Name', [], ['Code']);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
    }

    public function actionProcurementMethods()
    {
        $data = [
            '_blank_' => '_blank_',
            'Tender' => 'Tender',
            'RFQ' => 'RFQ',
            'Direct_Procurement' => 'Direct_Procurement',
            'RFP' => 'RFP'
        ];
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
    }


    public function actionUpload()
    {

        $targetPath = '';
        if ($_FILES) {
            $uploadedFile = $_FILES['attachment']['name'];
            list($pref, $ext) = explode('.', $uploadedFile);
            $targetPath = './uploads/' . Yii::$app->security->generateRandomString(5) . '.' . $ext; // Create unique target upload path

            // Create upload directory if it dnt exist.
            if (!is_dir(dirname($targetPath))) {
                FileHelper::createDirectory(dirname($targetPath));
                chmod(dirname($targetPath), 0755);
            }
        }

        // Upload
        if (Yii::$app->request->isPost) {
            $DocumentService = Yii::$app->params['ServiceName'][Yii::$app->request->post('DocumentService')];
            $parentDocument = Yii::$app->navhelper->readByKey($DocumentService, Yii::$app->request->post('Key'));

            $metadata = [];
            if (is_object($parentDocument) && isset($parentDocument->Key)) {
                $metadata = [
                    'Application' => $parentDocument->No,
                    'Employee' => $parentDocument->Employee_No,
                    'Leavetype' => 'Purchase Requisition - ' . $parentDocument->Title,
                ];
            }
            Yii::$app->session->set('metadata', $metadata);

            $file = $_FILES['attachment']['tmp_name'];
            //Return JSON
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if (move_uploaded_file($file, $targetPath)) {
                // Upload to sharepoint
                $spResult = Yii::$app->recruitment->sharepoint_attach($targetPath);
                return [
                    'status' => 'success',
                    'message' => 'File Uploaded Successfully' . $spResult,
                    'filePath' => $targetPath
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Could not upload file at the moment.'
                ];
            }
        }


        // Update Nav -  Get Request
        if (Yii::$app->request->isGet) {
            $fileName = basename(Yii::$app->request->get('filePath'));

            $DocumentService = Yii::$app->params['ServiceName'][Yii::$app->request->get('documentService')];
            $AttachmentService = Yii::$app->params['ServiceName'][Yii::$app->request->get('Service')];
            $Document = Yii::$app->navhelper->readByKey($DocumentService, Yii::$app->request->get('Key'));

            $data = [];
            if (is_object($Document) && isset($Document->No)) {
                $data = [
                    'Document_No' => $Document->No,
                    'Name' => $fileName,
                    'File_path' => \yii\helpers\Url::home(true) . 'uploads/' . $fileName,
                ];
            }

            // Update Nav
            $result = Yii::$app->navhelper->postData($AttachmentService, $data);

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if (is_object($result)) {
                return $result;
            } else {
                return $result;
            }
        }
    }


    public function actionUploadMultiple()
    {

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if ($_POST['Key']) {
            $Key = $_POST['Key'];
            $DocumentService = $_POST['DocumentService'];
            $AttachmentService =  $_POST['attachmentService'];
            $Document = Yii::$app->navhelper->readByKey($DocumentService, $Key);
            $NavResult = [];
            for ($i = 0; $i < count($_FILES['attachments']['name']); $i++) {
                $files[] =  $_FILES['attachments']['name'][$i];
                list($pref, $ext) = explode('.',  $_FILES['attachments']['name'][$i]);
                $targetPath = './uploads/' . Yii::$app->security->generateRandomString(5) . '.' . $ext; // Create unique target upload path

                // Create upload directory if it dnt exist.
                if (!is_dir(dirname($targetPath))) {
                    FileHelper::createDirectory(dirname($targetPath));
                    chmod(dirname($targetPath), 0755);
                }

                if (move_uploaded_file($_FILES['attachments']['tmp_name'][$i], $targetPath)) {
                    // Upload to sharepoint
                    $metadata = [
                        'Application' => $Document->No,
                        'Employee' => $Document->Employee_No,
                        'Leavetype' => 'Purchase Requisition - ' . $Document->Title,
                    ];

                    Yii::$app->session->set('metadata', $metadata);
                    $spResult = Yii::$app->recruitment->sharepoint_attach($targetPath);

                    // Update Nav Attachment Table
                    $data = [
                        'Document_No' => $Document->No,
                        'Name' => basename($targetPath),
                        'File_path' => \yii\helpers\Url::home(true) . 'uploads/' . basename($targetPath),
                    ];
                    $NavResult[] = Yii::$app->navhelper->postData($AttachmentService, $data);
                }
            }

            return [
                'status' =>  true,
                'data' => $_REQUEST,
                'files' => $_FILES,
                'extract' =>  $files,
                'parentDocument' => $Document,
                'sharepointResult' => $spResult,
                'NavAttachmentResults' => $NavResult
            ];
        }
    }
}
