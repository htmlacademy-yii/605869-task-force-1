<?php

    use frontend\models\City;
    use frontend\models\CreateTaskForm;
    use yii\widgets\ActiveForm;

    /**
     * @var CreateTaskForm $model
     * @var City $cities
     * @var array $categoryList
     */

    $this->title = 'Создать новую задачу - TaskForce';
?>

<section class="create__task">
    <h1>Публикация нового задания</h1>
    <div class="create__task-main">

        <?php
            $form = ActiveForm::begin(
                [
                    'id' => 'task-form',
                    'enableClientScript' => false,
                    'options' => [
                        'enctype' => 'multipart/form-data',
                        'class' => 'create__task-form form-create',
                        'name' => $model->formName()
                    ],
                    'fieldConfig' => [
                        'options' => ['tag' => false],
                        'errorOptions' => ['tag' => 'span'],
                        'hintOptions' => ['tag' => 'span'],
                    ]
                ]
            ); ?>

        <?= $form->field($model, 'name')
            ->textInput(
                [
                    'class' => 'input textarea',
                    'rows' => 1,
                    'placeholder' => 'Разместите свой текст',
                    'spellcheck' => 'false'
                ]
            )
            ->hint('Кратко опишите суть работы');
        ?>

        <?= $form->field($model, 'description')
            ->textarea(
                [
                    'class' => 'input textarea',
                    'rows' => 7,
                    'placeholder' => 'Разместите свой текст',
                    'spellcheck' => 'false'
                ]
            )
            ->hint(
                'Укажите все пожелания и детали, чтобы исполнителям было проще соориентироваться'
            );
        ?>

        <?= $form->field(
            $model,
            'categoryId',
            [
                'inputOptions' => [
                    'class' => 'multiple-select input multiple-select-big',
                    'size' => '1',
                ]
            ]
        )->dropDownList($categoryList)->label('Категория')
            ->hint('Выберите категорию');
        ?>

        <label>Файлы</label>
        <span>Загрузите файлы, которые помогут исполнителю лучше выполнить или оценить работу</span>

        <?= $form->field(
            $model,
            'files[]',
            [
                'template' => "{input}\n{label}",
                'options' =>
                    [
                        'tag' => 'div',
                        'class' => 'create__file'
                    ],
            ]
        )
            ->fileInput(
                [
                    'class' => 'dropzone',
                    'multiple' => 'true',
                ]
            )
            ->label('Добавить новый файл');
        ?>

        <input type="hidden" name="_csrf-frontend" value="<?= Yii::$app->request->getCsrfToken() ?>"/>

        <?= $form->field($model, 'address',
                         ['template' => "{label}\n{input}"]
        )
            ->textInput(
                [
                    'id' => 'address',
                    'class' => 'input-navigation input-middle input address'
                ]
            ) ?>
        <span>Укажите адрес исполнения, если задание требует присутствия</span>

        <div class="create__price-time">
            <?= $form->field(
                $model,
                'budget',
                [
                    'options' => [
                        'tag' => 'div',
                        'class' => 'create__price-time--wrapper'
                    ]
                ]
            )
                ->label('Бюджет')
                ->textInput(
                    [
                        'rows' => 1,
                        'placeholder' => '1000',
                        'class' => 'input textarea input-money'
                    ]
                )
                ->hint('Не заполняйте для оценки исполнителем');
            ?>

            <?= $form->field(
                $model,
                'expire',
                [
                    'options' => [
                        'tag' => 'div',
                        'class' => 'create__price-time--wrapper'
                    ]
                ]
            )
                ->label('Срок исполнения')
                ->input(
                    'date',
                    [
                        'rows' => 1,
                        'class' => 'input-middle input',
                    ]
                )
                ->hint('Укажите крайний срок исполнения');
            ?>
        </div>
        <input class="button" type="submit" value="Опубликовать"/>

        <?php
            ActiveForm::end(); ?>

        <div class="create__warnings">
            <div class="warning-item warning-item--advice">
                <h2>Правила хорошего описания</h2>
                <h3>Подробности</h3>
                <p>Друзья, не используйте случайный<br>
                    контент – ни наш, ни чей-либо еще. Заполняйте свои
                    макеты, вайрфреймы, мокапы и прототипы реальным
                    содержимым.</p>
                <h3>Файлы</h3>
                <p>Если загружаете фотографии объекта, то убедитесь,
                    что всё в фокусе, а фото показывает объект со всех
                    ракурсов.</p>
            </div>
            <?php
                if ($model->errors): ?>
                    <div class="warning-item warning-item--error">
                        <h2>Ошибки заполнения формы</h2>
                        <?php
                            foreach ($model->getErrors() as $attribute => $messages): ?>
                                <h3><?= $model->attributeLabels()[$attribute]; ?></h3>
                                <p>
                                    <?php
                                        for ($i = 0; $i < count($messages); $i++): ?>

                                            <?= $messages[$i]; ?>

                                            <?= ($i < count($messages) - 1) ? '<br>' : ''; ?>

                                        <?php
                                        endfor; ?>
                                </p>
                            <?php
                            endforeach; ?>
                    </div>
                <?php
                endif; ?>
        </div>
    </div>
</section>
