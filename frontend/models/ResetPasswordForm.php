<?php
namespace frontend\models;

use yii\base\InvalidArgumentException;
use yii\base\Model;
use common\models\User;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{
    public $password;
    public $confirmpassword;

    /**
     * @var \common\models\User
     */
    private $_user;


    /**
     * Creates a form model given a token.
     *
     * @param string $token
     * @param array $config name-value pairs that will be used to initialize the object properties
     * @throws InvalidArgumentException if token is empty or not valid
     */
    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidArgumentException('Password reset token cannot be blank.');
        }

        $userService = \Yii::$app->params['ServiceName']['UserSetup'];
        $User = \Yii::$app->navhelper->getData($userService,['password_reset_token' => $token]);
        $this->_user = $User[0];// User::findByPasswordResetToken($token);

        


        if (!$this->_user) {
            throw new InvalidArgumentException('Wrong password reset token.');
        }
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['password','confirmpassword'], 'required'],
            ['password', 'string', 'min' => 6],
            ['confirmpassword', 'string'],
            ['confirmpassword','compare','compareAttribute'=>'password','message'=>'Passwords do not match, try again'],
        ];
    }

    /**
     * Resets password.
     *
     * @return bool if password was reset.
     */
    public function resetPassword()
    {
        $Service = \Yii::$app->params['ServiceName']['PortalFactory'];
        // call code unit

        $data= [
            'newPassword' => $this->password,
            'confirmPassword' => $this->confirmpassword,
            'empNo' => $this->_user->Employee_No
        ];

        return \Yii::$app->navhelper->codeunit($Service,$data,'IanResetPassword');
        
        /*
        $user = $this->_user;
        $user->setPassword($this->password);
        $user->removePasswordResetToken();

        return $user->save(false);*/
    }
}
