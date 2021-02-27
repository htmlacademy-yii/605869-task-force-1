<?php

namespace frontend\controllers;

use frontend\models\User;
use frontend\models\UserFiltersForm;
use Yii;
use yii\web\NotFoundHttpException;


/**
 * Class usersController
 * @package frontend\controllers
 */
class UsersController extends SecuredController
{
	/**
	 * @return string
	 */
	public function actionIndex()
	{
		$filters = new UserFiltersForm();
		$filters->load(Yii::$app->request->post());

		return $this->render('index', [
			'dataProvider' => $filters->getDataProvider(),
			'filters' => $filters
		]);
	}
	
	public function actionView($id)
	{
		$user = User::findOne($id);
		if (!$user) {
			throw new NotFoundHttpException("Пользователь не найден");
		}

		return $this->render('view', ['user' => $user]);
	}

}
