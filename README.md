# Время работы организации
Плагин для добавление динамического времени работы на сайт

- Можно указать начало и конец рабочего дня в будни
- Можно указать начало и конец рабочего дня в выходные
- Можно выбрать выходные дни
- Можно указывать перерывы для будних и выходных дней

## Пример использования функции
```sh
$time_start_work = 10;
$time_end_work = 18;
$weekend_start = 6; // Суббота
$weekend_end = 7;   // Воскресенье
$weekend_time_start_work = 10;
$weekend_time_end_work = 15;

$break_start_weekdays = null; // Начало перерыва в будние дни, null если перерыва нет
$break_end_weekdays = null;   // Конец перерыва в будние дни, null если перерыва нет
$break_start_weekend = 12;  // Начало перерыва в выходные дни, null если перерыва нет
$break_end_weekend = 13;    // Конец перерыва в выходные дни, null если перерыва нет

$result = work_time($time_start_work, $time_end_work, $weekend_start, $weekend_end, $weekend_time_start_work, $weekend_time_end_work, $break_start_weekdays, $break_end_weekdays, $break_start_weekend, $break_end_weekend);
echo $result;
```