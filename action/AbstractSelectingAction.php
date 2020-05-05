<?php


namespace TaskForceAction;


/**
 * Class AbstractSelectingAction
 * @package TaskForceAction
 * абстрактный класс действий
 */

/**
 * Class AbstractSelectingAction
 * @package TaskForceAction
 */
abstract class AbstractSelectingAction
{
    abstract public function nameOfAction(); //метод для возврата названия
    abstract public function checkingUserStatus($idPerformer, $idCustomer, $idUser); //метод для проверки прав
}