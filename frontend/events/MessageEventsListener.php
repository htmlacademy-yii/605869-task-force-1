<?php

namespace frontend\events;

use frontend\models\Message;
use frontend\models\Notification;
use yii\base\Event;
use yii\helpers\Html;

class MessageEventsListener
{
    public static function afterInsert(Event $event)
    {
        /** @var Message $message */
        $message = $event->sender;

        $notification = new Notification();
        $notification->user_id = $message->task->customer_id;
        $notification->title = Html::encode($message->task->name);
        $notification->icon = 'message';
        $notification->description = 'Новое сообщение в чате';
        $notification->task_id = $message->task_id;
        $notification->save();
    }
}
