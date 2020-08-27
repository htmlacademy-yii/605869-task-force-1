<?php


namespace TaskForce\Action;


/**
 * Class Respond
 * @package TaskForceAction
 * Класс наследуется от абстрактного, возвращает действие "Откликнуться"
 */
class Respond extends AbstractSelectingAction
{
    /**
     * @return string|null
     * метод - для человекопонятного названия действия
     */
    public function getActionTitle()
    {
        return 'Откликнуться';
    }

    /**
     * @return string|null
     * метод - для машинного названия действия
     */
    public function getActionCode()
    {
        return 'action_respond';
    }

    /**
     * @param $idPerformer
     * @param $idCustomer
     * @param $idUser
     * @return bool
     * метод для проверки прав на совершение действия по отклику
     */
    public function checkingUserStatus($idPerformer, $idCustomer, $idUser)
    {
        return ($idUser !== $idCustomer);
    }
}