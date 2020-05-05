<?php


namespace TaskForceAction;



/**
 * Class ActionCancel
 * @package TaskForceAction
 * Класс наследуется от абстрактного, возвращает действие "Отменить"
 */
class ActionCancel extends AbstractSelectingAction
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
     * метод по возврату названия действия при отмене
     */
    public function nameOfAction()
    {
        $checkingStatus = $this->checkingStatus;
        if ($this->$checkingStatus)
        {
            return 'Отменить';
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