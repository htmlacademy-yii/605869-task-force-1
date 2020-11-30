<?php

namespace frontend\controllers;

use frontend\models\TaskFiltersForm;
use Yii;
use yii\base\Action;
use yii\web\Controller;

/**
 * Class tasksController
 * @package frontend\controllers
 */
class TasksController extends Controller
{
    /**
     * @param Action $action
     * @return bool
     */
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return true;
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $filters = new TaskFiltersForm();
        $filters->load(Yii::$app->request->post());
        $tasks = $filters->getList();

        return $this->render('index', ['tasks' => $tasks, 'filters' => $filters]);
    }
}