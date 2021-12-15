<?php
namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            //['rememberMe', 'boolean'],
            // password is validated by validatePassword()
           ['password', 'validatePassword'],
           
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
       
        // do Active directory authentication here
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            //Yii::$app->recruitment->printrr($user);

            if (!$user || !$user->validatePassword($this->password) || !User::Auth($this->username,$this->password)->Key) {//Add AD login condition here also--> when ad details are given

                $this->addError($attribute, 'Incorrect username or password.');
            }
        }else {
            Yii::$app->recruitment->printrr($this->errors);
        }


    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {

        if ($this->validate()) {

            //Lets log the password
            Yii::$app->session->set('IdentityPassword', $this->password);
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);

        }
        
        return false;
    }

    //Ad Login

    function logintoAD($username,$password){
        

        $adServer = Yii::$app->params['adServer'];//
        $ldap = ldap_connect($adServer, 389);//connect
        $ldaprdn = Yii::$app->params['ldPrefix'] . "\\" . strtoupper($username);//put the username in a way specific to the domain
        ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
        $bind = @ldap_bind($ldap, $ldaprdn, $password);
       
        if ($bind) {
           
            return $bind; // True for a bind else false
            $filter = "(sAMAccountName=$username)";
            $result = ldap_search($ldap, "CN=Users,DC=KWTRP, DC=ORG", $filter);

            // ldap_sort($ldap,$result,"sn");
            $info = ldap_get_entries($ldap, $result);

            return $info;
            @ldap_close($ldap);
        } else {
            //notify incorrect login
            return false;
        }

    }



    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {

        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }


        return $this->_user;
    }
}
