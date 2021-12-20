<?php

    namespace frontend\models;

    use DateTime;
    use Yii;
    use yii\db\ActiveQuery;
    use yii\db\ActiveRecord;
    use yii\web\IdentityInterface;

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
     * @property SiteSettings $siteSettings
     * @property Auth[] $auths
     *
     */
    class User extends ActiveRecord implements IdentityInterface
    {
        const ROLE_CUSTOMER = 2;
        const ROLE_EXECUTOR = 1;

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
                [['name', 'email', 'password'], 'required'],
                [['dt_add', 'last_activity_datetime'], 'safe'],
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
         * Gets query for [[Messages0]].
         *
         * @return ActiveQuery
         */
        public function getMessages0()
        {
            return $this->hasMany(Message::class, ['sender_id' => 'id']);
        }

        /**
         * Gets query for [[Photos]].
         *
         * @return ActiveQuery
         */
        public function getPhotos()
        {
            return $this->hasMany(Photo::class, ['user_id' => 'id']);
        }

        /**
         * Gets query for [[Profiles]].
         *
         * @return ActiveQuery
         */
        public function getProfiles()
        {
            return $this->hasOne(Profiles::class, ['user_id' => 'id']);
        }

        /**
         * Gets query for [[Replies]].
         *
         * @return ActiveQuery
         */
        public function getReplies()
        {
            return $this->hasMany(Replies::class, ['user_id' => 'id']);
        }

        /**
         * Gets query for [[SiteSettings]].
         *
         * @return ActiveQuery
         */
        public function getSiteSettings()
        {
            return $this->hasOne(SiteSettings::class, ['user_id' => 'id']);
        }

        /**
         * Gets query for [[Specializations]].
         *
         * @return ActiveQuery
         */
        public function getSpecializations()
        {
            return $this->hasMany(Specialization::class, ['user_id' => 'id']);
        }

        /**
         * Gets query for [[Tasks]].
         *
         * @return ActiveQuery
         */
        public function getOwnedTasks()
        {
            return $this->hasMany(Task::class, ['customer_id' => 'id']);
        }

        /**
         * Gets query for [[Tasks0]].
         *
         * @return ActiveQuery
         */
        public function getExecutedTasks()
        {
            return $this->hasMany(Task::class, ['executor_id' => 'id']);
        }

        /**
         * @return string
         */
        public function getAvatar()
        {
            if ($this->profiles->avatar) {
                return Yii::getAlias('@web') . '/uploads/avatars/' . $this->profiles->avatar;
            } else {
                return '/img/account.png';
            }
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
            return $this->hasMany(Task::class, ['customer_id' => 'id'])->count();
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

        public function validatePassword($password)
        {
            return Yii::$app->security->validatePassword($password, $this->password);
        }

        public static function findIdentity($id)
        {
            return self::findOne($id);
        }

        public static function findIdentityByAccessToken($token, $type = null)
        {
            // TODO: Implement findIdentityByAccessToken() method.
        }

        public function getId()
        {
            return $this->getPrimaryKey();
        }

        public function getAuthKey()
        {
            // TODO: Implement getAuthKey() method.
        }

        public function validateAuthKey($authKey)
        {
            // TODO: Implement validateAuthKey() method.
        }

        /**
         * @return bool
         */
        public function isCustomer(): bool
        {
            return (int)$this->role === self::ROLE_CUSTOMER;
        }

        public function isNotOnline()
        {
            $lastActivity = new DateTime($this->last_activity_datetime);;
            $currentTimeStamp = new DateTime();
            $differenceLastActivity = $currentTimeStamp->diff($lastActivity);

            return $differenceLastActivity->y > 1
                || $differenceLastActivity->m > 1
                || $differenceLastActivity->d > 1
                || $differenceLastActivity->h > 1
                || $differenceLastActivity->i > 5;
        }

        /**
         * Gets query for [[Auths]].
         */
        public function getAuths()
        {
            return
                $this->hasMany(Auth::class, ['user_id' => 'id']);
        }
    }
