<?php

use frontend\models\Task;
use TaskForce\TaskActionStrategy;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use TaskForce\Action\{Response, Refusal, Complete, Cancel};
use frontend\models\{ResponseTaskForm, CompleteTaskForm};

/**
 * @var TaskActionStrategy $strategy
 * @var Task $task
 * @var ResponseTaskForm $responseTaskForm
 * @var CompleteTaskForm $completeTaskForm
 */

?>
<?php foreach ($strategy->getAvailableActions() as $action): ?>
    <div class="content-view__action-buttons">
        <button class=" button button__big-color <?= $action->getActionCode() ?>-button open-modal"
                type="button" data-for="<?= $action->getActionCode() ?>-form">
            <?= $action->getActionTitle(); ?>
        </button>
    </div>

    <?php if ($action->getActionCode() === (new Response())->getActionCode()): ?>
        <section class="modal response-form form-modal" id="response-form">
            <h2>Отклик на задание</h2>

            <?php
            $form = ActiveForm::begin(
                [
                    'method' => 'POST',
                    'action' => ['tasks/response', 'id' => $task->id],
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
                ]
            );
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
                ->textarea(
                    [
                        'class' => 'input textarea',
                        'rows' => 4,
                        'placeholder' => 'Разместите здесь свой текст'
                    ]
                ); ?>
            <?= Html::error($responseTaskForm, 'comment', ['class' => 'help-block']); ?>

            <?= Html::submitButton(
                'Отправить',
                [
                    'class' => 'button modal-button'
                ]
            ); ?>

            <?php ActiveForm::end(); ?>

            <button class="form-modal-close" type="button">Закрыть</button>
        </section>
    <?php endif;  ?>

    <?php if ($action->getActionCode() === (new Complete())->getActionCode()): ?>
        <section class="modal completion-form form-modal" id="complete-form">
            <h2>Завершение задания</h2>
            <p class="form-modal-description">Задание выполнено?</p>

            <?php
            $form = ActiveForm::begin(
                [
                    'method' => 'POST',
                    'action' => ['tasks/complete', 'id' => $task->id],
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
                ]
            ); ?>

            <?= Html::activeRadioList(
                $completeTaskForm,
                'completion',
                CompleteTaskForm::getCompletionFields(),
                [
                    'item' => function ($index, $label, $name, $checked, $value) {
                        $radio = Html::radio(
                            $name,
                            $checked,
                            [
                                'id' => $value,
                                'value' => $value,
                                'class' => 'visually-hidden completion-input completion-input--' . $value
                            ]
                        );
                        $label = Html::label(
                            $label,
                            $value,
                            [
                                'class' => 'completion-label completion-label--' . $value
                            ]
                        );

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

            <?= $form->field(
                $completeTaskForm,
                'rating',
                [
                    'template' => "<p>{label}
                    <div class='feedback-card__top--name completion-form-star'>
                        <span class='star-disabled'></span>
                        <span class='star-disabled'></span>
                        <span class='star-disabled'></span>
                        <span class='star-disabled'></span>
                        <span class='star-disabled'></span>
                    </div>
                    {input}</p>{error}"
                ]
            )->hiddenInput(['id' => 'rating']); ?>

            <?= Html::submitButton(
                'Отправить',
                [
                    'class' => 'button modal-button'
                ]
            ) ?>

            <?php ActiveForm::end(); ?>

            <button class="form-modal-close" type="button">Закрыть</button>
        </section>
    <?php endif;  ?>

    <?php if ($action->getActionCode() === (new Refusal())->getActionCode()): ?>
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

            <?php if ($task->replies): ?>
                <?= Html::a(
                    'Отказаться',
                    [
                        'tasks/refuse',
                        'id' => $task->id,
                    ],
                    ['class' => 'button__form-modal refusal-button button']
                ) ?>
            <?php endif; ?>
            <button class="form-modal-close" type="button">Закрыть</button>
        </section>
    <?php endif;  ?>

    <?php if ($action->getActionCode() === (new Cancel())->getActionCode()): ?>
        <section class="modal form-modal refusal-form" id="cancel-form">
            <h2>Отмена задания</h2>
            <p>
                Вы собираетесь отменить задание.<br>
                Вы уверены?
            </p>

            <?= Html::a(
                'Не отменять',
                [
                    'tasks/view',
                    'id' => $task->id,
                ],
                [
                    'class' => "button__form-modal button",
                    'id' => "close-modal",
                    'type' => "button"
                ]
            ) ?>

            <?= Html::a(
                'Отменить',
                [
                    'tasks/cancel',
                    'id' => $task->id,
                ],
                ['class' => 'button__form-modal refusal-button button']
            ) ?>
            <button class="form-modal-close" type="button">Закрыть</button>
        </section>
    <?php endif;  ?>
<?php endforeach; ?>
