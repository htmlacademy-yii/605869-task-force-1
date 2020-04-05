<?php
require_once 'Task.php';
use TaskForce\src\Task;
$idPerformer = 1;
$idCustomer = 2;
$strategy = new Task($idPerformer, $idCustomer);
assert($strategy->getNextStatus('action_cancel') == Task::STATUS_CANCEL, 'cancel action');
