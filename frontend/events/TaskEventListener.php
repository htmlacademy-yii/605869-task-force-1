<?php

    namespace frontend\events;

    use frontend\helpers\MailSender;
    use frontend\models\Notification;
    use frontend\models\SiteSettings;
    use frontend\models\Status;
    use frontend\models\Task;
    use yii\base\Event;

    class TaskEventListener
    {
        private static function createTask(Task $task, string $icon, string $description)
        {
            $notification = new Notification();
            $notification->user_id = $task->executor_id;
            $notification->title = $task->name;
            $notification->icon = $icon;
            $notification->description = $description;
            $notification->task_id = $task->id;
            $notification->save();
        }

        /**
         * @param Event $event
         *
         */
        public static function setExecutor(Event $event)
        {
            /** @var Task $task */
            $task = $event->sender;

            $icon = 'executor';
            $description = 'Выбран исполнитель для';
            self::createTask($task, $icon, $description);
        }

        /**
         * @param Event $event
         */
        public static function complete(Event $event)
        {
            /** @var Task $task */
            $task = $event->sender;

            $icon = 'close';
            $description = 'Завершено задание';
            self::createTask($task, $icon, $description);
        }

        /**
         * @param Event $event
         */
        public static function statusChange(Event $event)
        {
            /** @var Task $task */
            $task = $event->sender;

            if ($task->customer->siteSettings->show_actions_of_task === SiteSettings::ENABLED) {
                $setTo = $task->customer->email;
                $subject = 'Задание «' . $task->name . '» изменило статус на «' . Status::STATUSES[$task->status_id] . '».';
                MailSender::mail($setTo, $subject);
            }

            if (isset($task->executor_id)) {
                if ($task->executor->siteSettings->show_actions_of_task === SiteSettings::ENABLED) {
                    $setTo = $task->executor->email;
                    $subject = 'Задание «' . $task->name . '» изменило статус на «' . Status::STATUSES[$task->status_id] . '».';
                    MailSender::mail($setTo, $subject);
                }
            }
        }
    }
