<?php


namespace TaskForce\Action;


/**
 * Class Refuse
 * @package TaskForceAction
 * Класс наследуется от абстрактного, возвращает действие "Отказаться"
 */
class Refusal extends AbstractSelectingAction
{
    /**
     * @return string|null
     * метод - для человекопонятного названия действия
     */
    public function getActionTitle($taskId)
    {
        return 'Отказаться';
    }

    /**
     * @return string|null
     * метод - для машинного названия действия
     */
    public function getActionCode()
    {
        return 'refusal';
    }

    /**
     * @param $idPerformer
     * @param $idCustomer
     * @param $idUser
     * @return bool
     * метод для проверки прав на совершение действия по отказу
     */
    public function checkingUserStatus($idPerformer, $idCustomer, $idUser)
    {
        return ($idUser !== $idCustomer);
    }
}