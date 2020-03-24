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

        public $formData = [];
        public $requiredFields = [];
        public $map = [];

        public function __construct($formData, $requiredFields)
        {
            $this->formData = $formData;
            $this->requiredFields = $requiredFields;
        }


    }