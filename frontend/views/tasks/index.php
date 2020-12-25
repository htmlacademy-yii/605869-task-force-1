<?php
/* @var $this yii\web\View */
/* @var $filters TaskFiltersForm*/
/* @var $tasks Task[] */

use frontend\models\Task;
use frontend\models\TaskFiltersForm;
use frontend\widgets\EstimatedTimeWidget;
    use yii\helpers\BaseUrl;
    use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Task Force';
?>

<section class="new-task">
    <div class="new-task__wrapper">
        <h1>Новые задания</h1>
        <?php foreach ($tasks as $task): ?>
            <div class="new-task__card">
                <div class="new-task__title">
                    <a href="<?= BaseUrl::to(['tasks/view/', 'id' => $task->id]); ?>" class="link-regular"><h2><?= $task->name; ?></h2></a>
                    <a  class="new-task__type link-regular" href="#"><p><?= $task->category->name; ?></p></a>
                </div>
                <div class="new-task__icon new-task__icon--<?= $task->category->icon; ?>"></div>
                <p class="new-task_description"><?= $task->description; ?></p>
                <b class="new-task__price new-task__price--<?= $task->category->icon; ?>"><?= $task->budget; ?><b> ₽</b></b>
                <p class="new-task__place"><?= $task->address; ?></p>
                <span class="new-task__time"><?= EstimatedTimeWidget::widget(['task'=>$task]); ?></span>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="new-task__pagination">
        <ul class="new-task__pagination-list">
            <li class="pagination__item"><a href="#"></a></li>
            <li class="pagination__item pagination__item--current"><a>1</a></li>
            <li class="pagination__item"><a href="#">2</a></li>
            <li class="pagination__item"><a href="#">3</a></li>
            <li class="pagination__item"><a href="#"></a></li>
        </ul>
    </div>
</section>
<section  class="search-task">
    <div class="search-task__wrapper">
        <?php $form = ActiveForm::begin([
            'options'=>[
                'class'=>'search-task__form',
                'name'=>'test',
                'method'=>'post'
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
                ->checkBox(['class' => 'visually-hidden checkbox__input', 'uncheck' => null],false);
            ?>
        </fieldset>
        <?= $form->field($filters, 'dateInterval', [
            'template' => "{label}\n{input}",
            'labelOptions' => ['for' => '103', 'class' => 'search-task__name'],
            'inputOptions' => ['id' => '103', 'class' => 'multiple-select input', 'size' => 1,]
        ])->dropdownList($filters->getDateIntervalList(), ['prompt' => 'За всё время']);
        ?>
        <?= $form->field($filters,'search',['template' => "{label}\n{input}"])
            ->label(null,['class' => 'search-task__name'])
            ->input('search',['class' => 'input-middle input', 'placeholder' => '']);
        ?>

        <?= Html::submitButton('Искать', ['class' => 'button'])?>

        <?php ActiveForm::end(); ?>
    </div>
</section>
