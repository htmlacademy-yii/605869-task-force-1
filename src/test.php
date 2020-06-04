<?php
require_once "../vendor/autoload.php";

$idPerformer = 1; // id исполнителя задания
$idCustomer = 2; // id заказчика задания
$idUser = 1; // id текущего пользователя

/**
 * Создаём объект для определения списков действий и статусов, и выполнения базовой работы с ними
 */
$strategy = new  \TaskForce\Task($idPerformer, $idCustomer, $idUser);
$strategy->status = \TaskForce\Task::STATUS_IN_WORK;

// проверка

var_dump($strategy->getAvailableAction()); //возвращающение статуса в который перейдет задание
//print_r(assert($strategy->getAvailableAction() == 'action_refuse'));