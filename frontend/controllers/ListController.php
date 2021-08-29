<?php

    namespace frontend\controllers;

    use frontend\models\Task;
    use frontend\strategies\MyTaskListStrategy;

    class ListController extends SecuredController
    {
        public function actionIndex(int $status = Task::STATUS_NEW)
        {
            $strategy = new MyTaskListStrategy($status);

            return $this->render(
                'index',
                [
                    'dataProvider' => $strategy->getDataProvider(),
                    'status' => $status,
                ]
            );
        }
    }
