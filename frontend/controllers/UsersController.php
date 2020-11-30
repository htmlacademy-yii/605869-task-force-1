<?php

namespace frontend\controllers;

use frontend\models\UserFiltersForm;
use Yii;
use yii\base\Action;
use yii\web\Controller;

/**
 * Class usersController
 * @package frontend\controllers
 */
class UsersController extends Controller
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
        $filters = new UserFiltersForm();
        $filters->load(Yii::$app->request->post());
        $users = $filters->getList();

        return $this->render('index', ['users' => $users, 'filters' => $filters]);
    }

}
