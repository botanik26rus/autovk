<?php

@session_start(); 


// подключаем файлы ядра
require_once 'core/model.php';
require_once 'core/view.php';
require_once 'core/controller.php';

// подключаем остальные файлы 
require_once 'class/telegram.class.php'; //Подключаем класс Telegram
require_once 'function/function.php'; //Временно
require_once 'function/function_old.php'; //Временно
include('application/class/vkapi.class.php');
include('application/function/vk.php');

include 'class/mysql.class.php'; 
$DB1 = new mysqlDB(); //Временно

require_once 'config.php';
$DB1->connect($db_host,$db_login,$db_pass);
$DB1->select_db($db_base,'utf8');

//Получаем информацию о пользователе
if (isset($_SESSION['vkid'])){
	$id = $_SESSION['vkid'];
 
	$v_us = $DB1->query("SELECT * FROM `vk_acc` WHERE `vkid` = '$id'",'assoc');
}

 

//~ echo 'Сессия логин'.$_SESSION['login'].'<br>';
//~ echo 'Сессия ид '.$_SESSION['id'].'<br>';

//Здесь обычно подключаются дополнительные модули, реализующие различный функционал:
 
/*
Здесь обычно подключаются дополнительные модули, реализующие различный функционал:
	> аутентификацию
	> кеширование https://ruseller.com/lessons.php?rub=37&id=1555
	> работу с формами
	> абстракции для доступа к данным
	> ORM
	> Unit тестирование
	> Benchmarking
	> Работу с изображениями
	> Backup
	> и др.
	* 
	* require_once 'module/auth.php'; 
*/

require_once 'core/route.php';
try {
   Route::start(); // запускаем маршрутизатор
   
} catch( Exception $e ) {
   //echo "Игрок поймал исключение: {$e->getMessage()}";
}

