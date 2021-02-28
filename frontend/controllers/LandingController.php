<?php

namespace frontend\controllers;

use common\models\LoginForm;
use frontend\models\Task;
use frontend\models\UserLoginForm;
use Yii;

class LandingController extends UnsecuredController
{
	public $layout = 'landing';
	
	public function actionIndex()
	{
		$model = new UserLoginForm();
//		$model = new LoginForm();
		if ($model->load(Yii::$app->request->post()) && $model->login()) {
			return $this->redirect('/tasks');
		} else {
			$model->password = '';
		}
		
		$tasks = Task::find()->orderBy('dt_add')->limit(4)->all();
		
		return $this->render('index', ['model' => $model, 'tasks' => $tasks]);
	}
	
//	public function actionLogin()
//	{
//		$model = new UserLoginForm();
//
//		if (Yii::$app->request->getIsPost()) {
//			$this->model->load(Yii::$app->request->post());
//			if ($this->model->validate()) {
//				Yii::$app->user->login($this->model->getUser());
//
//				return $this->redirect('/tasks', ['model' => $model]);
//			}
//		}
//
//		return $this->goHome();
//	}
}
