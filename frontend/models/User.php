<?php

    namespace frontend\models;

    use Yii;
    use yii\db\ActiveQuery;
    use yii\db\ActiveRecord;

    /**
     * This is the model class for table "user".
     *
     * @property int $id
     * @property string $name
     * @property string $email
     * @property string $password
     * @property string $dt_add
     * @property int $role
     * @property string $last_activity_datetime
     *
     * @property Message[] $messages
     * @property Message[] $messages0
     * @property Photo[] $photos
     * @property Profiles $profiles
     * @property Replies[] $replies
     * @property Specialization[] $specializations
     * @property Task[] $ownedTasks
     * @property Task[] $executedTasks
     * @property City $city
     * @property Opinions[] $opinions
     *
     */
    class User extends ActiveRecord
    {
        /**     * {@inheritdoc}
         */
        public static function tableName()
        {
            return 'user';
        }

        /**
         * {@inheritdoc}
         */
        public function rules()
        {
            return [
                [['name', 'email', 'password', 'role'], 'required'],
                [['dt_add'], 'safe'],
                [['role'], 'integer'],
                [['name', 'email'], 'string', 'max' => 45],
                [['password'], 'string', 'max' => 64],
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
                'email' => 'Email',
                'password' => 'Password',
                'dt_add' => 'Dt Add',
                'role' => 'Role',
                'last_activity_datetime' => 'Last Activity Datetime',
            ];
        }

        /**
         * Gets query for [[Messages]].
         *
         * @return ActiveQuery
         */
        public function getMessages()
        {
            return $this->hasMany(Message::className(), ['recipient_id' => 'id']);
        }

        /**
         * Gets query for [[Messages0]].
         *
         * @return ActiveQuery
         */
        public function getMessages0()
        {
            return $this->hasMany(Message::className(), ['sender_id' => 'id']);
        }

        /**
         * Gets query for [[Photos]].
         *
         * @return ActiveQuery
         */
        public function getPhotos()
        {
            return $this->hasMany(Photo::className(), ['user_id' => 'id']);
        }

        /**
         * Gets query for [[Profiles]].
         *
         * @return ActiveQuery
         */
        public function getProfiles()
        {
            return $this->hasOne(Profiles::className(), ['user_id' => 'id']);
        }

        /**
         * Gets query for [[Replies]].
         *
         * @return ActiveQuery
         */
        public function getReplies()
        {
            return $this->hasMany(Replies::className(), ['user_id' => 'id']);
        }

        /**
         * Gets query for [[Specializations]].
         *
         * @return ActiveQuery
         */
        public function getSpecializations()
        {
            return $this->hasMany(Specialization::className(), ['user_id' => 'id']);
        }

        /**
         * Gets query for [[Tasks]].
         *
         * @return ActiveQuery
         */
        public function getOwnedTasks()
        {
            return $this->hasMany(Task::className(), ['customer_id' => 'id']);
        }

        /**
         * Gets query for [[Tasks0]].
         *
         * @return ActiveQuery
         */
        public function getExecutedTasks()
        {
            return $this->hasMany(Task::className(), ['executor_id' => 'id']);
        }

        /**
         * @return string
         */
        public function getAvatar()
        {
            return $this->profiles->avatar ?? '/img/account.png';
        }

        /**
         * @return string
         */
        public function getCity()
        {
            return $this->city->name ?? '';
        }

        public function getAge()
        {
            return (new \DateTime())->diff(new \DateTime($this->profiles->bd))->y;
        }


        /**
         * @return bool|int|string|null
         */
        public function getTasksCount()
        {
            return $this->hasMany(Task::className(), ['customer_id' => 'id'])->count();
        }

        public function getOpinions()
        {
            return Opinions::find()->alias('o')
                ->join('INNER JOIN', Task::tableName() . ' t', 't.id = o.task_id')
                ->where('t.executor_id = :userId', ['userId' => $this->id])->all();
        }

        public function getRating()
        {
            return Opinions::find()->alias('o')
                ->join('INNER JOIN', Task::tableName() . ' t', 't.id = o.task_id')
                ->where('t.executor_id = :userId', ['userId' => $this->id])->average('o.rate');
        }

    }
