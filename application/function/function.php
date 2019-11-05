<?php 
 
//Добавление записи в лог
//Тип лога; Кто добавил;Сообщение
function addlog($type,$who,$message){
	global $DB1,$time;
	$DB1->query("INSERT INTO `log` (`id`, `timelog`, `type`, `who`, `message`) VALUES (NULL, '$time','$type','$who', '$message')");
}

//Проверка текста
function check_text($text){
	Global $DB1;
	##Убираем пробелы с начала и конца
	$text = trim($text);
	##Убираем теги
	$text=strip_tags($text);
	$text = htmlentities($text, ENT_QUOTES, "UTF-8");
	$text = htmlspecialchars($text, ENT_QUOTES);
	$text = $DB1->mysqli_real_escape_string_ex($text);
	return $text;
}
//Проверка чисел
function check_num($number){
	Global $DB1;
	##Убираем пробелы с начала и конца
	$text = trim($number);
	##Убираем теги
	$number = strip_tags($number);
	##разрешаем только циферки 
	$number=preg_replace("/[^0-9]/u","",$number);
	$number = htmlentities($text, ENT_QUOTES, "UTF-8");
	$number = htmlspecialchars($number, ENT_QUOTES);
	$number = $DB1->mysqli_real_escape_string_ex($number);
	return $number;
}
//Откуда подключен, возращет массив
function parent_street($id){
	global $DB1;
	$id = check_num($id);
	$result = $DB1->query("SELECT `street_id`,`house`,`model`,`ip` FROM `switch` WHERE `id` = '{$id}'",'assoc');
	return $result;
}

function print_ex($print){
	$result = print_r('<pre>');
	$result = print_r($print);
	$result = print_r('</pre>');
	return $result;
}
?>
