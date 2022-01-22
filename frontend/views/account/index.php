<?php

    use frontend\models\AccountForm;
    use frontend\models\Category;
    use frontend\models\City;
    use frontend\models\User;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\widgets\Pjax;

    /**
     * @var AccountForm $model
     * @var City $cities
     * @var User $user
     */

    $this->title = 'Настройки | ' . Yii::$app->user->getIdentity()->name;
?>

<section class="account__redaction-wrapper">
    <h1>Редактирование настроек профиля</h1>
    <?php
        $form = ActiveForm::begin(
            [
                'method' => 'POST',
                'id' => 'accont',
                'action' => ['account/index'],
                'options' => ['enctype' => 'multipart/form-data'],
                'fieldConfig' => [
                    'options' => ['tag' => false],
                    'errorOptions' => ['style' => 'color: #FF116E']
                ],
                'enableClientScript' => false,
            ]
        ) ?>
    <div class="account__redaction-section">
        <h3 class="div-line">Настройки аккаунта</h3>
        <div class="account__redaction-section-wrapper">
            <div class="account__redaction-avatar">
                <?= Html::img(Yii::$app->user->identity->getAvatar(), ['width' => '156', 'height' => '156']); ?>
                <?= $form->field($model, 'avatarUpload', [
                    'template' => "<div class='account__redaction-avatar'>" . " {label}{input}<span>{error}</span> </div>",
                    'labelOptions' => [
                        'class' => 'link-regular',
                    ]
                ])->fileInput(
                    [
                        'id' => 'upload-avatar'
                    ]
                ) ?>
            </div>
            <div class="account__redaction">
                <div class="account__input account__input--name">
                    <?= $form->field($model, 'name', ['options' => ['tag' => false]])
                        ->input(
                            'text',
                            [
                                'class' => 'input textarea',
                                'placeholder' => 'Укажите имя'
                            ]
                        ); ?>
                </div>
                <div class="account__input account__input--email">
                    <?= $form->field($model, 'email', ['options' => ['tag' => false]])
                        ->input(
                            'text',
                            [
                                'class' => 'input textarea',
                                'placeholder' => 'Укажите почту'
                            ]
                        ); ?>
                </div>
                <div class="account__input account__input--name">
                    <?= $form->field($model, 'city')
                        ->dropDownList(
                            $cities,
                            [
                                'size' => 1,
                                'class' => 'multiple-select input multiple-select-big',
                                'unselect' => null
                            ]
                        ); ?>
                </div>
                <div class="account__input account__input--date">
                    <?= $form->field($model, 'dateBirthday', ['options' => ['tag' => false]])
                        ->input(
                            'date',
                            [
                                'class' => 'input input-middle',
                                'placeholder' => $model->dateBirthday
                            ]
                        ); ?>
                </div>
                <div class="account__input account__input--info">
                    <?= $form->field($model, 'about', ['options' => ['tag' => false]])
                        ->textarea(
                            [
                                'class' => 'input textarea',
                                'rows' => 7,
                                'placeholder' => 'Разместите свой текст',
                            ]
                        )
                    ?>
                </div>
            </div>
        </div>
        <h3 class="div-line">Выберите свои специализации</h3>
        <div class="account__redaction-section-wrapper">
            <div class="search-task__categories account_checkbox--bottom">
                <?= $form->field($model, 'specializations', ['template' => "{input}",])
                    ->checkboxList(
                        Category::getMap(),
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
                    ); ?>
            </div>
        </div>
        <h3 class="div-line">Безопасность</h3>
        <div class="account__redaction-section-wrapper account__redaction">
            <div class="account__input">
                <?= $form->field($model, 'password')
                    ->input(
                        'password',
                        [
                            'class' => 'input textarea',
                            'value' => ''
                        ]
                    ); ?>
            </div>
            <div class="account__input">
                <?= $form->field($model, 'passwordConfirmation')
                    ->input(
                        'password',
                        [
                            'class' => 'input textarea',
                            'value' => ''
                        ]
                    ); ?>
            </div>
        </div>

        <h3 class="div-line">Фото работ</h3>

        <div class="account__redaction-section-wrapper account__redaction">
            <div class="portfolio-list">
                <?php
                    Pjax::begin(['id' => 'photoList']); ?>
                <?php
                    if (!empty($user->photos)): ?>
                        <?php
                        foreach ($user->photos as $img): ?>
                            <div class="user-work-photo"
                                 style="background-image: url('/uploads/photo/<?= $img->name; ?>')">
                                <a href="/account/delete-photo?id=<?= $img->id; ?>" class="drop-image-btn btn btn-xs button">Удалить</a>
                            </div>
                        <?php
                        endforeach; ?>
                    <?php
                    endif; ?>
                <?php
                    Pjax::end() ?>
            </div>

            <span id="dropzone">Выбрать фотографии</span>
        </div>

        <h3 class="div-line">Контакты</h3>
        <div class="account__redaction-section-wrapper account__redaction">
            <div class="account__input">
                <?= $form->field($model, 'phone', ['options' => ['tag' => false]])
                    ->input(
                        'tel',
                        [
                            'class' => 'input textarea',
                            'placeholder' => 'Укажите номер'
                        ]
                    ); ?>
            </div>
            <div class="account__input">
                <?= $form->field($model, 'skype', ['options' => ['tag' => false]])
                    ->input(
                        'text',
                        [
                            'class' => 'input textarea',
                            'placeholder' => 'Укажите логин пользователя'
                        ]
                    ); ?>
            </div>
            <div class="account__input">
                <?= $form->field($model, 'telegram', ['options' => ['tag' => false]])
                    ->input(
                        'text',
                        [
                            'class' => 'input textarea',
                            'placeholder' => 'Укажите логин пользователя'
                        ]
                    ); ?>
            </div>
        </div>
        <h3 class="div-line">Настройки сайта</h3>
        <h4>Уведомления</h4>
        <div class="account__redaction-section-wrapper account_section--bottom">
            <div class="search-task__categories account_checkbox--bottom">
                <?= $form->field($model, 'showNewMessages',
                                 [
                                     'options' => ['tag' => false],
                                     'template' => '{input}{label}'
                                 ]
                )->checkbox(
                    ['class' => 'visually-hidden checkbox__input'],
                    false
                ); ?>

                <?= $form->field($model, 'showActionsOfTask',
                                 [
                                     'options' => ['tag' => false],
                                     'template' => '{input}{label}'
                                 ]
                )->checkbox(
                    ['class' => 'visually-hidden checkbox__input'],
                    false
                ); ?>

                <?= $form->field($model, 'showNewReview',
                                 [
                                     'options' => ['tag' => false],
                                     'template' => '{input}{label}'
                                 ]
                )->checkbox(
                    ['class' => 'visually-hidden checkbox__input'],
                    false
                ); ?>

            </div>
            <div class="search-task__categories account_checkbox account_checkbox--secrecy">

                <?= $form->field($model, 'showMyContactsCustomer',
                                 [
                                     'options' => ['tag' => false],
                                     'template' => '{input}{label}'
                                 ]
                )->checkbox(
                    ['class' => 'visually-hidden checkbox__input'],
                    false
                ); ?>

                <?= $form->field($model, 'hideAccount',
                                 [
                                     'options' => ['tag' => false],
                                     'template' => '{input}{label}'
                                 ]
                )->checkbox(
                    ['class' => 'visually-hidden checkbox__input'],
                    false
                ); ?>

            </div>
        </div>
    </div>

    <?= Html::button('Сохранить изменения', ['type' => 'submit', 'class' => 'button']); ?>
    <?php
        $form::end() ?>
</section>
