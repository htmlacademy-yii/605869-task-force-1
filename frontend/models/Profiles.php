<?php

    namespace frontend\models;

    use yii\db\ActiveQuery;
    use yii\db\ActiveRecord;

    /**
     * This is the model class for table "profiles".
     *
     * @property int $id
     * @property string|null $address
     * @property string|null $bd
     * @property string|null $about
     * @property string|null $phone
     * @property string|null $email
     * @property int|null $counter_failed_tasks
     * @property int $specialization_id
     * @property int $city_id
     * @property string|null $avatar
     * @property int $user_id
     * @property string|null $skype
     *
     * @property City $city
     * @property Specialization $specialization
     * @property User $user
     */
    class Profiles extends ActiveRecord
    {
        /**
         * {@inheritdoc}
         */
        public static function tableName(): string
        {
            return 'profiles';
        }

        /**
         * {@inheritdoc}
         */
        public function rules(): array
        {
            return [
                [['bd'], 'safe'],
                [['about'], 'string'],
                [['counter_failed_tasks', 'specialization_id', 'city_id', 'user_id'], 'integer'],
                [['city_id', 'user_id'], 'required'],
                [['phone', 'email', 'avatar', 'skype'], 'string', 'max' => 45],
                [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'id']],
                [['specialization_id'], 'exist', 'skipOnError' => true, 'targetClass' => Specialization::className(), 'targetAttribute' => ['specialization_id' => 'id']],
                [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            ];
        }

        /**
         * {@inheritdoc}
         */
        public function attributeLabels(): array
        {
            return [
                'id' => 'ID',
                'address' => 'Address',
                'bd' => 'Bd',
                'about' => 'About',
                'phone' => 'Phone',
                'email' => 'Email',
                'counter_failed_tasks' => 'Counter Failed Tasks',
                'specialization_id' => 'Specialization ID',
                'city_id' => 'City ID',
                'avatar' => 'Avatar',
                'user_id' => 'User ID',
                'skype' => 'Skype',
            ];
        }

        /**
         * Gets query for [[City]].
         *
         * @return ActiveQuery
         */
        public function getCity(): ActiveQuery
        {
            return $this->hasOne(City::className(), ['id' => 'city_id']);
        }

        /**
         * Gets query for [[User]].
         *
         * @return ActiveQuery
         */
        public function getUser(): ActiveQuery
        {
            return $this->hasOne(User::className(), ['id' => 'user_id']);
        }
    }
