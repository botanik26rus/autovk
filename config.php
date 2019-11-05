<?php
ini_set('date.timezone', 'Europe/Minsk');

$db_host = '';
$db_login = '';
$db_pass = '';
$db_base = '';


$settings['root'] = $_SERVER['DOCUMENT_ROOT']; //Абсолютный путь (От корня)
$settings['sitename'] = 'Автответчик'; //Имя сайта
$settings['siteurl'] = ''; //Юрл сайта
 
if(isset($_SERVER['REMOTE_ADDR'])){
	$settings['ip'] = '';//Ип адрес
}
?>
