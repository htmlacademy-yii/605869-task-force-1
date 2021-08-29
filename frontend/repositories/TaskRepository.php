<?php

    namespace frontend\repositories;

    use frontend\models\Task;
    use yii\db\Expression;

    class TaskRepository
    {
        public static function getUserExpiredTask($id)
        {
            return Task::find()
                ->where(['<', new Expression('NOW')])
                ->andWhere(['executor_id' => $id])
                ->andWhere(['status_id' => Task::STATUS_IN_WORK])
                ->all();
        }
    }