<?php
use TaskForce\src\Task;
require_once 'Task.php';
$idPerformer = '1';
$idCustomer = '2';
$strategy = new \TaskForce\src\Task($idPerformer, $idCustomer);
assert($strategy->getNextStatus('action_cancel') == Task::STATUS_CANCEL, 'cancel action');
