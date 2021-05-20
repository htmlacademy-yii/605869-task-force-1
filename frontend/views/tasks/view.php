<?php
    /* @var $this yii\web\View */

    /* @var $task Task */
    /* @var $strategy Task */
    /* @var $pages Task */
    /* @var $responseTaskForm ResponseTaskForm */
    /* @var $completeTaskForm CompleteTaskForm */
    /* @var $replies Replies */

    /* @var $userId Yii */


    use frontend\models\CompleteTaskForm;
    use frontend\models\Replies;
    use frontend\models\ResponseTaskForm;
    use frontend\models\Task;
    use frontend\models\User;
    use frontend\widgets\EstimatedTimeWidget;
    use frontend\widgets\StarRatingWidget;
    use frontend\widgets\TimeOnSiteWidget;
    use yii\helpers\BaseUrl;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;

    $this->title = 'Задание: ' . Html::encode($task->name);
?>

<section class="content-view">
    <div class="content-view__card">
        <div class="content-view__card-wrapper">
            <div class="content-view__header">
                <div class="content-view__headline">
                    <h1><?= Html::encode($task->name); ?></h1>
                    <span>Размещено в категории
                        <a href="#"
                           class="link-regular"><?= $task->category->name; ?>
                        </a><?= EstimatedTimeWidget::widget(['task' => $task]); ?>
                    </span>
                </div>
                <b class="new-task__price new-task__price--<?= $task->category->icon; ?> content-view-price"><?= $task->budget; ?>
                    <b> ₽</b></b>
                <div class="new-task__icon new-task__icon--<?= $task->category->icon; ?> content-view-icon"></div>
            </div>
            <div class="content-view__description">
                <h3 class="content-view__h3">Общее описание</h3>
                <p><?= $task->description; ?></p>
            </div>
            <?php
                if ($task->files): ?>
                    <div class="content-view__attach">
                        <h3 class="content-view__h3">Вложения</h3>
                        <?php
                            foreach ($task->files as $attachment): ?>
                                <a href="#"><?= Html::encode($attachment->name); ?>></a>
                            <?php
                            endforeach; ?>
                    </div>
                <?php
                endif; ?>
            <div class="content-view__location">
                <h3 class="content-view__h3">Расположение</h3>
                <div class="content-view__location-wrapper">
                    <div class="content-view__map">
                        <a href="#"><img src="./img/map.jpg" width="361" height="292"
                                         alt="Москва, Новый арбат, 23 к. 1"></a>
                    </div>
                    <div class="content-view__address">
                        <span class="address__town">Москва</span><br>
                        <span>Новый арбат, 23 к. 1</span>
                        <p>Вход под арку, код домофона 1122</p>
                    </div>
                </div>
            </div>
        </div>

        <?php
            if (!empty($strategy->getAvailableAction($task->id)[1])): ?>
                <div class="content-view__action-buttons">
                    <button class=" button button__big-color <?= $strategy->getAvailableAction($task->id)[0]; ?>-button open-modal"
                            type="button" data-for="<?= $strategy->getAvailableAction($task->id)[0]; ?>-form">
                        <?= $strategy->getAvailableAction($task->id)[1]; ?>
                    </button>
                </div>
            <?php
            endif; ?>
    </div>

    <?php
        if (count($task->replies)): ?>
            <div class="content-view__feedback">
                <?php
                    if ($userId === $task->customer_id): ?>
                        <h2>Отклики <span>(<?= count($task->replies); ?>)</span></h2>
                        <?php
                        foreach ($task->replies as $reply): ?>

                            <div class="content-view__feedback-wrapper">
                                <div class="content-view__feedback-card">
                                    <div class="feedback-card__top">
                                        <a href="#"><img src="<?= Html::encode($reply->user->getAvatar()); ?>"
                                                         width="55"
                                                         height="55"></a>
                                        <div class="feedback-card__top--name">
                                            <p><a href="<?= BaseUrl::to(['users/view/', 'id' => $reply->user->id]) ?>"
                                                  class="link-regular"><?= Html::encode($reply->user->name); ?></a></p>

                                            <!--                    звезды рейтинга-->
                                            <?= StarRatingWidget::widget(['user' => $reply->user]); ?>
                                            <b><?= $reply->user->getRating(); ?></b>
                                        </div>
                                        <?php
                                            if ($reply->dt_add): ?>
                                                <span class="new-task__time"><?= Yii::$app->formatter->asRelativeTime($reply->dt_add); ?></span>
                                            <?php
                                            endif; ?>
                                    </div>
                                    <div class="feedback-card__content">
                                        <p><?= Html::encode($reply->description); ?></p>
                                        <span><?= ($reply->price) ? Html::encode($reply->price) : Html::encode($reply->task->budget); ?> ₽</span>
                                    </div>

                                    <?php
                                        if ($reply->task->status_id === 1 && $reply->status === 1): ?>
                                            <div class="feedback-card__actions">
                                                <?= Html::a('Подтвердить',
                                                    [
                                                        'tasks/apply',
                                                        'task_id' => $reply->task_id,
                                                        'user_id' => $reply->user_id,
                                                        'reply_id' => $reply->id
                                                    ],
                                                    ['class' => 'button__small-color request-button button']);
                                                ?>
                                                <?= Html::a('Отказать',
                                                    [
                                                        'tasks/refuse',
                                                        'task_id' => $reply->task_id,
                                                        'reply_id' => $reply->id
                                                    ],
                                                    ['class' => 'button__small-color refusal-button button']);
                                                ?>

                                            </div>
                                        <?php
                                        endif; ?>

                                </div>
                            </div>

                        <?php
                        endforeach; ?>
                    <?php
                    endif; ?>
            </div>
        <?php
        endif; ?>

    <?php
        foreach ($task->replies as $reply): ?>
            <!--отображение отклика для автора отклика -->
            <div class="content-view__feedback">
                <div class="content-view__feedback-wrapper">
                    <?php
                        if ($reply->user_id === $userId): ?>
                            <h2>Ваш отклик на задание:</h2>
                            <div class="content-view__feedback-card">
                                <div class="feedback-card__top">
                                    <a href="#"><img src="<?= Html::encode($reply->user->getAvatar()); ?>" width="55"
                                                     height="55"></a>
                                    <div class="feedback-card__top--name">
                                        <p><a href="<?= BaseUrl::to(['users/view/', 'id' => $reply->user->id]) ?>"
                                              class="link-regular"><?= Html::encode($reply->user->name); ?></a></p>
                                        <!--                    звезды рейтинга-->
                                        <?= StarRatingWidget::widget(['user' => $reply->user]); ?>
                                        <b><?= $reply->user->getRating(); ?></b>
                                    </div>
                                    <?php
                                        if ($reply->dt_add): ?>
                                            <span class="new-task__time"><?= Yii::$app->formatter->asRelativeTime($reply->dt_add); ?></span>
                                        <?php
                                        endif; ?>
                                </div>
                                <div class="feedback-card__content">
                                    <p><?= Html::encode($reply->description); ?></p>
                                    <span><?= ($reply->price) ? Html::encode($reply->price) : Html::encode($reply->task->budget); ?> ₽</span>
                                </div>
                            </div>
                        <?php
                        endif; ?>
                </div>
            </div>
        <?php
        endforeach; ?>

</section>

<section class="connect-desk">
    <div class="connect-desk__profile-mini">
        <div class="profile-mini__wrapper">
            <h3>Заказчик</h3>
            <div class="profile-mini__top">
                <img src="<?= Html::encode($task->customer->getAvatar()); ?>" width="62" height="62"
                     alt="Аватар заказчика">
                <div class="profile-mini__name five-stars__rate">
                    <p><?= Html::encode($task->customer->name); ?></p>
                </div>
            </div>
            <p class="info-customer"><span><?= $task->customer->getTasksCount(); ?> заданий</span>
                <span class="last-"><?= TimeOnSiteWidget::widget(['task' => $task]); ?></span>
            </p>
            <a href="<?= BaseUrl::to(['users/view/', 'id' => $task->customer_id]); ?>" class="link-regular">Смотреть
                профиль</a>
        </div>
    </div>
    <div id="chat-container">
        <!--                    добавьте сюда атрибут task с указанием в нем id текущего задания-->
        <chat class="connect-desk__chat"></chat>
    </div>
</section>

<!--модальное окно ОКЛИКНУТЬСЯ-->

<?php
    if (User::findOne($userId)->role === User::ROLE_EXECUTOR && empty($replies)): ?>
        <section class="modal response-form form-modal" id="response-form">
            <h2>Отклик на задание</h2>

            <?php
                $form = ActiveForm::begin(
                    [
                        'method' => 'POST',
                        'action' => ['tasks/response', 'task_id' => $task->id],
                        'enableClientValidation' => true,
                        'fieldConfig' => [
                            'template' => "<p>{label}\n{input}</p>{error}",
                            'labelOptions' => [
                                'class' => 'form-modal-description'
                            ],
                            'errorOptions' => [
                                'tag' => 'span',
                                'style' => 'color:red',
                            ],
                        ]
                    ]);
            ?>
            <?= $form
                ->field($responseTaskForm, 'payment')
                ->input(
                    'text',
                    [
                        'class' => 'response-form-payment input input-middle input-money',

                    ]
                );
            ?>
            <?= Html::error($responseTaskForm, 'payment', ['class' => 'help-block']); ?>

            <?= $form->field($responseTaskForm, 'comment')
                ->textarea([
                        'class' => 'input textarea',
                        'rows' => 4,
                        'placeholder' => 'Разместите здесь свой текст'
                    ]
                ); ?>
            <?= Html::error($responseTaskForm, 'comment', ['class' => 'help-block']); ?>

            <?= Html::submitButton('Отправить',
                [
                    'class' => 'button modal-button'
                ]);
            ?>

            <?php
                ActiveForm::end(); ?>

            <button class="form-modal-close" type="button">Закрыть</button>
        </section>
    <?php
    endif; ?>

<!--модальное окно ВЫПОЛНЕНО-->
<?php
    if ($userId === $task->customer_id): ?>
        <section class="modal completion-form form-modal" id="complete-form">
            <h2>Завершение задания</h2>
            <p class="form-modal-description">Задание выполнено?</p>

            <?php
                $form = ActiveForm::begin(
                    [
                        'method' => 'POST',
                        'action' => ['tasks/complete', 'task_id' => $task->id],
                        'enableClientValidation' => true,
                        'fieldConfig' => [
                            'template' => "<p>{label}\n{input}</p>{error}",
                            'labelOptions' => [
                                'class' => 'form-modal-description'
                            ],
                            'errorOptions' => [
                                'tag' => 'span',
                                'style' => 'color:red',
                            ],
                        ]
                    ]);
            ?>

            <?= Html::activeRadioList($completeTaskForm, 'completion',
                CompleteTaskForm::getCompletionFields(),
                [
                    'item' => function ($index, $label, $name, $checked, $value) {
                        $radio = Html::radio($name, $checked, [
                            'id' => $value,
                            'value' => $value,
                            'class' => 'visually-hidden completion-input completion-input--' . $value
                        ]);
                        $label = Html::label($label, $value, [
                            'class' => 'completion-label completion-label--' . $value
                        ]);

                        return $radio . $label;
                    },
                    'unselect' => null
                ]
            ); ?>
            <?= Html::error($completeTaskForm, 'completion', ['class' => 'help-block']); ?>

            <?= $form->field($completeTaskForm, 'comment')->textarea(
                [
                    'class' => 'input textarea',
                    'rows' => 4,
                    'placeholder' => 'Разместите здесь свой текст'
                ]
            ); ?>
            <?= Html::error($completeTaskForm, 'comment', ['class' => 'help-block']); ?>

            <?= $form->field($completeTaskForm, 'rating', [
                'template' => "<p>{label}
                    <div class='feedback-card__top--name completion-form-star'>
                        <span class='star-disabled'></span>
                        <span class='star-disabled'></span>
                        <span class='star-disabled'></span>
                        <span class='star-disabled'></span>
                        <span class='star-disabled'></span>
                    </div>
                    {input}</p>{error}"
            ])->hiddenInput(['id' => 'rating']); ?>

            <?= Html::submitButton('Отправить', [
                'class' => 'button modal-button'
            ]) ?>

            <?php
                ActiveForm::end(); ?>

            <button class="form-modal-close" type="button">Закрыть</button>
        </section>
    <?php
    endif; ?>

<!--модальное окно отказа от задания-->
<section class="modal form-modal refusal-form" id="refusal-form">
    <h2>Отказ от задания</h2>
    <p>
        Вы собираетесь отказаться от выполнения задания.
        Это действие приведёт к снижению вашего рейтинга.
        Вы уверены?
    </p>
    <button class="button__form-modal button" id="close-modal"
            type="button">Отмена
    </button>
    <?= Html::a('Отказаться',
        [
            'tasks/refuse',
            'task_id' => $reply->task_id,
            'reply_id' => $reply->id
        ],
        ['class' => 'button__form-modal refusal-button button']) ?>
    <button class="form-modal-close" type="button">Закрыть</button>
</section>

<!--модальное окно отмены задания-->
<section class="modal form-modal refusal-form" id="cancel-form">
    <h2>Отмена задания</h2>
    <p>
        Вы собираетесь отменить задание.<br>
        Вы уверены?
    </p>

    <?= Html::a('Не отменять',
        [
            'tasks/view',
            'id' => $task->id,
        ],
        [
            'class' => "button__form-modal button",
            'id' => "close-modal",
            'type' => "button"
        ]) ?>

    <?= Html::a('Отменить',
        [
            'tasks/cancel',
            'task_id' => $task->id,
        ],
        ['class' => 'button__form-modal refusal-button button']) ?>
    <button class="form-modal-close" type="button">Закрыть</button>
</section>