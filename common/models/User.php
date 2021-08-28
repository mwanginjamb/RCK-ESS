<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    public $Key;
    public $User_ID;
    public $Allow_Posting_From;
    public $Allow_Posting_To;
    public $Register_Time;
    public $Salespers_Purch_Code;
    public $Sales_Resp_Ctr_Filter;
    public $Purchase_Resp_Ctr_Filter;
    public $Service_Resp_Ctr_Filter;
    public $Time_Sheet_Admin;
    public $Email;
    public $Employee_No;
    public $Is_HR_Admin;
    public $Global_Dimension_1_Code;
    public $Additional_Program;
    public $Global_Dimension_2_Code;
    public $Head_of_Department;
    public $Supervisor_Id;
    public $Does_Payment_Voucher;
    public $Global_Dimension_3_Code;
    public $Apply_Leave_Later_Dater;
    public $Finance_Admin;
    public $Is_Store_Admin;
    public $Multiple_LogIns_Allowed_x003F_;
    public $Number_Of_Sessions;
    public $Allow_LogIn_From;
    public $Allow_LogIn_To;
    public $Allow_Time_Restriction;
    public $Payables_User;
    public $Is_Payroll_Admin;
    public $Approval_Administrator;
    public $M_PESA_User;
    public $password_reset_token;



    


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
       // return '{{%user}}';

        return Yii::$app->params['DBCompanyName'].'User Setup ';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
           // ['status', 'default', 'value' => self::STATUS_INACTIVE],
           // ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {

       /* if(is_int($id))
        {
            return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
        }
        return null;*/
        if($id == 1){
            return null;
        }
        $username = strtoupper($id);
        return static::findOne(['User ID' => $id]);

    }

     public static function Auth($username,$password)
    {
        $service = Yii::$app->params['ServiceName']['UserSetup'];
        $credentials = new \stdClass();
        //$json = file_get_contents('php://input');
        // Convert it into a PHP object
        //$data = json_decode($json);
        $NavisionUsername = $username;
        $NavisionPassword = $password;

        $credentials->UserName = $NavisionUsername;
        $credentials->PassWord = $NavisionPassword;

       // return $credentials;

        $result = Yii::$app->navhelper->authEntry($service,$credentials,'User_ID', $NavisionUsername);


        return new User($result);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
       // return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
        $username = strtoupper($username);

        return static::findOne(['User ID' => $username]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
       
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
           // 'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token) {

        return;

        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    public static function primaryKey()
    {
        return 'User ID';
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
       // return $this->getPrimaryKey();
        return $this->{'User ID'};
    }


    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return 1;
       // return $this->auth_key;


    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return true; // For AD auth just return true since we are not using yii2 validation mechanism.
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken($User)
    {
       // Yii::$app->recruitment->printrr($User);
       $token = Yii::$app->security->generateRandomString() . '_' . time();
       $this->password_reset_token = $token; // This is just for maintaining identity token state, update id done by user setup service
       $userService = Yii::$app->params['ServiceName']['UserSetup'];
       $data = [
           'Key' => $User[0]->Key,
           'password_reset_token' => $token 
        ];
       Yii::$app->navhelper->updateData($userService, $data);
    }

    /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /*public static function getDb(){
        return Yii::$app->nav;
    }*/

    public function getEmployee(){
        $service = Yii::$app->params['ServiceName']['EmployeeCard'];
        $filter = [
            'No' => Yii::$app->user->identity->{'Employee No_'},
        ];

        //Yii::$app->recruitment->printrr($filter);

        $employee = \Yii::$app->navhelper->getData($service,$filter);
        return $employee;
    }
    /* Get Appraisal Supervisor*/
    public function isSupervisor($AppraiseeNo = ''){

       // Yii::$app->recruitment->printrr(Yii::$app->user->identity->employee);
        $service = Yii::$app->params['ServiceName']['SupervisorList'];
        $filter = [
            'Emp_No' => Yii::$app->user->identity->{'Employee No_'},
        ];

        $result = \Yii::$app->navhelper->getData($service,$filter);

        // Yii::$app->recruitment->printrr($result);

        if(is_array($result))
        {
             // return ($result[0]->Level == 'Line_Manager')?TRUE:FALSE;

            foreach($result as $obj) 
            {
                if($obj->Level == 'Line_Manager')
                {
                    return TRUE;
                }
            }
        }

        return false;
       
       
    }

    /* Get Overview Status*/

    public function isOverview($AppraiseeNo = ''){

       // Yii::$app->recruitment->printrr(Yii::$app->user->identity->employee);
        $service = Yii::$app->params['ServiceName']['SupervisorList'];
        $filter = [
            'Emp_No' => Yii::$app->user->identity->{'Employee No_'},
        ];

        $result = \Yii::$app->navhelper->getData($service,$filter);

        if(is_array($result))
        {
            return $result[0]->Level == 'OverView';
        }

        return false;        
       
    }

    public function isApprover()
    {
        $service = Yii::$app->params['ServiceName']['RequestsTo_ApprovePortal'];
        $filter = [
            'Approver_No' => Yii::$app->user->identity->{'Employee No_'},
        ];

        $result = \Yii::$app->navhelper->getData($service,$filter);

        return is_array($result);
    }

    public function getNavuserid()
    {
        return \Yii::$app->user->identity->employee[0]->User_ID;
    }

    public function isAppraisalSupervisor($AppraiseeNo = ''){

        if(Yii::$app->session->has('Appraisee_ID'))
        {
            $Appraisee_ID = Yii::$app->session->get('Appraisee_ID');
            //check if current identity appears as supervisor with corresponsing Employee_User_Id
            $super = \common\models\AppraisalHeader::find()->where([
                'Supervisor User Id ' => $this->getId(),
                'Employee User Id ' => $Appraisee_ID
            ])->count();

            if($super > 0){
                return true;
            }else{
                return false;
            }
        }else {
            Yii::$app->session->setFlash('error', 'Appraisee Employee_User_ID state is missing.');
            return false;

        }
    }

    // Loggedin User is designated as an appraisal Supervisor

    public function isAppraisalSupervisorDesignate($AppraiseeNo = ''){

        //check if current identity is designated as supervisor for any employee
        $super = \common\models\AppraisalHeader::find()->where([
            'Supervisor User Id ' => $this->getId()
        ])->count();

        if($super > 0){
            return true;
        }else{
            return false;
        }

    }

    public function isPeer1(){
        //loop through user setup check if current identity appears in approvers column
        $super = Appraisalheader::find()->where(['Peer 1 Employee No ' => $this->{'Employee No_'}])->count();

        if($super > 0){
            return true;
        }else{
            return false;
        }
    }

    public function isPeer2(){
        //loop through user setup check if current identity appears in approvers column
        $super = Appraisalheader::find()->where(['Peer 2 Employee No ' => $this->{'Employee No_'}])->count();

        if($super > 0){
            return true;
        }else{
            return false;
        }
    }



}
