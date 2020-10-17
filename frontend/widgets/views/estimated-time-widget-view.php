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
    <?= $dateInterval->m . $GetNounPluralForm->Form($dateInterval->m, 'месяц', 'месяца', 'месяцев') . ' '; ?>
<?php endif; ?>
<?php if ($dateInterval->d): ?>
    <?= $dateInterval->d . $GetNounPluralForm->Form($dateInterval->d, 'день', 'дня', 'дней') . ' '; ?> д.
<?php endif; ?>
<?php if ($dateInterval->h): ?>
    <?= $dateInterval->h . $GetNounPluralForm->Form($dateInterval->h, 'час', 'часа', 'часов'); ?> г.
<?php endif; ?>

</span>