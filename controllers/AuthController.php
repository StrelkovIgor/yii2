<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use app\models\form\auth as form;
use app\models\Users;

class AuthController extends Controller
{

    public function actionRegistration($r = null)
    {
		$this->hasGuest();
		
		if($r)
		{
			$cookies = Yii::$app->response->cookies;
			$cookies->add(new \yii\web\Cookie([
				'name' => 'referral',
				'value' => $r,
				'expire' => time()+(60*60*24*3)
			]));
		}
		
		$model = new form\Registration($r);
		
		if ($model->load(Yii::$app->request->post()) && $model->reg())
		{
			$this->redirect('login');
        }
		
        return $this->render('registration', [
			'model' => $model,
			'referral' => Users::getUserReferral($r)
		]);
    }


    public function actionLogin()
    {
		$this->hasGuest();
		
        $model = new form\Login();
        if ($model->load(Yii::$app->request->post()) && $model->login())
		{
            return $this->redirect('/auth/room');
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
	
	public function actionUrl()
	{
		if($user = Yii::$app->user->getIdentity())
		{
			$key = $user->getAttribute('referral_key');
			if(!$key)
			{
				$key = Users::getAccessToken('referral_key',4);
				$user->referral_key = $key;
				$user->save();
			}
			
			return $this->redirect('/auth/room');
		}
		return $this->redirect('/');
	}
	
	public function actionRoom()
	{
		if($user = Yii::$app->user->getIdentity())
		{
			$q = Users::find()->where(['referral_id' => $user->getAttribute('id')]);
			
			$pagination = new Pagination([
				'defaultPageSize' => 10,
				'totalCount' => $q->count(),
			]);
			
			$myReferral = $q->orderBy('login')
				->offset($pagination->offset)
				->limit($pagination->limit)
				->all();
			
			return $this->render('room',[
				'url' => ($user->getAttribute('referral_key'))? ('/auth/registration?r='.$user->getAttribute('referral_key')) : NULL,
				'userReferral' => Users::find()->where(['id' => $user->getAttribute('referral_id')])->one(),
				'myReferral' => $myReferral,
				'pagination' => $pagination
				
			]);
		}
		return $this->redirect('/');
	}
	
	private function hasGuest()
	{
		if (!Yii::$app->user->isGuest)
		{
            return $this->redirect('/');
        }
	}
	
}
