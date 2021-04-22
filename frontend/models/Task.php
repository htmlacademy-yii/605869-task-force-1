<?php

namespace frontend\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

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
 * @property int $replies_id //drop column
 * @property float $lat
 * @property float $long
 * @property int $executor_id //not required
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
class Task extends ActiveRecord
{
    /**
     * константы статусов заданий
     */
    const STATUS_NEW = 1; //статус нового задания
    const STATUS_CANCEL = 2; //статус отмененного задания
    const STATUS_IN_WORK = 3; //статус задания находящегося в работе
    const STATUS_COMPLETED = 4; //статус выполненного задания
    const STATUS_FAILED = 5; //статус проваленного задания
    
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
            [
                [
                    'name',
                    'category_id',
                    'city_id',
                    'budget',
                    'expire',
                    'description',
                    'customer_id',
                ],
                'required'
            ],
            [
                [
                    'category_id',
                    'city_id',
                    'customer_id',
                    'status_id',
                    'replies_id',
                    'executor_id'
                ],
                'integer'
            ],
            [['budget', 'lat', 'long'], 'number'],
            [['expire', 'dt_add'], 'safe'],
            [['description'], 'string'],
            [['name', 'address'], 'string', 'max' => 45],
            [
                ['category_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Category::className(),
                'targetAttribute' => ['category_id' => 'id']
            ],
            [
                ['city_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => City::className(),
                'targetAttribute' => ['city_id' => 'id']
            ],
            [
                ['customer_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::className(),
                'targetAttribute' => ['customer_id' => 'id']
            ],
            [
                ['executor_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::className(),
                'targetAttribute' => ['executor_id' => 'id']
            ],
            [
                ['status_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Status::className(),
                'targetAttribute' => ['status_id' => 'id']
            ],
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
     * @return ActiveQuery
     */
    public function getFiles()
    {
        return $this->hasMany(File::className(), ['task_id' => 'id']);
    }
    
    /**
     * Gets query for [[Opinions]].
     *
     * @return ActiveQuery
     */
    public function getOpinions()
    {
        return $this->hasMany(Opinions::className(), ['task_id' => 'id']);
    }
    
    /**
     * Gets query for [[Replies]].
     *
     * @return ActiveQuery
     */
    public function getReplies()
    {
        return $this->hasMany(Replies::className(), ['task_id' => 'id']);
    }
    
    /**
     * Gets query for [[Category]].
     *
     * @return ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }
    
    /**
     * Gets query for [[City]].
     *
     * @return ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }
    
    /**
     * Gets query for [[Customer]].
     *
     * @return ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(User::className(), ['id' => 'customer_id']);
    }
    
    /**
     * Gets query for [[Executor]].
     *
     * @return ActiveQuery
     */
    public function getExecutor()
    {
        return $this->hasOne(User::className(), ['id' => 'executor_id']);
    }
    
    /**
     * Gets query for [[Status]].
     *
     * @return ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(Status::className(), ['id' => 'status_id']);
    }
}
