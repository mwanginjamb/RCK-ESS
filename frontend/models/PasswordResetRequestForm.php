<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'emailCheck' ],
        ];
    }


    public function emailCheck($attribute, $params)
    {
       $service = Yii::$app->params['ServiceName']['EmployeeCard'];
       $Employee = Yii::$app->navhelper->getData($service,['Company_E_Mail' => $this->email]);

        if(is_object($Employee) ||   is_string($Employee) ){
            $this->addError($attribute,'E-mail address supplied is not associated with any Employee.');
        }

        
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail()
    {
        $service = Yii::$app->params['ServiceName']['EmployeeCard'];
        $userService = Yii::$app->params['ServiceName']['UserSetup'];
        $Employee = Yii::$app->navhelper->getData($service,['Company_E_Mail' => $this->email]);


        /* @var $user User */
        $User = Yii::$app->navhelper->getData($userService,['Employee_No' => $Employee[0]->No]);

        if (!is_array($User)) {
            return false;
        }

        $user = User::findOne(['User ID' => $User[0]->User_ID]);

        //Yii::$app->recruitment->printrr($user->User_ID);
        
        if (!user::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken($User);
            if (!$user->save(false)) {
                return false;
            }
        }

        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                ['user' => $user, 'employee' => $Employee[0]]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' SUPPORT'])
            ->setTo($this->email)
            ->setSubject('Password reset for ' . Yii::$app->name)
            ->send();
    }
}
