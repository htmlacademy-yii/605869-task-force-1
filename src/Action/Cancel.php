<?php

namespace TaskForce\Action;

use frontend\models\Task;
use frontend\models\User;

/**
 * Class Refusal
 * @package TaskForceAction
 * Класс наследуется от абстрактного, возвращает действие "Отменить"
 */
class Cancel extends AbstractSelectingAction
{
    /** @inheritDoc */
    public function getActionTitle(): string
    {
        return 'Отменить';
    }

    /** @inheritDoc */
    public function getActionCode(): string
    {
        return 'cancel';
    }

    /** @inheritDoc */
    public function checkingUserStatus(Task $task, User $user): bool
    {
        return $user->id === $task->customer_id;
    }
}