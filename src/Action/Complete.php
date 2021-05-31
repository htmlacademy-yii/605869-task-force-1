<?php

namespace TaskForce\Action;

use frontend\models\Task;
use Yii;

/**
 * Class Done
 * @package TaskForceAction
 * Класс наследуется от абстрактного, возвращает действие "Завершить"
 */
class Complete extends AbstractSelectingAction
{
    /**
     * @return string|null
     * метод - для человекопонятного названия действия
     */
    public function getActionTitle($taskId)
    {
        $task = Task::findOne($taskId);
        
        if (Yii::$app->user->identity->getId() === $task->customer_id) {
            return 'Выполнено';
        }
        
        return null;
    }

    /**
     * @return string|null
     * метод - для машинного названия действия
     */
    public function getActionCode()
    {
        return 'complete';
    }

    /**
     * @param $idPerformer
     * @param $idCustomer
     * @param $idUser
     * @return bool
     * метод для проверки прав на совершение действия по завершению
     */
    public function checkingUserStatus($idPerformer, $idCustomer, $idUser)
    {
        return ($idUser !== $idPerformer);
    }
}