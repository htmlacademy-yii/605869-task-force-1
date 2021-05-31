<?php
namespace frontend\widgets;

use frontend\models\User;
use yii\base\Widget;

class StarRatingWidget extends Widget
{
    /**
     * @var User
     */
    public $user;

    public function run()
    {
        $rating = round($this->user->getRating());

        return $this->render('star-rating-widget-view', ['rating' => $rating]);
    }
}
