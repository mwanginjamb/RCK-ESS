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
            [['Leave_Code','Start_Date','Days_To_Go_on_Leave','Reliever'], 'required'],
            ['Days_To_Go_on_Leave','integer','min'=> 1],
            [['attachment'],'file','mimeTypes' => Yii::$app->params['QualificationsMimeTypes']],
            [['attachment'],'file','maxSize' => '5120000'],
            [['attachment'], 'file', 'skipOnEmpty' => false, 'extensions' => 'pdf, doc, docx']
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
            $this->attachment->saveAs('qualifications/' . str_replace(' ','',$this->attachment->baseName) . '.' . $this->attachment->extension);
            $this->Attachement_Path = 'qualifications/'.str_replace(' ','',$this->attachment->name);
            //You can then attach to sharepoint and unlink the resource on local file system

            var_dump( $this->attachment); exit;

            Yii::$app->recruitment->sharepoint_attach($this->Attachement_Path);
            return true;
        } else {

            print_r($this->errors); exit;
            return $this->getErrors();
        }
    }



}