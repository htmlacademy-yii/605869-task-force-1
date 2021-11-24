<?php

    namespace frontend\strategies;

    use frontend\models\Task;
    use Yii;
    use yii\data\ActiveDataProvider;
    use yii\db\Expression;

    class MyTaskListStrategy
    {
        private int $status;

        /**
         * @param int $status
         */
        public function __construct(int $status)
        {
            $this->status = $status;
        }

        public function getDataProvider()
        {
            $userId = Yii::$app->user->getId();

            switch ($this->status) {
                case Task::STATUS_CANCELED:
                    $query = $this->getCancelQuery();
                    break;
                case  Task::STATUS_EXPIRED:
                    $query = $this->getExpiredQuery();
                    break;
                default:
                    $query = Task::find()->where(['status_id' => $this->status]);
            }

            $query->andWhere('customer_id = :id OR executor_id = :id', ['id' => $userId]);

            return new ActiveDataProvider(
                [
                    'query' => $query,
                    'pagination' => ['pageSize' => 5]
                ]
            );
        }

        private function getCancelQuery()
        {
            return Task::find()
                ->where([
                    'IN',
                    'status_id',
                    [
                        Task::STATUS_CANCELED,
                        Task::STATUS_FAILED,
                    ]
                ]);
        }

        private function getExpiredQuery()
        {
            return Task::find()
                ->where(['<', 'expire', new Expression('NOW()')])
                ->andWhere(['status_id' => Task::STATUS_IN_WORK]);
        }
    }