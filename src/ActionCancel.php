<?php


namespace TaskForce;


/**
 * Класс наследуется от абстрактного, возвращает действие "Отменить"
 * Class ActionCancel
 * @package TaskForce
 */
class ActionCancel extends AbstractSelectingAction
{

    /**
     * @param $idPerformer
     * @param $idCustomer
     * @param $idUser
     * @return string|null
     * метод по возврату названия действия при отмене
     */
    public function nameOfAction($idPerformer, $idCustomer, $idUser)
    {
        if ($this->checkingUserStatus($idPerformer, $idCustomer, $idUser))
        {
            return 'Отменить';
        }
        return null;
    }

    /**
     * @param $idPerformer
     * @param $idCustomer
     * @param $idUser
     * @return string|null
     * метод по возврату внутреннего имени при отмене
     */
    public function internalNameOfAction($idPerformer, $idCustomer, $idUser)
    {
        if ($this->checkingUserStatus($idPerformer, $idCustomer, $idUser))
        {
            return 'action_cancel';
        }
        return null;
    }

    /**
     * @param $idPerformer
     * @param $idCustomer
     * @param $idUser
     * @return bool
     * метод для проверки прав на совершение действия по отмене
     */
    public function checkingUserStatus($idPerformer, $idCustomer, $idUser)
    {
        return ($idCustomer == $idUser);
    }
}