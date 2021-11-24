<?php

namespace frontend\models;

use frontend\events\MessageEventsListener;
use yii\base\Event;

/**
 * This is the model class for table "message".
 *
 * @property int $id
 * @property int $sender_id
 * @property int $task_id
 * @property string $message
 * @property string $dt_add
 *
 * @property User $sender
 * @property Task $task
 */
class Message extends \yii\db\ActiveRecord
{
    public function init()
    {
        Event::on(self::class, self::EVENT_AFTER_INSERT, [
            MessageEventsListener::class, 'afterInsert'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'message';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sender_id', 'message', 'task_id'], 'required'],
            [['sender_id', 'task_id'], 'integer'],
            [['message'], 'string'],
            [['sender_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['sender_id' => 'id']],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::class, 'targetAttribute' => ['task_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sender_id' => 'Sender ID',
            'message' => 'Message',
        ];
    }

    /**
     * Gets query for [[Sender]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSender()
    {
        return $this->hasOne(User::class, ['id' => 'sender_id']);
    }

    /**
     * Gets query for [[Task]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::class, ['id' => 'task_id']);
    }
}
