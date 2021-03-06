<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 5/11/2020
 * Time: 3:51 AM
 */

namespace frontend\models;
use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class Leaveattachment extends Model
{

    /**
     * @var UploadedFile
     */
public $Document_No;
public $Name;
public $File_path;
public $Key;
public $attachmentfile;


    public function rules()
    {
        return [
            [['attachmentfile'],'file','maxFiles'=> Yii::$app->params['LeavemaxUploadFiles']],
            [['attachmentfile'],'file','mimeTypes'=> Yii::$app->params['MimeTypes']],
            [['attachmentfile'],'file','maxSize' => '5120000'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'attachmentfile' => 'Document Attachment',
        ];
    }

    public function upload($docId)
    {
        $model = $this;

        $imageId = Yii::$app->security->generateRandomString(8);

        $imagePath = Yii::getAlias('@frontend/web/leave_attachments/'.$imageId.'.'.$this->attachmentfile->extension);
        $navPath = \yii\helpers\Url::home(true).'leave_attachments/'.$imageId.'.'.$this->attachmentfile->extension; // Readable from nav interface


        //return($model); 
        //Yii::$app->recruitment->printrr($this->attachmentfile->error);
        //var_dump($model->errors); exit;
        if($this->attachmentfile->error)
        {
            print 'Following file upload error occured: <b>'.Yii::$app->params['FILE_ERRORS'][$this->attachmentfile->error - 1].'</b>';
            exit;
        }

        if($model->validate()){
            // Check if directory exists, else create it
            if(!is_dir(dirname($imagePath))){
                FileHelper::createDirectory(dirname($imagePath));
            }



            $uploadObject = $this->attachmentfile->saveAs($imagePath);


            //Post to Nav
            if($docId && !$this->getAttachment($docId)) // A create scenario
            {
                $service = Yii::$app->params['ServiceName']['LeaveAttachments'];
                $model->Document_No = $docId;
                $model->File_path = $imagePath;//$imagePath;
                $result = Yii::$app->navhelper->postData($service, $model);
                
                return $result;
                
            }elseif($docId && $this->getAttachment($docId)) //An update scenario
            {
                $service = Yii::$app->params['ServiceName']['LeaveAttachments'];
                $model->Document_No = $docId;
                $model->File_path = $imagePath;
                $model->Key =  $this->getAttachment($docId)->Key;
                $result = Yii::$app->navhelper->updateData($service, $model);
                
                    return $result;
                
            }
            return true;
        }else{
            return false;
        }
    }

    public function getPath($DocNo=''){
        if(!$DocNo){
            return false;
        }
        $service = Yii::$app->params['ServiceName']['LeaveAttachments'];
        $filter = [
            'Document_No' => $DocNo
        ];

        $result = Yii::$app->navhelper->getData($service,$filter);
        if(is_array($result)) {
            return basename($result[0]->File_path);
        }else{
            return false;
        }

    }

    public function readAttachment($DocNo)
    {
        $service = Yii::$app->params['ServiceName']['LeaveAttachments'];
        $filter = [
            'Document_No' => $DocNo
        ];

        $result = Yii::$app->navhelper->getData($service,$filter);

        $path = $result[0]->File_path;

        if(is_file($path))
        {
            $binary = file_get_contents($path);
            $content = chunk_split(base64_encode($binary));
            return $content;
        }
    }

    public function getAttachment($DocNo)
    {

        $service = Yii::$app->params['ServiceName']['LeaveAttachments'];
        $filter = [
            'Document_No' => $DocNo
        ];

        $result = Yii::$app->navhelper->getData($service,$filter);
        if(is_array($result)){
            return $result[0];
        }else{
            return false;
        }

    }

    public function getFileProperties($binary)
    {
        $bin  = base64_decode($binary);
        $props =  getImageSizeFromString($bin);
        return $props['mime'];
    }
}