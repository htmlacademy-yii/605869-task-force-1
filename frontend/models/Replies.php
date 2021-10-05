<?php
    
    namespace frontend\models;
    
    use frontend\events\ReplyEventListener;
    use frontend\events\TaskEventListener;
    use yii\base\Event;
    use yii\db\ActiveQuery;
    use yii\db\ActiveRecord;

    /**
     * This is the model class for table "replies".
     *
     * @property int $id
     * @property int $task_id
     * @property int $user_id
     * @property int $status
     * @property float $price
     * @property string $description
     * @property string $dt_add
     *
     * @property Task $task
     * @property User $user
     */
    class Replies extends ActiveRecord
    {
        /**
         * константы статусов откликов
         */
        const STATUS_NEW = 1; //статус нового отклика
        const STATUS_ACCEPTED = 2; //статус принятого отклика
        const STATUS_REFUSAL = 3; //статус отказа от отклика

        public function init()
        {
            Event::on(self::class, self::EVENT_AFTER_INSERT, [
                ReplyEventListener::class, 'reply'
            ]);
        }
        
        /**
         * {@inheritdoc}
         */
        public static function tableName()
        {
            return 'replies';
        }
        
        /**
         * {@inheritdoc}
         */
        public function rules()
        {
            return [
                [['task_id', 'user_id', 'price', 'description'], 'required'],
                [['task_id', 'user_id', 'status'], 'integer'],
                [['price'], 'number'],
                [['description'], 'string'],
                [['dt_add'], 'safe'],
                [
                    ['task_id'],
                    'exist',
                    'skipOnError' => true,
                    'targetClass' => Task::class,
                    'targetAttribute' => ['task_id' => 'id']
                ],
                [
                    ['user_id'],
                    'exist',
                    'skipOnError' => true,
                    'targetClass' => User::class,
                    'targetAttribute' => ['user_id' => 'id']
                ],
            ];
        }
        
        /**
         * {@inheritdoc}
         */
        public function attributeLabels()
        {
            return [
                'id' => 'ID',
                'task_id' => 'Task ID',
                'user_id' => 'User ID',
                'price' => 'Price',
                'description' => 'Description',
                'dt_add' => 'Dt Add',
                'status' => 'Статус',
            ];
        }
        
        /**
         * Gets query for [[Task]].
         *
         * @return ActiveQuery
         */
        public function getTask()
        {
            return $this->hasOne(Task::class, ['id' => 'task_id']);
        }
        
        /**
         * Gets query for [[User]].
         *
         * @return ActiveQuery
         */
        public function getUser()
        {
            return $this->hasOne(User::class, ['id' => 'user_id']);
        }
    }
