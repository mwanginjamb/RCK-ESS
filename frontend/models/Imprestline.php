<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 3/9/2020
 * Time: 4:09 PM
 */

namespace frontend\models;
use yii\base\Model;


class Imprestline extends Model
{

public $Key;
public $Line_No;
public $Transaction_Type;
public $Account_No;
public $Account_Name;
public $Description;
public $Amount;
public $Amount_LCY;
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
public $isNewRecord;
public $Request_No;


    public function rules()
    {
        return [
            [['Transaction_Type', 'Description', 'Amount','Donor_No'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'Global_Dimension_1_Code' => 'Sub Office',
            'Global_Dimension_2_Code' => 'Program Code',
            
        ];
    }
}