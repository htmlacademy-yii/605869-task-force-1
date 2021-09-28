<?php

    namespace frontend\models;

    use yii\db\ActiveQuery;
    use yii\db\ActiveRecord;

    /**
     * This is the model class for table "city".
     *
     * @property int $id
     * @property string $name
     * @property string $kladr
     * @property float $lat
     * @property float $long
     *
     * @property Profiles[] $profiles
     * @property Task[] $tasks
     */
    class City extends ActiveRecord
    {
        /**
         * {@inheritdoc}
         */
        public static function tableName()
        {
            return 'city';
        }

        /**
         * {@inheritdoc}
         */
        public function rules()
        {
            return [
                [['name', 'lat', 'long', 'kladr'], 'required'],
                [['name', 'lat', 'long', 'kladr'], 'safe'],
                [['lat', 'long'], 'number'],
                [['name'], 'string', 'max' => 45],
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
                'lat' => 'Lat',
                'long' => 'Long',
                'kladr' => 'Код КЛАДР города',
            ];
        }

        /**
         * Gets query for [[Profiles]].
         *
         * @return ActiveQuery
         */
        public function getProfiles()
        {
            return $this->hasMany(Profiles::class, ['city_id' => 'id']);
        }

        /**
         * Gets query for [[Tasks]].
         *
         * @return ActiveQuery
         */
        public function getTasks()
        {
            return $this->hasMany(Task::class, ['city_id' => 'id']);
        }

        public static function getListCities()
        {
            return City::find()->select('name')->indexBy('id')->column();
        }
    }

