<?php
require_once "../vendor/autoload.php";

$idPerformer = 1; // id исполнителя задания
$idCustomer = 2; // id заказчика задания
$idUser = 2; // id текущего пользователя
$status = 'new';

/**
 * Создаём объект для определения списков действий и статусов, и выполнения базовой работы с ними
 */
$strategy = new \TaskForce\Task($idPerformer, $idCustomer, $idUser);

// проверка
var_dump($strategy->getNextStatus()); //возвращающение статуса в который перейдет задание