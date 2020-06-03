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
    public $idPerformer;

    /**
     * id заказчика
     * @var int
     */
    public $idCustomer;

    /**
     * id текущего пользователя
     * @var int
     */
    public $idUser;

    /**
     * @return string|null
     * метод - для человекопонятного названия действия
     */
    public function nameOfAction()
    {
        return 'Отменить';
    }

    /**
     * @return string|null
     * метод - для машинного названия действия
     */
    public function internalNameOfAction()
    {
        return 'action_cancel';
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
        return ($idUser !== $idPerformer);
    }
}