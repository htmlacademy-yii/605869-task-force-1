<?php


namespace TaskForce;


/**
 * абстрактный класс задаёт три абстрактных метода: для возврата названия, внутреннего имени и для проверки прав
 * Class AbstractSelectingAction
 * @package TaskForce
 */
abstract class AbstractSelectingAction
{
    abstract public function nameOfAction($idPerformer, $idCustomer, $idUser); //метод для возврата названия
    abstract public function internalNameOfAction($idPerformer, $idCustomer, $idUser); //метод для возврата внутреннего имени
    abstract public function checkingUserStatus($idPerformer, $idCustomer, $idUser); //метод для проверки прав
}