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

/**
 * Передаём объект для возврата названия, внутреннего имени и для проверки прав при отмене
 */
$strategy->actionCancel(new \TaskForce\ActionCancel());

/**
 * Передаём объект для возврата названия, внутреннего имени и для проверки прав при отклике
 */
$strategy->actionRespond(new \TaskForce\ActionRespond());

/**
 * Передаём объект для возврата названия, внутреннего имени и для проверки прав при завершении
 */
$strategy->actionDone(new \TaskForce\ActionDone());

/**
 * Передаём объект для возврата названия, внутреннего имени и для проверки прав при отказе
 */
$strategy->actionRefuse(new \TaskForce\ActionRefuse());

// проверка

$action = $strategy->getAvailableActions($status); // возвращение возможного действия к текущему статусу


var_dump($strategy->getNextStatus($action)); //возвращающение статуса в который перейдет задание