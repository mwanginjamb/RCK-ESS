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
public $Departure_Time;
public $Destination_Location;
public $Reason_For_Request;
public $No_of_Person_s_Travelling;
public $Document_No;
public $Total_Travel_Cost;
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
}