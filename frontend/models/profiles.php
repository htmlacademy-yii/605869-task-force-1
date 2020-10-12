<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "profiles".
 *
 * @property int $id
 * @property string|null $address
 * @property string|null $bd
 * @property string|null $about
 * @property string|null $phone
 * @property string|null $telegram
 * @property int|null $counter_failed_tasks
 * @property int $specialization_id
 * @property int $city_id
 * @property string|null $avatar
 * @property int $user_id
 *
 * @property City $city
 * @property Specialization $specialization
 * @property User $user
 */
class profiles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'profiles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bd'], 'safe'],
            [['about'], 'string'],
            [['counter_failed_tasks', 'specialization_id', 'city_id', 'user_id'], 'integer'],
            [['specialization_id', 'city_id', 'user_id'], 'required'],
            [['address', 'phone', 'telegram', 'avatar'], 'string', 'max' => 45],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'id']],
            [['specialization_id'], 'exist', 'skipOnError' => true, 'targetClass' => Specialization::className(), 'targetAttribute' => ['specialization_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'address' => 'Address',
            'bd' => 'Bd',
            'about' => 'About',
            'phone' => 'Phone',
            'telegram' => 'Telegram',
            'counter_failed_tasks' => 'Counter Failed Tasks',
            'specialization_id' => 'Specialization ID',
            'city_id' => 'City ID',
            'avatar' => 'Avatar',
            'user_id' => 'User ID',
        ];
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    /**
     * Gets query for [[Specialization]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSpecialization()
    {
        return $this->hasOne(Specialization::className(), ['id' => 'specialization_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
