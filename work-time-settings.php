<?php
// Функция для добавления настроек в админ-панель
function work_time_settings_page() {
    ?>
    <div class="wrap">
        <h2>Настройки времени работы организации</h2>
        <form method="post" action="options.php">
            <?php
            settings_fields('work_time_settings');
            do_settings_sections('work_time_settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Функция для регистрации настроек и добавления полей в админ-панели
function work_time_settings() {
    register_setting('work_time_settings', 'weekday_start_time', array('default' => '10:00'));
    register_setting('work_time_settings', 'weekday_end_time', array('default' => '18:00'));
    register_setting('work_time_settings', 'weekend_start_time', array('default' => '10:00'));
    register_setting('work_time_settings', 'weekend_end_time', array('default' => '18:00'));
    register_setting('work_time_settings', 'weekday_break_start', array('default' => '12:00'));
    register_setting('work_time_settings', 'weekday_break_end', array('default' => '13:00'));
    register_setting('work_time_settings', 'weekend_break_start', array('default' => '12:00'));
    register_setting('work_time_settings', 'weekend_break_end', array('default' => '13:00'));
    register_setting('work_time_settings', 'weekend_start', array('default' => 6));
    register_setting('work_time_settings', 'weekend_end', array('default' => 7));
    register_setting('work_time_settings', 'test_custom_time');

    add_settings_section('work_time_main_section', 'Основные настройки', 'work_time_section_callback', 'work_time_settings');

    add_settings_field(
		'weekday_start_time', 
		'Начало работы в будние дни', 
		'work_time_time_field_callback',
		'work_time_settings', 
		'work_time_main_section',
		[
			'field' => 'weekday_start_time'
		]
	);
    add_settings_field(
		'weekday_end_time', 
		'Конец работы в будние дни', 
		'work_time_time_field_callback', 
		'work_time_settings', 
		'work_time_main_section', 
		[
			'field' => 'weekday_end_time'
		]
	);
    add_settings_field(
		'weekend_start_time', 
		'Начало работы в выходные дни',
		'work_time_time_field_callback',
		'work_time_settings', 
		'work_time_main_section', 
		[
			'field' => 'weekend_start_time'
		]
	);
    add_settings_field(
		'weekend_end_time',
		'Конец работы в выходные дни', 
		'work_time_time_field_callback', 
		'work_time_settings', 
		'work_time_main_section', 
		[
			'field' => 'weekend_end_time'
		]
	);
    add_settings_field(
		'weekday_break_start', 
		'Начало перерыва в будние дни',
		'work_time_time_field_callback',
		'work_time_settings', 
		'work_time_main_section', 
		[
			'field' => 'weekday_break_start',
			'description' => 'Может быть пустым если нет перерывов'
		]
	);
    add_settings_field(
		'weekday_break_end', 
		'Конец перерыва в будние дни',
		'work_time_time_field_callback', 
		'work_time_settings', 
		'work_time_main_section',
		[
			'field' => 'weekday_break_end',
			'description' => 'Может быть пустым если нет перерывов'
		]
	);
    add_settings_field(
		'weekend_break_start', 
		'Начало перерыва в выходные дни', 
		'work_time_time_field_callback',
		'work_time_settings', 
		'work_time_main_section', 
		[
			'field' => 'weekend_break_start',
			'description' => 'Может быть пустым если нет перерывов'
		]
	);
    add_settings_field(
		'weekend_break_end',
		'Конец перерыва в выходные дни', 
		'work_time_time_field_callback', 
		'work_time_settings', 
		'work_time_main_section',
		[
			'field' => 'weekend_break_end',
			'description' => 'Может быть пустым если нет перерывов'
		]
	);
    add_settings_field(
		'weekend_start', 
		'День начала выходных', 
		'work_time_select_field_callback', 
		'work_time_settings', 
		'work_time_main_section', 
		[
			'field' => 'weekend_start',
			'description' => 'День начала выходных не должен быть днём недели последующим за днём окончания выходных'
		]
	);
    add_settings_field(
		'weekend_end', 
		'День конца выходных', 
		'work_time_select_field_callback', 
		'work_time_settings', 'work_time_main_section', 
		[
			'field' => 'weekend_end',
			'description' => 'День окончания выходных не должен быть днём недели предшествующим перед днём начала выходных'
		]
	);
    add_settings_field(
		'test_custom_time', 
		'Время для тестирования вывода', 
		'work_time_datetime_field_callback', 
		'work_time_settings', 
		'work_time_main_section', 
		[
			'field' => 'test_custom_time',
			'description' => 'Если пусто то используется ваше текущее время. Указанное в поле время будет использоваться вместо вашего текущего времени для теста функциональности'
		]
	);
}


function work_time_section_callback() {
    echo 'Задайте параметры времени работы организации.';
}

function work_time_select_field_callback($args) {
    $field = $args['field'];
    $value = get_option($field, '');
    $selected_value = !empty($value) ? $value : ''; // Значение, которое нужно выбрать по умолчанию
    $description = isset($args['description']) ? esc_attr($args['description']) : '';

    // Массив с вашими значениями
    $options = array(
        '1' => 'Понедельник',
        '2' => 'Вторник',
        '3' => 'Среда',
        '4' => 'Четверг',
        '5' => 'Пятница',
        '6' => 'Суббота',
        '7' => 'Воскресенье',
    );

    echo "<select name='$field'>";
    foreach ($options as $key => $label) {
        $selected = ($selected_value == $key) ? 'selected' : '';
        echo "<option value='$key' $selected>$label</option>";
    }
    echo "</select>";
    echo "<p class='description'>$description</p>";
}

function work_time_time_field_callback($args) {
    $field = $args['field'];
    $value = get_option($field, '');
    $description = isset($args['description']) ? esc_attr($args['description']) : '';
    echo "<input type='time' name='$field' value='$value' required/>";
    echo "<p class='description'>$description</p>";
}

function work_time_datetime_field_callback($args) {
    $field = $args['field'];
    $value = get_option($field, '');
    $description = isset($args['description']) ? esc_attr($args['description']) : '';

    echo "<input type='datetime-local' name='$field' value='$value' />";
    echo "<p class='description'>$description</p>";
}


// Добавление страницы настроек в админ-панель
add_action('admin_menu', 'work_time_add_menu');
add_action('admin_init', 'work_time_settings');

function work_time_add_menu() {
    add_menu_page(
        'Время работы организации',
        'Время работы',
        'manage_options',
        'work-time-settings',
        'work_time_settings_page',
        'dashicons-clock'
    );
}