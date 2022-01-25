<?php

use frontend\models\Replies;
use frontend\models\Task;
use frontend\widgets\EstimatedTimeWidget;
use frontend\widgets\StarRatingWidget;
use frontend\widgets\TaskActionsWidget;
use frontend\widgets\TimeOnSiteWidget;
use yii\helpers\BaseUrl;
use yii\helpers\Html;
use phpnt\yandexMap\YandexMaps;

/**
 * @var yii\web\View $this
 * @var Task $task
 */

$this->title = 'Задание: ' . Html::encode($task->name);
?>

<section class="content-view">
    <div class="content-view__card">
        <div class="content-view__card-wrapper">
            <div class="content-view__header">
                <div class="content-view__headline">
                    <h1><?= Html::encode($task->name); ?></h1>
                    <span>Размещено в категории
                        <a href="#"
                           class="link-regular"><?= $task->category->name; ?>
                        </a><?= EstimatedTimeWidget::widget(['task' => $task]); ?>
                    </span>
                </div>
                <b class="new-task__price new-task__price--<?= Html::encode($task->category->icon); ?> content-view-price"><?= Html::encode($task->budget); ?>
                    <b> ₽</b></b>
                <div class="new-task__icon new-task__icon--<?= Html::encode($task->category->icon); ?> content-view-icon"></div>
            </div>
            <div class="content-view__description">
                <h3 class="content-view__h3">Общее описание</h3>
                <p><?= Html::encode($task->description); ?></p>
            </div>
            <?php
            if ($task->files): ?>
                <div class="content-view__attach">
                    <h3 class="content-view__h3">Вложения</h3>
                    <?php
                    foreach ($task->files as $attachment): ?>
                        <a href="#"><?= Html::encode($attachment->name); ?>></a>
                    <?php
                    endforeach; ?>
                </div>
            <?php
            endif; ?>
            <div class="content-view__location">
                <h3 class="content-view__h3">Расположение</h3>
                <div class="content-view__location-wrapper">
                    <div class="content-view__map">
                        <?php
                        $items = [
                            [
                                'latitude' => $task->lat,
                                'longitude' => $task->long,
                                'options' => [
                                    'preset' => 'islands#icon',
                                    'iconColor' => '#19a111'
                                ]
                            ],
                        ]; ?>

                        <?= YandexMaps::widget(
                            [
                                'myPlacemarks' => [
                                    [
                                        'latitude' => $task->lat,
                                        'longitude' => $task->long,
                                        'options' => []
                                    ],
                                ],
                                'mapOptions' => [
                                    // центр карты
                                    'center' => [$task->lat, $task->long],
                                    // показывать в масштабе
                                    'zoom' => 13,
                                    // использовать эл. управления
                                    'controls' => ['zoomControl', 'fullscreenControl', 'searchControl'],
                                    'control' => [
                                        'zoomControl' => [
                                            // расположение кнопок управлением масштабом
                                            'top' => 75,
                                            'left' => 5
                                        ],
                                    ],
                                ],
                                // отключить скролл колесиком мыши (по умолчанию true)
                                'disableScroll' => true,
                                // длинна карты (по умолчанию 100%)
                                'windowWidth' => '361px',
                                // высота карты (по умолчанию 400px)
                                'windowHeight' => '292px',
                            ]
                        ); ?>

                    </div>
                    <div class="content-view__address">
                        <span class="address__town"><?= Html::encode($task->address); ?></span>
                        <p></p>
                    </div>
                </div>
            </div>
        </div>

        <?= TaskActionsWidget::widget(['task' => $task]); ?>
    </div>

    <?php
    if (count($task->replies)): ?>
        <div class="content-view__feedback">
            <?php
            if ((int)Yii::$app->user->getId() === (int)$task->customer_id): ?>
                <h2>Отклики <span>(<?= count($task->replies); ?>)</span></h2>
                <?php
                foreach ($task->replies as $reply): ?>

                    <div class="content-view__feedback-wrapper">
                        <div class="content-view__feedback-card">
                            <div class="feedback-card__top">
                                <a href="<?= BaseUrl::to(['users/view/', 'id' => $reply->user->id]); ?>">
                                    <img src="<?= Html::encode($reply->user->getAvatar()); ?>"
                                         width="55" height="55">
                                </a>
                                <div class="feedback-card__top--name">
                                    <p><a href="<?= BaseUrl::to(['users/view/', 'id' => $reply->user_id]) ?>"
                                          class="link-regular"><?= Html::encode($reply->user->name); ?>
                                        </a>
                                    </p>

                                    <!--                    звезды рейтинга-->
                                    <?= StarRatingWidget::widget(['user' => $reply->user]); ?>
                                    <b><?= $reply->user->getRating(); ?></b>
                                </div>
                                <?php
                                if ($reply->dt_add): ?>
                                    <span class="new-task__time">
                                        <?= Yii::$app->formatter->asRelativeTime($reply->dt_add); ?>
                                    </span>
                                <?php
                                endif; ?>
                            </div>
                            <div class="feedback-card__content">
                                <p><?= Html::encode($reply->description); ?></p>
                                <span>
                                    <?= Html::encode($reply->price); ?> ₽
                                </span>
                            </div>

                            <?php
                            if (
                                $task->status_id === Task::STATUS_NEW &&
                                $task->customer_id === Yii::$app->user->getId() &&
                                $reply->status === Replies::STATUS_NEW
                            ): ?>
                                <div class="feedback-card__actions">

                                    <?= Html::a(
                                        'Подтвердить',
                                        [
                                            'tasks/apply',
                                            'id' => $reply->id
                                        ],
                                        ['class' => 'button__small-color request-button button']
                                    ); ?>

                                    <?= Html::a(
                                        'Отказать',
                                        [
                                            'tasks/refuse',
                                            'id' => $reply->task_id,
                                        ],
                                        ['class' => 'button__small-color refusal-button button']
                                    ); ?>

                                </div>
                            <?php
                            endif; ?>
                        </div>
                    </div>
                <?php
                endforeach; ?>
            <?php
            endif; ?>
        </div>
    <?php
    endif; ?>

    <?php
    if ($reply = $task->getReplyByUserId(Yii::$app->user->getId())): ?>
        <!--отображение отклика для автора отклика -->
        <div class="content-view__feedback">
            <div class="content-view__feedback-wrapper">
                <h2>Ваш отклик на задание:</h2>
                <div class="content-view__feedback-card">
                    <div class="feedback-card__top">
                        <a href="<?= BaseUrl::to(['users/view/', 'id' => $reply->user_id]) ?>"><img
                                    src="<?= Html::encode($reply->user->getAvatar()); ?>" width="55"
                                    height="55">
                        </a>
                        <div class="feedback-card__top--name">
                            <p><a href="<?= BaseUrl::to(['users/view/', 'id' => $reply->user->id]) ?>"
                                  class="link-regular"><?= Html::encode($reply->user->name); ?></a></p>
                            <!--                    звезды рейтинга-->
                            <?= StarRatingWidget::widget(['user' => $reply->user]); ?>
                            <b><?= $reply->user->getRating(); ?></b>
                        </div>
                        <?php
                        if ($reply->dt_add): ?>
                            <span class="new-task__time">
                                    <?= Yii::$app->formatter->asRelativeTime($reply->dt_add); ?>
                                </span>
                        <?php
                        endif; ?>
                    </div>
                    <div class="feedback-card__content">
                        <p><?= Html::encode($reply->description); ?></p>
                        <span>
                                <?= ($reply->price) ? Html::encode($reply->price) : Html::encode(
                                    $reply->task->budget
                                ); ?> ₽
                            </span>
                    </div>
                </div>
            </div>
        </div>
    <?php
    endif; ?>

</section>

<section class="connect-desk">
    <div class="connect-desk__profile-mini">
        <div class="profile-mini__wrapper">
            <h3>Заказчик</h3>
            <div class="profile-mini__top">
                <img src="<?= Html::encode($task->customer->getAvatar()); ?>" width="62" height="62"
                     alt="Аватар заказчика">
                <div class="profile-mini__name five-stars__rate">
                    <p><?= Html::encode($task->customer->name); ?></p>
                </div>
            </div>
            <p class="info-customer"><span><?= $task->customer->getTasksCount(); ?> заданий</span>
                <span class="last-"><?= TimeOnSiteWidget::widget(['task' => $task]); ?></span>
            </p>
            <a href="<?= BaseUrl::to(['users/view/', 'id' => $task->customer_id]); ?>" class="link-regular">
                Смотреть профиль
            </a>
        </div>
    </div>
    <div id="chat-container">
        <!--добавьте сюда атрибут task с указанием в нем id текущего задания-->
        <chat class="connect-desk__chat"
              task="<?= $task->id; ?>"
              sender_id="<?= (int)Yii::$app->user->getId(); ?>">
        </chat>
    </div>
</section>
