<?php
/**
 * Plugin Name: Время работы организации
 * Plugin URI:  https://github.com/majbernhardt/work-time-plugin-wp
 * Description: Добавление динамического времени работы на сайт
 * Version:     3.0.1
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

/**
 * Пример использования функции
 * echo $work_time();
 */

function work_time() {
	$weekday_start_time = get_option('weekday_start_time');
    $weekday_end_time = get_option('weekday_end_time');
    $weekend_start_time = get_option('weekend_start_time');
    $weekend_end_time = get_option('weekend_end_time');
    $weekday_break_start = get_option('weekday_break_start');
    $weekday_break_end = get_option('weekday_break_end');
    $weekend_break_start = get_option('weekend_break_start');
    $weekend_break_end = get_option('weekend_break_end');
	$weekend_start = intval(get_option('weekend_start'));
	$weekend_end = intval(get_option('weekend_end'));
	$test_custom_time = get_option('test_custom_time');
	
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
    return $result; 
}

// Подключение файла с настройками
include_once(plugin_dir_path(__FILE__) . 'work-time-settings.php');

// Добавление ссылок в список действий плагина
function work_time_plugin_action_links($links) {
    $settings_links = array(
        '<a href="admin.php?page=work-time-settings">Настройки</a>',
        '<a href="https://github.com/majbernhardt/work-time-plugin-wp#readme" target="_blank">Документация</a>',
    );
    $links = array_merge($settings_links, $links);
    return $links;
}

// Регистрация хука для добавления ссылок
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'work_time_plugin_action_links');