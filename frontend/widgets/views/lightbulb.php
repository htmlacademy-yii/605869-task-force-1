<?php
/* @var $this \yii\web\View */

/* @var array $notifications */

use yii\helpers\BaseUrl;
use yii\helpers\Html;

?>

<div class="header__lightbulb"></div>
<div class="lightbulb__pop-up">
    <?php if ($notifications): ?>
        <h3>Новые события</h3>

        <?php foreach ($notifications as $notification): ?>
            <p class="lightbulb__new-task lightbulb__new-task--<?= Html::encode($notification->icon); ?>">
                <?= Html::encode($notification->description); ?>
                <a href="<?= BaseUrl::to(['tasks/view/', 'id' => $notification->task_id]); ?>" class="link-regular">
                    «<?= Html::encode($notification->title); ?>»</a>
            </p>
        <?php endforeach; ?>
    <?php else: ?>
        <h3>Новые события не найдены</h3>
    <?php endif; ?>
</div>
