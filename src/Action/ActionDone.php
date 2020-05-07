<?php


namespace TaskForce\Action;


/**
 * Class ActionDone
 * @package TaskForceAction
 * Класс наследуется от абстрактного, возвращает действие "Завершить"
 */
class ActionDone extends AbstractSelectingAction
{
    /**
     * @var AbstractSelectingAction $checkingStatus
     * право на совершение действия
     */
    public $checkingStatus;
    /**
     * @return string|null
     * метод по возврату названия действия при завершении
     */
    public function nameOfAction()
    {
        $checkingStatus = $this->checkingStatus;
        if ($this->$checkingStatus)
        {
            return 'Завершить';
        }
        return null;
    }
    /**
     * @return string|null
     */
    public function internalNameOfAction()
    {
        $checkingStatus = $this->checkingStatus;
        if ($this->$checkingStatus)
        {
            return 'action_done';
        }
        return null;
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
        return ($this->checkingStatus = ($idCustomer == $idUser));
    }
}