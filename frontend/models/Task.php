<?php

namespace frontend\models;

use Yii;

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
 * @property int $status_execution
 * @property int $replies_id
 * @property float $lat
 * @property float $long
 * @property int $executor_id
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
class Task extends \yii\db\ActiveRecord
{
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
            [['name', 'category_id', 'city_id', 'budget', 'expire', 'description', 'customer_id', 'status_id', 'status_execution', 'replies_id', 'lat', 'long', 'executor_id'], 'required'],
            [['category_id', 'city_id', 'customer_id', 'status_id', 'status_execution', 'replies_id', 'executor_id'], 'integer'],
            [['budget', 'lat', 'long'], 'number'],
            [['expire', 'dt_add'], 'safe'],
            [['description'], 'string'],
            [['name', 'address'], 'string', 'max' => 45],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['customer_id' => 'id']],
            [['executor_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['executor_id' => 'id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => Status::className(), 'targetAttribute' => ['status_id' => 'id']],
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
            'status_execution' => 'Status Execution',
            'replies_id' => 'Replies ID',
            'lat' => 'Lat',
            'long' => 'Long',
            'executor_id' => 'Executor ID',
            'dt_add' => 'Dt Add',
        ];
    }

    /**
     * Gets query for [[Files]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFiles()
    {
        return $this->hasMany(File::className(), ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Opinions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOpinions()
    {
        return $this->hasMany(Opinions::className(), ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Replies]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReplies()
    {
        return $this->hasMany(Replies::className(), ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
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
     * Gets query for [[Customer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(User::className(), ['id' => 'customer_id']);
    }

    /**
     * Gets query for [[Executor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExecutor()
    {
        return $this->hasOne(User::className(), ['id' => 'executor_id']);
    }

    /**
     * Gets query for [[Status]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(Status::className(), ['id' => 'status_id']);
    }
}
