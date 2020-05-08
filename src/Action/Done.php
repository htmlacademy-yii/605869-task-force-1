<?php


namespace TaskForce\Action;


/**
 * Class Done
 * @package TaskForceAction
 * Класс наследуется от абстрактного, возвращает действие "Завершить"
 */
class Done extends AbstractSelectingAction
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
            return 'Завершить';
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
        return $idCustomer == $idUser;
    }
}