<?php
/*
* Plugin Name: Время работы организации
* Plugin URI:  https://github.com/majbernhardt/work-time-plugin-wp
* Description: Добавление динамического времени работы на сайт
* Version:     1.0
* Author:      Maj Bernhardt
* Author URI:  https://github.com/majbernhardt
* License:     GNU General Public License v3
* License URI: https://github.com/majbernhardt/work-time-plugin-wp/blob/main/LICENSE
*
* Network:     true
*/
function work_time($time_start_work, $time_end_work, $weekend_start, $weekend_end, $weekend_time_start_work, $weekend_time_end_work, $break_start_weekdays = null, $break_end_weekdays = null, $break_start_weekend = null, $break_end_weekend = null) {
    /*
    // Раскомментировать, чтобы протестировать
    date_default_timezone_set('UTC'); // Устанавливаем временную зону по желанию
    $custom_time = strtotime('2023-11-04 13:00:00'); // Задаем произвольную дату и время для тестирования (например, 5:00 утра) 
    $current_time = $custom_time; // Устанавливаем кастомное время вместо текущего
    */
    
    $current_time = time(); // Получаем текущее время в формате Unix timestamp (Закомментировать, чтобы протестировать)

    // Проверка, является ли текущий день выходным (субботой или воскресеньем)
    $is_weekend = in_array(date('N', $current_time), [6, 7]);

    // Вычисляем час текущего времени
    $current_hour = date('H', $current_time);

    if ($is_weekend) {
        // Если сегодня выходной
        if ($current_hour >= $weekend_time_start_work && $current_hour < $weekend_time_end_work) {
            if ($break_start_weekend !== null && $break_end_weekend !== null && $current_hour >= $break_start_weekend && $current_hour < $break_end_weekend) {
                return "Перерыв до $break_end_weekend:00";
            } else {
                return "Сегодня до $weekend_time_end_work:00";
            }
        } else {
            return ($current_hour < $weekend_time_start_work) ? "Сегодня с $weekend_time_start_work:00" : "Завтра с $weekend_time_start_work:00";
        }
    } else {
        // Если сегодня рабочий день
        if ($current_hour >= $time_start_work && $current_hour < $time_end_work) {
            if ($break_start_weekdays !== null && $break_end_weekdays !== null && $current_hour >= $break_start_weekdays && $current_hour < $break_end_weekdays) {
                return "Перерыв до $break_end_weekdays:00";
            } else {
                return "Сегодня до $time_end_work:00";
            }
        } else {
            return ($current_hour < $time_start_work) ? "Сегодня с $time_start_work:00" : "Завтра с $time_start_work:00";
        }
    }
}

// Пример использования функции
# $time_start_work = 10;
# $time_end_work = 18;
# $weekend_start = 6; // Суббота
# $weekend_end = 7;   // Воскресенье
# $weekend_time_start_work = 10;
# $weekend_time_end_work = 15;

# $break_start_weekdays = null; // Начало перерыва в будние дни, null если перерыва нет
# $break_end_weekdays = null;   // Конец перерыва в будние дни, null если перерыва нет
# $break_start_weekend = 12;  // Начало перерыва в выходные дни, null если перерыва нет
# $break_end_weekend = 13;    // Конец перерыва в выходные дни, null если перерыва нет

# $result = work_time($time_start_work, $time_end_work, $weekend_start, $weekend_end, $weekend_time_start_work, $weekend_time_end_work, $break_start_weekdays, $break_end_weekdays, $break_start_weekend, $break_end_weekend);
# echo $result;