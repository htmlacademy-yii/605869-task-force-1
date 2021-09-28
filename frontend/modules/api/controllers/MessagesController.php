<?php

    namespace frontend\modules\api\controllers;

    use DateTime;
    use Exception;
    use frontend\models\Message;
    use frontend\models\Notification;
    use frontend\models\Task;
    use Yii;
    use yii\helpers\Json;
    use yii\web\BadRequestHttpException;
    use yii\web\Controller;
    use yii\web\Response;

    class MessagesController extends Controller
    {
        public $enableCsrfValidation = false;

        /**
         * @param int $id
         * @return array
         * @throws Exception
         */
        public function actionGet(int $id)
        {
            Yii::$app->response->format = Response::FORMAT_JSON;

            /** @var Message[] $messages */
            $messages = Message::find()->where(['task_id' => $id])->all();

            $data = [];
            foreach ($messages as $message) {
                $data[] = [
                    'message' => $message->message,
                    'published_at' => (new DateTime($message->dt_add))->format('Y-m-d H:i:s'),
                    'is_mine' => $message->sender_id == Yii::$app->user->getId(),
                ];
            }

            return $data;
        }

        /**
         * @param int $id
         * @return array
         * @throws BadRequestHttpException
         */
        public function actionAdd(int $id)
        {
            Yii::$app->response->format = Response::FORMAT_JSON;

            $body = Json::decode(Yii::$app->request->rawBody);

            if (!isset($body['message'])) {
                throw new BadRequestHttpException();
            }

            $model = new Message();
            $model->task_id = $id;
            $model->sender_id = Yii::$app->user->getId();
            $model->message = $body['message'];
            $model->save();

            // сохраняем уведомление о новом сообщении
            $notification = new Notification();
            $task = Task::findOne($id);
            $notification->user_id = $task->customer_id;
            $notification->title = $task->name;
            $notification->is_view = 0;
            $notification->icon = 'message';
            $notification->description = 'Новое сообщение в чате';
            $notification->task_id = $id;
            $notification->save();

            return [
                'id' => $model->id,
                'message' => $model->message,
                'published_at' => (new DateTime())->format('Y-m-d H:i:s'),
                'is_mine' => true,
            ];
        }
    }
