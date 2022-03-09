<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 3/9/2020
 * Time: 4:09 PM
 */

namespace frontend\models;
use yii\base\Model;


class Taxieline extends Model
{

public $Key;
public $Request_Type;
public $Departure_Location;
public $Travel_Date;
public $Departure_Time;
public $Destination_Location;
public $Reason_For_Request;
public $No_of_Person_s_Travelling;
public $Document_No;
public $Job_No;
public $Job_Task_No;
public $Job_Planning_Line_No;
public $G_L_Account_No;
public $Budget_Amount;
public $Estimated_Travel_Cost;
public $isNewRecord;

public $Vendor_No;
public $Vendor_Name;

    public function rules()
    {
        return [
            [[
                'Donor_No',
                'Objective_Code',
                'Output_Code',
                'Activity_Code',
                'Partner_Code',
                'Outcome_Code',
                'Grant_No'
    
            ], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [

        ];
    }
}