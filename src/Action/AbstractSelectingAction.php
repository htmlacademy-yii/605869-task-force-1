<?php

namespace TaskForce\Action;

use frontend\models\Task;
use frontend\models\User;

/**
 * Class AbstractSelectingAction
 * @package TaskForceAction
 * Действие, которое теоретически может совершить пользователь:
 * например, взять задачу, закрыть задачу и так далее
 */
abstract class AbstractSelectingAction
{
    /**
     * Action title
     *
     * @return string
     */
    abstract public function getActionTitle(): string;
    //метод - для человекопонятного названия действия ("Отменить", "Откликнуться")

    /**
     * Action internal code
     *
     * @return string
     */
    abstract public function getActionCode(): string;
    // метод - для машинного названия действия ("action_cancel", "action_respond")

    /**
     * Is action can be applied to the task
     *
     * @param Task $task
     * @param User $user
     *
     * @return bool
     */
    abstract public function checkingUserStatus(Task $task, User $user): bool;
    //метод для проверки прав
}