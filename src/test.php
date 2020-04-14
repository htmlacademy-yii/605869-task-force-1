<?php
require_once "../vendor/autoload.php";

$idPerformer = 1;
$idCustomer = 2;
$strategy = new \TaskForce\Task($idPerformer, $idCustomer);
assert($strategy->getNextStatus('action_cancel') == \TaskForce\Task::STATUS_CANCEL, 'cancel action');