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


class Overtimeline extends Model
{

  public $Key;
  public $Date; 
  public $Start_Time; 
  public $End_Time; 
  public $Percentage; 
  public $Hours_Worked; 
  public $Activity_Description; 
  public $Grant; 
  public $Supervisor_Comments; 
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

    public function attributeLabels()
    {
        return [

        ];
    }





}