<?php

namespace frontend\controllers;

use frontend\models\Category;
use frontend\models\City;
use frontend\models\CompleteTaskForm;
use frontend\models\CreateTaskForm;
use frontend\models\Opinions;
use frontend\models\Profiles;
use frontend\models\Replies;
use frontend\models\ResponseTaskForm;
use frontend\models\Status;
use frontend\models\Task;
use frontend\models\TaskFiltersForm;
use frontend\models\User;
use TaskForce\Tasks;
use Yii;
use yii\helpers\ArrayHelper;
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
    public function actionView($id)
    {
        $task = Task::findOne($id);
        if (!$task) {
            throw new NotFoundHttpException("Задание с ID {$id} не существует!");
        }

        $strategy = new Tasks(
            $task->executor_id, $task->customer_id, Yii::$app->user->identity->getId(),
            $task->status_id
        );
        $strategy->getAvailableAction($task->id);

        $responseTaskForm = new ResponseTaskForm($task);
        $completeTaskForm = new CompleteTaskForm();
        $replies = Replies::find()->where(['task_id' => $task->id, 'user_id' => Yii::$app->user->identity->getId()]);

        return $this->render(
            'view',
            [
                'task' => $task,
                'strategy' => $strategy,
                'responseTaskForm' => $responseTaskForm,
                'completeTaskForm' => $completeTaskForm,
                'replies' => $replies,
                'userId' => Yii::$app->user->identity->getId(),
            ]
        );
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

    public function actionApply($taskId, $userId, $replyId)
    {
        $task = Task::findOne($taskId);
        if ($task->status_id = Status::STATUS_NEW) {
            $task->status_id = Status::STATUS_IN_WORK;
            $task->replies_id = $replyId;
            $task->executor_id = $userId;
            $task->save();
            $reply = Replies::findOne($replyId);
            $reply->status = Replies::STATUS_ACCEPT;
            $reply->save();
        }

        return $this->redirect(['tasks/view', 'id' => $task->id]);
    }

    public function actionRefuse($taskId, $replyId)
    {
        $task = Task::findOne($taskId);
        $reply = Replies::findOne($replyId);
        $profiles = Profiles::findOne(['user_id' => $reply->user_id]);

        if ($reply->status == Replies::STATUS_NEW) {
            $reply->status = Replies::STATUS_REFUSAL;
            $reply->save();
        } elseif ($reply->status == Replies::STATUS_ACCEPT && $task->status_id == Task::STATUS_IN_WORK) {
            $reply->status = Replies::STATUS_REFUSAL;
            $reply->save();

            $task->status_id = Status::STATUS_FAILED;
            $task->save();

            $profiles->counter_failed_tasks++;
            $profiles->save();
        }

        return $this->redirect(['tasks/view', 'id' => $task->id]);
    }

    public function actionResponse($taskId)
    {
        $task = Task::findOne($taskId);

        if (!$task) {
            throw new NotFoundHttpException("Задание не найдено");
        }

        $user = User::findOne(Yii::$app->user->identity->getId());

        $replies = Replies::find()->where(['task_id' => $taskId, 'user_id' => $user->id]);

        if ($user->role === User::ROLE_EXECUTOR && empty($replies)) {
            $responseTaskForm = new ResponseTaskForm($task);

            if (Yii::$app->request->getIsPost()) {
                $request = Yii::$app->request->post();
            }

            if ($responseTaskForm->load($request) && $responseTaskForm->validate()) {
                $responseTaskForm->createReply();
            }

            return $this->redirect(['tasks/view', 'id' => $task->id]);
        } else {
            throw new NotFoundHttpException(
                "Откликаться на задания могут только пользователи со статусом ИСПОЛНИТЕЛЬ и ранее не откликавшиеся на задание"
            );
        }
    }

    public function actionComplete($taskId)
    {
        $task = Task::findOne($taskId);

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
                } else {
                    $task->status_id = Status::STATUS_COMPLETED;
                    $task->save();
                }
            }

            $opinions = new Opinions();
            $opinions->task_id = $task->id;
            $opinions->comment = $completeTaskForm->comment;
            $opinions->rate = $completeTaskForm->rating;
            $opinions->save();

            return $this->redirect('/tasks');
        } else {
            throw new NotFoundHttpException(
                "Завершать задание может только его автор"
            );
        }
    }

    public function actionCancel($taskId)
    {
        $task = Task::findOne($taskId);

        if ($task->status_id == Status::STATUS_NEW) {
            $task->status_id = Status::STATUS_CANCEL;
            $task->save();
        }

        return $this->redirect(['tasks/view', 'id' => $task->id]);
    }
}