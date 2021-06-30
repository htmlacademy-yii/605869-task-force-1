<?php

declare(strict_types=1);

namespace TaskForce;

use Exception;
use frontend\models\Task;
use frontend\models\User;
use TaskForce\Action\AbstractSelectingAction;
use TaskForce\Action\Cancel;
use TaskForce\Action\Complete;
use TaskForce\Action\Refusal;
use TaskForce\Action\Response;

/**
 * класс для определения списков действий и статусов, и выполнения базовой работы с ними
 * Class Task
 * @package TaskForce\src
 */
class TaskActionStrategy
{
    /** @var Task */
    private $task;

    /** @var User */
    private $user;

    public function __construct(Task $task, User $user)
    {
        $this->task = $task;
        $this->user = $user;
    }

    /**
     * метод возвращающий статус в который перейдет задание
     * @param string $actionCode
     * @return string|null
     */
    public function getStatusByAction(string $actionCode): ?string
    {
        $map = [
            (new Cancel())->getActionCode() => Task::STATUS_CANCELED,
            (new Complete())->getActionCode() => Task::STATUS_COMPLETED,
            (new Refusal())->getActionCode() => Task::STATUS_FAILED,
        ];

        return $map[$actionCode] ?? null;
    }

    /**
     * Метод возвращающий возможные действия к текущему статусу
     * @return array
     * @throws Exception
     */
    public function availableActions(): array
    {
        $actions = [
            Task::STATUS_NEW => [new Response(), new Cancel()],
            Task::STATUS_IN_WORK => [new Complete(), new Refusal()],
        ];

        return $actions[$this->task->status_id] ?? [];
    }

    /**
     * Метод возвращающий действие к текущему статусу
     *
     * @return AbstractSelectingAction[]
     * @throws Exception
     */
    public function getAvailableActions(): array
    {
        return array_filter(
            $this->availableActions(),
            function (AbstractSelectingAction $action) {
                return $action->checkingUserStatus($this->task, $this->user);
            }
        );
    }
}