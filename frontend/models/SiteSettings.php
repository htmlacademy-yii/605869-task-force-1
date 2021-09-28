<?php

namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "site_settings".
 *
 * @property int $id
 * @property int $user_id
 * @property int $show_new_messages
 * @property int $show_actions_of_task
 * @property int $show_new_review
 * @property int $show_my_contacts_customer
 * @property int $hide_account
 *
 * @property User $user
 */
class SiteSettings extends ActiveRecord
{
    const ENABLED = 1;
    const DISABLED = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site_settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'show_new_messages', 'show_actions_of_task', 'show_new_review', 'show_my_contacts_customer', 'hide_account'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'show_new_messages' => 'Show New Messages',
            'show_actions_of_task' => 'Show Actions Of Task',
            'show_new_review' => 'Show New Review',
            'show_my_contacts_customer' => 'Show My Contacts Customer',
            'hide_account' => 'Hide Account',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
