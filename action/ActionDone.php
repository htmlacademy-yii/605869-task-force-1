<?php


namespace TaskForceAction;


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
     * @param $idPerformer
     * @param $idCustomer
     * @param $idUser
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
     * @param $idPerformer
     * @param $idCustomer
     * @param $idUser
     * @return bool
     * метод для проверки прав на совершение действия по завершению
     */
    public function checkingUserStatus($idPerformer, $idCustomer, $idUser)
    {
        return ($idCustomer == $idUser);
    }
}