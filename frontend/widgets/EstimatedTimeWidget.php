<?php
namespace frontend\widgets;

use frontend\models\Task;
use yii\base\Widget;

class EstimatedTimeWidget extends Widget
{
    /**
     * @var Task
     */
    public $task;


    public function run()
    {
        $currentTimeStamp = new DateTime();
        $taskCreatedTimeStamp = new DateTime($this->task->dt_add);
        $dateInterval = $currentTimeStamp->diff($taskCreatedTimeStamp);

        return $this->render('estimated-time-widget-view', ['dateInterval' => $dateInterval]);
    }
}