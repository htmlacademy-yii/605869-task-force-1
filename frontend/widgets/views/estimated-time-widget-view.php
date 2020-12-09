<?php
use frontend\helpers\WordHelper;
/**
 * @var DateInterval $dateInterval
 */
?>

<span class="new-task__time">

<?php if ($dateInterval->y): ?>
    <?= $dateInterval->y; ?>  <?= WordHelper::pluralForm($dateInterval->y, 'год', 'года', 'лет'); ?>
<?php endif; ?>
<?php if ($dateInterval->m): ?>
    <?= $dateInterval->m; ?>  <?= WordHelper::pluralForm($dateInterval->m, 'месяц', 'месяца', 'месяцев') . ' '; ?>
<?php endif; ?>
<?php if ($dateInterval->d): ?>
    <?= $dateInterval->d; ?>  <?= WordHelper::pluralForm($dateInterval->d, 'день', 'дня', 'дней') . ' '; ?>
<?php endif; ?>
<?php if ($dateInterval->h): ?>
    <?= $dateInterval->h; ?>  <?= WordHelper::pluralForm($dateInterval->h, 'час', 'часа', 'часов'); ?>
<?php endif; ?>

</span>