<?php

/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 3/10/2020
 * Time: 2:27 PM
 */



namespace common\library;

use yii;
use yii\base\Component;
use common\models\Hruser;




use Office365\Runtime\Auth\NetworkCredentialContext;
use Office365\SharePoint\ClientContext;
use Office365\Runtime\Auth\UserCredentials;
use Office365\Runtime\Auth\AuthenticationContext;
use Office365\Runtime\Utilities\RequestOptions;
use Office365\Runtime\ClientRuntimeContext;
use Office365\SharePoint\ListCreationInformation;
use Office365\SharePoint\SPList;
use Office365\SharePoint\Web;
use Office365\SharePoint\ListTemplateType;
use Office365\SharePoint\FileCreationInformation;
use Office365\SharePoint\File;
use stdClass;

class Recruitment extends Component
{
    public function absoluteUrl()
    {
        return \yii\helpers\Url::home(true);
    }


    public function printrr($var)
    {
        print '<pre>';
        print_r($var);
        print '<br>';
        exit('turus!!!');
    }

    function currentCtrl($ctrl)
    {
        $controller = Yii::$app->controller->id;

        if (is_array($ctrl) && in_array($controller, $ctrl)) {
            return true;
        } else if ($controller == $ctrl) {
            return true;
        } else {
            return false;
        }
    }

    public function currentaction($ctrl, $actn)
    { //modify it to accept an array of controllers as an argument--> later please
        $controller = Yii::$app->controller->id;
        $action = Yii::$app->controller->action->id;

        if ($controller == $ctrl && is_array($actn) && in_array($action, $actn)) {
            return true;
        } else if (is_array($ctrl) && in_array($controller, $ctrl)) {
            return true;
        } else if ($controller == $ctrl && $action == $actn) {
            return true;
        } else {
            return false;
        }
    }

    public function getProfileID()
    {
        if (Yii::$app->session->has('HRUSER')) {
            $hruser = Hruser::findByUsername(Yii::$app->session->get('HRUSER')->username);
            return $hruser->profileID;
        } else if (!Yii::$app->user->isGuest && !Yii::$app->session->has('HRUSER')) {
            $srvc = Yii::$app->params['ServiceName']['EmployeeCard'];
            $filter = [
                'No' => Yii::$app->user->identity->employee[0]->No
            ];
            $Employee = Yii::$app->navhelper->getData($srvc, $filter);

            return !empty($Employee[0]->ProfileID) ? $Employee[0]->ProfileID : false;
        } else {
            return null;
        }
    }

    public function getRequisitionID($Job_ID)
    {
        $service = Yii::$app->params['ServiceName']['RequisitionEmployeeList'];

        $filter = [
            'Job_ID' => $Job_ID
        ];

        $result = Yii::$app->navhelper->getData($service, $filter);

        if (is_object($result)) { //RETURNS AN EMPTY object if the filter result fails
            return null;
        }
        return $result[0]->Requisition_No;
    }

    public function getRequisitionType($Job_ID)
    { //Internal, External, Both
        $service = Yii::$app->params['ServiceName']['RequisitionEmployeeCard'];

        $filter = [
            'Job_ID' => $Job_ID
        ];

        $result = Yii::$app->navhelper->getData($service, $filter);

        if (is_object($result)) { //RETURNS AN EMPTY object if the filter result fails
            return null;
        }
        return $result[0]->Requisition_Type;
    }

    /* Checking is various profile setups exists for an applicant */


    public function hasProfile()
    {
        if (Yii::$app->session->has('HRUSER')) {
            //update a HRUser
            $hruser = Hruser::findByUsername(Yii::$app->session->get('HRUSER')->username);
            $profileID = $hruser->profileID;

            if (!empty($profileID)) {
                return true;
            } else {
                return false;
            }
        } else {

            //check if an identity is guest, then check for ProfileID 
            if (!Yii::$app->user->isGuest) {
                $srvc = Yii::$app->params['ServiceName']['EmployeeCard'];
                $filter = [
                    'No' => Yii::$app->user->identity->employee[0]->No
                ];
                $Employee = Yii::$app->navhelper->getData($srvc, $filter);

                if (!empty($Employee[0]->ProfileID)) {
                    return true;
                } else {
                    return false;
                }
            } else { //if for some reason this check is called by a guest ,return false;
                return false;
            }
        }
    }

    //check for experience

    public function hasExperience($profileID)
    {
        $service = Yii::$app->params['ServiceName']['experience'];
        $filter = [
            'Job_Application_No' => $profileID
        ];

        $result = Yii::$app->navhelper->getData($service, $filter);
        if (is_object($result)) { //RETURNS AN EMPTY object if the filter result has null property object
            return false;
        } else { // returns an array of objects
            return true;
        }
    }

    //check for academic qualifications

    public function hasAcademic($profileID)
    {
        $service = Yii::$app->params['ServiceName']['qualifications'];
        $filter = [
            'Employee_No' => $profileID
        ];

        $result = Yii::$app->navhelper->getData($service, $filter);
        if (is_object($result)) { //RETURNS AN EMPTY object if the filter result has null property object
            return false;
        } else { // returns an array of objects
            return true;
        }
    }

    //check for professional qualification

    public function hasProfessional($profileID)
    {
        $service = Yii::$app->params['ServiceName']['qualifications'];

        //FILTER FOR PROFESSIONAL QUALIFICATIONS
        $filter = [
            'Employee_No' => $profileID
        ];

        $result = Yii::$app->navhelper->getData($service, $filter);
        if (is_object($result)) { //RETURNS AN EMPTY object if the filter result has null property object
            return false;
        } else { // returns an array of objects
            return true;
        }
    }

    //check for languages

    public function hasLanguages($profileID)
    {

        $service = Yii::$app->params['ServiceName']['applicantLanguages'];
        $filter = [
            'Applicant_No' => $profileID
        ];

        $result = Yii::$app->navhelper->getData($service, $filter);
        if (is_object($result)) { //RETURNS AN EMPTY object if the filter result has null property object
            return false;
        } else { // returns an array of objects
            return true;
        }
    }

    //check for referees

    public function hasReferees($profileID)
    {

        $service = Yii::$app->params['ServiceName']['referees'];
        $filter = [
            'Application_No' => $profileID
        ];

        $result = Yii::$app->navhelper->getData($service, $filter);
        if (is_object($result)) { //RETURNS AN EMPTY object if the filter result has null property object
            return false;
        } else { // returns an array of objects
            return true;
        }
    }

    //Check Cv
    public function hasCv()
    {

        $service = Yii::$app->params['ServiceName']['HRJobApplicationsCard'];
        $filter = [
            'No' => \Yii::$app->session->get('Job_Application_No'),
        ];

        $result = Yii::$app->navhelper->getData($service, $filter);

        if (is_array($result)) {
            return property_exists($result[0], 'CV');
        } else {
            return false;
        }
    }

    //Check Cover Letter

    public function hasCoverletter()
    {

        $service = Yii::$app->params['ServiceName']['HRJobApplicationsCard'];
        $filter = [
            'No' => \Yii::$app->session->get('Job_Application_No')
        ];

        $result = Yii::$app->navhelper->getData($service, $filter);
        if (is_array($result)) {
            return property_exists($result[0], 'Cover_Letter');
        } else {
            return false;
        }
    }



    //Show Job Responsibility Specifications / children

    public function Responsibilityspecs($resp)
    {
        $service = Yii::$app->params['ServiceName']['ResponsibilitySpecification'];
        $filter = [
            'Responsibility_Line_No' => $resp
        ];

        $results = Yii::$app->navhelper->getData($service, $filter);

        $html  = '<td class="child"><table class="table table-info table-hover">';

        if (!is_string($results) && !is_object($results)) {

            foreach ($results as $spec) {
                if (isset($spec->Specifaction))
                    $html .= '<tr>

                            <td>' . $spec->Specifaction . '</td>

                        </tr>';
            }
        }

        $html .= '</table></td>';

        return $html;
    }

    public function Requirementspecs($req)
    {
        $service = Yii::$app->params['ServiceName']['RequirementSpecification'];
        $filter = [
            'Requirement_Line_No' => $req
        ];

        $results = Yii::$app->navhelper->getData($service, $filter);

        $html  = '<td class="child">
                    <table class="table table-info table-hover">';

        if (!is_string($results) && !is_object($results)) {

            foreach ($results as $spec) {

                $html .= '<tr>
                            
                                                        <td>' . $spec->Requirement_Specifiaction . '</td>
                            
                                                    </tr>';
            }
        }

        $html .= '    </table>
                   </td>';

        return $html;
    }

    //Sharepoint Functions

    //SHAREPOINT UPLOAD
    public function sharepoint_attach($filepath)
    {  //read list

        $targetLibraryTitle = \Yii::$app->params['library'];

        try {

            /*$ctx = $this->connectWithAppOnlyToken(
                Yii::$app->params['sharepointUrl'],
                Yii::$app->params['clientID'],
                Yii::$app->params['clientSecret']
            );*/
            $ctx = $this->connectWithUserCredentials(Yii::$app->params['sharepointUrl'], Yii::$app->params['sharepointUsername'], Yii::$app->params['sharepointPassword']);
            $site = $ctx->getSite();


            $ctx->load($site); //load site settings
            $ctx->executeQuery();

            $list = $this->ensureList($ctx->getWeb(), $targetLibraryTitle, ListTemplateType::DocumentLibrary);


            $localFilePath = realpath($filepath);
            // Yii::$app->recruitment->printrr($localFilePath);
            $this->uploadFiles($localFilePath, $list);
        } catch (\Exception $e) {
            print 'Authentication failed: ' . $e->getMessage() . "\n";
        }
    }

    // Prepare Download

    public function download($file)
    {

        $credentials = new UserCredentials(Yii::$app->params['sharepointUsername'], Yii::$app->params['sharepointPassword']);
        $ctx = (new ClientContext(Yii::$app->params['sharepointUrl']))->withCredentials($credentials);

        $content = $this->downloadFile($ctx, $file);
        return $content;
    }

    //Upload Files function

    private static function uploadFiles($localFilePath, SPList $targetList)
    {



        $ctx = $targetList->getContext();

        $session = Yii::$app->session;

        $fileCreationInformation = new FileCreationInformation();
        $fileCreationInformation->Content = file_get_contents($localFilePath);
        $fileCreationInformation->Url = basename($localFilePath);

        //print_r($fileCreationInformation); exit;

        $uploadFile = $targetList->getRootFolder()->getFiles()->add($fileCreationInformation);
        $ctx->executeQuery();
        // print "File {$uploadFile->getProperty('Name')} has been uploaded\r\n";

        $listEntity = $uploadFile->getListItemAllFields();
        $metadata = Yii::$app->session->get('metadata');
        if ($metadata) {
            // Yii::$app->recruitment->printrr($uploadFile->getListItemAllFields());
            $listEntity->setProperty('application', $metadata['Application']);
            $listEntity->setProperty('employee', $metadata['Employee']);
            $listEntity->setProperty('leavetype', $metadata['Leavetype']);
            $listEntity->update();
        }


        $ctx->executeQuery();

        // unset metadata session

        Yii::$app->session->remove('metadata');
    }

    public function actionFetchdocument($id)
    {
        $Url = Yii::$app->params['sharepointUrl']; //"http://rbadev-shrpnt";
        $username = Yii::$app->params['sharepointUsername']; //'rbadev\administrator';
        $password = Yii::$app->params['sharepointPassword']; //'rba123!!';

        $authCtx = new NetworkCredentialContext($username, $password);

        $authCtx->AuthType = CURLAUTH_NTLM; //NTML Auth schema
        $ctx = new ClientContext($Url, $authCtx);

        // Fetch Doc Name by ID
        $model = '';
        if (!empty($model)) {
            return $this->downloadFile($ctx, \Yii::$app->params['library'] . $model->FileName);
        } else {
            return '';
        }
    }

    function downloadFile(ClientRuntimeContext $ctx, $fileUrl)
    {
        try {
            $fileContent = \Office365\sharePoint\File::openBinary($ctx, $fileUrl);
            return "data:application/pdf;base64," . base64_encode($fileContent);
        } catch (\Exception $e) {

            echo $e->getMessage();
        }
    }

    public static function ensureList(Web $web, $listTitle, $type, $clearItems = true)
    {
        $ctx = $web->getContext();
        $lists = $web->getLists()->filter("Title eq '$listTitle'")->top(1);
        $ctx->load($lists);
        $ctx->executeQuery();
        if ($lists->getCount() == 1) {
            $existingList = $lists->getData()[0];
            if ($clearItems) {
                //self::deleteListItems($existingList);
            }
            return $existingList;
        }
        //return ListExtensions::createList($web, $listTitle, $type);
        return ListExtensions::createList($web, $listTitle, $type);
    }

    /**
     * @param Web $web
     * @param $listTitle
     * @param $type
     * @return SPList
     * @internal param ClientRuntimeContext $ctx
     */
    public static function createList(Web $web, $listTitle, $type)
    {
        $ctx = $web->getContext();
        $info = new ListCreationInformation($listTitle);
        $info->BaseTemplate = $type;
        $list = $web->getLists()->add($info);
        $ctx->executeQuery();
        return $list;
    }

    /**
     * @param \Office365\PHP\Client\SharePoint\SPList $list
     */
    public static function deleteList(SPList $list)
    {
        $ctx = $list->getContext();
        $list->deleteObject();
        $ctx->executeQuery();
    }



    /*Sharepoint Authentication Context methods */


    function connectWithUserCredentials($url, $username, $password)
    {
        $authCtx = new AuthenticationContext($url);
        $authCtx->acquireTokenForUser($username, $password);
        $ctx = new ClientContext($url, $authCtx);
        return $ctx;
    }

    function connectWithNTLMAuth($url, $username, $password)
    {
        $authCtx = new NetworkCredentialContext($username, $password);
        $authCtx->AuthType = CURLAUTH_NTLM;
        $ctx = new ClientContext($url, $authCtx);
        return $ctx;
    }

    function connectWithAppOnlyToken($url, $clientId, $clientSecret)
    {
        $authCtx = new AuthenticationContext($url);
        $authCtx->acquireAppOnlyAccessToken($clientId, $clientSecret);
        $ctx = new ClientContext($url, $authCtx);
        return $ctx;
    }
}
