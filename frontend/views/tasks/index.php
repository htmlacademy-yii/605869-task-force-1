<?php

use frontend\models\Task;
use frontend\models\TaskFiltersForm;
use frontend\widgets\EstimatedTimeWidget;
use yii\data\ActiveDataProvider;
use yii\helpers\BaseUrl;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;

/**
 * @var yii\web\View $this
 * @var TaskFiltersForm $filters
 * @var ActiveDataProvider $dataProvider
 * @var Task $task
 */

$this->title = 'Задания - Task Force';

?>

<section class="new-task">
    <div class="new-task__wrapper">
        <h1>Новые задания</h1>
        <?php
        foreach ($dataProvider->getModels() as $task): ?>
            <div class="new-task__card">
                <div class="new-task__title">
                    <a href="<?= BaseUrl::to(['tasks/view/', 'id' => $task->id]); ?>" class="link-regular">
                        <h2><?= Html::encode($task->name); ?></h2></a>
                    <a class="new-task__type link-regular" href="#"><p><?= Html::encode($task->category->name); ?></p>
                    </a>
                </div>
                <div class="new-task__icon new-task__icon--<?= Html::encode($task->category->icon); ?>"></div>
                <p class="new-task_description"><?= Html::encode($task->description); ?></p>
                <b class="new-task__price new-task__price--<?= Html::encode($task->category->icon); ?>"><?= Html::encode($task->budget); ?>
                    <b>
                        ₽</b></b>
                <p class="new-task__place"><?= Html::encode($task->address); ?></p>
                <span class="new-task__time"><?= EstimatedTimeWidget::widget(['task' => $task]); ?></span>
            </div>
        <?php
        endforeach; ?>
    </div>
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
            'options' => [
                'class' => 'search-task__form',
                'name' => 'test',

            ],
            'fieldConfig' => [
                'template' => "{input}\n{label}",
                'options' => ['tag' => false]
            ]
        ]);
        ?>
        <fieldset class="search-task__categories">
            <legend>Категории</legend>
            <?= $form->field($filters, 'categories', ['template' => "{input}",])
                ->checkboxList($filters->getCategoriesList(), [
                    'tag' => false,
                    'item' => function ($index, $label, $name, $checked, $value) {
                        return Html::checkbox($name, $checked, [
                                'id' => $value,
                                'class' => 'visually-hidden checkbox__input',
                                'value' => $value
                            ]) . Html::label($label, $value);
                    }
                ]);
            ?>
        </fieldset>
        <fieldset class="search-task__categories">
            <legend>Дополнительно</legend>
            <?= $form->field($filters, 'withoutReplies')
                ->checkbox(['class' => 'visually-hidden checkbox__input', 'uncheck' => null], false);
            ?>
            <?= $form->field($filters, 'isRemote')
                ->checkBox(['class' => 'visually-hidden checkbox__input', 'uncheck' => null], false);
            ?>
        </fieldset>
        <?= $form->field($filters, 'dateInterval', [
            'template' => "{label}\n{input}",
            'labelOptions' => ['for' => '103', 'class' => 'search-task__name'],
            'inputOptions' => ['id' => '103', 'class' => 'multiple-select input', 'size' => 1,]
        ])->dropdownList($filters->getDateIntervalList(), ['prompt' => 'За всё время']);
        ?>
        <?= $form->field($filters, 'search', ['template' => "{label}\n{input}"])
            ->label(null, ['class' => 'search-task__name'])
            ->input('search', ['class' => 'input-middle input', 'placeholder' => '']);
        ?>

        <?= Html::submitButton('Искать', ['class' => 'button']) ?>

        <?php
        ActiveForm::end(); ?>
    </div>
</section>
