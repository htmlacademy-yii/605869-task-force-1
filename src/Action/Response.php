<?php

namespace TaskForce\Action;

use frontend\models\Task;
use frontend\models\User;

/**
 * Class Response
 * @package TaskForceAction
 * Класс наследуется от абстрактного, возвращает действие "Откликнуться"
 */
class Response extends AbstractSelectingAction
{
    /** @inheritDoc */
    public function getActionTitle(): string
    {
        return 'Откликнуться';
    }

    /** @inheritDoc */
    public function getActionCode(): string
    {
        return 'response';
    }

    /** @inheritDoc */
    public function checkingUserStatus(Task $task, User $user): bool
    {
        if ($user->isCustomer()) {
            return false;
        }

        return !$task->getReplies()->where(['user_id' => $user->id])->exists();
    }
}