<?php


namespace TaskForce;


/**
 * Класс наследуется от абстрактного, возвращает действие "Откликнуться"
 * Class ActionRespond
 * @package TaskForce
 */
class ActionRespond extends AbstractSelectingAction
{

    /**
     * @param $idPerformer
     * @param $idCustomer
     * @param $idUser
     * @return string|null
     * метод по возврату названия действия при отклике
     */
    public function nameOfAction($idPerformer, $idCustomer, $idUser)
    {
        if ($this->checkingUserStatus($idPerformer, $idCustomer, $idUser))
        {
            return 'Откликнуться';
        }
        return null;
    }

    /**
     * @param $idPerformer
     * @param $idCustomer
     * @param $idUser
     * @return string|null
     * метод по возврату внутреннего имени при отклике
     */
    public function internalNameOfAction($idPerformer, $idCustomer, $idUser)
    {
        if ($this->checkingUserStatus($idPerformer, $idCustomer, $idUser))
        {
            return 'action_respond';
        }
        return null;
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
        return ($idPerformer == $idUser);
    }
}