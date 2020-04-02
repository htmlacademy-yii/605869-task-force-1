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
        private int $idPerformer;
        /**
         * id заказчика
         * @var int
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
                case 'ACTION_RESPOND':
                    $status = 'STATUS_IN_WORK'; // задание переходит в статус: в работе
                    break;
                case 'ACTION_CANCEL':
                    $status = 'STATUS_CANCEL'; // задание переходит в статус: отменено
                    break;
                case 'ACTION_REFUSE':
                    $status = 'STATUS_FAILED'; // задание переходит в статус: провалено
                    break;
                case 'ACTION_DONE':
                    $status = 'STATUS_PERFORMED'; // задание переходит в статус: выполнено
                    break;
                default:
                    $status = '';
                    break;
            }
/*            $status = $this->status;
            if ($status == 'STATUS_NEW') // если у задания статус: новое задание
            {
                if ($action == 'ACTION_RESPOND')  // если исполнитель откликается на задание
                {
                    $status = 'STATUS_IN_WORK'; // задание переходит в статус: в работе
                }
                elseif ($action == 'ACTION_CANCEL') //если заказчик отменяет  задание
                {
                    $status = 'STATUS_CANCEL'; // задание переходит в статус: отменено
                }
            }
            elseif ($status == 'STATUS_IN_WORK') // если задание находится в статусе: в работе
            {
                if ($action == 'ACTION_REFUSE') // если исполнитель отказывается от задания
                {
                    $status = 'STATUS_FAILED'; // задание переходит в статус: провалено
                }
                elseif ($action == 'ACTION_DONE') // если заказчик переводит задание в статус: выполнено
                {
                    $status = 'STATUS_PERFORMED'; // задание переходит в статус: выполнено
                }
            }*/
            return $status;
        }
        /**
         * метод возвращающий карту статусов
         * @return array
         */
        private function getStatusMap()
        {
            return ['new' => 'Новый',
                'cancel' => 'Отменен',
                'in_work' => 'В работе',
                'performed' => 'Выполнено',
                'failed' => 'Провалено'];
        }
        /**
         * метод возвращающий карту действий
         * @return array
         */
        private function getActionMap()
        {
            return ['action_cancel' => 'Отменить',
                'action_respond' => 'Откликнуться',
                'action_done' => 'Ввыполнено',
                'action_refuse' => 'Отказаться'];
        }

        /**
         * метод возвращающий возможные действия к текущему статусу
         * @return array
         */
        private function getAvailableActions()
        {
            $status = $this->status;
            switch ($status) {
                case 'STATUS_NEW':
                    $action = ['ACTION_RESPOND', 'ACTION_CANCEL'];
                    break;
                case 'STATUS_IN_WORK':
                    $action = ['ACTION_DONE', 'ACTION_REFUSE'];
                    break;
                default:
                    $action = [];
                    break;
            }
            return $action;
        }
    }