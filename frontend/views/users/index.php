<?php

use frontend\helpers\WordHelper;
use frontend\models\SiteSettings;
use frontend\models\User;
use frontend\models\UserFiltersForm;
use frontend\widgets\LastActivityWidget;
use frontend\widgets\StarRatingWidget;
use yii\data\ActiveDataProvider;
use yii\helpers\BaseUrl;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;

/**
 * @var yii\web\View $this
 * @var UserFiltersForm $filters
 * @var User $user
 * @var ActiveDataProvider $dataProvider
 */


$this->title = 'Исполнители - Task Force';

?>

<section class="user__search">
    <?php
    foreach ($dataProvider->getModels() as $user): ?>
        <?php
        if ($user->siteSettings->hide_account == SiteSettings::DISABLED): ?>
            <div class="content-view__feedback-card user__search-wrapper">
                <div class="feedback-card__top">
                    <div class="user__search-icon">
                        <a href="<?= BaseUrl::to(['users/view/', 'id' => $user->id]); ?>"><img
                                    src="<?= $user->getAvatar(); ?>" width="65" height="65" alt="аватар"/></a>
                        <?php
                        if ($user->executedTasks): ?>
                            <span><?= count($user->executedTasks); ?> <?= WordHelper::pluralForm(
                                    $user->getExecutedTasks()->count(),
                                    'задание',
                                    'задания',
                                    'заданий'
                                ); ?></span>
                        <?php
                        else: ?>
                            <span><?= 'нет заданий'; ?></span>
                        <?php
                        endif; ?>

                        <?php
                        if (count($user->opinions)): ?>

                            <?php
                            if (count($user->opinions) == 3 || count($user->opinions) == 4): ?>
                                <span><?= count($user->opinions); ?> отзыва</span>
                            <?php
                            else: ?>
                                <span><?= count($user->opinions); ?> <?= WordHelper::pluralForm(
                                        $user->getExecutedTasks()->count(),
                                        'отзыв',
                                        'отзыва',
                                        'отзывов'
                                    ); ?></span>
                            <?php
                            endif; ?>

                        <?php
                        else: ?>
                            <span><?= 'нет отзывов'; ?></span>
                        <?php
                        endif; ?>
                    </div>
                    <div class="feedback-card__top--name user__search-card">
                        <p class="link-name"><a href="<?= BaseUrl::to(['users/view/', 'id' => $user->id]); ?>"
                                                class="link-regular"><?= Html::encode($user->name); ?></a></p>
                        <!--                    звезды рейтинга-->
                        <?= StarRatingWidget::widget(['user' => $user]); ?>
                        <?php
                        if ($user->getRating()): ?>
                            <b><?= round($user->getRating(), 2); ?></b>
                        <?php
                        endif; ?>
                        <p class="user__search-content"><?= Html::encode($user->getProfiles()->about) ?? null; ?></p>
                    </div>
                    <span class="new-task__time">
                        <?php
                        if ($user->isNotOnline()): ?>
                            Был на сайте <?= LastActivityWidget::widget(['user' => $user]); ?>
                        <?php
                        else: ?>
                            Сейчас онлайн
                        <?php
                        endif; ?>
            </span>
                </div>
                <div class="link-specialization user__search-link--bottom">
                    <?php
                    foreach ($user->specializations as $item): ?>
                        <a href="#" class="link-regular"><?= Html::encode($item->category->name); ?></a>
                    <?php
                    endforeach; ?>
                </div>
            </div>
        <?php
        endif; ?>
    <?php
    endforeach; ?>
    <div class="new-task__pagination">
        <?php
        echo LinkPager::widget([
            'pagination' => $dataProvider->getPagination(),
            //Css option for container
            'options' => ['class' => 'new-task__pagination-list'],
            //Current Active option value
            'activePageCssClass' => 'pagination__item--current',
            'pageCssClass' => 'pagination__item',
            'nextPageCssClass' => 'pagination__item',
            'prevPageCssClass' => 'pagination__item',
            'nextPageLabel' => '',
            'prevPageLabel' => '',
        ]); ?>
    </div>
</section>
<section class="search-task">
    <div class="search-task__wrapper">
        <?php
        $form = ActiveForm::begin([
            'method' => 'get',
            'options' =>
                [
                    'class' => 'search-task__form',
                    'name' => 'users',

                ],
            'fieldConfig' =>
                [
                    'template' => "{input}\n{label}",
                    'options' => ['tag' => false]
                ]
        ]); ?>
        <fieldset class="search-task__categories">
            <legend>Категории</legend>
            <?= $form->field($filters, 'categories', ['template' => "{input}",])
                ->checkboxList($filters->getCategoriesList(),
                    [
                        'tag' => false,
                        'item' => function ($index, $label, $name, $checked, $value) {
                            return Html::checkbox($name, $checked,
                                    [
                                        'id' => $index,
                                        'class' => 'visually-hidden checkbox__input',
                                        'value' => $value
                                    ]
                                ) . Html::label($label, $index);
                        }
                    ]
                );
            ?>
        </fieldset>
        <fieldset class="search-task__categories">
            <legend>Дополнительно</legend>
            <?= $form->field($filters, 'isAvailable')
                ->checkbox(
                    ['class' => 'visually-hidden checkbox__input', 'uncheck' => null],
                    $enclosedByLabel = false
                );
            ?>

            <?= $form->field($filters, 'isOnLine')
                ->checkbox(
                    ['class' => 'visually-hidden checkbox__input', 'uncheck' => null],
                    $enclosedByLabel = false
                );
            ?>

            <?= $form->field($filters, 'isFeedbacks')
                ->checkbox(
                    ['class' => 'visually-hidden checkbox__input', 'uncheck' => null],
                    $enclosedByLabel = false
                );
            ?>

            <?= $form->field($filters, 'isFavorite')
                ->checkbox(
                    ['class' => 'visually-hidden checkbox__input', 'uncheck' => null],
                    $enclosedByLabel = false
                );
            ?>
        </fieldset>
        <?= $form->field(
            $filters,
            'search',
            ['template' => "{label}\n{input}"]
        )
            ->label($label = null,
                ['class' => 'search-task__name']
            )
            ->input('search',
                ['class' => 'input-middle input', 'placeholder' => '']
            );
        ?>

        <?= Html::submitButton('Искать', ['class' => 'button']) ?>

        <?php
        ActiveForm::end(); ?>
    </div>
</section>
