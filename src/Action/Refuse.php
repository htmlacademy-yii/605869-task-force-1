<?php


namespace TaskForce\Action;


/**
 * Class Refuse
 * @package TaskForceAction
 * Класс наследуется от абстрактного, возвращает действие "Отказаться"
 */
class Refuse extends AbstractSelectingAction
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
        return 'Отказаться';
    }

    /**
     * @return string|null
     * метод - для машинного названия действия
     */
    public function internalNameOfAction()
    {
        return 'action_refuse';
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
        return ($idUser !== $idCustomer);
    }
}