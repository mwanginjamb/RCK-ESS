<?php

/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 3/9/2020
 * Time: 4:21 PM
 */

namespace frontend\controllers;

use frontend\models\Careerdevelopmentstrength;
use frontend\models\Employeeappraisalkra;
use frontend\models\Experience;
use frontend\models\Fundrequisition;
use frontend\models\Imprestcard;
use frontend\models\Imprestline;
use frontend\models\Imprestsurrendercard;
use frontend\models\Leaveplancard;
use frontend\models\Trainingplan;
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
use yii\helpers\FileHelper;

class FundRequisitionController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup', 'index', 'requestlist', 'create', 'update', 'delete'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'index', 'requestlist', 'create', 'update', 'delete'],
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
                'only' => ['getrequests'],
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
            'dimension1', 'dimension2', 'transactiontypes',
            'grants', 'objectives', 'outputs', 'outcome',
            'activities', 'partners', 'donors', 'upload', 'accounts', 'rates', 'employees'
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

    public function actionSurrenderlist()
    {

        return $this->render('surrenderlist');
    }

    public function actionCreate()
    {

        $model = new Fundrequisition();
        $service = Yii::$app->params['ServiceName']['AllowanceRequestCard'];

        /*Do initial request */

        $model->Employee_No = Yii::$app->user->identity->{'Employee No_'};
        // $model->Currency_Code = 'KES';
        $request = Yii::$app->navhelper->postData($service, $model);
        if (is_object($request)) {
            Yii::$app->navhelper->loadmodel($request, $model);
            return $this->redirect(['update', 'Key' => $request->Key]);
        } else {
            Yii::$app->session->setFlash('error', $request);
            return $this->redirect(['index']);
        }
    }





    public function actionUpdate($No = '', $Key = '')
    {
        $model = new Fundrequisition();
        $service = Yii::$app->params['ServiceName']['AllowanceRequestCard'];
        $model->isNewRecord = false;

        if (!empty($No)) {
            $result = Yii::$app->navhelper->findOne($service, '', 'No', $No);
        } elseif (!empty($Key)) {
            $result = Yii::$app->navhelper->readByKey($service, $Key);
        } else {
            Yii::$app->session->setFlash('error', 'You are accessing the document illegally.', true);
            return $this->redirect(['index']);
        }

        // Update only If Document is New

        if ($result->Status !== 'New') {
            Yii::$app->session->setFlash('error', 'Your document status is: <b>' . $result->Status . '</b>, it can only be updated if status is <u><b>New</b></u>', true);
            return $this->redirect(['index']);
        }


        if (is_object($result)) {
            //load nav result to model
            $model = Yii::$app->navhelper->loadmodel($result, $model); //$this->loadtomodeEmployee_Nol($result[0],$Expmodel);
        } else {
            Yii::$app->session->setFlash('error', $result, true);
            return $this->redirect(['index']);
        }


        if (Yii::$app->request->post() && Yii::$app->navhelper->loadpost(Yii::$app->request->post()['Fundrequisition'], $model)) {


            $refresh = Yii::$app->navhelper->readByKey($service, $model->Key);
            Yii::$app->navhelper->loadmodel($refresh, $model);


            $result = Yii::$app->navhelper->updateData($service, $model);
            if (!is_string($result)) {
                Yii::$app->session->setFlash('success', 'Fund Request Updated Successfully.');

                return $this->redirect(['view', 'No' => $result->No]);
            } else {

                Yii::$app->session->setFlash('error', 'Error Updating Fund Request ' . $result);
                return $this->redirect(['index']);
            }
        }


        // Yii::$app->recruitment->printrr($model);
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('update', [
                'model' => $model,
                'employees' => $this->getEmployees(),
                'programs' => $this->getPrograms(),
                'departments' => $this->getDepartments(),
                'currencies' => Yii::$app->navhelper->dropdown('Currencies', 'Code', 'Description'),
                'document' => $result,
                'donors' => Yii::$app->navhelper->dropdown('CustomerLookup', 'No', 'Name')
            ]);
        }

        return $this->render('update', [
            'model' => $model,
            'employees' => $this->getEmployees(),
            'programs' => $this->getPrograms(),
            'departments' => $this->getDepartments(),
            'currencies' => Yii::$app->navhelper->dropdown('Currencies', 'Code', 'Description'),
            'document' => $result,
            'attachments' => Yii::$app->navhelper->getData(Yii::$app->params['ServiceName']['LeaveAttachments'], ['Document_No' => $model->No])
        ]);
    }

    public function actionDelete()
    {
        $service = Yii::$app->params['ServiceName']['CareerDevStrengths'];
        $result = Yii::$app->navhelper->deleteData($service, Yii::$app->request->get('Key'));
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (!is_string($result)) {

            return ['note' => '<div class="alert alert-success">Record Purged Successfully</div>'];
        } else {
            return ['note' => '<div class="alert alert-danger">Error Purging Record: ' . $result . '</div>'];
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

    public function actionView($No)
    {
        $service = Yii::$app->params['ServiceName']['AllowanceRequestCard'];
        $model = new Fundrequisition();

        $result = Yii::$app->navhelper->findOne($service, '', 'No', $No);

        //load nav result to model
        $model = $this->loadtomodel($result, $model);

        // Yii::$app->recruitment->printrr($model);

        return $this->render('view', [
            'model' => $model,
            'document' => $result,
            'attachments' => Yii::$app->navhelper->getData(Yii::$app->params['ServiceName']['LeaveAttachments'], ['Document_No' => $model->No]),
        ]);
    }

    /*Imprest surrender card view*/

    public function actionViewSurrender($No)
    {
        $service = Yii::$app->params['ServiceName']['ImprestSurrenderCard'];

        $filter = [
            'No' => $No
        ];

        $result = Yii::$app->navhelper->getData($service, $filter);
        //load nav result to model
        $model = $this->loadtomodel($result[0], new Imprestsurrendercard());

        return $this->render('viewsurrender', [
            'model' => $model,
            'employees' => $this->getEmployees(),
            'programs' => $this->getPrograms(),
            'departments' => $this->getDepartments(),
            'currencies' => Yii::$app->navhelper->dropdown('Currencies', 'Code', 'Description')
        ]);
    }

    // Get imprest list

    public function actionGetimprests()
    {
        $service = Yii::$app->params['ServiceName']['ImprestRequestListPortal'];
        $filter = [
            'Employee_No' => Yii::$app->user->identity->Employee[0]->No,
        ];
        //Yii::$app->recruitment->printrr( );
        $results = \Yii::$app->navhelper->getData($service, $filter);
        $result = [];
        foreach ($results as $item) {
            $link = $updateLink = $deleteLink =  '';
            $Viewlink = Html::a('<i class="fas fa-eye"></i>', ['view', 'No' => $item->No], ['class' => 'btn btn-outline-primary btn-xs']);
            if ($item->Status == 'New') {
                $link = Html::a('<i class="fas fa-paper-plane"></i>', ['send-for-approval', 'No' => $item->No], ['title' => 'Send Approval Request', 'class' => 'btn btn-primary btn-xs']);

                $updateLink = Html::a('<i class="far fa-edit"></i>', ['update', 'No' => $item->No], ['class' => 'btn btn-info btn-xs']);
            } else if ($item->Status == 'Pending_Approval') {
                $link = Html::a('<i class="fas fa-times"></i>', ['cancel-request', 'No' => $item->No], ['title' => 'Cancel Approval Request', 'class' => 'btn btn-warning btn-xs']);
            }

            $result['data'][] = [
                'Key' => $item->Key,
                'No' => $item->No,
                'Employee_No' => !empty($item->Employee_No) ? $item->Employee_No : '',
                'Employee_Name' => !empty($item->Employee_Name) ? $item->Employee_Name : '',
                'Purpose' => !empty($item->Purpose) ? $item->Purpose : '',
                'Imprest_Amount' => !empty($item->Imprest_Amount) ? $item->Imprest_Amount : '',
                'Status' => $item->Status,
                'Action' => $link,
                'Update_Action' => $updateLink,
                'view' => $Viewlink
            ];
        }

        return $result;
    }

    // Get Fund Request list

    public function actionGetrequests()
    {
        $service = Yii::$app->params['ServiceName']['AllowanceRequestList'];
        $filter = [
            'Employee_No' => Yii::$app->user->identity->{'Employee No_'},
        ];
        //Yii::$app->recruitment->printrr( );
        $results = \Yii::$app->navhelper->getData($service, $filter);
        $result = [];


        if (is_array($result)) {
            foreach ($results as $item) {

                if (empty($item->No)) {
                    continue;
                }

                $link = $updateLink = $deleteLink =  '';
                $Viewlink = Html::a('<i class="fas fa-eye"></i>', ['view', 'No' => $item->No], ['class' => 'btn btn-outline-primary btn-xs']);
                if ($item->Status == 'New') {
                    $link = Html::a('<i class="fas fa-paper-plane"></i>', ['send-for-approval', 'No' => $item->No], ['title' => 'Send Approval Request', 'class' => 'btn btn-primary btn-xs']);

                    $updateLink = Html::a('<i class="far fa-edit"></i>', ['update', 'No' => $item->No], ['class' => 'btn btn-info btn-xs']);
                } else if ($item->Status == 'Pending_Approval') {
                    $link = Html::a('<i class="fas fa-times"></i>', ['cancel-request', 'No' => $item->No], ['title' => 'Cancel Approval Request', 'class' => 'btn btn-warning btn-xs']);
                }

                $result['data'][] = [
                    'Key' => $item->Key,
                    'No' => $item->No,
                    'Employee_No' => !empty($item->Employee_No) ? $item->Employee_No : '',
                    'Employee_Name' => !empty($item->Employee_Name) ? $item->Employee_Name : '',
                    'Purpose' => !empty($item->Purpose) ? $item->Purpose : '',
                    'Gross_Allowance' => !empty($item->Gross_Amount) ? $item->Gross_Amount : '',
                    'Status' => $item->Status,
                    'Action' => $link,
                    'Update_Action' => $updateLink,
                    'view' => $Viewlink
                ];
            }
        }



        return $result;
    }


    public function getEmployees()
    {
        $service = Yii::$app->params['ServiceName']['Employees'];

        $employees = \Yii::$app->navhelper->getData($service);
        return ArrayHelper::map($employees, 'No', 'FullName');
    }

    /* My Imprests*/

    public function getmyimprests()
    {
        $service = Yii::$app->params['ServiceName']['PostedImprestRequest'];
        $filter = [
            'Employee_No' => Yii::$app->user->identity->Employee[0]->No,
            'Surrendered' => false,
        ];

        $results = \Yii::$app->navhelper->getData($service, $filter);

        $result = [];
        $i = 0;
        if (is_array($results)) {
            foreach ($results as $res) {
                $result[$i] = [
                    'No' => $res->No,
                    'detail' => $res->No . ' - ' . $res->Imprest_Amount
                ];
                $i++;
            }
        }
        // Yii::$app->recruitment->printrr(ArrayHelper::map($result,'No','detail'));
        return ArrayHelper::map($result, 'No', 'detail');
    }

    /* Get My Posted Imprest Receipts */

    public function getimprestreceipts($imprestNo)
    {
        $service = Yii::$app->params['ServiceName']['PostedReceiptsList'];
        $filter = [
            'Employee_No' => Yii::$app->user->identity->Employee[0]->No,
            'Imprest_No' => $imprestNo,
        ];

        $results = \Yii::$app->navhelper->getData($service, $filter);

        $result = [];
        $i = 0;
        if (is_array($results)) {
            foreach ($results as $res) {
                $result[$i] = [
                    'No' => $res->No,
                    'detail' => $res->No . ' - ' . $res->Imprest_No
                ];
                $i++;
            }
        }
        // Yii::$app->recruitment->printrr(ArrayHelper::map($result,'No','detail'));
        return ArrayHelper::map($result, 'No', 'detail');
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


    // Get Currencies

    public function getCurrencies()
    {
        $service = Yii::$app->params['ServiceName']['Currencies'];

        $result = \Yii::$app->navhelper->getData($service, []);
        return ArrayHelper::map($result, 'Code', 'Description');
    }

    public function actionSetpurpose()
    {
        $model = new Fundrequisition();
        $service = Yii::$app->params['ServiceName']['AllowanceRequestCard'];

        $request = Yii::$app->navhelper->postData($service, []);

        if (is_object($request)) {
            Yii::$app->navhelper->loadmodel($request, $model);
            $model->Key = $request->Key;
            $model->Purpose = Yii::$app->request->post('Purpose');
            $model->Employee_No = Yii::$app->user->identity->{'Employee_No'};
        }

        // Refresh record you are updating

        $refresh = Yii::$app->navhelper->getData($service, ['No' => $model->No]);
        Yii::$app->navhelper->loadmodel($refresh[0], $model);


        $result = Yii::$app->navhelper->updateData($service, $model);

        Yii::$app->response->format = \yii\web\response::FORMAT_JSON;

        return $result;
    }

    public function actionSetdimension($dimension)
    {
        $model = new Imprestcard();
        $service = Yii::$app->params['ServiceName']['ImprestRequestCardPortal'];

        $filter = [
            'No' => Yii::$app->request->post('No')
        ];
        $request = Yii::$app->navhelper->getData($service, $filter);

        if (is_array($request)) {
            Yii::$app->navhelper->loadmodel($request[0], $model);
            $model->Key = $request[0]->Key;
            $model->{$dimension} = Yii::$app->request->post('dimension');
        }


        $result = Yii::$app->navhelper->updateData($service, $model);

        Yii::$app->response->format = \yii\web\response::FORMAT_JSON;

        return $result;
    }

    /* Set Imprest Type */

    public function actionSetimpresttype()
    {
        $model = new Imprestcard();
        $service = Yii::$app->params['ServiceName']['ImprestRequestCardPortal'];

        $filter = [
            'No' => Yii::$app->request->post('No')
        ];
        $request = Yii::$app->navhelper->getData($service, $filter);

        if (is_array($request)) {
            Yii::$app->navhelper->loadmodel($request[0], $model);
            $model->Key = $request[0]->Key;
            $model->Imprest_Type = Yii::$app->request->post('Imprest_Type');
        }


        $result = Yii::$app->navhelper->updateData($service, $model, ['Amount_LCY']);

        Yii::$app->response->format = \yii\web\response::FORMAT_JSON;

        return $result;
    }

    /*Set Imprest to Surrend*/

    public function actionSetimpresttosurrender()
    {
        $model = new Imprestsurrendercard();
        $service = Yii::$app->params['ServiceName']['ImprestSurrenderCardPortal'];

        $filter = [
            'No' => Yii::$app->request->post('No')
        ];
        $request = Yii::$app->navhelper->getData($service, $filter);

        if (is_array($request)) {
            Yii::$app->navhelper->loadmodel($request[0], $model);
            $model->Key = $request[0]->Key;
            $model->Imprest_No = Yii::$app->request->post('Imprest_No');
        }


        $result = Yii::$app->navhelper->updateData($service, $model);

        Yii::$app->response->format = \yii\web\response::FORMAT_JSON;

        return $result;
    }

    public function loadtomodel($obj, $model)
    {

        if (!is_object($obj)) {
            return false;
        }
        $modeldata = (get_object_vars($obj));
        foreach ($modeldata as $key => $val) {
            if (is_object($val)) continue;
            $model->$key = $val;
        }

        return $model;
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


        $result = Yii::$app->navhelper->PortalWorkFlows($service, $data, 'IanSendImprestForApproval');

        if (!is_string($result)) {
            Yii::$app->session->setFlash('success', 'Approval Request Sent to Supervisor Successfully.', true);
            return $this->redirect(['view', 'No' => $No]);
        } else {

            Yii::$app->session->setFlash('error', 'Error Sending Approval Request for Approval  : ' . $result);
            return $this->redirect(['view', 'No' => $No]);
        }
    }

    /*Cancel Approval Request */

    public function actionCancelRequest($No)
    {
        $service = Yii::$app->params['ServiceName']['PortalFactory'];

        $data = [
            'applicationNo' => $No,
        ];


        $result = Yii::$app->navhelper->PortalWorkFlows($service, $data, 'IanCancelImprestForApproval');

        if (!is_string($result)) {
            Yii::$app->session->setFlash('success', 'Imprest Request Cancelled Successfully.', true);
            return $this->redirect(['view', 'No' => $No]);
        } else {

            Yii::$app->session->setFlash('error', 'Error Cancelling Imprest Approval Request.  : ' . $result);
            return $this->redirect(['view', 'No' => $No]);
        }
    }

    /*Print Surrender*/
    public function actionPrintRequisition($No)
    {
        $service = Yii::$app->params['ServiceName']['PortalReports'];
        $data = [
            'fundsReq' => $No
        ];
        $path = Yii::$app->navhelper->PortalReports($service, $data, 'IanGenerateFundsRequisition');
        if (!is_file($path['return_value'])) {
            Yii::$app->session->setFlash('error', 'File is not available: ' . $path['return_value']);
            return $this->render('printout', [
                'report' => false,
                'content' => null,
                'No' => $No
            ]);
        }

        $binary = file_get_contents($path['return_value']);
        $content = chunk_split(base64_encode($binary));
        //delete the file after getting it's contents --> This is some house keeping
        unlink($path['return_value']);
        return $this->render('printout', [
            'report' => true,
            'content' => $content,
            'No' => $No
        ]);
    }

    /** Updates a single field */
    public function actionSetfield($field)
    {
        $service = 'AllowanceRequestCard';
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
            'Request_No' => $Document_No,
            'Employee_No' => Yii::$app->user->identity->{'Employee No_'},
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

    public function actionEmployees()
    {
        $data = Yii::$app->navhelper->dropdown('EmployeesUnfiltered', 'No', 'Full_Name');
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
    }


    // Get Transaction Types

    public function actionTransactiontypes()
    {
        $data = Yii::$app->navhelper->dropdown('PaymentTypes', 'Code', 'Description', [], ['Code']);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
    }

    public function actionRates()
    {
        $data = Yii::$app->navhelper->dropdown('RequisitionRates', 'Code', 'Description', [], ['Code']);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
    }

    public function actionAccounts()
    {
        $data = Yii::$app->navhelper->dropdown('GLAccountList', 'No', 'Name', [], ['No']);
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

    // File reader action -- Model Neutral Function --Ooh shit, @francnjamb

    public function actionRead()
    {
        $path = Yii::$app->request->post('path');
        $No = Yii::$app->request->post('No');
        $file = basename($path);
        $library = env('SP_LIBRARY');
        $SP_RESOURCE_PATH = '/sites/RCKIntranet/' . $library . '/' . $file;
        $content = Yii::$app->recruitment->download($SP_RESOURCE_PATH);

        return $this->render('read', [
            'path' => $path,
            'No' => $No,
            'content' => $content
        ]);
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
                    'Leavetype' => 'Imprest - ' . $parentDocument->Purpose,
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
}
