<?php
require_once "vendor/autoload.php";
$idPerformer = 1;
$idCustomer = 2;
$strategy = new Task($idPerformer, $idCustomer);
assert($strategy->getNextStatus('action_cancel') == Task::STATUS_CANCEL, 'cancel action');
