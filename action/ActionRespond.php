<?php


namespace TaskForceAction;


/**
 * Class ActionRespond
 * @package TaskForceAction
 * Класс наследуется от абстрактного, возвращает действие "Откликнуться"
 */
class ActionRespond extends AbstractSelectingAction
{
    /**
     * @var AbstractSelectingAction $checkingStatus
     * право на совершение действия
     */
    public $checkingStatus;

    /**
     * @param $idPerformer
     * @param $idCustomer
     * @param $idUser
     * @return string|null
     * метод по возврату названия действия при отклике
     */
    public function nameOfAction()
    {
        $checkingStatus = $this->checkingStatus;
        if ($this->$checkingStatus)
        {
            return 'Откликнуться';
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