<?php

namespace frontend\models;

class CreateMessage extends Message
{
    public function saveMessage(object $task, string $content): ?object
    {
        $message = new Message();
        $message->sender_id = $task->customer_id;
        $message->task_id = $task->id;
        $message->message = $content;
        $message->dt_add = date("Y-m-d H:i:s");
        $message->save();

        return $message;
    }

}