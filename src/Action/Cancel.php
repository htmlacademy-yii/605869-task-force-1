<?php


namespace TaskForce\Action;


/**
 * Class Cancel
 * @package TaskForceAction
 * Класс наследуется от абстрактного, возвращает действие "Отменить"
 */
class Cancel extends AbstractSelectingAction
{
    /**
     * id исполнителя
     * @var int
     */
    protected $idPerformer;

    /**
     * id заказчика
     * @var int
     */
    protected $idCustomer;

    /**
     * id текущего пользователя
     * @var int
     */
    protected $idUser;

    /**
     * @return string|null
     * метод по возврату названия действия при отмене
     */
    public function nameOfAction()
    {
        $idPerformer = $this->idPerformer;
        $idCustomer = $this->idCustomer;
        $idUser = $this->idUser;
        $checkingStatus = $this->checkingUserStatus($idPerformer, $idCustomer, $idUser);
        if ($this->$checkingStatus)
        {
            return 'Отменить';
        }
        return null;
    }

    /**
     * @return string|null
     * метод возвращающий внутреннее имя действия
     */
    public function internalNameOfAction()
    {
        $idPerformer = $this->idPerformer;
        $idCustomer = $this->idCustomer;
        $idUser = $this->idUser;
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
        return $idCustomer == $idUser;
    }
}