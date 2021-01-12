<?php

/* @var $this yii\web\View */
/* @var $users User */
/* @var $cities City */
/* @var $model UserFiltersForm */
/* @var array $cityList   */

use frontend\models\City;
use frontend\models\User;
use frontend\models\UserFiltersForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
	
	$formConfig = [
    'method' => 'post',
    'options' => [
        'class' => 'registration__user-form form-create',
    ],
];

?>

<section class="registration__user">
    <h1>Регистрация аккаунта</h1>
    <div class="registration-wrapper">
        <?php $form = ActiveForm::begin($formConfig); ?>
        <label for="16">Электронная почта</label>
        <?= $form->field($model, 'email', [
                'inputOptions' => [
                    'class' => 'input textarea',
                    'rows' => '1',
                    'id' => '16',
                    'placeholder' => 'kumarm@mail.ru',
                    ]
        ])->textInput(); ?>
        <span>Введите валидный адрес электронной почты</span>
        <label for="17">Ваше имя</label>
        <?= $form->field($model, 'name', [
                'inputOptions' => [
                    'class' => 'input textarea',
                    'rows' => '1',
                    'id' => '17',
                    'placeholder' => 'Мамедов Кумар',
                ]
        ])->textInput(); ?>
        <span>Введите ваше имя и фамилию</span>
        <label for="18">Город проживания</label>
        <?= $form->field($model, 'city',  [
                'inputOptions' => [
                    'class' => 'multiple-select input town-select registration-town',
                    'size' => '1',
                    'id' => '18',
                ]
        ])->dropDownList($cityList); ?>
        <span>Укажите город, чтобы находить подходящие задачи</span>
        <label class="input-danger" for="19">Пароль</label>
        <?= $form->field($model, 'password', [
                'inputOptions' => ['class' => 'input textarea',
                'id' => '19',
                ],
       ])->passwordInput(); ?>
        <span>Длина пароля от 8 символов</span>
        <?= Html::button('Создать аккаунт', ['class' => 'button button__registration', 'type' => 'submit']); ?>
        <?php $form = ActiveForm::end(); ?>
    </div>
</section>
