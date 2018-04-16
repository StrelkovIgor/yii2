<?php

namespace app\models\form\auth;

use Yii;
use yii\base\Model;
use app\models\Users;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class Login extends Model
{
    public $login_email;
    public $password;
    public $rememberMe = true;


    public function attributeLabels()
	{
		return [
			'login_email' => Yii::t('auth','login'),
			'password' => Yii::t('form','password'),
			'rememberMe' => Yii::t('auth','rememberMe')
		];
	}
	
    public function rules()
    {
        return [
            [['login_email', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
        ];
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate())
		{
            if(!Users::authorization($this))
			{
				$this->addError('login_email',Yii::t('auth', 'login_error'));
				return false;
			}
			return true;
        }
        return false;
    }

}
