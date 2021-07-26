<?php

namespace frontend\models;

use yii\base\Model;

class CompleteTaskForm extends Model
{
    public $completion;
    public $comment;
    public $rating;

    const COMPLETION_YES = 'yes';
    const COMPLETION_PROBLEMS = 'difficult';

    const COMPLETION = [
        self::COMPLETION_YES => 'да',
        self::COMPLETION_PROBLEMS => 'Возникли проблемы'
    ];

    public function rules()
    {
        return [
            ['completion', 'required', 'skipOnEmpty' => false],
            [
                'completion',
                'in',
                'range' => array_keys(self::COMPLETION),
            ],
            [['comment'], 'trim'],
            ['rating', 'required'],
            [['rating'], 'integer', 'min' => 1, 'max' => 5]
        ];
    }

    public function attributeLabels()
    {
        return [
            'completion' => 'Задание выполнено?',
            'comment' => 'Комментарий',
            'rating' => 'Оценка'
        ];
    }

    public static function getCompletionFields()
    {
        return self::COMPLETION;
    }
}