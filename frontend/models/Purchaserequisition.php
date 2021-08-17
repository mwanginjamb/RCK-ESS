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


class Purchaserequisition extends Model
{

public $Key;
public $No;
public $Employee_No;
public $Employee_Name;
public $Title;
public $Requested_Delivery_Date;
public $Project_Code;
public $Global_Dimension_1_Code;
public $Global_Dimension_2_Code;
public $Requisition_Date;
public $Status;
public $Purchase_Requisition_Line;
public $Approval_Entries;
public $isNewRecord;

    /*public function __construct(array $config = [])
    {
        return $this->getLines($this->No);
    }*/

    public function rules()
    {
        return [
                [['Title','No','Requested_Delivery_Date'], 'required'],
                ['Title', 'string','max' => 250],
                ['Requested_Delivery_Date', 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'Global_Dimension_2_Code' => 'Program Code',
        ];
    }

    public function getLines(){
        $service = Yii::$app->params['ServiceName']['PurchaseRequisitionLine'];
        $filter = [
            'Requisition_No' => $this->No,
        ];

        $lines = Yii::$app->navhelper->getData($service, $filter);
       return $lines;


    }



}