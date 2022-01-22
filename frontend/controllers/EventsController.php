<?php

    namespace frontend\controllers;

    use frontend\models\Notification;
    use Yii;
    use yii\web\Controller;

    class EventsController extends Controller
    {
        public function actionIndex()
        {
            $notifications = Notification::find()
                ->where(['user_id' => Yii::$app->user->identity->getId()])
                ->andWhere(['and', ['is_view' => 0]])
                ->all();

            foreach ($notifications as $notification) {
                $notification->is_view = 1;
                $notification->save();
            }
        }
    }
