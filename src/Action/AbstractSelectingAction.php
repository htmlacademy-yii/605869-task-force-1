<?php


namespace TaskForce\Action;


/**
 * Class AbstractSelectingAction
 * @package TaskForceAction
 * абстрактный класс действий
 */
abstract class AbstractSelectingAction
{
    abstract public function nameOfAction($idPerformer, $idCustomer, $idUser); //метод для возврата названия
    abstract public function internalNameOfAction($idPerformer, $idCustomer, $idUser); // метод для возврата нутреннего имени
    abstract public function checkingUserStatus($idPerformer, $idCustomer, $idUser); //метод для проверки прав
}