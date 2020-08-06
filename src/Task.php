<?php
    declare(strict_types=1);

    namespace TaskForce;

    use TaskForce\Action\AbstractSelectingAction;
    use TaskForce\Action\Cancel;
    use TaskForce\Action\Done;
    use TaskForce\Action\Refuse;
    use TaskForce\Action\Respond;
    use TaskForce\Exception\StatusExistsException;

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
         * @var array AbstractSelectingAction
         */
        public $actions = []; //действия которое можно выполнить из текущего статуса

        /**
         * @var string
         */
        public $availableAction; // действие которое выполняется из текущего статуса

        /**
         * Task constructor.
         * конструктор для получения id исполнителя и id заказчика
         * @param $idPerformer int
         * @param $idCustomer int
         * @param $idUser int
         * @param $status string
         * @throws StatusExistsException
         */
        public function __construct(int $idPerformer, int $idCustomer, int $idUser, string $status)
        {
            $this->idPerformer = $idPerformer;
            $this->idCustomer = $idCustomer;
            $this->idUser = $idUser;
//            проверка статуса передаваемого в конструктор, на существование
//            если передаваемый статус не существует, то выбрасывается исключение
                if (in_array($status,
                    [self::STATUS_CANCEL,
                    self::STATUS_FAILED,
                    self::STATUS_IN_WORK,
                    self::STATUS_NEW,
                    self::STATUS_PERFORMED]))
                {
                    $this->status = $status;
                }
                else throw new StatusExistsException("Неожиданный cтатус задачи ". $status);
        }

        /**
         * @return string
         * метод возвращающий статус в который перейдет задание
         */
        public function getNextStatus (): string
        {
            $availableAction = $this->getAvailableAction();
            if ($availableAction == 'action_respond')
            {
                $status = self::STATUS_IN_WORK; // задание переходит в статус: в работе
            }
            elseif ($availableAction == 'action_cancel')
            {
                $status = self::STATUS_CANCEL; // задание переходит в статус: отменено
            }
            elseif ($availableAction == 'action_refuse')
            {
                $status = self::STATUS_FAILED; // задание переходит в статус: провалено
            }
            elseif ($availableAction == 'action_done')
            {
                $status = self::STATUS_PERFORMED; // задание переходит в статус: выполнено
            }
            return $status;
        }

        /**
         * метод возвращающий карту статусов
         * @return array
         */
        private function getStatusMap(): array
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
        private function getActionMap(): array
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
         * @return array
         * @throws \Exception
         */
        public function availableAction():?array
        {
            if ($this->status == self::STATUS_NEW)
            {
                return $actions = [new Respond(), new Cancel()];
            }
            elseif ($this->status == self::STATUS_IN_WORK)
            {
                return $actions = [new Done(),new Refuse()];
            } else {
                throw new StatusExistsException("Неожиданный cтатус задачи ".$this->status);
            }
        }

        /**
         * Метод возвращающий действие к текущему статусу
         * @return string
         */
        public function getAvailableAction():string
        {
            $idPerformer = $this->idPerformer;
            $idCustomer = $this->idCustomer;
            $idUser = $this->idUser;
            $actions = $this->availableAction();
            foreach ($actions as $action)
            {
                if ($action->checkingUserStatus($idPerformer, $idCustomer, $idUser))
                {
                    return $action->internalNameOfAction();
                }
            }
        }
    }