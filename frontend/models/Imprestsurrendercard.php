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


class Imprestsurrendercard extends Model
{

    public $Key;
    public  $No;
    public  $Employee_No;
    public  $Employee_Name;
    public  $Imprest_No;
    public  $Purpose;
    public  $Employee_Balance;
    public  $Surrender_Amount;
    public  $Claim_Amount;
    public  $Status;
    public  $Global_Dimension_1_Code;
    public  $Global_Dimension_2_Code;
    public  $Posting_Date;
    public  $Receipt_No;
    public  $Receipt_Amount;
    public  $Approval_Entries;
    public  $Created_On;
    public  $Account_Type;
    public  $Paying_Bank;
    public  $Paying_Bank_Name;
    public  $Bank_Balance;
    public  $Pay_Mode;
    public  $Cheque_No;
    public  $EFT_No;
    public $isNewRecord;

    public $Request_For;
    public  $attachment;

    /*public function __construct(array $config = [])
    {
        return $this->getLines($this->No);
    }*/

    public function rules()
    {
        return [
            [['attachment'],'file','mimeTypes' => ['application/pdf']],
            [['attachment'],'file','maxSize' => '15728640'], //15mb
        ];
    }

    public function attributeLabels()
    {
        return [
            'Global_Dimension_1_Code' => 'Sub Office',
            'Global_Dimension_2_Code' => 'Program Code',
        ];
    }

    public function getLines($No){
        $service = Yii::$app->params['ServiceName']['ImprestRequestLine'];
        $filter = [
            'Request_No' => $No,
        ];

        $lines = Yii::$app->navhelper->getData($service, $filter);
       return $lines;


    }



}