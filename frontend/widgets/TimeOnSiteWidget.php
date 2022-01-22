<?php
namespace frontend\widgets;

use DateTime;
use frontend\models\Task;
use yii\base\Widget;

class TimeOnSiteWidget extends Widget
{
    /**
     * @var Task
     */
    public $task;


    public function run()
    {
        $currentTimeStamp = new DateTime();
        $taskCreatedTimeStamp = new DateTime($this->task->customer->dt_add);
        $dateInterval = $currentTimeStamp->diff($taskCreatedTimeStamp);

        return $this->render('time-on-site-widget-view', ['dateInterval' => $dateInterval]);
    }
}
