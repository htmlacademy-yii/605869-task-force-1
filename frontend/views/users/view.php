<?php
    /* @var $this yii\web\View */
    /* @var $user User */

    use frontend\helpers\WordHelper;
    use frontend\models\User;
    use frontend\widgets\LastActivityWidget;
    use frontend\widgets\StarRatingWidget;
    use yii\helpers\BaseUrl;
    use yii\helpers\Html;

    $this->title = 'Исполнитель: ' . Html::encode($user->name);
?>

<section class="content-view">
    <div class="user__card-wrapper">
        <div class="user__card">
            <img src="<?= Html::encode($user->getAvatar()); ?>" width="120" height="120" alt="<?= Html::encode($user->name); ?>: Аватар пользователя">
            <div class="content-view__headline">
                <h1><?= Html::encode($user->name); ?></h1>
                <?php if ($user->getAge()): ?>
                <p><?= $user->getCity(); ?> <?= $user->getAge(); ?> <?= WordHelper::pluralForm($user->getAge(), 'год', 'года', 'лет'); ?></p>
                <?php endif; ?>
                <div class="profile-mini__name five-stars__rate">
<!--                    звезды рейтинга-->
                    <?= StarRatingWidget::widget(['user' => $user]); ?>
                    <?php if ($user->getRating()): ?>
                    <b><?= round($user->getRating(), 2); ?></b>
                    <?php endif; ?>
                </div>
                <?php if ($user->getExecutedTasks()->count()): ?>
                <b class="done-task">Выполнил <?= $user->getExecutedTasks()->count() ; ?>

                    <?= WordHelper::pluralForm($user->getExecutedTasks()->count(), 'заказ', 'заказа', 'заказов'); ?>
                </b>
                <?php endif; ?>
                <?php if (count($user->opinions)): ?>
                <b class="done-review">Получил <?= count($user->opinions); ?>

                    <?= WordHelper::pluralForm(count($user->opinions), 'отзыв', 'отзыва', 'отзывов'); ?>
                </b>
                <?php endif; ?>
            </div>
            <div class="content-view__headline user__card-bookmark user__card-bookmark--current">
                <span>Был на сайте <?= LastActivityWidget::widget(['user'=>$user]); ?></span>
                <a href="#"><b></b></a>
            </div>
        </div>
        <div class="content-view__description">
            <p><?= Html::encode($user->profiles->about); ?></p>
        </div>
        <div class="user__card-general-information">
            <div class="user__card-info">
                <h3 class="content-view__h3">Специализация:</h3>
                <div class="link-specialization">
                    <?php foreach ($user->specializations as $item): ?>
                        <a href="#" class="link-regular"><?= $item->category->name; ?></a>
                    <?php endforeach; ?>
                </div>
                <?php if ($user->profiles->phone || $user->profiles->email || $user->profiles->skype): ?>
                <h3 class="content-view__h3">Контакты</h3>
                <div class="user__card-link">
                    <a class="user__card-link--tel link-regular" href="#"><?= Html::encode($user->profiles->phone); ?></a>
                    <a class="user__card-link--email link-regular" href="#"><?= Html::encode($user->profiles->email); ?></a>
                    <a class="user__card-link--skype link-regular" href="#"><?= Html::encode($user->profiles->skype)?></a>
                </div>
                <?php endif; ?>
            </div>
            <?php if ($user->photos): ?>
            <div class="user__card-photo">
                <h3 class="content-view__h3">Фото работ</h3>
                <?php foreach ($user->photos as $photo):?>
                    <a href="#"><img src="<?= $photo->name; ?>" width="85" height="86" alt="Фото работы"></a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php if (count($user->opinions)): ?>
    <div class="content-view__feedback">
        <h2>Отзывы<span>(<?= count($user->opinions); ?>)</span></h2>
        <div class="content-view__feedback-wrapper reviews-wrapper">
            <?php foreach ($user->opinions as $opinion): ?>
                <div class="feedback-card__reviews">
                    <p class="link-task link">
                        Задание <a href="<?= BaseUrl::to(['tasks/view/', 'id' => $opinion->task->id]); ?>" class="link-regular">«<?= Html::encode($opinion->task->name); ?>»</a>
                    </p>
                    <div class="card__review">
                        <a href="#"><img src="<?= Html::encode($opinion->task->executor->getAvatar()); ?>" width="55" height="54"></a>
                        <div class="feedback-card__reviews-content">
                            <p class="link-name link">
                                <a href="<?= BaseUrl::to(['users/view/', 'id' => $opinion->task->executor->id]); ?>" class="link-regular"><?= Html::encode($opinion->task->executor->name); ?></a>
                            </p>
                            <p class="review-text"><?= Html::encode($opinion->comment); ?></p>
                        </div>
                        <div class="card__review-rate">
                            <p class="five-rate big-rate"><?= Html::encode($opinion->rate); ?><span></span></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</section>
<section class="connect-desk">
    <div class="connect-desk__chat">

    </div>
</section>
