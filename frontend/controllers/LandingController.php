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
		if ($model->load(Yii::$app->request->post()) && $model->login()) {
			return $this->redirect('/tasks');
		} else {
			$model->password = '';
		}
		
		$tasks = Task::find()->orderBy('dt_add')->limit(4)->all();
		
		return $this->render('index', ['model' => $model, 'tasks' => $tasks]);
	}
}
