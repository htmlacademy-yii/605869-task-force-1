<?php
namespace frontend\widgets;

use DateTime;
use frontend\models\User;
use yii\base\Widget;

class LastActivityWidget extends Widget
{
    /**
     * @var User
     */
    public $user;


    public function run()
    {
        $currentTimeStamp = new DateTime();
        $taskCreatedTimeStamp = new DateTime($this->user->last_activity_datetime);
        $dateInterval = $currentTimeStamp->diff($taskCreatedTimeStamp);

        return $this->render('last-activity-widget-view', ['dateInterval' => $dateInterval]);
    }
}
