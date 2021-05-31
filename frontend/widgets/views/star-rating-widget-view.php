<?php
/**
 * @var $rating
 */

use yii\helpers\Html;

for ($x = 0; $x < $rating; $x++)
{
    echo Html::tag('span');
}
for ($x = $rating; $x < 5; $x++)
{
    echo Html::tag('span',null, ['class' => 'star-disabled']);
}
