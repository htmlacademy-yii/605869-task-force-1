<?php
    namespace TaskForce;

    use TaskForceAction\AbstractSelectingAction;
    use TaskForceAction\ActionCancel;
    use TaskForceAction\ActionDone;
    use TaskForceAction\ActionRefuse;
    use TaskForceAction\ActionRespond;

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
        private $idPerformer;

        /**
         * id заказчика
         * @var int
         */
        private $idCustomer;

        /**
         * id текущего пользователя
         * @var int
         */
        private $idUser;

        /**
         * статус
         * @var string
         */
        private $status;

        /**
         * @var AbstractSelectingAction $checkingStatus
         */
        protected $checkingStatus; //право на совершение действия

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
         * @return string|null
         * метод возвращающий статус в который перейдет задание
         */
        public function getNextStatus ()
        {
            foreach ($this->AvailableActions() as $availableAction)
            {
                if ($availableAction->internalNameOfAction() == 'action_respond')
                {
                    return ($status = self::STATUS_IN_WORK); // задание переходит в статус: в работе
                }
                elseif ($availableAction->internalNameOfAction() == 'action_cancel')
                {
                    return ($status = self::STATUS_CANCEL); // задание переходит в статус: отменено
                }
                elseif ($availableAction->internalNameOfAction() == 'action_refuse')
                {
                    return ($status = self::STATUS_FAILED); // задание переходит в статус: провалено
                }
                elseif ($availableAction->internalNameOfAction() == 'action_done')
                {
                    return ($status = self::STATUS_PERFORMED); // задание переходит в статус: выполнено
                }
            }
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
                (new ActionCancel()),
                (new ActionRespond()),
                (new ActionDone()),
                (new ActionRefuse())
            ];
        }

        /**
         * @return array|null AbstractSelectingAction
         * метод возвращающий возможные действия к текущему статусу
         */
        protected function AvailableActions()
        {
            if ($this->status == self::STATUS_NEW)
            {
                return ([new ActionRespond(), new ActionCancel()]);
            }
            elseif ($this->status == self::STATUS_IN_WORK)
            {
                return ([new ActionDone(),new ActionRefuse()]);
            }
        }
    }