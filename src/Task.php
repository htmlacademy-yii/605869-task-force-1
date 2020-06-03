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
        public $idPerformer;

        /**
         * id заказчика
         * @var int
         */
        public $idCustomer;

        /**
         * id текущего пользователя
         * @var int
         */
        public $idUser;

        /**
         * статус
         * @var string
         */
        public $status;

        /**
         * @var string AbstractSelectingAction
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
         * @return string
         * метод возвращающий статус в который перейдет задание
         */
        public function getNextStatus ()
        {
            $action = $this->action;
                if (((new Respond())->internalNameOfAction()) == $action)
                {
                    return $status = self::STATUS_IN_WORK; // задание переходит в статус: в работе
                }
                elseif (((new Cancel())->internalNameOfAction()) == $action)
                {
                    return $status = self::STATUS_CANCEL; // задание переходит в статус: отменено
                }
                elseif (((new Refuse())->internalNameOfAction()) == $action)
                {
                    return $status = self::STATUS_FAILED; // задание переходит в статус: провалено
                }
                elseif (((new Done())->internalNameOfAction()) == $action)
                {
                    return $status = self::STATUS_PERFORMED; // задание переходит в статус: выполнено
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
                (new Cancel()),
                (new Respond()),
                (new Done()),
                (new Refuse())
            ];
        }

        /**
         * Метод возвращающий возможное действия к текущему статусу
         * @return string
         * @throws \Exception
         */
        public function getAvailableAction()
        {
            $idPerformer = $this->idPerformer;
            $idCustomer = $this->idCustomer;
            $idUser = $this->idUser;
            if ($this->status == self::STATUS_NEW)
            {
                if ((new Respond())->checkingUserStatus($idPerformer, $idCustomer, $idUser))
                {
                    return $action = 'action_respond';
                }
                elseif ((new Cancel())->checkingUserStatus($idPerformer, $idCustomer, $idUser))
                {
                    return $action = 'action_cancel';
                }
            }
            elseif ($this->status == self::STATUS_IN_WORK)
            {
                if ((new Done())->checkingUserStatus($idPerformer, $idCustomer, $idUser))
                {
                    return $action = 'action_done';
                }
                elseif ((new Refuse())->checkingUserStatus($idPerformer, $idCustomer, $idUser))
                {
                    return $action = 'action_refuse';
                }
            } else {
                throw new \Exception("Неожиданный татус задачи ".$this->status);
            }
        }
    }