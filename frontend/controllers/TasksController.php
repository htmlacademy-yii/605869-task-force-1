<?php

    namespace frontend\controllers;

    use frontend\helpers\MailSender;
    use frontend\models\Category;
    use frontend\models\City;
    use frontend\models\CompleteTaskForm;
    use frontend\models\CreateTaskForm;
    use frontend\models\Notification;
    use frontend\models\Opinions;
    use frontend\models\Replies;
    use frontend\models\ResponseTaskForm;
    use frontend\models\SiteSettings;
    use frontend\models\Status;
    use frontend\models\Task;
    use frontend\models\TaskFiltersForm;
    use frontend\models\User;
    use Yii;
    use yii\db\ActiveQuery;
    use yii\helpers\ArrayHelper;
    use yii\web\BadRequestHttpException;
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
        public function actionView(int $id)
        {
            $task = Task::find()->where(['id' => $id])->with(
                [
                    'replies' => function (ActiveQuery $query) {
                        $query->with('user');
                    },
                    'customer',
                ]
            )->one();

            if (!$task) {
                throw new NotFoundHttpException("Задание не существует!");
            }

            return $this->render('view', ['task' => $task]);
        }

        public function actionCreate()
        {
            if (Yii::$app->user->getIdentity()->role != User::ROLE_CUSTOMER) {
                throw new ForbiddenHttpException('Страница доступна только для заказчиков');
            }

            $categories = Category::find()->all();
            $categoryList = ArrayHelper::map($categories, 'id', 'name');
            $cities = City::find()->all();

            $model = new CreateTaskForm();

            if (Yii::$app->request->isPost) {
                $model->load(Yii::$app->request->post());

                if ($model->validate() && ($task = $model->saveFields())) {
                    return $this->redirect(['tasks/view', 'id' => $task->id]);
                }
            }

            return $this->render(
                'create',
                [
                    'model' => $model,
                    'categoryList' => $categoryList,
                    'cities' => $cities,
                ]
            );
        }

        public function actionApply(int $id)
        {
            $reply = Replies::findOne($id);

            if (!$reply) {
                throw new NotFoundHttpException('Отклик не найден');
            }

            if ($reply->task->status_id != Task::STATUS_NEW) {
                throw new BadRequestHttpException('Недопустимый статус задачи');
            }

            $reply->task->updateAttributes(
                [
                    'status_id' => Task::STATUS_IN_WORK,
                    'executor_id' => $reply->user_id,
                ]
            );

            $reply->updateAttributes(
                [
                    'status' => Replies::STATUS_ACCEPTED,
                ]
            );

            if ($reply->task->executor->siteSettings->show_actions_of_task === SiteSettings::ENABLED) {
                $setTo = $reply->task->executor->email;
                $subject = 'Отклик на задание «' . $reply->task->name . '» от «' . $reply->task->executor->name . '» принят';
                MailSender::mail($setTo, $subject);
            }

            // сохраняем уведомление о выборе исполнителя
            $notification = new Notification();
            $notification->user_id = $reply->user_id;
            $notification->title = $reply->task->name;
            $notification->is_view = 0;
            $notification->icon = 'executor';
            $notification->description = 'Выбран исполнитель для';
            $notification->task_id = $reply->task->id;
            $notification->save();

            return $this->redirect(['tasks/view', 'id' => $reply->task_id]);
        }

        public function actionRefuse(int $id)
        {
            $task = Task::findOne($id);
            if (!$task) {
                throw new NotFoundHttpException("Задание не найдено");
            }

            $profiles = $task->executor->profiles;

            $task->updateAttributes(['status_id' => Task::STATUS_FAILED]);

            $profiles->counter_failed_tasks++;
            $profiles->save();

            if ($task->executor->siteSettings->show_actions_of_task === SiteSettings::ENABLED) {
                $setTo = $task->customer->email;
                $subject = $task->executor->name . ' отказался от вашего задания «' . $task->name . '»';
                MailSender::mail($setTo, $subject);
            }

            return $this->redirect(['tasks/view', 'id' => $task->id]);
        }

        public function actionResponse(int $id)
        {
            $task = Task::findOne($id);

            if (!$task) {
                throw new NotFoundHttpException("Задание не найдено");
            }

            $user = User::findOne(Yii::$app->user->getId());

            if ($user->isCustomer()) {
                throw new BadRequestHttpException(
                    'Откликаться на задания могут только пользователи со статусом ИСПОЛНИТЕЛЬ'
                );
            }

            if ($reply = Replies::find()->where(['task_id' => $id, 'user_id' => $user->id])->exists()) {
                throw new BadRequestHttpException(
                    'Откликаться на задания могут только пользователи ранее не откликавшиеся на задание'
                );
            }

            $responseTaskForm = new ResponseTaskForm($task);

            if ($responseTaskForm->load(Yii::$app->request->post()) && $responseTaskForm->validate()) {
                $responseTaskForm->createReply();

                if ($task->customer->siteSettings->show_actions_of_task === SiteSettings::ENABLED) {
                    $setTo = $task->customer->email;
                    $subject = 'На ваше задание: «' . $task->name . '» откликнулся «' . $user->name . '»';
                    MailSender::mail($setTo, $subject);
                }
            }

            return $this->redirect(['tasks/view', 'id' => $task->id]);
        }

        public function actionComplete(int $id)
        {
            $task = Task::findOne($id);

            if (!$task) {
                throw new NotFoundHttpException("Задание не найдено");
            }

            if (Yii::$app->user->identity->getId() === $task->customer_id) {
                $completeTaskForm = new CompleteTaskForm();

                if (Yii::$app->request->getIsPost()) {
                    $request = Yii::$app->request->post();
                }

                if ($completeTaskForm->load($request) && $completeTaskForm->validate()) {
                    if ($completeTaskForm->completion === CompleteTaskForm::COMPLETION_PROBLEMS) {
                        $task->status_id = Status::STATUS_FAILED;
                        $task->save();

                        if ($task->executor->siteSettings->show_actions_of_task === SiteSettings::ENABLED) {
                            $setTo = $task->executor->email;
                            $subject = 'Задание: «' . $task->name . '» провалено!';
                            MailSender::mail($setTo, $subject);
                        }
                    } else {
                        $task->status_id = Status::STATUS_COMPLETED;
                        $task->save();

                        if ($task->executor->siteSettings->show_actions_of_task === SiteSettings::ENABLED) {
                            $setTo = $task->executor->email;
                            $subject = 'Задание: «' . $task->name . '» принято!';
                            MailSender::mail($setTo, $subject);
                        }
                    }
                }

                $opinions = new Opinions();
                $opinions->task_id = $task->id;
                $opinions->comment = $completeTaskForm->comment;
                $opinions->rate = $completeTaskForm->rating;
                $opinions->save();

                // сохраняем уведомление о завершении задания
                $notification = new Notification();
                $notification->user_id = $task->executor_id;
                $notification->title = $task->name;
                $notification->is_view = 0;
                $notification->icon = 'close';
                $notification->description = 'Завершено задание';
                $notification->task_id = $task->id;
                $notification->save();

                return $this->redirect('/tasks');
            } else {
                throw new NotFoundHttpException(
                    "Завершать задание может только его автор"
                );
            }
        }

        public function actionCancel(int $id)
        {
            $task = Task::findOne($id);
            if (!$task) {
                throw new NotFoundHttpException("Задание не найдено");
            }

            if ($task->status_id == Status::STATUS_NEW) {
                $task->status_id = Status::STATUS_CANCEL;
                $task->save();
            }

            return $this->redirect(['tasks/view', 'id' => $task->id]);
        }
    }
