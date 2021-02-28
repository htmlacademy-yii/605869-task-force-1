<?php
/* @var $this yii\web\View */
/* @var $task Task*/
/* @var $pages Task*/


use frontend\models\Task;
use frontend\widgets\EstimatedTimeWidget;
use frontend\widgets\StarRatingWidget;
use frontend\widgets\TimeOnSiteWidget;
use yii\helpers\BaseUrl;
use yii\helpers\Html;

$this->title = 'Task Force';
?>

<section class="content-view">
    <div class="content-view__card">
        <div class="content-view__card-wrapper">
            <div class="content-view__header">
                <div class="content-view__headline">
                    <h1><?= Html::encode($task->name); ?></h1>
                    <span>Размещено в категории<a href="#" class="link-regular"><?= $task->category->name; ?></a><?= EstimatedTimeWidget::widget(['task'=>$task]); ?></span>
                </div>
                <b class="new-task__price new-task__price--<?= $task->category->icon; ?> content-view-price"><?= $task->budget; ?><b> ₽</b></b>
                <div class="new-task__icon new-task__icon--<?= $task->category->icon; ?> content-view-icon"></div>
            </div>
            <div class="content-view__description">
                <h3 class="content-view__h3">Общее описание</h3>
                <p><?= $task->description; ?></p>
            </div>
            <?php if ($task->files): ?>
            <div class="content-view__attach">
            <h3 class="content-view__h3">Вложения</h3>
            <?php foreach ($task->files as $attachment): ?>
            <a href="#"><?= Html::encode($attachment->name); ?>></a>
            <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <div class="content-view__location">
                <h3 class="content-view__h3">Расположение</h3>
                <div class="content-view__location-wrapper">
                    <div class="content-view__map">
                        <a href="#"><img src="./img/map.jpg" width="361" height="292"alt="Москва, Новый арбат, 23 к. 1"></a>
                    </div>
                    <div class="content-view__address">
                        <span class="address__town">Москва</span><br>
                        <span>Новый арбат, 23 к. 1</span>
                        <p>Вход под арку, код домофона 1122</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-view__action-buttons">
            <button class=" button button__big-color response-button open-modal" type="button" data-for="response-form">Откликнуться</button>
            <button class="button button__big-color refusal-button open-modal" type="button" data-for="refuse-form">Отказаться</button>
            <button class="button button__big-color request-button open-modal" type="button" data-for="complete-form">Завершить</button>
        </div>
    </div>
    <?php if (count($task->replies)): ?>
    <div class="content-view__feedback">
        <h2>Отклики <span>(<?= count($task->replies); ?>)</span></h2>
        <div class="content-view__feedback-wrapper">
            <?php foreach ($task->replies as $reply): ?>
                <div class="content-view__feedback-card">
                    <div class="feedback-card__top">
                        <a href="#"><img src="<?= Html::encode($reply->user->getAvatar()); ?>" width="55" height="55"></a>
                        <div class="feedback-card__top--name">
                            <p><a href="<?= BaseUrl::to(['users/view/', 'id' => $reply->user->id]) ?>" class="link-regular"><?= Html::encode($reply->user->name); ?></a></p>
                            <!--                    звезды рейтинга-->
                            <?= StarRatingWidget::widget(['user' => $reply->user]); ?>
                            <b><?= $reply->user->getRating(); ?></b>
                        </div>
                        <?php if ($reply->dt_add): ?>
                        <span class="new-task__time"><?= Yii::$app->formatter->asRelativeTime($reply->dt_add); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="feedback-card__content">
                        <p><?= Html::encode($reply->description); ?></p>
                        <span><?= Html::encode($reply->price); ?> ₽</span>
                    </div>
                    <div class="feedback-card__actions">
                        <a class="button__small-color request-button button" type="button">Подтвердить</a>
                        <a class="button__small-color refusal-button button" type="button">Отказать</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</section>
<section class="connect-desk">
    <div class="connect-desk__profile-mini">
        <div class="profile-mini__wrapper">
            <h3>Заказчик</h3>
            <div class="profile-mini__top">
                <img src="<?= Html::encode($task->customer->getAvatar()); ?>" width="62" height="62" alt="Аватар заказчика">
                <div class="profile-mini__name five-stars__rate">
                    <p><?= Html::encode($task->customer->name); ?></p>
                </div>
            </div>
            <p class="info-customer"><span><?= $task->customer->getTasksCount(); ?> заданий</span>
                <span class="last-"><?= TimeOnSiteWidget::widget(['task'=>$task]); ?></span>
            </p>
            <a href="<?= BaseUrl::to(['users/view/', 'id' => $task->customer_id]); ?>" class="link-regular">Смотреть профиль</a>
        </div>
    </div>
    <div id="chat-container">
        <!--                    добавьте сюда атрибут task с указанием в нем id текущего задания-->
        <chat class="connect-desk__chat"></chat>
    </div>
</section>
