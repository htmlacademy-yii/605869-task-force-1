<?php

    namespace frontend\behavior;

    use frontend\models\User;
    use Yii;
    use yii\base\Behavior;
    use yii\base\Controller;
    use yii\db\Expression;

    class UserLastActivityBehavior extends Behavior
    {
        public function events()
        {
            return [Controller::EVENT_BEFORE_ACTION => 'beforeAction'];
        }

        public function beforeAction()
        {
            User::findOne(Yii::$app->user->getId())
                ->updateAttributes(
                    ['last_activity_datetime' => new Expression('NOW()')]
                );

            return true;
        }
    }
