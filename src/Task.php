<?php
    namespace TaskForce\src;

    class Task
    {
        const STATUS_NEW = 'new';
        const STATUS_CANCEL = 'status_cancel';
        const STATUS_IN_WORK = 'status_in_work';
        const STATUS_PERFORMED = 'status_performed';
        const STATUS_FAILED = 'status_failed';
        const ACTION_CANCEL = 'action_cancel';
        const ACTION_RESPOND = 'action_respond';
        const ACTION_DONE = 'action_done';
        const ACTION_REFUSE = 'action_refuse';

        private $idPerformer = [];
        private $idCustomer = [];
        private $status = [];
        private $statuses = [];
        private $action = [];

        public function __construct($idPerformer, $idCustomer)
        {
            $this->idPerformer = $idPerformer;
            $this->idCustomer = $idCustomer;
        }

        public function getNextStatus ($status)
        {
            switch ($status) {
                case 'new':
                    $statuses [action_respond] = 'status_in_work';
                    $statuses[action_cancel] = 'status_cancel';
                    break;
                case 'status_in_work':
                    $statuses [action_refuse] = 'status_failed';
                    $statuses[action_done] = 'status_performed';
                    break;
                    
                default:
                    $statuses = [] ;
                    break;
            }
            return $statuses;
        }

        public function changeStatus ()
        {
            $status = $Task -> status;
            $action = $Task -> action;
            $statuses = $this -> getNextStatus($status);
            (isset($statuses)) ? $status = $statuses[$action] : $status;
            return $status;
        }


    }