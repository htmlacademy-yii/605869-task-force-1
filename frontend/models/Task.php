<?php

    namespace frontend\models;

    use frontend\events\TaskEventListener;
    use yii\base\Event;
    use yii\db\ActiveQuery;
    use yii\db\ActiveRecord;

    /**
     * This is the model class for table "task".
     *
     * @property int $id
     * @property string $name
     * @property int $category_id
     * @property int $city_id
     * @property string|null $address
     * @property float $budget
     * @property string $expire
     * @property string $description
     * @property int $customer_id
     * @property int $status_id
     * @property float $lat
     * @property float $long
     * @property int $executor_id //not required
     * @property string $dt_add
     *
     * @property File[] $files
     * @property Opinions[] $opinions
     * @property Replies[] $replies
     * @property Category $category
     * @property City $city
     * @property User $customer
     * @property User $executor
     * @property Status $status
     */
    class Task extends ActiveRecord
    {
        const EVENT_SET_EXECUTOR = 'eventSetExecutor';
        const EVENT_COMPLETE_TASK = 'eventCompleteTask';
        const EVENT_STATUS_CHANGE = 'eventStatusChange';

        /**
         * константы статусов заданий
         */
        const STATUS_NEW = 1; //статус нового задания
        const STATUS_CANCELED = 2; //статус отмененного задания
        const STATUS_IN_WORK = 3; //статус задания находящегося в работе
        const STATUS_COMPLETED = 4; //статус выполненного задания
        const STATUS_FAILED = 5; //статус проваленного задания
        const STATUS_EXPIRED = 6; //статус просроченного задания

        public function init()
        {
            Event::on(self::class, self::EVENT_SET_EXECUTOR, [
                TaskEventListener::class, 'setExecutor'
            ]);
            Event::on(self::class, self::EVENT_COMPLETE_TASK, [
                TaskEventListener::class, 'complete'
            ]);
            Event::on(self::class, self::EVENT_STATUS_CHANGE, [
                TaskEventListener::class, 'statusChange'
            ]);
            Event::on(self::class, self::EVENT_AFTER_UPDATE, function (Event $event) {
                if ($this->oldAttributes['status_id'] !== $this->status_id) {
                    $this->trigger(self::EVENT_STATUS_CHANGE);
                }
            });
        }

        /**
         * {@inheritdoc}
         */
        public static function tableName()
        {
            return 'task';
        }

        /**
         * {@inheritdoc}
         */
        public function rules()
        {
            return [
                [
                    [
                        'name',
                        'category_id',
                        'city_id',
                        'budget',
                        'expire',
                        'description',
                        'customer_id',
                    ],
                    'required'
                ],
                [
                    [
                        'category_id',
                        'city_id',
                        'customer_id',
                        'status_id',
                        'executor_id'
                    ],
                    'integer'
                ],
                [['budget', 'lat', 'long'], 'number'],
                [['expire', 'dt_add'], 'safe'],
                [['description'], 'string'],
                [['name'], 'string', 'max' => 128],
                [['address'], 'string', 'max' => 200],
                [
                    ['category_id'],
                    'exist',
                    'skipOnError' => true,
                    'targetClass' => Category::class,
                    'targetAttribute' => ['category_id' => 'id']
                ],
                [
                    ['city_id'],
                    'exist',
                    'skipOnError' => true,
                    'targetClass' => City::class,
                    'targetAttribute' => ['city_id' => 'id']
                ],
                [
                    ['customer_id'],
                    'exist',
                    'skipOnError' => true,
                    'targetClass' => User::class,
                    'targetAttribute' => ['customer_id' => 'id']
                ],
                [
                    ['executor_id'],
                    'exist',
                    'skipOnError' => true,
                    'targetClass' => User::class,
                    'targetAttribute' => ['executor_id' => 'id']
                ],
                [
                    ['status_id'],
                    'exist',
                    'skipOnError' => true,
                    'targetClass' => Status::class,
                    'targetAttribute' => ['status_id' => 'id']
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
                'name' => 'Name',
                'category_id' => 'Category ID',
                'city_id' => 'City ID',
                'address' => 'Address',
                'budget' => 'Budget',
                'expire' => 'Expire',
                'description' => 'Description',
                'customer_id' => 'Customer ID',
                'status_id' => 'Status ID',
                'lat' => 'Lat',
                'long' => 'Long',
                'executor_id' => 'Executor ID',
                'dt_add' => 'Dt Add',
            ];
        }

        /**
         * Gets query for [[Files]].
         *
         * @return ActiveQuery
         */
        public function getFiles()
        {
            return $this->hasMany(File::class, ['task_id' => 'id']);
        }

        /**
         * Gets query for [[Opinions]].
         *
         * @return ActiveQuery
         */
        public function getOpinions()
        {
            return $this->hasMany(Opinions::class, ['task_id' => 'id']);
        }

        /**
         * Gets query for [[Replies]].
         *
         * @return ActiveQuery
         */
        public function getReplies()
        {
            return $this->hasMany(Replies::class, ['task_id' => 'id']);
        }

        /**
         * Gets query for [[Category]].
         *
         * @return ActiveQuery
         */
        public function getCategory()
        {
            return $this->hasOne(Category::class, ['id' => 'category_id']);
        }

        /**
         * Gets query for [[City]].
         *
         * @return ActiveQuery
         */
        public function getCity()
        {
            return $this->hasOne(City::class, ['id' => 'city_id']);
        }

        /**
         * Gets query for [[Customer]].
         *
         * @return ActiveQuery
         */
        public function getCustomer()
        {
            return $this->hasOne(User::class, ['id' => 'customer_id']);
        }

        /**
         * Gets query for [[Executor]].
         *
         * @return ActiveQuery
         */
        public function getExecutor()
        {
            return $this->hasOne(User::class, ['id' => 'executor_id']);
        }

        /**
         * Gets query for [[Status]].
         *
         * @return ActiveQuery
         */
        public function getStatus()
        {
            return $this->hasOne(Status::class, ['id' => 'status_id']);
        }

        /**
         * @param int $userId
         * @return Replies|null
         */
        public function getReplyByUserId(int $userId): ?Replies
        {
            return $this->getReplies()->where(['user_id' => $userId])->one();
        }
    }
