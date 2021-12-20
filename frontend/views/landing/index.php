<?php

    use frontend\models\Task;
    use frontend\widgets\EstimatedTimeWidget;
    use yii\helpers\StringHelper;
    use common\models\LoginForm;
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;

    /**
     * @var Task[] $tasks
     * @var LoginForm $model
     */

    $this->title = 'Landing - Task Force';
?>

<div class="landing-container">
    <div class="landing-top">
        <h1>Работа для всех.<br>Найди исполнителя на любую задачу.</h1>
        <p>Сломался кран на кухне? Надо отправить документы? Нет времени самому гулять с собакой?
            У нас вы быстро найдёте исполнителя для любой жизненной ситуации?<br>
            Быстро, безопасно и с гарантией. Просто, как раз, два, три. </p>
        <a href="<?= Yii::$app->urlManager->createUrl(['registration/index']); ?>">
            <button class="button">Создать аккаунт</button>
        </a>
    </div>
    <div class="landing-center">
        <div class="landing-instruction">
            <div class="landing-instruction-step">
                <div class="instruction-circle circle-request"></div>
                <div class="instruction-description">
                    <h3>Публикация заявки</h3>
                    <p>Создайте новую заявку.</p>
                    <p>Опишите в ней все детали
                        и стоимость работы.</p>
                </div>
            </div>
            <div class="landing-instruction-step">
                <div class="instruction-circle  circle-choice"></div>
                <div class="instruction-description">
                    <h3>Выбор исполнителя</h3>
                    <p>Получайте отклики от мастеров.</p>
                    <p>Выберите подходящего<br>
                        вам исполнителя.</p>
                </div>
            </div>
            <div class="landing-instruction-step">
                <div class="instruction-circle  circle-discussion"></div>
                <div class="instruction-description">
                    <h3>Обсуждение деталей</h3>
                    <p>Обсудите все детали работы<br>
                        в нашем внутреннем чате.</p>
                </div>
            </div>
            <div class="landing-instruction-step">
                <div class="instruction-circle circle-payment"></div>
                <div class="instruction-description">
                    <h3>Оплата&nbsp;работы</h3>
                    <p>По завершении работы оплатите
                        услугу и закройте задание</p>
                </div>
            </div>
        </div>
        <div class="landing-notice">
            <div class="landing-notice-card card-executor">
                <h3>Исполнителям</h3>
                <ul class="notice-card-list">
                    <li>
                        Большой выбор заданий
                    </li>
                    <li>
                        Работайте где удобно
                    </li>
                    <li>
                        Свободный график
                    </li>
                    <li>
                        Удалённая работа
                    </li>
                    <li>
                        Гарантия оплаты
                    </li>
                </ul>
            </div>
            <div class="landing-notice-card card-customer">
                <h3>Заказчикам</h3>
                <ul class="notice-card-list">
                    <li>
                        Исполнители на любую задачу
                    </li>
                    <li>
                        Достоверные отзывы
                    </li>
                    <li>
                        Оплата по факту работы
                    </li>
                    <li>
                        Экономия времени и денег
                    </li>
                    <li>
                        Выгодные цены
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="landing-bottom">
        <div class="landing-bottom-container">
            <h2>Последние задания на сайте</h2>
            <?php
                foreach ($tasks as $task): ?>
                    <div class="landing-task">
                        <div class="landing-task-top task-<?= $task->category->icon; ?>"></div>
                        <div class="landing-task-description">
                            <h3><a href="#" class="link-regular"><?= StringHelper::truncate($task->name, 20); ?></a>
                            </h3>
                            <p><?= StringHelper::truncate($task->description, 70); ?></p>
                        </div>
                        <div class="landing-task-info">
                            <div class="task-info-left">
                                <p><a href="#" class="link-regular"><?= $task->category->name; ?></a></p>
                                <p><?= EstimatedTimeWidget::widget(['task' => $task]); ?></p>
                            </div>
                            <span><?= $task->budget; ?><b>₽</b></span>
                        </div>
                    </div>
                <?php
                endforeach; ?>
        </div>
        <div class="landing-bottom-container">
            <a href="#" class="header__account-enter open-modal" data-for="enter-form">
                <button type="button" class="button red-button">смотреть все задания</button>
            </a>
        </div>
    </div>
</div>

<!--модальное окно входа на сайт-->
<section class="modal enter-form form-modal" id="enter-form">
    <h2>Вход на сайт</h2>
    <?php
        $form = ActiveForm::begin(); ?>
    <p>
        <?= $form->field($model, 'email')->textInput(); ?>
    </p>
    <p>
        <?= $form->field($model, 'password')->passwordInput(); ?>
    </p>
    <?= Html::submitButton('Войти', ['class' => 'button']); ?>

    <?php
        ActiveForm::end(); ?>

    <?= yii\authclient\widgets\AuthChoice::widget(
        [
            'baseAuthUrl' => ['site/auth'],
            'popupMode' => false,
        ]
    ) ?>

    <button class="form-modal-close" type="button">Закрыть</button>
</section>
