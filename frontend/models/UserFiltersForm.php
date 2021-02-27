<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class UserFiltersForm extends Model
{
    public $categories;
    public $isAvailable;
    public $isOnLine;
    public $isFeedbacks;
    public $isFavorite;
    public $search;

    public function attributeLabels()
    {
        return [
            'categories' => 'Категории',
            'isAvailable' => 'Сейчас свободен',
            'isOnLine' => 'Сейчас онлайн',
            'isFeedbacks' => 'Есть отзывы',
            'isFavorite' => 'В избранном',
            'search' => 'Поиск по имени',
        ];
    }

    public function rules()
    {
        return [
            [['categories', 'isAvailable', 'isOnLine', 'isFeedbacks', 'isFavorite'], 'safe'],
            ['search', 'filter', 'filter' => 'trim'],
        ];
    }

    public function getCategoriesList()
    {
        $categories = Category::find()->all();

        return ArrayHelper::map($categories, 'id', 'name');
    }

    public function getDataProvider()
    {
        $query = User::find()->alias('u');

        if ($this->categories) {
            $query->join('LEFT JOIN', Specialization::tableName() . ' s', 's.user_id = u.id')
            ->andWhere(['s.category_id'=>$this->categories])
            ->groupBy('u.id');
        }

        if ($this->isAvailable) {
            $query->join('LEFT JOIN', Task::tableName() . ' t', 't.executor_id = u.id AND t.status_id = 3')
                ->andWhere('u.role = 1')
                ->groupBy ('u.id')->having('count(t.id) = 0');
        }

        if ($this->isOnLine) {
            $query->where('last_activity_datetime >= NOW() - INTERVAL 5 MINUTE');
        }

        if ($this->isFeedbacks) {
            $query->join('JOIN', Task::tableName() . ' t', 't.executor_id = u.id')
                ->join('JOIN', Opinions::tableName() . ' o', 't.id = o.task_id')
                ->groupBy('u.id');
        }

        if ($this->isFavorite) {
            if (!Yii::$app->user->isGuest) {
                $query->join('JOIN', FavoriteUser::tableName() . ' f', 'f.favorite_user_id = u.id')
                    ->andWhere('f.authorized_id = Yii::$app->user->identity->getId()')
                    ->groupBy('u.id');
            } else {
                $query->join('JOIN', FavoriteUser::tableName() . ' f', 'f.favorite_user_id = u.id')
                    ->andWhere('f.authorized_id = 0')
                    ->groupBy('u.id');
            }
        }

        if ($this->search) {
            $query->andWhere(['like', 'u.name', $this->search]);
        }

		return new ActiveDataProvider([
			'query' => $query,
			'pagination' => [
				'pageSize' => 5
			]
	  	]);
    }
}