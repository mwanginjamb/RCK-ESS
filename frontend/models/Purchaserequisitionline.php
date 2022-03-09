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


class Purchaserequisitionline extends Model
{
    public $Key;
    public $Type;
    public $No;
    public $Name;
    public $Unit_of_Measure;
    public $Description;
    public $Quantity;
    public $Location;
    public $Estimate_Unit_Price;
    public $Estimate_Total_Amount;
    public $Procurement_Method;
    public $Global_Dimension_1_Code;
    public $Global_Dimension_2_Code;
    public $Project_Code;
    public $Donor_No;
    public $Grant_No;
    public $Objective_Code;
    public $Output_Code;
    public $Outcome_Code;
    public $Activity_Code;
    public $Partner_Code;
    public $ShortcutDimCode_x005B_3_x005D_;
    public $ShortcutDimCode_x005B_4_x005D_;
    public $ShortcutDimCode_x005B_5_x005D_;
    public $ShortcutDimCode_x005B_6_x005D_;
    public $ShortcutDimCode_x005B_7_x005D_;
    public $ShortcutDimCode_x005B_8_x005D_;
    public $Requisition_No;
    public $Line_No;
    public $isNewRecord;

    /*public function __construct(array $config = [])
    {
        return $this->getLines($this->No);
    }*/

    public function rules()
    {
        return [
               
        ];
    }

// General Ledger Setup -- Lookup dimension defs there

    public function attributeLabels()
    {
        return [
            'Global_Dimension_1_Code' => 'Sub Office',
            'Global_Dimension_2_Code' => 'Program Code',
            'ShortcutDimCode_x005B_4_x005D_' => 'Shade',
            'ShortcutDimCode_x005B_5_x005D_' => 'Animal',
            'ShortcutDimCode_x005B_6_x005D_' => 'unknown Dim 3',
            'ShortcutDimCode_x005B_7_x005D_' => 'Unknown Dim',
            'ShortcutDimCode_x005B_8_x005D_' => 'Unknown Dim2',
           
        ];
    }

    



}