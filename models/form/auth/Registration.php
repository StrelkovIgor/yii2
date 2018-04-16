<?php

namespace app\models\form\auth;

use Yii;
use yii\base\Model;
use app\models\Users;

class registration extends Model
{
	public $login;
	public $email;
	public $password;
	public $repeat_password;
	public $referral;
	
	public function __construct($r = null)
	{
		if($r) $this->referral = $r;
	}
	
	public function rules()
    {
		 return [
            [['login', 'email', 'password', 'repeat_password'], 'required'],
            [['login', 'email'], 'uniqueAmongTables'],
			['repeat_password','repeatPassword']
        ];
	}
	public function attributeLabels()
	{
		return [
			'login' => Yii::t('form','login'),
			'email' => Yii::t('form','email'),
			'password' => Yii::t('form','password'),
			'repeat_password' => Yii::t('form','repeat_password'),
			'referral' => ''
		];
	}
	
	public function repeatPassword($attribute)
	{
		$attr = $this->attributeLabels();
		if($this->password !== $this->repeat_password)
			$this->addError($attribute, Yii::t('form','repeat',['pole1'=>$attr['password'],'pole2'=>$attr['repeat_password']]));
			
	}
	
	public function uniqueAmongTables($attribute)
	{
		$count = (int) Users::find()->where([$attribute => $this->$attribute])->count();
		if($count)
		{
			$this->addError($attribute, Yii::t('form','unique',['attribute'=>$this->attributeLabels()[$attribute]]));
		}
	}
	
	
	public function reg(){
		if($this->validate()){
			Users::addUser($this->getDataRequest());
			Yii::$app->response->cookies->remove('referral');
			return true;
		}
		return false;
		
	}
	
	public function getDataRequest(){
		$data = [];
		foreach($this->attributeLabels() as $attribute => $name)
			$data[$attribute] = $this->$attribute;
		return $data;
	}
	
}