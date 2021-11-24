<?php

    namespace frontend\widgets;

    use frontend\models\Notification;
    use Yii;
    use yii\base\Widget;

    class Lightbulb extends Widget
    {
        public function init()
        {
            parent::init();
        }

        public function run()
        {
            $notifications = [];
            if (!Yii::$app->user->isGuest) {
                $notifications = Notification::find()
                    ->where(['user_id' => Yii::$app->user->identity->getId()])
                    ->orderBy(['dt_add' => SORT_DESC])
                    ->andWhere(['and', ['is_view' => 0]])
                    ->all();
            }
            return $this->render('lightbulb', [
                'notifications' => $notifications
            ]);
        }

    }
