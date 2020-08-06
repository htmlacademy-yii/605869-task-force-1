<?php
declare(strict_types=1);
ini_set('display_errors', 'On');
error_reporting(E_ALL);
use TaskForce\Task;
use TaskForce\Exception\StatusExistsException;

require_once "../vendor/autoload.php";

$idPerformer = 1; // id исполнителя задания
$idCustomer = 2; // id заказчика задания
$idUser = 1; // id текущего пользователя
$status = 'in_work';

try {
    /**
     * Создаём объект для определения списков действий и статусов, и выполнения базовой работы с ними
     */
    $strategy = new  \TaskForce\Task($idPerformer, $idCustomer, $idUser, $status);
    $strategy->availableAction();
}
catch (\TaskForce\Exception\StatusExistsException $e) {
    error_log("Ошибка статуса: " . $e->getMessage());
}

// проверка
assert($strategy->getAvailableAction() == 'action_refuse');