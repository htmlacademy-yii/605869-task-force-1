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
        return 'Откликнуться';
    }

    /**
     * @return string|null
     * метод возвращающий внутреннее имя действия
     */
    public function internalNameOfAction()
    {
        return 'action_respond';
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
        if ($idUser !== $idCustomer) return true;
        else return false;
    }
}