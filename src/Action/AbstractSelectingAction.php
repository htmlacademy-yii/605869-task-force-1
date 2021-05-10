<?php

namespace TaskForce\Action;

/**
 * Class AbstractSelectingAction
 * @package TaskForceAction
 * Действие, которое теоретически может совершить пользователь:
 * например, взять задачу, закрыть задачу и так далее
 */
abstract class AbstractSelectingAction
{
    abstract public function getActionTitle($task_id);
    //метод - для человекопонятного названия действия ("Отменить", "Откликнуться")

    abstract public function getActionCode();
    // метод - для машинного названия действия ("action_cancel", "action_respond")

    abstract public function checkingUserStatus($idPerformer, $idCustomer, $idUser);
    //метод для проверки прав
}