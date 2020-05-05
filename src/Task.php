<?php
    namespace TaskForce;

    use TaskForceAction\AbstractSelectingAction;

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
         * переменные действий к заданиям
         */

        /**
         * @var AbstractSelectingAction $nameOfAction_cancel
         */
        private $nameOfAction_cancel; // имя 'Отменить'

        /**
         * @var abstractSelectingAction $nameOfAction_respond
         */
        private $nameOfAction_respond; // имя 'Откликнуться'

        /**
         * @var AbstractSelectingAction $nameOfAction_done
         */
        private $nameOfAction_done; // имя 'Завершить'

        /**
         * @var AbstractSelectingAction $nameOfAction_refuse
         */
        private $nameOfAction_refuse; // имя 'Отказаться'

        /**
         * @var AbstractSelectingAction $internalNameOfAction_cancel
         */
        private $internalNameOfAction_cancel; // внутреннее имя 'action_cancel'

        /**
         * @var AbstractSelectingAction $internalNameOfAction_respond
         */
        private $internalNameOfAction_respond; // внутреннее имя 'action_respond'

        /**
         * @var AbstractSelectingAction $internalNameOfAction_done
         */
        private $internalNameOfAction_done; // внутреннее имя 'action_done'

        /**
         * @var AbstractSelectingAction $internalNameOfAction_refuse
         */
        private $internalNameOfAction_refuse; // внутреннее имя 'action_refuse'

        /**
         * @var AbstractSelectingAction $checkingUserOfAction_cancel
         */
        private $checkingUserOfAction_cancel; // проверка прав к действию по отмене

        /**
         * @var AbstractSelectingAction $checkingUserOfAction_respond
         */
        private $checkingUserOfAction_respond; // проверка прав к действию по отклику

        /**
         * @var AbstractSelectingAction $checkingUserOfAction_done
         */
        private $checkingUserOfAction_done; // проверка прав к действию по завершению

        /**
         * @var AbstractSelectingAction $checkingUserOfAction_refuse
         */
        private $checkingUserOfAction_refuse; // проверка прав к действию по отказу

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
         * @var AbstractSelectingAction $action
         */
        private $action; //действие
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
         * @param AbstractSelectingAction $actionCancel
         * метод для возврата названия, внутреннего имени и для проверки прав к действию по отмене
         */
        public function actionCancel(AbstractSelectingAction $actionCancel)
        {
            $idPerformer = $this->idPerformer;
            $idCustomer = $this->idCustomer;
            $idUser = $this->idUser;
            $this->nameOfAction_cancel = $actionCancel->nameOfAction();
            $this->internalNameOfAction_cancel = 'actionCancel';
            $this->checkingStatus = $actionCancel->checkingUserStatus($idPerformer, $idCustomer, $idUser);
        }

        /**
         * @param AbstractSelectingAction $actionRespond
         * метод для возврата названия, внутреннего имени и для проверки прав к действию по отклику
         */
        public function actionRespond(AbstractSelectingAction $actionRespond)
        {
            $idPerformer = $this->idPerformer;
            $idCustomer = $this->idCustomer;
            $idUser = $this->idUser;
            $this->nameOfAction_respond = $actionRespond->nameOfAction();
            $this->internalNameOfAction_respond = 'actionRespond';
            $this->checkingStatus = $actionRespond->checkingUserStatus($idPerformer, $idCustomer, $idUser);
        }

        /**
         * @param AbstractSelectingAction $actionDone
         * метод для возврата названия, внутреннего имени и для проверки прав к действию по завершению
         */
        public function actionDone(AbstractSelectingAction $actionDone)
        {
            $idPerformer = $this->idPerformer;
            $idCustomer = $this->idCustomer;
            $idUser = $this->idUser;
            $this->nameOfAction_done = $actionDone->nameOfAction();
            $this->internalNameOfAction_done = 'actionDone';
            $this->checkingStatus = $actionDone->checkingUserStatus($idPerformer, $idCustomer, $idUser);
        }

        /**
         * @param AbstractSelectingAction $actionRefuse
         * метод для возврата названия, внутреннего имени и для проверки прав к действию по отказу
         */
        public function actionRefuse(AbstractSelectingAction $actionRefuse)
        {
            $idPerformer = $this->idPerformer;
            $idCustomer = $this->idCustomer;
            $idUser = $this->idUser;
            $this->nameOfAction_refuse = $actionRefuse->nameOfAction();
            $this->internalNameOfAction_refuse = 'actionRefuse';
            $this->checkingStatus = $actionRefuse->checkingUserStatus($idPerformer, $idCustomer, $idUser);
        }

        /**
         * @param $action
         * @return string
         * метод принимающий действие и возвращающий статус в который перейдет задание
         */
        public function getNextStatus ($action)
        {
            switch ($action) {
                case $this->internalNameOfAction_respond:
                    $status = self::STATUS_IN_WORK; // задание переходит в статус: в работе
                    break;
                case $this->internalNameOfAction_cancel:
                    $status = self::STATUS_CANCEL; // задание переходит в статус: отменено
                    break;
                case $this->internalNameOfAction_refuse:
                    $status = self::STATUS_FAILED; // задание переходит в статус: провалено
                    break;
                case $this->internalNameOfAction_done:
                    $status = self::STATUS_PERFORMED; // задание переходит в статус: выполнено
                    break;
                default:
                    $status = null;
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
         * @return string[]
         * метод возвращающий карту действий
         */
        private function getActionMap()
        {
            $internalNameOfAction_cancel = $this->internalNameOfAction_cancel;
            $internalNameOfAction_respond = $this->internalNameOfAction_respond;
            $internalNameOfAction_done = $this->internalNameOfAction_done;
            $internalNameOfAction_refuse = $this->internalNameOfAction_refuse;

            $nameOfAction_cancel = $this->nameOfAction_cancel;
            $nameOfAction_respond = $this->nameOfAction_respond;
            $nameOfAction_done = $this->nameOfAction_done;
            $nameOfAction_refuse = $this->nameOfAction_refuse;

            return [
                "$internalNameOfAction_cancel" => "$nameOfAction_cancel",
                "$internalNameOfAction_respond" => "$nameOfAction_respond",
                "$internalNameOfAction_done" => "$nameOfAction_done",
                "$internalNameOfAction_refuse" => "$nameOfAction_refuse"
            ];
        }

        /**
         * @param $status
         * @return AbstractSelectingAction
         * метод возвращающий возможные действия к текущему статусу
         */
        public function getAvailableActions($status)
        {
            switch ($status) {
                case self::STATUS_NEW:
                    if ($this->checkingUserOfAction_respond)
                    {
                        $action = $this->internalNameOfAction_respond;
                    }
                    elseif ($this->checkingUserOfAction_cancel)
                    {
                        $action = $this->internalNameOfAction_cancel;
                    }
                    break;
                case self::STATUS_IN_WORK:
                    if ($this->checkingUserOfAction_done)
                    {
                        $action = $this->internalNameOfAction_done;
                    }
                    elseif ($this->checkingUserOfAction_refuse)
                    {
                        $action = $this->internalNameOfAction_refuse;
                    }
                    break;
                default:
                    $action = null;
                    break;
            }
            return $action;
        }
    }