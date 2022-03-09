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
public $Donor_No;
public $Donor_Name;
public $Grant_No;
public $Objective_Code;
public $Output_Code;
public $Outcome_Code;
public $Activity_Code;
public $Partner_Code;
public $Request_No;
public $Line_No;
public $isNewRecord;

    public function rules()
    {
        return [
            [[
            'Employee_No', 
            'Description', 
            'No_of_Days',
            'Donor_No',
            'Objective_Code',
            'Output_Code',
            'Activity_Code',
            'Partner_Code',
            'Outcome_Code',
            'Grant_No'

        ], 'required'],
            [['No_of_Days'],'integer','min' => 1]
        ];
    }

    public function attributeLabels()
    {
        return [

        ];
    }
}