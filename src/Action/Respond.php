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
     * метод возвращающий внутреннее имя действия
     */
    public function nameOfAction()
    {
        $idPerformer = $this->idPerformer;
        $idCustomer = $this->idCustomer;
        $idUser = $this->idUser;
        $checkingStatus = $this->checkingUserStatus($idPerformer, $idCustomer, $idUser);
        if ($this->$checkingStatus)
        {
            return 'Откликнуться';
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
        return $idPerformer == $idUser;
    }
}