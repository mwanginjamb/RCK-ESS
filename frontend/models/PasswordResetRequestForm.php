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

        if(!is_array($Employee)){
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
        $user = User::findOne([
            'Employee No_ ' => $Employee[0]->No,
        ]);

        if (!$user) {
            return false;
        }
        
        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }

        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                ['user' => $user, 'employee' => $Employee[0]]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' ESS SUPPORT'])
            ->setTo($this->email)
            ->setSubject('Password reset for ' . Yii::$app->name)
            ->send();
    }
}
