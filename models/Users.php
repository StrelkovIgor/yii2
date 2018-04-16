<?php

namespace app\models;

use Yii;
use \yii\db\ActiveRecord;

class Users extends ActiveRecord implements \yii\web\IdentityInterface
{
	public static function tableName()
	{
		return 'users';
	}
	
	public static function findIdentity($id)
    {
		return self::find()->where(['id'=>$id])->one();
    }
	public static function findIdentityByAccessToken($token, $type = null)
	{
		return self::find()->where(['accessToken' => $token])->one();
	}
	public function getId()
	{
		return $this->getAttribute('id');
	}
	public function getAuthKey(){
		return md5($this->getAttribute('id').md5($this->getAttribute('email')));
	}
	
	public function validateAuthKey($authKey){
		return $this->getAuthKey() === $authKey;
	}
	
	public static function authorization($login)
	{
		$user = self::find()
		->where(['password' => self::genPass($login->password)])
		->andWhere(['or', ['login' => $login->login_email],['email' => $login->login_email]])
		->one();
		if($user)
		{
			Yii::$app->user->login($user, $login->rememberMe ? 3600*24*30 : 0);
			return true;
		}
		return false;

	}
	
	public static function addUser($data)
	{
		$user = new Users();
		$user->login = $data['login'];
		$user->email = $data['email'];
		$user->password = self::genPass($data['password']);
		$user->accessToken = self::getAccessToken();
		$user->referral_id = self::getIdUserReferral($data['referral']);
		$user->save();
		
		return $user;
	}
	
	public static function getIdUserReferral($formReferral = NULL)
	{
		$cookies = Yii::$app->request->cookies;
		$referral = $formReferral ?? $cookies->getValue('referral', NULL);
		
		$user = self::find()->where(['referral_key'=>$referral])->one();
		if($user && $referral)
		{	
			return $user->getAttribute('id');
		}
		return 0;
	}
	
	public static function getUserReferral($referral = NULL)
	{
		return self::find()->where(['id' => self::getIdUserReferral($referral)])->one();
	}
	
	public static function genPass($password)
	{
		return md5(Yii::$app->params['hash'].md5($password));
	}
	
	public static function getAccessToken($name = 'accessToken', $k = 32)
	{
		$token = substr(bin2hex(random_bytes(16)), 0, $k);
		
		if(((int) self::find()->where([$name => $token])->count() ) == 0){
			return $token;
		}
		return self::getAccessToken($name, $k);
	}
	
	public function __get($name)
	{
		if($name == 'username')
		{
			return $this->getAttribute('login');
		}
	}
	
}