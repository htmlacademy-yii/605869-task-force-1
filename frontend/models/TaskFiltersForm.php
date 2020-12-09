<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class TaskFiltersForm extends Model
{
    public $categories;
    public $withoutReplies;
    public $isRemote;
    public $dateInterval;
    public $search;

    const INTERVAL_DAY = 1;
    const INTERVAL_WEEK = 7;
    const INTERVAL_MONTH = 30;


    public function attributeLabels()
    {
        return [
            'categories' => 'Категории',
            'withoutReplies' => 'Без откликов',
            'isRemote' => 'Удаленная работа',
            'dateInterval' => 'Период',
            'search' => 'Поиск по названию',
        ];
    }

    public function rules()
    {
        return [
            [['categories', 'withoutReplies', 'isRemote', 'dateInterval', 'search'], 'safe'],
            ['withoutReplies', 'default', 'value' => '0'],
            ['isRemote', 'default', 'value' => '1'],
            ['search', 'filter', 'filter' => 'trim'],
        ];
    }

    public function getCategoriesList()
    {
        $categories = Category::find()->all();

        return ArrayHelper::map($categories, 'id', 'name');
    }

    public function getDateIntervalList()
    {
        return [
            self::INTERVAL_DAY => 'За день',
            self::INTERVAL_WEEK => 'За неделю',
            self::INTERVAL_MONTH => 'За месяц'
        ];
    }

    public function getList()
    {
        $query = Task::find()->alias('t');

        if ($this->categories) {
            $query->andWhere(['category_id'=>$this->categories]);
        }

        if ($this->withoutReplies) {
            $query->join('LEFT JOIN', Replies::tableName() . ' r', 'r.task_id = t.id' )
                ->groupBy('t.id')
                ->having('count(r.id) = 0');
        }

        if (!Yii::$app->user->getIsGuest() && !$this->isRemote) {
            $user = User::findOne(Yii::$app->user->identity->getId());
            if ($cityId = $user->profiles->city_id) {
                $query->andWhere('t.city_id = :cityId', ['cityId' => $cityId]); //city_id - в настройках профиля обязательное значение
            }
        }

        if ($this->dateInterval) {
            $query->andWhere( 'DATE(dt_add) >= DATE(NOW() - INTERVAL :interval DAY)', ['interval' => $this->dateInterval]);
        }

        if ($this->search) {
            $query->andWhere(['like', 't.name', $this->search]);
        }

        return $query->all();
    }
}