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


class Taxie extends Model
{

public $Key;
public $No;
public $Created_By;
public $Created_Date;
public $Created_Time;
public $Employee_No;
public $Employee_Name;
public $Status;
public $Approvals;
public $isNewRecord;


    public function rules()
    {
        return [
               
               
        ];
    }

    public function attributeLabels()
    {
        return [

        ];
    }

    public function getLines(){
        $service = Yii::$app->params['ServiceName']['TaxieLines'];
        $filter = [
            'Document_No' => $this->No,
        ];

        $lines = Yii::$app->navhelper->getData($service, $filter);
       return $lines;


    }



}