<?php

/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 3/9/2020
 * Time: 4:09 PM
 */

namespace frontend\models;

use common\models\User;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\helpers\Url;


class Leave extends Model
{

    public $Key;
    public $Employee_No;
    public $Employee_Name;
    public $_x003C_Global_Dimension_1_Code_x003E_;
    public $Global_Dimension_2_Code;
    public $Application_No;
    public $Application_Date;
    public $User_ID;
    public $Leave_Code;
    public $Leave_Type_Decription;
    public $Start_Date;
    public $Days_To_Go_on_Leave;
    public $End_Date;
    public $Total_No_Of_Days;
    public $Leave_balance;
    public $Half_Day_on_Start_Date;
    public $Half_Day_on_End_Date;
    public $Holidays;
    public $Weekend_Days;
    public $Days;
    public $Balance_After;
    public $Return_Date;
    public $Reporting_Date;
    public $Comments;
    public $Reliever;
    public $Reliever_Name;
    public $Appointment_Date;
    public $Phone_No;
    public $E_Mail_Address;
    public $Grade;
    public $Status;
    public $Approval_Entries;
    public $Leave_Allowance;
    public $Rejection_Comments;



    public $Include_Leave_Allowance;
    public $Leave_Allowance_Amount;

    public $attachment;
    public $Attachement_Path;


    public $isNewRecord;

    /*public function __construct(array $config = [])
    {
        return $this->getLines($this->No);
    }*/

    public function rules()
    {
        return [
            [['Leave_Code', 'Start_Date', 'Days_To_Go_on_Leave', 'Reliever'], 'required'],
            ['Days_To_Go_on_Leave', 'integer', 'min' => 1],
            [['attachment'], 'file', 'mimeTypes' => Yii::$app->params['QualificationsMimeTypes']],
            [['attachment'], 'file', 'maxSize' => '5120000'],
            [['attachment'], 'file', 'skipOnEmpty' => true, 'extensions' => 'pdf, doc, docx']
        ];
    }

    public function attributeLabels()
    {
        return [
            '_x003C_Global_Dimension_1_Code_x003E_' => 'Program',
            'Global_Dimension_2_Code' => 'Department',
        ];
    }

    public function upload()
    {
        if ($this->validate()) {

            $imageId = Yii::$app->security->generateRandomString(8);
            $this->attachment->saveAs('leave_attachments/' . $imageId . '.' . $this->attachment->extension);
            $this->Attachement_Path = \yii\helpers\Url::home(true) . 'leave_attachments/' . $imageId . '.' . $this->attachment->extension;
            $localPath = 'leave_attachments/' . $imageId . '.' . $this->attachment->extension;
            //You can then attach to sharepoint and unlink the resource on local file system

            // var_dump( $this->attachment); exit;

            Yii::$app->recruitment->sharepoint_attach($localPath);

            //Save upload record to Nav
            $Name = basename($localPath);
            $DocNo = $this->Application_No;
            $File_path = Url::home(true) . $localPath;

            $attachmentService = Yii::$app->params['ServiceName']['LeaveAttachments'];
            $payload = [
                'Document_No' => $DocNo,
                'Name' => $Name,
                'File_path' => $File_path
            ];

            $result = Yii::$app->navhelper->postData($attachmentService, $payload);
            if (!is_object($result)) {
                Yii::$app->recruitment->printrr($result);
            }


            return true;
        } else {

            print_r($this->errors);
            exit;
            return $this->getErrors();
        }
    }


    public function readFile($path)
    {

        $binary = @file_get_contents($path);
        $content = @chunk_split(base64_encode($binary));
        return $content;
    }
}
