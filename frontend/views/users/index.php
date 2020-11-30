<?php
    /* @var $this yii\web\View */
    /* @var UserFiltersForm $filters */
    /**
     * @var $users User[]
     */

    use frontend\models\User;
    use frontend\models\UserFiltersForm;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;

?>

<section class="user__search">
    <div class="user__search-link">
        <p>Сортировать по:</p>
        <ul class="user__search-list">
            <li class="user__search-item user__search-item--current">
                <a href="#" class="link-regular">Рейтингу</a>
            </li>
            <li class="user__search-item">
                <a href="#" class="link-regular">Числу заказов</a>
            </li>
            <li class="user__search-item">
                <a href="#" class="link-regular">Популярности</a>
            </li>
        </ul>
    </div>

    <?php foreach ($users as $user): ?>
    <div class="content-view__feedback-card user__search-wrapper">
        <div class="feedback-card__top">
            <div class="user__search-icon">
                <a href="#"><img src="<?= $user->getAvatar(); ?>" width="65" height="65" alt="аватар" /></a>
                <span><?= count($user->executedTasks); ?> заданий</span>
                <span>6 отзывов</span>
            </div>
            <div class="feedback-card__top--name user__search-card">
                <p class="link-name"><a href="#" class="link-regular"><?= $user->name; ?></a></p>
                <span></span><span></span><span></span><span></span><span class="star-disabled"></span>
                <b>4.25</b>
                <p class="user__search-content"><?= $user->getProfiles()->about ?? null; ?></p>
            </div>
            <span class="new-task__time">Был на сайте 25 минут назад</span>
        </div>
        <div class="link-specialization user__search-link--bottom">
            <?php foreach ($user->specializations as $item): ?>
                <a href="#" class="link-regular"><?= $item->category->name; ?></a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endforeach; ?>

</section>
<section  class="search-task">
    <div class="search-task__wrapper">
        <?php $form = ActiveForm::begin(
                [
                        'options'=>
                            [
                                'class'=>'search-task__form',
                                'name'=>'users',
                                'method'=>'post'
                            ], 'fieldConfig'=>
                        [
                            'template' => "{input}\n{label}",
                            'options' => ['tag' => false]
                        ]
                ]);
        ?>

            <fieldset class="search-task__categories">
                <legend>Категории</legend>
                <?= $form->field($filters, 'categories', ['template' => "{input}",])
                    ->checkboxList($filters->getCategoriesList(),
                        ['tag' => false,
                            'item' => function ($index, $label, $name, $checked, $value)
                            {
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
                            ['class' => 'visually-hidden checkbox__input','uncheck' => null],
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
                            ['class' => 'visually-hidden checkbox__input','uncheck' => null],
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

        <?= Html::submitButton('Искать', ['class' => 'button'])?>

        <?php ActiveForm::end(); ?>
    </div>
</section>
