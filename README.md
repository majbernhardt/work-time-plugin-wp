# Время работы организации
Плагин для добавление динамического времени работы на сайт

- Можно указать начало и конец рабочего дня в будни
- Можно указать начало и конец рабочего дня в выходные
- Можно выбрать выходные дни

## Пример использования функции
```sh
$time_start_work = 10;
$time_end_work = 18;
$weekend_start = 6; // Суббота
$weekend_end = 7;   // Воскресенье
$weekend_time_start_work = 10;
$weekend_time_end_work = 15;

$result = work_time($time_start_work, $time_end_work, $weekend_start, $weekend_end, $weekend_time_start_work, $weekend_time_end_work);
echo $result;
```