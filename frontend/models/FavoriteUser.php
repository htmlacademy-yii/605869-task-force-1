<?php

namespace app\models;

use frontend\models\User;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "favorite_user".
 *
 * @property int $id
 * @property int $authorized_id
 * @property int $favorite_user_id
 *
 * @property User $authorized
 * @property User $favoriteUser
 */
class FavoriteUser extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'favorite_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['authorized_id', 'favorite_user_id'], 'required'],
            [['authorized_id', 'favorite_user_id'], 'integer'],
            [['authorized_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['authorized_id' => 'id']],
            [['favorite_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['favorite_user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'authorized_id' => 'Authorized ID',
            'favorite_user_id' => 'Favorite User ID',
        ];
    }

    /**
     * Gets query for [[Authorized]].
     *
     * @return ActiveQuery
     */
    public function getAuthorized()
    {
        return $this->hasOne(User::className(), ['id' => 'authorized_id']);
    }

    /**
     * Gets query for [[FavoriteUser]].
     *
     * @return ActiveQuery
     */
    public function getFavoriteUser()
    {
        return $this->hasOne(User::className(), ['id' => 'favorite_user_id']);
    }
}
