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
use frontend\models\Imprestcard;
use frontend\models\Imprestline;
use frontend\models\Imprestsurrendercard;
use frontend\models\Leaveattachment;
use frontend\models\Leaveplancard;
use frontend\models\Leave;
use frontend\models\Salaryadvance;
use frontend\models\Trainingplan;
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
use yii\web\UploadedFile;

class LeaveController extends Controller
{
    private $metadata = [];

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup', 'index', 'advance-list', 'create', 'update', 'delete', 'view'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'index', 'advance-list', 'create', 'update', 'delete', 'view'],
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
                'only' => ['list'],
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
            'upload', 'upload-multiple'
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


    public function actionCreate()
    {

        $model = new Leave();
        $service = Yii::$app->params['ServiceName']['LeaveCard'];

        /*Do initial request */
        if (!isset(Yii::$app->request->post()['Leave']) && empty($_FILES)) {

            $now = date('Y-m-d');
            $model->Start_Date = date('Y-m-d', strtotime($now . ' + 2 days'));
            $model->Employee_No = Yii::$app->user->identity->{'Employee No_'};
            $request = Yii::$app->navhelper->postData($service, $model);
            //Yii::$app->recruitment->printrr($request);
            if (is_object($request)) {
                Yii::$app->navhelper->loadmodel($request, $model);
                return $this->redirect(['update', 'No' => $model->Application_No]);
            } else {
                Yii::$app->session->setFlash('error', 'Error : ' . $request, true);
                return $this->redirect(['index']);
            }
        } /*End Application Initialization*/

        if (Yii::$app->request->post() && !empty(Yii::$app->request->post()['Leave']) && Yii::$app->navhelper->loadpost(Yii::$app->request->post()['Leave'], $model)) {

            $filter = [
                'Application_No' => $model->Application_No,
            ];
            /*Read the card again to refresh Key in case it changed*/
            $refresh = Yii::$app->navhelper->getData($service, $filter);
            $model->Key = $refresh[0]->Key;

            //Yii::$app->recruitment->printrr($refresh );
            Yii::$app->navhelper->loadmodel($refresh[0], $model);


            if (!empty($_FILES['Leave']['name']['attachment'])) {

                $this->metadata = [
                    'Application' => $model->Application_No,
                    'Employee' => $model->Employee_No,
                    'Leavetype' => $model->Leave_Type_Decription,
                ];

                Yii::$app->session->set('metadata', $this->metadata);
                $model->attachment = UploadedFile::getInstance($model, 'attachment');
                $model->upload();
            }



            $result = Yii::$app->navhelper->updateData($service, $model);
            if (!is_string($result)) {

                Yii::$app->session->setFlash('success', 'Leave Request Created Successfully.');
                return $this->redirect(['view', 'No' =>  $refresh[0]->Application_No]);
            } else {
                Yii::$app->session->setFlash('error', 'Error Creating Leave Request : ' . $result);
                return $this->redirect(['view', 'No' => $refresh[0]->Application_No]);
            }
        }




        return $this->render('create', [
            'model' => $model,
            'leavetypes' => $this->getLeaveTypes(),
            'employees' => $this->getEmployees(),
        ]);
    }

    public function actionAttach()
    {
        // Upload Attachment File
        if (!empty($_FILES)) {
            $Attachmentmodel = new Leaveattachment();
            $Attachmentmodel->Document_No =  Yii::$app->request->post()['Leaveattachment']['Document_No'];
            $Attachmentmodel->attachmentfile = UploadedFile::getInstanceByName('attachmentfile');

            $result = $Attachmentmodel->Upload($Attachmentmodel->Document_No);


            return $result;
        }
    }




    public function actionUpdate()
    {
        $model = new Leave();
        $service = Yii::$app->params['ServiceName']['LeaveCard'];
        $model->isNewRecord = false;

        $filter = [
            'Application_No' => Yii::$app->request->get('No'),
        ];
        $result = Yii::$app->navhelper->getData($service, $filter);

        if (is_array($result)) {
            //load nav result to model
            $model = Yii::$app->navhelper->loadmodel($result[0], $model); //$this->loadtomodeEmployee_Nol($result[0],$Expmodel);
        } else {
            Yii::$app->recruitment->printrr($result);
        }



        if (Yii::$app->request->post() && Yii::$app->navhelper->loadpost(Yii::$app->request->post()['Leave'], $model)) {
            $filter = [
                'Application_No' => $model->Application_No,
            ];
            /*Read the card again to refresh Key in case it changed*/
            $refresh = Yii::$app->navhelper->getData($service, $filter);
            $model->Key = $refresh[0]->Key;

            $result = Yii::$app->navhelper->updateData($service, $model);

            if (!is_string($result)) {

                Yii::$app->session->setFlash('success', 'Leave Updated Successfully.');

                return $this->redirect(['view', 'No' => $result->Application_No]);
            } else {
                Yii::$app->session->setFlash('success', 'Error Updating Leave Document ' . $result);
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'leavetypes' => $this->getLeaveTypes(),
            'employees' => $this->getEmployees(),
            'attachments' => Yii::$app->navhelper->getData(Yii::$app->params['ServiceName']['LeaveAttachments'], ['Document_No' => $model->Application_No])
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
                    'Application' => $parentDocument->Application_No,
                    'Employee' => $parentDocument->Employee_No,
                    'Leavetype' => $parentDocument->Leave_Type_Decription,
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
            if (is_object($Document) && isset($Document->Application_No)) {
                $data = [
                    'Document_No' => $Document->Application_No,
                    'Name' => $fileName,
                    'File_path' => \yii\helpers\Url::home(true) . 'uploads/' . $fileName
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

    public function actionDelete()
    {
        $service = Yii::$app->params['ServiceName']['LeaveCard'];
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
        // exit($No);
        $model = new Leave();
        $service = Yii::$app->params['ServiceName']['LeaveCard'];

        $filter = [
            'Application_No' => $No
        ];

        $result = Yii::$app->navhelper->getData($service, $filter);

        //load nav result to model
        $model = $this->loadtomodel($result[0], $model);

        //Yii::$app->recruitment->printrr(Yii::$app->navhelper->getData(Yii::$app->params['ServiceName']['LeaveAttachments'], []));

        return $this->render('view', [
            'model' => $model,
            'attachments' => Yii::$app->navhelper->getData(Yii::$app->params['ServiceName']['LeaveAttachments'], ['Document_No' => $model->Application_No])
        ]);
    }

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



    // Get imprest list

    public function actionList()
    {
        $service = Yii::$app->params['ServiceName']['LeaveList'];
        $filter = [
            'Employee_No' => Yii::$app->user->identity->Employee[0]->No,
        ];

        $results = \Yii::$app->navhelper->getData($service, $filter);
        $result = [];
        foreach ($results as $item) {
            $link = $updateLink = $deleteLink =  '';
            $Viewlink = Html::a('<i class="fas fa-eye"></i>', ['view', 'No' => $item->Application_No], ['class' => 'btn btn-outline-primary btn-xs']);
            if ($item->Status == 'New') {
                $link = Html::a('<i class="fas fa-paper-plane"></i>', ['send-for-approval', 'No' => $item->Application_No], ['title' => 'Send Approval Request', 'class' => 'btn btn-primary btn-xs']);
                $updateLink = Html::a('<i class="far fa-edit"></i>', ['update', 'No' => $item->Application_No], ['class' => 'btn btn-info btn-xs']);
            } else if ($item->Status == 'Pending_Approval') {
                $link = Html::a('<i class="fas fa-times"></i>', ['cancel-request', 'No' => $item->Application_No], ['title' => 'Cancel Approval Request', 'class' => 'btn btn-warning btn-xs']);
            }

            $result['data'][] = [
                'Key' => $item->Key,
                'No' => $item->Application_No,
                'Employee_No' => !empty($item->Employee_No) ? $item->Employee_No : '',
                'Employee_Name' => !empty($item->Employee_Name) ? $item->Employee_Name : '',
                'Application_Date' => !empty($item->Application_Date) ? $item->Application_Date : '',
                'Status' => $item->Status,
                'Action' => $link,
                'Update_Action' => $updateLink,
                'view' => $Viewlink
            ];
        }

        return $result;
    }

    // Get Imprest  surrender list

    public function actionGetimprestsurrenders()
    {
        $service = Yii::$app->params['ServiceName']['ImprestSurrenderList'];
        $filter = [
            'Employee_No' => Yii::$app->user->identity->{'Employee No_'},
        ];
        //Yii::$app->recruitment->printrr( );
        $results = \Yii::$app->navhelper->getData($service, $filter);
        $result = [];
        foreach ($results as $item) {
            $link = $updateLink = $deleteLink =  '';
            $Viewlink = Html::a('<i class="fas fa-eye"></i>', ['view-surrender', 'No' => $item->No], ['class' => 'btn btn-outline-primary btn-xs']);
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


    public function getCovertypes()
    {
        $service = Yii::$app->params['ServiceName']['MedicalCoverTypes'];

        $results = \Yii::$app->navhelper->getData($service);
        $result = [];
        $i = 0;
        if (is_array($results)) {
            foreach ($results as $res) {
                if (!empty($res->Code) && !empty($res->Description)) {
                    $result[$i] = [
                        'Code' => $res->Code,
                        'Description' => $res->Description
                    ];
                    $i++;
                }
            }
        }
        return ArrayHelper::map($result, 'Code', 'Description');
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

    /*Get Staff Loans */

    public function getLoans()
    {
        $service = Yii::$app->params['ServiceName']['StaffLoans'];

        $results = \Yii::$app->navhelper->getData($service);
        return ArrayHelper::map($results, 'Code', 'Loan_Name');
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

    public function getLeaveTypes($gender = '')
    {
        $service = Yii::$app->params['ServiceName']['LeaveTypesSetup']; //['leaveTypes'];
        $filter = [];

        $arr = [];
        $i = 0;
        $result = \Yii::$app->navhelper->getData($service, $filter);
        foreach ($result as $res) {
            if ($res->Gender == 'Both' || $res->Gender == Yii::$app->user->identity->Employee[0]->Gender) {
                ++$i;
                $arr[$i] = [
                    'Code' => $res->Code,
                    'Description' => $res->Description
                ];
            }
        }
        return ArrayHelper::map($arr, 'Code', 'Description');
    }

    public function actionRequiresattachment($Code)
    {
        $service = Yii::$app->params['ServiceName']['LeaveTypesSetup'];
        $filter = [
            'Code' => $Code
        ];

        $result = \Yii::$app->navhelper->getData($service, $filter);

        Yii::$app->response->format = Response::FORMAT_JSON;
        return ['Requires_Attachment' => $result[0]->Requires_Attachment];
    }

    public function getEmployees()
    {

        // Yii::$app->recruitment->printrr(Yii::$app->user->identity->Employee[0]->Global_Dimension_2_Code);

        $service = Yii::$app->params['ServiceName']['EmployeesUnfiltered'];
        $filter = [];

        if (property_exists(Yii::$app->user->identity->Employee[0], 'Global_Dimension_2_Code')) {
            $filter = [
                'Global_Dimension_2_Code' => Yii::$app->user->identity->Employee[0]->Global_Dimension_2_Code
            ];
        } else {
            Yii::$app->session->setFlash('error', 'Note: You are going to see a list of all employees as relievers since your PROGRAM CODE is not set in your Employee Card.');
        }

        $employees = \Yii::$app->navhelper->getData($service, $filter);

        return Yii::$app->navhelper->refactorArray($employees, 'No', 'Full_Name');
    }




    public function actionSetleavetype()
    {
        $model = new Leave();
        $service = Yii::$app->params['ServiceName']['LeaveCard'];

        $filter = [
            'Application_No' => Yii::$app->request->post('No')
        ];
        $request = Yii::$app->navhelper->getData($service, $filter);

        if (is_array($request)) {
            Yii::$app->navhelper->loadmodel($request[0], $model);
            $model->Key = $request[0]->Key;
            $model->Leave_Code = Yii::$app->request->post('Leave_Code');
        }


        $result = Yii::$app->navhelper->updateData($service, $model);

        Yii::$app->response->format = \yii\web\response::FORMAT_JSON;

        return $result;
    }

    public function actionSetreliever()
    {
        $model = new Leave();
        $service = Yii::$app->params['ServiceName']['LeaveCard'];

        $filter = [
            'Application_No' => Yii::$app->request->post('No')
        ];
        $request = Yii::$app->navhelper->getData($service, $filter);

        if (is_array($request)) {
            Yii::$app->navhelper->loadmodel($request[0], $model);
            $model->Key = $request[0]->Key;
            $model->Reliever = Yii::$app->request->post('Reliever');
        }


        $result = Yii::$app->navhelper->updateData($service, $model);

        Yii::$app->response->format = \yii\web\response::FORMAT_JSON;

        return $result;
    }

    /*Set Receipt Amount */
    public function actionSetdays()
    {
        $model = new Leave();
        $service = Yii::$app->params['ServiceName']['LeaveCard'];

        $filter = [
            'Application_No' => Yii::$app->request->post('No')
        ];
        $request = Yii::$app->navhelper->getData($service, $filter);

        if (is_array($request)) {
            Yii::$app->navhelper->loadmodel($request[0], $model);
            $model->Key = $request[0]->Key;
            $model->Days_To_Go_on_Leave = Yii::$app->request->post('Days_To_Go_on_Leave');
        }

        $result = Yii::$app->navhelper->updateData($service, $model);

        Yii::$app->response->format = \yii\web\response::FORMAT_JSON;

        return $result;
    }

    /*Set Start Date */
    public function actionSetstartdate()
    {
        $model = new Leave();
        $service = Yii::$app->params['ServiceName']['LeaveCard'];

        $filter = [
            'Application_No' => Yii::$app->request->post('No')
        ];
        $request = Yii::$app->navhelper->getData($service, $filter);

        if (is_array($request)) {
            Yii::$app->navhelper->loadmodel($request[0], $model);
            $model->Key = $request[0]->Key;
            $model->Start_Date = Yii::$app->request->post('Start_Date');
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


        $result = Yii::$app->navhelper->PortalWorkFlows($service, $data, 'IanSendLeaveForApproval');

        if (!is_string($result)) {
            Yii::$app->session->setFlash('success', 'Request Sent to Supervisor for Approval Successfully.', true);
            //return $this->redirect(['view','No' => $No]);
            return $this->redirect(['index']);
        } else {

            Yii::$app->session->setFlash('error', 'Error Sending Request for Approval  : ' . $result);
            // return $this->redirect(['view','No' => $No]);
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


        $result = Yii::$app->navhelper->PortalWorkFlows($service, $data, 'IanCancelLeaveApprovalRequest');

        if (!is_string($result)) {
            Yii::$app->session->setFlash('success', 'Approval Request Cancelled Successfully.', true);
            return $this->redirect(['view', 'No' => $No]);
        } else {

            Yii::$app->session->setFlash('error', 'Error Cancelling Approval Request.  : ' . $result);
            return $this->redirect(['view', 'No' => $No]);
        }
    }
}
