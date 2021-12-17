<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 3/9/2020
 * Time: 4:09 PM
 */

namespace frontend\models;
use yii\base\Model;


class Fundsrequisitionline extends Model
{

public $Key;
public $Employee_No;
public $Employee_Name;
public $CBS_Member_Id;
public $PD_Transaction_Code;
public $Account_No;
public $Account_Name;
public $Description;
public $Daily_Rate;
public $Payroll_Scale;
public $No_of_Days;
public $Daily_Tax_Relief;
public $Tax_Relief;
public $Amount;
public $Amount_LCY;
public $Taxable_Amount;
public $Net_Allowance_Amount;
public $Unbudgeted;
public $Global_Dimension_1_Code;
public $Global_Dimension_2_Code;
public $Donor_Code;
public $Project_Code;
public $Job_No;
public $Job_Task_No;
public $Job_Planning_Line_No;
public $Request_No;
public $Line_No;
public $isNewRecord;

    public function rules()
    {
        return [
            [['Employee_No', 'Description', 'No_of_Days'], 'required'],
            [['No_of_Days'],'integer','min' => 1]
        ];
    }

    public function attributeLabels()
    {
        return [

        ];
    }
}