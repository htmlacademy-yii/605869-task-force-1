<?php

use frontend\models\City;
use frontend\models\RegistrationForm;
use frontend\models\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var User $users
 * @var City $cities
 * @var RegistrationForm $model
 */

?>

<section class="registration__user">
    <h1>Регистрация аккаунта</h1>
    <div class="registration-wrapper">
        <?php
        $form = ActiveForm::begin(
            [
                'enableClientValidation' => true,
                'fieldConfig' => [
                    'template' => "</br>{label}</br>{input}</br>{hint}</br>{error}",
                    'inputOptions' => ['class' => 'input textarea input-wide'],
                    'errorOptions' => ['tag' => 'span', 'class' => 'input-error'],
                    'hintOptions' => ['tag' => 'span'],
                ],
                'options' => [
                    'class' => 'registration__user-form form-create',
                ]
            ]
        ); ?>
        <?= $form->field($model, 'email')
            ->textInput(
                [
                    'class' => 'input textarea',
                    'rows' => '1',
                    'placeholder' => 'login@mail.ru',
                ]
            )
            ->label('Электронная почта')
            ->hint('Введите валидный адрес электронной почты');

        ?>

        <?= $form->field($model, 'name')
            ->textInput(
                [
                    'class' => 'input textarea',
                    'rows' => '1',
                    'placeholder' => 'Имя и Фамилия',
                ]
            )
            ->label('Ваше имя')
            ->hint('Введите ваше имя и фамилию');
        ?>

        <?= $form->field($model, 'address',
            ['template' => "{label}\n{input}\n{hint}"]
        )
            ->textInput(
                [
                    'id' => 'address',
                    'class' => 'input-navigation input-middle input address'
                ]
            )->hint('Укажите город, чтобы находить подходящие задачи') ?>

        <?= $form->field(
            $model,
            'password',
            [
                'inputOptions' => [
                    'class' => 'input textarea',
                    'id' => '19',
                ],
            ]
        )->passwordInput()
            ->label('Пароль')
            ->hint('Длина пароля от 8 символов');
        ?>

        <?= Html::button('Создать аккаунт', ['class' => 'button button__registration', 'type' => 'submit']); ?>
        <?= $form->field($model, 'lat')->hiddenInput(['id' => 'lat'])->label(false); ?>
        <?= $form->field($model, 'long')->hiddenInput(['id' => 'long'])->label(false); ?>
        <?= $form->field($model, 'city')->hiddenInput(['id' => 'city'])->label(false); ?>
        <?= $form->field($model, 'village')->hiddenInput(['id' => 'village'])->label(false); ?>
        <?= $form->field($model, 'kladr')->hiddenInput(['id' => 'kladr'])->label(false); ?>
        <?php
        $form = ActiveForm::end(); ?>
    </div>
</section>