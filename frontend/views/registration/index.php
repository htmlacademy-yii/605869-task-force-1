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
		'action' => '/signup/index',
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
	];

?>

<section class="registration__user">
    <h1>Регистрация аккаунта</h1>
    <div class="registration-wrapper">
		<?php $form = ActiveForm::begin($formConfig); ?>
		<?= $form->field($model, 'email')
			->textarea(
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
			->textarea(
				[
					'class' => 'input textarea',
					'rows' => '1',
					'placeholder' => 'Имя и Фамилия',
				]
			)
			->label('Ваше имя')
			->hint('Введите ваше имя и фамилию');
		?>
	
	
	
		<?= $form->field(
			$model,
			'city',
			[
				'inputOptions' => [
					'class' => 'multiple-select input town-select registration-town',
					'size' => '1',
					'id' => '18',
				]
			]
		)->dropDownList($cityList)->label('Город проживания')
			->hint('Укажите город, чтобы находить подходящие задачи');
		?>
        
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
        <?php $form = ActiveForm::end(); ?>
    </div>
</section>
