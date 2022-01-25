<?php

namespace frontend\helpers;

/**
 * Class WordHelper
 * @package frontend\helpers
 */
class WordHelper
{
    /**
     * Возвращает корректную форму множественного числа
     * Ограничения: только для целых чисел
     *
     * Пример использования:
     * $remaining_minutes = 5;
     * echo "Я поставил таймер на {$remaining_minutes} " .
     *     get_noun_plural_form(
     *         $remaining_minutes,
     *         'минута',
     *         'минуты',
     *         'минут'
     *     );
     * Результат: "Я поставил таймер на 5 минут"
     *
     * @param int $number Число, по которому вычисляем форму множественного числа
     * @param string $one Форма единственного числа: яблоко, час, минута
     * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
     * @param string $many Форма множественного числа для остальных чисел
     *
     * @return string Рассчитанная форма множественнго числа
     */
    public static function pluralForm(int $number, string $one, string $two, string $many): string
    {
        $number = (int)$number;
        $mod10 = $number % 10;
        $mod100 = $number % 100;
        switch (true) {
            case ($mod100 >= 11 && $mod100 <= 20):
                return $many;

            case ($mod10 > 5):
                return $many;

            case ($mod10 === 1):
                return $one;

            case ($mod10 >= 2 && $mod10 <= 4):
                return $two;

            default:
                return $many;
        }
    }
}
