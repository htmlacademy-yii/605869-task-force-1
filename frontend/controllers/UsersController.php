<?php

    namespace frontend\controllers;

    use frontend\models\User;
    use frontend\models\UserFiltersForm;
    use Yii;
    use yii\base\Action;
    use yii\web\Controller;
    use yii\web\NotFoundHttpException;

    /**
     * Class usersController
     * @package frontend\controllers
     */
    class UsersController extends Controller
    {
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

        public function actionView($id)
        {
            $user = User::findOne($id);
            if (!$user) {
                throw new NotFoundHttpException("Задание с ID {$id} не существует!");
            }

            return $this->render('view', ['user' => $user]);
        }

    }
