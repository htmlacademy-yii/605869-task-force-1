<?php

    namespace frontend\events;

    use frontend\helpers\MailSender;
    use frontend\models\Replies;
    use frontend\models\SiteSettings;
    use yii\base\Event;

    class ReplyEventListener
    {
        /**
         * @param Event $event
         */
        public static function reply(Event $event)
        {
            /** @var Replies $reply */
            $reply = $event->sender;

            if ($reply->task->customer->siteSettings->show_actions_of_task === SiteSettings::ENABLED) {
                $setTo = $reply->task->customer->email;
                $subject = 'На ваше задание: «' . $reply->task->name . '» откликнулся «' . $reply->user->name . '»';
                MailSender::mail($setTo, $subject);
            }
        }

    }
