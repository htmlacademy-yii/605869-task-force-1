<?php

    namespace frontend\models;

    use yii\db\ActiveRecord;

    /**
     * This is the model class for table "status".
     *
     * @property int $id
     * @property string $name
     * @property string $translate
     *
     * @property Task[] $tasks
     */
    class Status extends ActiveRecord
    {
        /**
         * константы статусов заданий
         */
        const STATUS_NEW = '1'; //статус нового задания
        const STATUS_CANCEL = '2'; //статус отмененного задания
        const STATUS_IN_WORK = '3'; //статус задания находящегося в работе
        const STATUS_COMPLETED = '4'; //статус выполненного задания
        const STATUS_FAILED = '5'; //статус проваленного задания

        const STATUSES = [
            self::STATUS_NEW => 'новое',
            self::STATUS_CANCEL => 'отменено',
            self::STATUS_IN_WORK => 'в работе',
            self::STATUS_COMPLETED => 'выполнено',
            self::STATUS_FAILED => 'провалено'
        ];

        /**
         * {@inheritdoc}
         */
        public static function tableName()
        {
            return 'status';
        }

        /**
         * {@inheritdoc}
         */
        public function rules()
        {
            return [
                [['name', 'translate'], 'required'],
                [['name', 'translate'], 'string', 'max' => 45],
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
                'translate' => 'Translate',
            ];
        }

        /**
         * Gets query for [[Tasks]].
         *
         * @return \yii\db\ActiveQuery
         */
        public function getTasks()
        {
            return $this->hasMany(Task::class, ['status_id' => 'id']);
        }
    }
