<?php
/**
 * Plugin Name: Время работы организации
 * Plugin URI:  https://github.com/majbernhardt/work-time-plugin-wp
 * Description: Добавление динамического времени работы на сайт
 * Version:     2.0.0
 * Author:      Maj Bernhardt
 * Author URI:  https://github.com/majbernhardt
 * License:     GNU General Public License v3
 * License URI: https://github.com/majbernhardt/work-time-plugin-wp/blob/main/LICENSE
 *
 * Network:     true
*/

/**
 * Функция для определения текущего статуса работы организации в зависимости от времени и дней недели.
 *
 * @param string $weekday_start_time Время начала работы в будние дни в формате ЧЧ:ММ.
 * @param string $weekday_end_time Время окончания работы в будние дни в формате ЧЧ:ММ.
 * @param int $weekend_start Номер дня недели, с которого начинается выходной (1 - понедельник, 7 - воскресенье).
 * @param int $weekend_end Номер дня недели, которым заканчивается выходной (1 - понедельник, 7 - воскресенье).
 * @param string $weekend_start_time Время начала работы в выходные дни в формате ЧЧ:ММ.
 * @param string $weekend_end_time Время окончания работы в выходные дни в формате ЧЧ:ММ.
 * @param string|null $weekday_break_start Начальное время перерыва в будние дни, null если перерыва нет.
 * @param string|null $weekday_break_end Конечное время перерыва в будние дни, null если перерыва нет.
 * @param string|null $weekend_break_start Начальное время перерыва в выходные дни, null если перерыва нет.
 * @param string|null $weekend_break_end Конечное время перерыва в выходные дни, null если перерыва нет.
 * @param string|null $test_custom_time Произвольное время для тестирования в формате 'Y-m-d H:i:s', null для использования текущего времени.
 *
 */

function work_time($weekday_start_time, $weekday_end_time, $weekend_start, $weekend_end, $weekend_start_time, $weekend_end_time, $weekday_break_start = null, $weekday_break_end = null, $weekend_break_start = null, $weekend_break_end = null, $test_custom_time = null) {
    if ($test_custom_time !== null) {
        date_default_timezone_set('UTC');
        $current_time = strtotime($test_custom_time);
    } else {
        $current_time = time();
    }

    $current_day_of_week = intval(date('N', $current_time));
    $current_hour_with_minutes = date('H:i', $current_time);

    $break_start_formatted = null;
    $break_end_formatted = null;

    if ($current_day_of_week >= $weekend_start && $current_day_of_week <= $weekend_end) {
        // Если текущий день находится между $weekend_start и $weekend_end, то это выходной
        $start_time = $weekend_start_time;
        $end_time = $weekend_end_time;
        $break_start_formatted = $weekend_break_start;
        $break_end_formatted = $weekend_break_end;
    } else {
        // Иначе это будний день
        $start_time = $weekday_start_time;
        $end_time = $weekday_end_time;
        $break_start_formatted = $weekday_break_start;
        $break_end_formatted = $weekday_break_end;
    }

    if ($current_hour_with_minutes >= $start_time && $current_hour_with_minutes < $end_time) {
        if ($break_start_formatted !== null && $break_end_formatted !== null && $current_hour_with_minutes >= $break_start_formatted && $current_hour_with_minutes < $break_end_formatted) {
            return "Перерыв до $break_end_formatted";
        } else {
            return "Сегодня до $end_time";
        }
    } else {
		if ( intval(date('N', $current_time)) === $weekend_start ) {
			// Если начало выходных
        	return ($current_hour_with_minutes < $start_time) ? "Сегодня с $start_time" : "Завтра с $start_time";
		} else if (intval(date('N', $current_time)) > $weekend_start && intval(date('N', $current_time)) < $weekend_end) {
			// Если выходные не подряд
			return ($current_hour_with_minutes < $start_time) ? "Сегодня с $start_time" : "Завтра с $weekend_start_time";
		} else if ( intval(date('N', $current_time)) === $weekend_end ) {
			// Если конец выходных
        	return ($current_hour_with_minutes < $start_time) ? "Сегодня с $start_time" : "Завтра с $weekday_start_time";
		} else if ( intval(date('N', $current_time)) === ($weekend_start - 1)) {
			// Если один день до выходных
        	return ($current_hour_with_minutes < $start_time) ? "Сегодня с $start_time" : "Завтра с $weekend_start_time";
		} else {
			// Если будние дни
        	return ($current_hour_with_minutes < $start_time) ? "Сегодня с $start_time" : "Завтра с $start_time";
		}
    }
}


// Пример использования функции
// $weekday_start_time = "11:30";      // Начало в будние дни
// $weekday_end_time =  "18:30";       // Конец в будние дни

// $weekend_start_time = "10:30";      // Начало в выходные дни
// $weekend_end_time = "17:30";        // Конец в выходные дни

// $weekday_break_start = "12:30";     // Начало перерыва в будние дни, null если перерыва нет
// $weekday_break_end = "13:30";       // Конец перерыва в будние дни, null если перерыва нет

// $weekend_break_start = null;        // Начало перерыва в выходные дни, null если перерыва нет
// $weekend_break_end = null;          // Конец перерыва в выходные дни, null если перерыва нет

// $weekend_start = 6;                 // День начала выходных (1 - понедельник, 7 - воскресенье)
// $weekend_end = 7;                   // День конца выходных (1 - понедельник, 7 - воскресенье)

// Пример использования без тестирования
// $result = work_time($weekday_start_time, $weekday_end_time, $weekend_start, $weekend_end, $weekend_start_time, $weekend_end_time, $weekday_break_start, $weekday_break_end, $weekend_break_start, $weekend_break_end);
// echo $result;

// Пример использования с тестированием (произвольное время)
// $test_custom_time = "2023-11-22 19:00:00";
// $result_test = work_time($weekday_start_time, $weekday_end_time, $weekend_start, $weekend_end, $weekend_start_time, $weekend_end_time, $weekday_break_start, $weekday_break_end, $weekend_break_start, $weekend_break_end, $test_custom_time);
// echo $result_test;