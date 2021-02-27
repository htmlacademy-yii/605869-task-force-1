<?php

namespace frontend\controllers;

use frontend\models\Task;
use frontend\models\TaskFiltersForm;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Class tasksController
 * @package frontend\controllers
 */
class TasksController extends SecuredController
{
	/**
	 * @return string
	 */
	public function actionIndex()
	{
		$filters = new TaskFiltersForm();
		$filters->load(Yii::$app->request->post());

		return $this->render('index', ['dataProvider' => $filters->getDataProvider(), 'filters' => $filters]);
	}

	/**
	 * @param $id
	 * @return string
	 * @throws NotFoundHttpException
	 */
	public function actionView($id)
	{
		$task = Task::findOne($id);
		if (!$task) {
			throw new NotFoundHttpException("Задание с ID {$id} не существует!");
		}

		return $this->render('view', ['task' => $task]);
	}
}