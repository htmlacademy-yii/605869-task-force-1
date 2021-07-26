<?php

namespace TaskForce\Action;

use frontend\models\Task;
use frontend\models\User;

/**
 * Class Done
 * @package TaskForceAction
 * Класс наследуется от абстрактного, возвращает действие "Завершить"
 */
class Complete extends AbstractSelectingAction
{
    /** @inheritDoc */
    public function getActionTitle(): string
    {
        return 'Выполнено';
    }

    /** @inheritDoc */
    public function getActionCode(): string
    {
        return 'complete';
    }

    /** @inheritDoc */
    public function checkingUserStatus(Task $task, User $user): bool
    {
        return $user->id === $task->customer_id;
    }
}