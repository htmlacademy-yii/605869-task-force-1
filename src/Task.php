<?php
    namespace TaskForce\src;

    /**
     * класс для определения списков действий и статусов, и выполнения базовой работы с ними
     * Class Task
     * @package TaskForce\src
     */
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
         * @var int
         */
        private $idPerformer;
        /**
         * id заказчика
         * @var int
         */
        private $idCustomer;
        /**
         * статус
         * @var string
         */
        private $status;
        /**
         * действие с заданием
         * @var string
         */
        private $action; //действие
        /**
         * Task constructor.
         * конструктор для получения id исполнителя и id заказчика
         * @param $idPerformer int
         * @param $idCustomer int
         */
        public function __construct($idPerformer, $idCustomer)
        {
            $this->idPerformer = $idPerformer;
           $this->idCustomer = $idCustomer;
        }
        /**
         * метод принимающий действие и возвращающий статус в который перейдет задание
         * @param $action
         * @return string
         */
        public function getNextStatus ($action)
        {
            switch ($action) {
                case self::ACTION_RESPOND:
                    $status = self::STATUS_IN_WORK; // задание переходит в статус: в работе
                    break;
                case self::ACTION_CANCEL:
                    $status = self::STATUS_CANCEL; // задание переходит в статус: отменено
                    break;
                case 'action_refuse':
                    $status = self::STATUS_FAILED; // задание переходит в статус: провалено
                    break;
                case 'action_done':
                    $status = self::ACTION_RESPOND; // задание переходит в статус: выполнено
                    break;
                default:
                    $status = $this->status;
                    break;
            }
            return $status;
        }
        /**
         * метод возвращающий карту статусов
         * @return array
         */
        private function getStatusMap()
        {
            return [
                self::STATUS_NEW => 'Новый',
                self::STATUS_CANCEL => 'Отменен',
                self::STATUS_IN_WORK => 'В работе',
                self::STATUS_PERFORMED => 'Выполнено',
                self::STATUS_FAILED => 'Провалено'
            ];
        }
        /**
         * метод возвращающий карту действий
         * @return array
         */
        private function getActionMap()
        {
            return [
                self::ACTION_CANCEL => 'Отменить',
                self::ACTION_RESPOND => 'Откликнуться',
                self::ACTION_DONE => 'Ввыполнено',
                self::ACTION_REFUSE => 'Отказаться'
            ];
        }
        /**
         * метод возвращающий возможные действия к текущему статусу
         * @return array
         */
        private function getAvailableActions()
        {
            switch ($this->status) {
                case self::STATUS_NEW:
                    $action = [self::ACTION_RESPOND, self::ACTION_CANCEL];
                    break;
                case self::STATUS_IN_WORK:
                    $action = [self::ACTION_DONE, self::ACTION_REFUSE];
                    break;
                default:
                    $action = $this->action;
                    break;
            }
            return $action;
        }
    }