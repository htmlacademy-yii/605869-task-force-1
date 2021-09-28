<?php

namespace frontend\models;

use frontend\api\LocationApiService;
use frontend\repositories\CityRepository;
use Throwable;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * Class CreateTaskForm
 * @package frontend\models
 */
class CreateTaskForm extends Model
{
    public $name;
    public $description;
    public $categoryId;
    public $files;
    public $budget;
    public $expire;
    public $address;
    public $lat;
    public $long;
    public $city;
    public $village;
    public $kladr;

    public function attributeLabels()
    {
        return [
            'name' => 'Мне нужно',
            'description' => 'Подробности задания',
            'categoryId' => 'Категория',
            'files' => 'Файлы',
            'budget' => 'Бюджет',
            'expire' => 'Срок исполнения',
            'address' => 'Локация',
            'long' => 'Долгота',
            'lat' => 'Широта',
            'city' => 'Город',
            'kladr' => 'Код КЛАДР города',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                ['name', 'description', 'categoryId', 'address'],
                'required',
                'message' => 'Это поле должно быть заполнено.'
            ],
            [['name', 'description', 'categoryId', 'files', 'address', 'lat', 'long', 'city', 'kladr', 'village'], 'safe'],
            [['name', 'description'], 'trim'],
            ['name', 'string', 'min' => 10, 'tooShort' => 'Это поле должно содержать не менее 10 символов.'],
            ['name', 'string', 'max' => 128, 'tooLong' => 'Это поле должно содержать не более 128 символов.'],
            ['description', 'string', 'min' => 30, 'tooShort' => 'Это поле должно содержать не менее 30 символов.'],
            [
                'categoryId',
                'exist',
                'targetClass' => Category::class,
                'targetAttribute' => 'id',
                'message' => 'Задание должно принадлежать одной из категорий'
            ],
            [['files'], 'file', 'skipOnEmpty' => true, 'maxFiles' => 0],
            ['budget', 'integer', 'min' => 0, 'tooSmall' => 'Бюджет не может быть меньше нуля'],
            ['expire', 'date', 'format' => 'php:Y-m-d'],
            ['expire', 'isDateInFuture'],
        ];
    }

    /**
     * data validator
     * @param $attribute
     */
    public function isDateInFuture($attribute): void
    {
        if (strtotime('now') > strtotime($this->$attribute)) {
            $this->addError($attribute, 'Дата окончания не может быть меньше даты начала задачи.');
        }
    }

    /**
     * @return Task|null
     * @throws Throwable
     * @throws Exception
     * @throws \yii\db\Exception
     */
    public function saveFields(): ?Task
    {
        $task = new Task();
        $task->name = $this->name;
        $task->status_id = Task::STATUS_NEW;
        $task->description = $this->description;
        $task->category_id = $this->categoryId;
        //@todo required from requrest
        $task->city_id = 1;
        $task->lat = $this->lat;
        $task->long = $this->long;
        //MYSQL: status_id DEFAULT 1
        //$task->status_id = Task::STATUS_NEW;
        $task->budget = $this->budget;
        $task->expire = $this->expire;
        $task->customer_id = Yii::$app->user->getId();

        $task->address = $this->address;

        if (!$this->city && !$this->village) {
            return null;
        }

        $cityName = $this->city ?: $this->village;

        $cityModel = CityRepository::getCityByKladrCode(
            $this->kladr,
            $cityName,
            $this->long,
            $this->lat
        );

        $task->city_id = $cityModel->id;

        if (!$task->save()) {

            return null;
        }

        $this->files = UploadedFile::getInstances($this, 'files');
        $src = Yii::getAlias('@app/uploads/') . $task->id;

        foreach ($this->files as $file) {
            FileHelper::createDirectory($src);
            $file->saveAs($src . '/' . $file->name);
            $newFile = new File();
            $newFile->name = $file->name;
            $newFile->task_id = $task->id;
            $newFile->save();
        }

        return $task;
    }
}