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


class Timesheetline extends Model
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
  public $Application_No;
  public $Line_No;
  public $Employee_No;

public $isNewRecord;

    /*public function __construct(array $config = [])
    {       
        return $this->getLines($this->No);
    }*/

    public function rules()
    {
        return [
            [['Grant','Start_Time','End_Time','Activity_Description'], 'required']
        ];
    }

    public function attributeLabels()
    {
        return [

        ];
    }





}