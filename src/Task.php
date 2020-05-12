<?php
    namespace TaskForce;


    use TaskForce\Action\AbstractSelectingAction;
    use TaskForce\Action\Cancel;
    use TaskForce\Action\Done;
    use TaskForce\Action\Refuse;
    use TaskForce\Action\Respond;

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
         * id исполнителя
         * @var int
         */
        protected $idPerformer;

        /**
         * id заказчика
         * @var int
         */
        protected $idCustomer;

        /**
         * id текущего пользователя
         * @var int
         */
        protected $idUser;

        /**
         * статус
         * @var string
         */
        public $status;

        /**
         * @var AbstractSelectingAction[]
         */
        public $action; //действие

        /**
         * Task constructor.
         * конструктор для получения id исполнителя и id заказчика
         * @param $idPerformer int
         * @param $idCustomer int
         * @param $idUser int
         */
        public function __construct($idPerformer, $idCustomer, $idUser)
        {
            $this->idPerformer = $idPerformer;
            $this->idCustomer = $idCustomer;
            $this->idUser = $idUser;
        }

        /**
         * @param $action
         * @return string
         * метод возвращающий статус в который перейдет задание
         */
        public function getNextStatus ($action)
        {
            $AvailableActions = $this->action;
            foreach ($this->$AvailableActions as $availableAction)
            {
                $idPerformer = $this->idPerformer;
                $idCustomer = $this->idCustomer;
                $idUser = $this->idUser;
                if ($availableAction->internalNameOfAction() == 'action_respond')
                {
                    $status = self::STATUS_IN_WORK; // задание переходит в статус: в работе
                }
                elseif ($availableAction->internalNameOfAction() == 'action_cancel')
                {
                    $status = self::STATUS_CANCEL; // задание переходит в статус: отменено
                }
                elseif ($availableAction->internalNameOfAction() == 'action_refuse')
                {
                    $status = self::STATUS_FAILED; // задание переходит в статус: провалено
                }
                elseif ($availableAction->internalNameOfAction() == 'action_done')
                {
                    $status = self::STATUS_PERFORMED; // задание переходит в статус: выполнено
                }
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
         * @return array AbstractSelectingAction
         * метод возвращающий карту действий
         */
        private function getActionMap()
        {
            return [
                (new Cancel()),
                (new Respond()),
                (new Done()),
                (new Refuse())
            ];
        }

        /**
         * Метод возвращающий возможные действия к текущему статусу
         * @return AbstractSelectingAction[]
         * @throws \Exception
         */
        protected function AvailableActions()
        {
            if ($this->status == self::STATUS_NEW)
            {
                return $action = [new Respond(), new Cancel()];
            }
            elseif ($this->status == self::STATUS_IN_WORK)
            {
                return $action = [new Done(),new Refuse()];
            } else {
                throw new \Exception("Неожиданный татус задачи ".$this->status);
            }
        }
    }