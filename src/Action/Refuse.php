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
     * @param $idPerformer
     * @param $idCustomer
     * @param $idUser
     * @return string|null
     * метод возвращающий внутреннее имя действия
     */
    public function nameOfAction($idPerformer, $idCustomer, $idUser)
    {
        $checkingStatus = $this->checkingUserStatus($idPerformer, $idCustomer, $idUser);
        if ($this->$checkingStatus)
        {
            return 'Отказаться';
        }
        return null;
    }

    /**
     * @param $idPerformer
     * @param $idCustomer
     * @param $idUser
     * @return string|null
     * метод возвращающий внутреннее имя действия
     */
    public function internalNameOfAction($idPerformer, $idCustomer, $idUser)
    {
        if ($this->checkingUserStatus($idPerformer, $idCustomer, $idUser))
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
        return $idPerformer == $idUser;
    }
}