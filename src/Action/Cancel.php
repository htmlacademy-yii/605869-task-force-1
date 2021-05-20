<?php


namespace TaskForce\Action;

use frontend\models\Status;
use frontend\models\Task;

/**
 * Class Refusal
 * @package TaskForceAction
 * Класс наследуется от абстрактного, возвращает действие "Отменить"
 */
class Cancel extends AbstractSelectingAction
{
    /**
     * @return string|null
     * метод - для человекопонятного названия действия
     */
    public function getActionTitle($taskId)
    {
        $task = Task::findOne($taskId);
        if ($task->status_id == Status::STATUS_NEW) {
            return 'Отменить';
        }

        return  null;
    }

    /**
     * @return string|null
     * метод - для машинного названия действия
     */
    public function getActionCode()
    {
        return 'cancel';
    }

    /**
     * @param $idPerformer
     * @param $idCustomer
     * @param $idUser
     * @return bool
     * метод для проверки прав на совершение действия по отмене
     */
    public function checkingUserStatus($idPerformer, $idCustomer, $idUser)
    {
        return ($idUser !== $idPerformer);
    }
}