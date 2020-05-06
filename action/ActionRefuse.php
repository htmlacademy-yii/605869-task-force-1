<?php


namespace TaskForceAction;


/**
 * Class ActionRefuse
 * @package TaskForceAction
 * Класс наследуется от абстрактного, возвращает действие "Отказаться"
 */
class ActionRefuse extends AbstractSelectingAction
{
    /**
     * @var AbstractSelectingAction $checkingStatus
     * право на совершение действия
     */
    public $checkingStatus;

    /**
     * @return string|null
     * метод по возврату названия действия при отказе
     */
    public function nameOfAction()
    {
        $checkingStatus = $this->checkingStatus;
        if ($this->$checkingStatus)
        {
            return 'Отказаться';
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
            return 'action_refuse';
        }
        return null;
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
        return ($this->checkingStatus = ($idPerformer == $idUser));
    }
}