<?php
    namespace TaskForce\src;

    class Task
    {
        /**
         * константы статусов заданий
         */
        const STATUS_NEW = 'new'; //статус нового задания
        const STATUS_CANCEL = 'cancel'; //статус отмененного задания
        const STATUS_IN_WORK = 'in_work'; //статус задания находящегося в работе
        const STATUS_PERFORMED = 'performed'; //статус выполненного задания
        const STATUS_FAILED = 'failed'; //статус проваленного задания
        /**
         * константы действий к заданиям
         */
        const ACTION_CANCEL = 'action_cancel'; //действие по отмене задания (выполняет заказчик)
        const ACTION_RESPOND = 'action_respond'; //действие по отклику на задание (выполняет исполнитель)
        const ACTION_DONE = 'action_done'; //действие по переводу задания в статус "Выполнено" (выполняет заказчик)
        const ACTION_REFUSE = 'action_refuse'; //действие по отказу от задания (выполняет исполнитель), переводит задание в статус "Провалено"
        /**
         * id исполнителя
         * @var integer
         */
        private int $idPerformer;
        /**
         * id заказчика
         * @var integer
         */
        private int $idCustomer;
        /**
         * статус
         * @var string
         */
        private string $status;
        /**
         * действие с заданием
         * @var string
         */
        private string $action; //действие
        /**
         * Task constructor.
         * конструктор для получения id исполнителя и id заказчика
         * @param $idPerformer integer
         * @param $idCustomer integer
         */
        public function __construct($idPerformer, $idCustomer)
        {
            $idPerformer = $this->idPerformer;
            $idCustomer = $this->idCustomer;
        }

        /**
         * метод принимающий статус и возвращающий массив статусов в которые можно перейти из этого статуса
         * @param $status
         * @param $action
         * @return array
         */
        public function getNextStatus ($status, $action)
        {
            if ($status == 'new') // если узадания статус: новое задание
            {
                if ($action == 'action_respond')  // если исполнитель откликается на задание
                {
                    return ['action' => 'откликнуться на задание',
                        'status' => 'статус задания: в работе']; // задание переходит в статус: в работе
                }
                elseif ($action == 'action_cancel') //если заказчик отменяет  задание
                {
                    return ['action' => 'отменить задание',
                        'status' => 'статус задания: отменено']; // задание переходит в статус: отменено
                }
            }
            elseif ($status == 'in_work') // если задание находится в статусе: в работе
            {
                if ($action == 'action_refuse') // если исполнитель отказывается от задания
                {
                    return ['action' => 'отказаться от задания',
                        'status' => 'статус задания: провалено']; // задание переходит в статус: провалено
                }
                elseif ($action == 'action_done') // если заказчик переводит задание в статус: выполнено
                {
                    return ['action' => 'задание выполнено',
                        'status' => 'статус задания: выполнено']; // задание переходит в статус: выполнено
                }
            }
        }
        /**
         * метод для перевода задания из одного статуса в другой
         * @return array|string
         */
        public function changeStatus ()
        {
            $status = $this->status;
            $action = $this->action;
            $status = $this -> getNextStatus($status, $action);
            return $status;
        }
    }
