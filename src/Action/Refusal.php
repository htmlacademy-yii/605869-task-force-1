<?php

namespace TaskForce\Action;

use frontend\models\Task;
use frontend\models\User;

/**
 * Class Refuse
 * @package TaskForceAction
 * Класс наследуется от абстрактного, возвращает действие "Отказаться"
 */
class Refusal extends AbstractSelectingAction
{
    /** @inheritDoc */
    public function getActionTitle(): string
    {
        return 'Отказаться';
    }

    /** @inheritDoc */
    public function getActionCode(): string
    {
        return 'refusal';
    }

    /** @inheritDoc */
    public function checkingUserStatus(Task $task, User $user): bool
    {
        return $user->id === $task->executor_id;
    }
}