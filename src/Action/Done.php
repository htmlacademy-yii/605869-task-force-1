<?php


namespace TaskForce\Action;


/**
 * Class Done
 * @package TaskForceAction
 * Класс наследуется от абстрактного, возвращает действие "Завершить"
 */
class Done extends AbstractSelectingAction
{
    /**
     * @return string|null
     * метод - для человекопонятного названия действия
     */
    public function nameOfAction()
    {
        return 'Завершить';
    }

    /**
     * @return string|null
     * метод - для машинного названия действия
     */
    public function internalNameOfAction()
    {
        return 'action_done';
    }

    /**
     * @param $idPerformer
     * @param $idCustomer
     * @param $idUser
     * @return bool
     * метод для проверки прав на совершение действия по завершению
     */
    public function checkingUserStatus($idPerformer, $idCustomer, $idUser)
    {
        return ($idUser !== $idPerformer);
    }
}