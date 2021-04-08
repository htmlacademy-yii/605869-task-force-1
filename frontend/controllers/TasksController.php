<?php

namespace frontend\controllers;

use frontend\models\Category;
use frontend\models\City;
use frontend\models\CreateTaskForm;
use frontend\models\Task;
use frontend\models\TaskFiltersForm;
use frontend\models\User;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;
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
        $filters->load(Yii::$app->request->get());
        
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
    
    public function actionCreate()
    {
        $categories = Category::find()->all();
        $categoryList = ArrayHelper::map($categories, 'id', 'name');
        $cities = City::find()->all();
        $cityList = ArrayHelper::map($cities, 'id', 'name');
        
        if (Yii::$app->user->getIdentity()->role != User::ROLE_CUSTOMER) {
            throw new ForbiddenHttpException('Страница доступна только для заказчиков');
        }
        
        $model = new CreateTaskForm();
        
        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            
            if ($model->validate()) {
                if ($task = $model->saveFields()) {
                    return $this->redirect(['tasks/view', 'id' => $task->id]);
                }
            }
        }
        
        return $this->render(
            'create',
            [
                'model' => $model,
                'categoryList' => $categoryList,
                'cities' => $cities,
                'cityList' => $cityList
            ]
        );
    }
}