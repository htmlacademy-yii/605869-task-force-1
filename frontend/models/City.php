<?php

    namespace frontend\models;

    use yii\db\ActiveRecord;

    /**
     * This is the model class for table "city".
     *
     * @property int $id
     * @property string $name
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
                [['name', 'lat', 'long'], 'required'],
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
            ];
        }

        /**
         * Gets query for [[Profiles]].
         *
         * @return \yii\db\ActiveQuery
         */
        public function getProfiles()
        {
            return $this->hasMany(Profiles::className(), ['city_id' => 'id']);
        }

        /**
         * Gets query for [[Tasks]].
         *
         * @return \yii\db\ActiveQuery
         */
        public function getTasks()
        {
            return $this->hasMany(Task::className(), ['city_id' => 'id']);
        }
    }
