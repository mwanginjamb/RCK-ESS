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


class Storerequisition extends Model
{

    public $Key;
    public $No;
    public $Employee_No;
    public $Employee_Name;
    public $Created_On;
    public $Global_Dimension_1_Code;
    public $Global_Dimension_2_Code;
    public $Status;
    public $Global_Dimension_3_Code;
    public $Posting_Date;
    public $Approval_Entries;
    public $Store_Location;
    public $isNewRecord;

    /*public function __construct(array $config = [])
    {
        return $this->getLines($this->No);
    }*/

    public function rules()
    {
        return [
            ['Store_Location', 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'Global_Dimension_1_Code' => 'Program',
            'Global_Dimension_2_Code' => 'Department',
            'Global_Dimension_3_Code' => 'Store'
        ];
    }

    public function getLines()
    {
        $service = Yii::$app->params['ServiceName']['StoreRequisitionLine'];
        $filter = [
            'Requisition_No' => $this->No,
        ];

        $lines = Yii::$app->navhelper->getData($service, $filter);
        return $lines;
    }
}
