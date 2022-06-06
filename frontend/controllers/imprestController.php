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
use stdClass;
use yii\helpers\FileHelper;

class ImprestController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup', 'index', 'surrenderlist', 'create', 'update', 'view'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'index', 'surrenderlist', 'create', 'update', 'view'],
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
                'only' => ['getimprests', 'getimprestsurrenders'],
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
            'activities', 'partners', 'donors', 'upload', 'upload-multiple'
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

    public function actionCreate($requestfor = 'Self')
    {

        $model = new Imprestcard();
        $service = Yii::$app->params['ServiceName']['ImprestRequestCardPortal'];
        $request = '';
        /*Do initial request */
        if (!isset(Yii::$app->request->post()['Imprestcard'])) {
            $model->Employee_No =  $model->Employee_No = Yii::$app->user->identity->Employee[0]->No;
            $request = Yii::$app->navhelper->postData($service, $model);

            if (is_object($request)) {
                $model = Yii::$app->navhelper->loadmodel($request, $model);

                // Update Request for
                $model->Request_For = $requestfor;
                $model->Key = $request->Key;
                $model->Imprest_Type = 'Local';
                $request = Yii::$app->navhelper->updateData($service, $model);
                $model = Yii::$app->navhelper->loadmodel($request, $model);
                if (is_object($request)) {
                    return $this->redirect(['update', 'No' => $model->Key]);
                } else {
                    Yii::$app->session->setFlash('error', $request);
                    $this->redirect(['index']);
                }
            } else {
                Yii::$app->session->setFlash('error', $request);
                return $this->render('index');
            }
        }


        if (Yii::$app->request->post() && Yii::$app->navhelper->loadpost(Yii::$app->request->post()['Imprestcard'], $model)) {


            $result = Yii::$app->navhelper->findOne($service, '', 'No', Yii::$app->request->post()['Imprestcard']['No']);

            $model = Yii::$app->navhelper->loadmodel($result, $model);

            $result = Yii::$app->navhelper->updateData($service, $model, ['No']);


            if (!is_string($result)) {

                Yii::$app->session->setFlash('success', 'Imprest Request Created Successfully.');

                // Yii::$app->recruitment->printrr($result);
                return $this->redirect(['view', 'No' => $result->No]);
            } else {
                Yii::$app->session->setFlash('success', 'Error Creating Imprest Request ' . $result);
                return $this->redirect(['index']);
            }
        }


        //Yii::$app->recruitment->printrr($model);

        return $this->render('create', [
            'model' => $model,
            'employees' => $this->getEmployees(),
            'programs' => $this->getPrograms(),
            'departments' => $this->getDepartments(),
            'currencies' => Yii::$app->navhelper->dropdown('Currencies', 'Code', 'Description'),
            'attachments' => Yii::$app->navhelper->getData(Yii::$app->params['ServiceName']['LeaveAttachments'], ['Document_No' => $model->No]),
        ]);
    }


    public function actionCreateSurrender($requestfor = "Self")
    {
        // Yii::$app->recruitment->printrr(Yii::$app->request->get('requestfor'));
        $model = new Imprestsurrendercard();
        $service = Yii::$app->params['ServiceName']['ImprestSurrenderCardPortal'];

        /*Do initial request */
        if (!isset(Yii::$app->request->post()['Imprestsurrendercard'])) {

            if ($requestfor == "Self") {
                $model->Employee_No = Yii::$app->user->identity->{'Employee No_'};
                $request = Yii::$app->navhelper->postData($service, $model);
            } elseif ($requestfor == "Other") {
                $request = Yii::$app->navhelper->postData($service, []);
            }

            if (is_string($request)) {
                Yii::$app->session->setFlash('error', 'Error Creating Imprest Surrender ' . $request);
                return $this->redirect('surrenderlist');
            }

            if (!is_string($request)) {

                //Yii::$app->recruitment->printrr($request);
                // redirect to update page

                return $this->redirect(['update-surrender', 'No' => $request->Key]);

                Yii::$app->navhelper->loadmodel($request, $model);

                // Update Request for
                $model->Request_For = $requestfor;
                $model->Key = $request->Key;
                $request = Yii::$app->navhelper->updateData($service, $model);

                if (is_string($request)) {
                    Yii::$app->session->setFlash('error', 'Error Creating Imprest Surrender ' . $request);
                    return $this->redirect(['surrenderlist']);
                }
            }
        }

        return $this->render('createsurrender', [
            'model' => $model,
            'employees' => $this->getEmployees(),
            'programs' => $this->getPrograms(),
            'departments' => $this->getDepartments(),
            'currencies' => Yii::$app->navhelper->dropdown('Currencies', 'Code', 'Description'),
            'imprests' => $this->getmyimprests(),
            'receipts' => $this->getimprestreceipts($model->No),
            'attachments' => Yii::$app->navhelper->getData(Yii::$app->params['ServiceName']['LeaveAttachments'], ['Document_No' => $model->No]),
        ]);
    }

    public function actionUpdateSurrender($No)
    {
        $model = new Imprestsurrendercard();
        $service = Yii::$app->params['ServiceName']['ImprestSurrenderCard'];
        $model->isNewRecord = false;


        //  $result = Yii::$app->navhelper->findOne($service,'No', Yii::$app->request->get('No'));
        $result = Yii::$app->navhelper->readByKey($service, $No);
        //Yii::$app->recruitment->printrr($result);

        if (!is_string($result)) {
            //load nav result to model
            $model = Yii::$app->navhelper->loadmodel($result, $model); //$this->loadtomodeEmployee_Nol($result[0],$Expmodel);
        } else {
            Yii::$app->session->setFlash('error', 'Error : ' . $result);
            return $this->redirect(['index']);
        }


        if (Yii::$app->request->post() && Yii::$app->navhelper->loadpost(Yii::$app->request->post()['Imprestsurrendercard'], $model)) {
            $result = Yii::$app->navhelper->updateData($service, $model);
            if (!empty($result)) {

                Yii::$app->session->setFlash('success', 'Imprest Surrender Request Updated Successfully.');

                return $this->render('updatesurrender', [
                    'model' => $model,
                    'employees' => $this->getEmployees(),
                    'programs' => $this->getPrograms(),
                    'departments' => $this->getDepartments(),
                    'currencies' => Yii::$app->navhelper->dropdown('Currencies', 'Code', 'Description'),
                    'imprests' => $this->getmyimprests(),
                    'receipts' => $this->getimprestreceipts($model->No),
                    'surrender' =>  $result,
                    'attachments' => Yii::$app->navhelper->getData(Yii::$app->params['ServiceName']['LeaveAttachments'], ['Document_No' => $model->No]),

                ]);
            } else {

                Yii::$app->session->setFlash('updatesurrender', 'Error : ' . $result);
                return $this->redirect(['index']);
            }
        }


        // Yii::$app->recruitment->printrr($model);
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('updatesurrender', [
                'model' => $model,
                'employees' => $this->getEmployees(),
                'programs' => $this->getPrograms(),
                'departments' => $this->getDepartments(),
                'currencies' => Yii::$app->navhelper->dropdown('Currencies', 'Code', 'Description'),
                'imprests' => $this->getmyimprests(),
                'receipts' => $this->getimprestreceipts($model->No),
                'surrender' =>  $result

            ]);
        }

        return $this->render('updatesurrender', [
            'model' => $model,
            'employees' => $this->getEmployees(),
            'programs' => $this->getPrograms(),
            'departments' => $this->getDepartments(),
            'currencies' => Yii::$app->navhelper->dropdown('Currencies', 'Code', 'Description'),
            'imprests' => $this->getmyimprests(),
            'receipts' => $this->getimprestreceipts($model->No),
            'surrender' =>  $result,
            'attachments' => Yii::$app->navhelper->getData(Yii::$app->params['ServiceName']['LeaveAttachments'], ['Document_No' => $model->No]),
        ]);
    }


    public function actionUpdate($No)
    {
        $model = new Imprestcard();
        $Documentservice = Yii::$app->params['ServiceName']['ImprestRequestCard'];
        $service = Yii::$app->params['ServiceName']['ImprestRequestCardPortal'];
        $model->isNewRecord = false;


        $result = Yii::$app->navhelper->readByKey($Documentservice, $No);
        $res = Yii::$app->navhelper->readByKey($service, $No);
        //Yii::$app->recruitment->printrr($result);
        if (!is_string($res)) {
            //load nav result to model
            $model = Yii::$app->navhelper->loadmodel($res, $model); //$this->loadtomodeEmployee_Nol($result[0],$Expmodel);
        } else {
            Yii::$app->session->setFlash('error', 'Error : ' . $result);
            return $this->redirect(['index']);
        }


        if (Yii::$app->request->post() && Yii::$app->navhelper->loadpost(Yii::$app->request->post()['Imprestcard'], $model)) {
            $result = Yii::$app->navhelper->updateData($service, $model);
            if (!empty($result)) {

                Yii::$app->session->setFlash('success', 'Imprest Request Updated Successfully.');

                return $this->redirect(['index']);
            } else {

                Yii::$app->session->setFlash('error', 'Error : ' . $result);
                return $this->redirect(['index']);
            }
        }


        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('update', [
                'model' => $model,
                'document' => $result,
                'employees' => $this->getEmployees(),
                'programs' => $this->getPrograms(),
                'departments' => $this->getDepartments(),
                'currencies' => Yii::$app->navhelper->dropdown('Currencies', 'Code', 'Description')

            ]);
        }
        //Yii::$app->recruitment->printrr(Yii::$app->navhelper->getData(Yii::$app->params['ServiceName']['LeaveAttachments'], ['Document_No' => $model->No]));

        return $this->render('update', [
            'model' => $model,
            'document' => $result,
            'employees' => $this->getEmployees(),
            'programs' => $this->getPrograms(),
            'departments' => $this->getDepartments(),
            'currencies' => Yii::$app->navhelper->dropdown('Currencies', 'Code', 'Description'),
            'attachments' => Yii::$app->navhelper->getData(Yii::$app->params['ServiceName']['LeaveAttachments'], ['Document_No' => $model->No]),
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

    public function actionView($No = "", $DocNo = '')
    {
        $service = Yii::$app->params['ServiceName']['ImprestRequestCard'];


        if (!empty($DocNo)) {
            $result = Yii::$app->navhelper->findOne($service, '', 'No', $DocNo);
        } else if (!empty($No)) {
            $result = Yii::$app->navhelper->readByKey($service, $No);
        } else {
            Yii::$app->session->setFlash('error', 'We could not find the document you are looking for, Sorry.');
            return $this->redirect(['index']);
        }



        //load nav result to model
        $model = $this->loadtomodel($result, new Imprestcard());

        //Yii::$app->recruitment->printrr($model);

        return $this->render('view', [
            'model' => $model,
            'document' => $result,
            'attachments' => Yii::$app->navhelper->getData(Yii::$app->params['ServiceName']['LeaveAttachments'], ['Document_No' => $model->No]),

        ]);
    }

    // File reader action -- Model Neutral Function --Ooh shit, @francnjamb

    public function actionRead()
    {

        exit(Yii::$app->recruitment->download());
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

    /*Print Imprest*/
    public function actionPrintImprest($No)
    {
        $service = Yii::$app->params['ServiceName']['PortalReports'];
        $data = [
            'imprest' => $No
        ];
        $path = Yii::$app->navhelper->PortalReports($service, $data, 'IanGenerateImprest');
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

    /*Print Surrender*/
    public function actionPrintSurrender($No)
    {
        $service = Yii::$app->params['ServiceName']['PortalReports'];
        $data = [
            'surrender' => $No
        ];
        $path = Yii::$app->navhelper->PortalReports($service, $data, 'IanGenerateImprestSurrender');

        //Yii::$app->recruitment->printrr($path);
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
        return $this->render('surrender_printout', [
            'report' => true,
            'content' => $content,
            'No' => $No
        ]);
    }

    /*Imprest surrender card view*/

    public function actionViewSurrender($No)
    {
        $service = Yii::$app->params['ServiceName']['ImprestSurrenderCard'];

        $result = Yii::$app->navhelper->findOne($service, '', 'No', $No);
        //load nav result to model
        $model = $this->loadtomodel($result, new Imprestsurrendercard());

        // Yii::$app->recruitment->printrr($result);

        return $this->render('viewsurrender', [
            'model' => $model,
            'surrender' => $result,
            'attachments' => Yii::$app->navhelper->getData(Yii::$app->params['ServiceName']['LeaveAttachments'], ['Document_No' => $model->No]),
        ]);
    }

    // Get imprest list

    public function actionGetimprests()
    {
        $service = Yii::$app->params['ServiceName']['ImprestRequestListPortal'];
        $filter = [
            'Employee_No' => Yii::$app->user->identity->Employee[0]->No,
        ];

        $results = \Yii::$app->navhelper->getData($service, $filter);
        $result = [];
        // Yii::$app->recruitment->printrr( $results);
        if (is_array($results)) {
            foreach ($results as $item) {

                if (isset($item->No) && isset($item->Key)) {
                    $link = $updateLink = $deleteLink =  '';
                    $Viewlink = Html::a('<i class="fas fa-eye"></i>', ['view', 'No' => $item->Key], ['class' => 'btn btn-outline-primary btn-xs']);
                    if ($item->Status == 'New') {
                        $link = Html::a('<i class="fas fa-paper-plane"></i>', ['send-for-approval', 'No' => $item->No], ['title' => 'Send Approval Request', 'class' => 'btn btn-primary btn-xs']);

                        $updateLink = Html::a('<i class="far fa-edit"></i>', ['update', 'No' => $item->Key], ['class' => 'btn btn-info btn-xs']);
                    } else if ($item->Status == 'Pending_Approval') {
                        $link = Html::a('<i class="fas fa-times"></i>', ['cancel-request', 'No' => $item->No], ['title' => 'Cancel Approval Request', 'class' => 'btn btn-warning btn-xs']);
                    }

                    $result['data'][] = [
                        'Key' => $item->Key,
                        'No' => !empty($item->No) ? $item->No : '',
                        'Employee_No' => !empty($item->Employee_No) ? $item->Employee_No : '',
                        'Employee_Name' => !empty($item->Employee_Name) ? $item->Employee_Name : '',
                        'Purpose' => !empty($item->Purpose) ? $item->Purpose : '',
                        'Imprest_Amount' => !empty($item->Imprest_Amount) ? $item->Imprest_Amount : '',
                        'Status' => $item->Status,
                        'Posted' => ($item->Posted) ? 'Yes' : 'No',
                        'Surrendered' => ($item->Surrendered) ? 'Yes' : 'No',
                        'Action' => $link,
                        'Update_Action' => $updateLink,
                        'view' => $Viewlink
                    ];
                }
            }
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
        // Yii::$app->recruitment->printrr( $filter);
        $results = \Yii::$app->navhelper->getData($service, $filter);
        $result = [];

        if (is_array($results)) {
            foreach ($results as $item) {
                $link = $updateLink = $deleteLink =  '';
                $Viewlink = Html::a('<i class="fas fa-eye"></i>', ['view-surrender', 'No' => $item->No], ['class' => 'btn btn-outline-primary btn-xs']);
                if ($item->Status == 'New') {
                    $link = Html::a('<i class="fas fa-paper-plane"></i>', ['send-for-approval', 'No' => $item->No], ['title' => 'Send Approval Request', 'class' => 'btn btn-primary btn-xs']);

                    $updateLink = Html::a('<i class="far fa-edit"></i>', ['update-surrender', 'No' => $item->Key], ['class' => 'btn btn-info btn-xs']);
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
        }


        return $result;
    }


    public function getEmployees()
    {

        return Yii::$app->navhelper->dropdown('EmployeesUnfiltered', 'No', 'FullName');
    }

    /* My Imprests*/

    public function getmyimprests()
    {
        $service = Yii::$app->params['ServiceName']['PortalImprestRequest'];
        $filter = [
            'Employee_No' => Yii::$app->user->identity->Employee[0]->No,
            'Status' => 'Approved',
            'Posted' =>  true,
            'Surrendered' => false,
            'Surrender_Booked' => false
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

    public function actionSetemployee()
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
            $model->Employee_No = Yii::$app->request->post('Employee_No');
        }


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
            Yii::$app->session->setFlash('success', 'Imprest Request Sent to Supervisor Successfully.', true);
            return $this->redirect(['index']);
        } else {

            Yii::$app->session->setFlash('error', 'Error Sending Imprest Request for Approval  : ' . $result);
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


        $result = Yii::$app->navhelper->PortalWorkFlows($service, $data, 'IanCancelImprestForApproval');

        if (!is_string($result)) {
            Yii::$app->session->setFlash('success', 'Imprest Request Cancelled Successfully.', true);
            return $this->redirect(['index']);
        } else {

            Yii::$app->session->setFlash('error', 'Error Cancelling Imprest Approval Request.  : ' . $result);
            return $this->redirect(['index']);
        }
    }


    /** Updates a single field */
    public function actionSetfield($field)
    {

        $service = Yii::$app->request->post('service') !== 'ImprestRequestCardPortal'  ? Yii::$app->request->post('service') : 'ImprestRequestCardPortal';
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


    // Get Transaction Types

    public function actionTransactiontypes()
    {
        $data = Yii::$app->navhelper->dropdown('PaymentTypes', 'Code', 'Description', [], ['Code']);
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
                    'Leavetype' => 'Imprest - ' . !empty($parentDocument->Purpose) ?? '',
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

    // Upload Multiple

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
                        'Leavetype' => 'Imprest - ' . $Document->Purpose,
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
