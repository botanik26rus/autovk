<?php 
//ПОСМОТРЕТЬ ЧТО УДАЛИТЬ, ОСТАЛЬНОЕ ПЕРЕНЕСТИ В function.php


##Выражение в зависимости от оператора сравнения
function op($op,$field,$data){
	switch ($op) {
		case 'eq':
			//return '=';
			return "`".$field."` = '".$data."'";
			
		break;
		
		case 'ne':
			//return '<>';
			$qWhere .= $field." <> ".$data;
			return "`".$field."` <> '".$data."'";
		break;
		
		case 'cn':
			//return 'LIKE';
			return "`".$field."` LIKE '%".$data."%'";
		break;

		case 'nc':
			//return 'NOT LIKE';
			return "`".$field."` NOT LIKE '%".$data."'%";
		break;

		default: return '';
	}
}

##Возрат ошибки в json формате. Для уведомления bootstrap
function info($type,$message){
	$arr=array(
	"type" => $type,
	"message" => $message
	);
	header("Content-type: application/json; charset=utf-8");
	echo json_encode($arr);
	exit;
}
		
##Получение Вендора оборудования по id
function get_vendor($id){
	global $DB1;
		if ($vendorname = $DB1->query("SELECT `v_name` FROM `vendors` WHERE `v_id` = '$id'",'assoc')){
			return $vendorname['v_name'];
		}else{
			return '';
		}

}

##Получение модели оборудования по id
function get_model($id){
	global $DB1;
	if ($modelname = $DB1->query("SELECT `m_name` FROM `models` WHERE `m_id` = '$id'",'assoc')){
		return $modelname['m_name'];
	}else{
		return '';
	}
}

##Получение улицы по id
function get_street($id){
	global $DB1;
	if ($modelname = $DB1->query("SELECT `s_name` FROM `streets` WHERE `s_id` = '$id'",'assoc')){
		return $modelname['s_name'];
	}else{
		return '';
	}
}

//Транслит текста
function rus_to_lat($str) {
$str = strtr($str, array ('а' => 'a','б' => 'b','в' => 'v','г' => 'g','д' => 'd','е' => 'e','ё' => 'e','ж' => 'j','з' => 'z','и' => 'i','й' => 'i','к' => 'k','л' => 'l','м' => 'm','н' => 'n','о' => 'o','п' => 'p','р' => 'r','с' => 's','т' => 't','у' => 'u','ф' => 'f','х' => 'h','ц' => 'c','ч' => 'ch','ш' => 'sh','щ' => 'sch','ъ' => "",'ы' => 'y','ь' => "",

'э' => 'ye','ю' => 'yu','я' => 'ya'));
return $str;
}
##Форматирование json
function json_format($data){
	//Убираем обратные слеши
	$data = str_replace('\\','/',$data); 
	//Убираем двойные ковычки
	$data = str_replace('"','\'',$data);
	//Удаляем табуляцию
	$data = str_replace("\t", "\\t", $data);
	//Удаляем квадратные скобки
	$data = str_replace("[", "", $data);
	$data = str_replace("]", "", $data);
	//Убираем переносы строк
	$data = str_replace("\n", "\\n", $data);
	//Преобразуем теги в сущьности
	$data = htmlspecialchars($data);
	//Убираем переносы строк
	$data = str_replace("\r", "\\r", $data);
	//Удаляем пробелы в начале строки
	$data = ltrim($data);
	//Удаляем пробелы в конце строки
	$data = rtrim($data);
	##Убираем теги
	$data=strip_tags($data);
	return $data;
}



##Функция шифрования
function encode($unencoded,$key){
	//Переводим в base64
	$string=base64_encode($unencoded);
	//Это массив
	$arr=array();
	$x=0;
	while ($x++< strlen($string)) {//Цикл
	$arr[$x-1] = md5(md5($key.$string[$x-1]).$key);//Почти чистый md5
	$newstr = $arr[$x-1][3].$arr[$x-1][6].$arr[$x-1][1].$arr[$x-1][2];//Склеиваем символы
	}
	//Вертаем строку
	return $newstr;
}



##Функция дешифрования
function decode($encoded, $key){
	$strofsym="qwertyuiopasdfghjklzxcvbnm1234567890QWERTYUIOPASDFGHJKLZXCVBNM=";//Символы, с которых состоит base64-ключ
	$x=0;
	while ($x++<= strlen($strofsym)) {//Цикл
	$tmp = md5(md5($key.$strofsym[$x-1]).$key);//Хеш, который соответствует символу, на который его заменят.
	$encoded = str_replace($tmp[3].$tmp[6].$tmp[1].$tmp[2], $strofsym[$x-1], $encoded);//Заменяем №3,6,1,2 из хеша на символ
	}
	return base64_decode($encoded);//Вертаем расшифрованную строку
}


//Выводим сообщение 
if(isset($_SESSION['msg']) and isset($_SESSION['type'])){
	$tpl->load_template('info.htm');
	$tpl->set('{type}', $_SESSION['type']);
	$tpl->set('{msg}', $_SESSION['msg']);
	$tpl->compile( 'info' ); 
	$tpl->clear();	
	
	unset ($_SESSION['type']);
	unset ($_SESSION['msg']);
}
//Выводим сообщение	

##Рабочие часы
function work_time($start_date, $finish_date,$unixtime=false){  
			##Начальная дата
            $start  = $start_date;
            ##Конечная дата
            $finish = $finish_date; 
            ##ХЗ     
            $hours = $minutes = 0;
            ##Начальная дата
            $s = $start;
            ##Если начальная дата меньше конечной
            while ($s < $finish){
				##Прибавляем к начальной дате час(хз зачем)
                $s += 3600;
                ##Конвентируем в часы
				$vhour = date("H", $s);
				//echo $vhour."<br>";
				##Если час (больше или равняется 9) или (меньше 21) или (не равно 13)
				if ($vhour >= 9 and $vhour < 21 and $vhour <> 13) {
					$minus = 0;
					##Если начальная больше меньше конечной
					if ($s > $finish) {
						##Отнимаем из начальной конечную делим на 60 и округляем до целого (хз зачем)
						$minus = round(($s - $finish) / 60);
					}
					##Прибавляем к минутам 60 секунд (?)
					$minutes += (60 - $minus);
				}
            }
            //echo "<br>";
            if($unixtime){ 
				$sec = $minutes * 60;
				return $sec;           
            }else{
				$hours = round($minutes/60);    
				return $hours;
            }
}

function downcounter($sdate,$date){
	$check_time = strtotime($date) - $sdate;
	if($check_time <= 0){
		return false;
	}

	$days = floor($check_time/86400);
	$hours = floor(($check_time%86400)/3600);
	$minutes = floor(($check_time%3600)/60);
	$seconds = $check_time%60; 

	$str = '';
	if($days > 0) $str .= declension($days,array('день','дня','дней')).' ';
	if($hours > 0) $str .= declension($hours,array('час','часа','часов')).' ';
	if($minutes > 0) $str .= declension($minutes,array('минута','минуты','минут')).' ';
	if($seconds > 0) $str .= declension($seconds,array('секунда','секунды','секунд'));

	return $str;
}


function work_time_test($start_date, $finish_date,$unixtime=false){  
			##Со скольки начинается рабочий день
			$sw = 9;
			##Во сколько заканчитвается рабочий день
			$ew = 21;
			##Со скольки обед (час обеда)
			$o = 13;
			##Начальная дата
            $start  = $start_date;
            ##Конечная дата
            $finish = $finish_date; 
            ##Начальная дата
            $s = $start;
            
            ##Начальная дата плюс час
            $ss = $start + 3600;
            
            ##Начальная дата вывод даты
            $s_date = date("Y-m-d",$s);
            
            ##Начальная дата плюс час вывод время
            //$ss_time = date("H:i:s",$ss);
            
            ///$date = '2016-05-24 16:32:45';
			///$timestamp = strtotime($date);
            
            
            ##Начальная дата в часы
			$s_hour = date("H", $ss);

			##Если час (больше или равняется 9) и (меньше 21) и (не равно 13)
			if ($s_hour >= $sw and $s_hour < $ew and $s_hour <> $o) {
				
				##Дикий изврат получения следующего часа
				$pieces = $s_hour.":00:00";
				$pieces = $s_date.' '.$pieces;
				##В юникс тайм
				$pieces = strtotime($pieces);
				$pieces = $pieces - $s;
				
			}
			echo $pieces;
			echo "<br>";
			##Вычисляем минуты между часами
            ##Если начальная дата меньше конечной
            while ($s < $finish){
				##Прибавляем к начальной дате час(хз зачем)
                $s += 3600;
                ##Конвентируем в часы
				$vhour = date("H", $s);
				//echo $vhour."<br>";
				##Если час (больше или равняется 9) или (меньше 21) или (не равно 13)
				if ($vhour >= 9 and $vhour < 21 and $vhour <> 13) {
					$minus = 0;
					##Если начальная больше меньше конечной
					if ($s > $finish) {
						##Отнимаем из начальной конечную делим на 60 и округляем до целого (хз зачем)
						$minus = round(($s - $finish) / 60);
					}
					##Прибавляем к минутам 60 секунд (?)
					$minutes += (60 - $minus);
				}
            }
            //echo "<br>";
            if($unixtime){ 
				$sec = $minutes * 60;
				return $sec;           
            }else{
				$hours = round($minutes/60);    
				return $hours;
            }
}

##Юникстайм в дататайм
function date_time($time){
	$date_time = date("H:i:s d-m-Y",$time);
	return $date_time;
}

##Вывод селекта
function select($base,$name,$value,$sort=Null) { 
			global $DB1;
			
			if($sort){
				$order = "ORDER BY `$sort`";
			}else{
				$order = "";
			}
			
			$result = $DB1->query("SELECT `$name`,`$value` FROM `$base` $order"); 
			$cons = "";
			while($row = mysql_fetch_array($result)){
				$cons .= "<option value='$row[$value]'>$row[$name]</option>";
			}	
			
			return $cons;
}





function generateCode($length) { 
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789"; 
	$code = ""; 
	$clen = strlen($chars) - 1;   
	while (strlen($code) < $length) { 
		$code .= $chars[mt_rand(0,$clen)];   
	} 
	return $code; 
}
//Доступ по Ip
function check_ip($ip){
	//$ip=$_SERVER['REMOTE_ADDR']; 
	$dStart1="10.0.0.0"; 
	$dEnd1="10.0.0.255"; 

	//~ $dStart2="194.28.212.0"; 
	//~ $dEnd2="194.28.215.255";
	//~ $dStart3="10.0.0.0"; 
	//~ $dEnd3="10.255.255.255";  
	//~ $dStart4="172.16.0.0"; 
	//~ $dEnd4="172.31.255.255";  

	if ($ip>=$dStart1 AND $ip<=$dEnd1) { 

		//echo "Все ок</br>";
		//echo $ip;
		
	} else { 
		//echo $ip;
		//exit('Доступ запрещен');
		
	} 
}



##Генерирация пароля
function image_random($folder)
{
	$images = array();
	$all_files = scandir($folder);
	while ($i++ < sizeof($all_files)){
		if ($all_files[$i]!==".." and !empty($all_files[$i])) array_push($images, $all_files[$i]);
	}
	$img_random = $images[rand(0,sizeof($images)-1)];
	$img_src = $folder."/".$img_random;
	return $img_src;
}

//Проценты
function procent($total, $totalall){
	$sto = "100";	
	$totalg1 = ($total / $totalall)* $sto;
	$totalg1 = round($totalg1, 1);
	return $totalg1;
}

//Случайный цвет
function random_html_color()
{
    return sprintf( '#%02X%02X%02X', rand(0, 255), rand(0, 255), rand(0, 255) );
}

##Генерация пароля
function generate_password($number){
	global $usenums, $usecaps,$uselower,$usesymbols;
	
	if(isset($usenums) and isset($usecaps) and isset($uselower) and isset($usesymbols)){
	$arr = array('a','b','c','d','e','f',
				 'g','h','i','j','k','l',
				 'm','n','o','p','r','s',
				 't','u','v','x','y','z',
				 'A','B','C','D','E','F',
				 'G','H','I','J','K','L',
				 'M','N','O','P','R','S',
				 'T','U','V','X','Y','Z',
				 '1','2','3','4','5','6',
				 '7','8','9','0','.',',',
				 '(',')','[',']','!','?',
				 '&','^','%','@','*','$',
				 '<','>','/','|','+','-',
				 '{','}','`','~');
	}elseif(isset($usenums) and isset($usecaps) and isset($uselower)){
	$arr = array('a','b','c','d','e','f',
				 'g','h','i','j','k','l',
				 'm','n','o','p','r','s',
				 't','u','v','x','y','z',
				 'A','B','C','D','E','F',
				 'G','H','I','J','K','L',
				 'M','N','O','P','R','S',
				 'T','U','V','X','Y','Z',
				 '1','2','3','4','5','6',
				 '7','8','9','0');
	}elseif(isset($usenums) and isset($usecaps) and isset($usesymbols)){
	$arr = array('A','B','C','D','E','F',
				 'G','H','I','J','K','L',
				 'M','N','O','P','R','S',
				 'T','U','V','X','Y','Z',
				 '1','2','3','4','5','6',
				 '7','8','9','0','.',',',
				 '(',')','[',']','!','?',
				 '&','^','%','@','*','$',
				 '<','>','/','|','+','-',
				 '{','}','`','~');
	}elseif(isset($usenums) and isset($uselower) and isset($usesymbols)){
	$arr = array('a','b','c','d','e','f',
				 'g','h','i','j','k','l',
				 'm','n','o','p','r','s',
				 't','u','v','x','y','z',
				 '1','2','3','4','5','6',
				 '7','8','9','0','.',',',
				 '(',')','[',']','!','?',
				 '&','^','%','@','*','$',
				 '<','>','/','|','+','-',
				 '{','}','`','~');
	}elseif(isset($usenums) and isset($usecaps)){
	$arr = array('A','B','C','D','E','F',
				 'G','H','I','J','K','L',
				 'M','N','O','P','R','S',
				 'T','U','V','X','Y','Z',
				 '1','2','3','4','5','6',
				 '7','8','9','0');
	}elseif(isset($usenums) and isset($uselower)){
	$arr = array('a','b','c','d','e','f',
				 'g','h','i','j','k','l',
				 'm','n','o','p','r','s',
				 't','u','v','x','y','z',
				  '1','2','3','4','5','6',
				 '7','8','9','0');
	}elseif(isset($usenums) and isset($usesymbols)){
	$arr = array('1','2','3','4','5','6',
				 '7','8','9','0','.',',',
				 '(',')','[',']','!','?',
				 '&','^','%','@','*','$',
				 '<','>','/','|','+','-',
				 '{','}','`','~');
	}else{
	$arr = array('1','2','3','4','5','6',
				 '7','8','9','0');
	
	}


	// Генерируем пароль
	$pass = "";
	for($i = 0; $i < $number; $i++){
	  // Вычисляем случайный индекс массива
	  $index = rand(0, count($arr) - 1);
	  $pass .= $arr[$index];
	}
	return $pass;
}

/*
* Функция, получающая из метки времени в формате UNIX TIME
* месяцы, дни, часы, минуты и секунды
*
* $t - значение UNIX timestamp, которое нужно перевести
* в месяцы, дни, часы, минуты и секунды
*/

function parse_timestamp( $t = 0 ){
	$month = floor( $t / 2592000 );
	$day = ( $t / 86400 ) % 30;
	$hour = ( $t / 3600 ) % 24;
	$min = ( $t / 60 ) % 60;
	$sec = $t % 60;

	//return array( 'month' => $month, 'day' => $day, 'hour' => $hour, 'min' => $min, 'sec' => $sec );
	$time_diff_str = '0';
	if ($month == 0 and $day == 0 and $hour == 0 and $min == 0 and $sec != 0){      
			$time_diff_str = "$sec с";
	}	
	elseif ($month == 0 and $day == 0 and $hour == 0 and $min != 0){		      
			//$time_diff_str = "$min м $sec с";
			$time_diff_str = "$min мин.";
	}	
	elseif ($month == 0 and $day == 0 and $hour != 0){	      
			$time_diff_str = "$hour час. $min мин.";
	}	
	elseif ($month == 0 and $day != 0){	      
			$time_diff_str = "$day д $hour ч $min мин.";
	}
	elseif ($month != 0){		      
			$time_diff_str = "$month мин. $day д $hour ч";
	}
	
	return $time_diff_str;
}
?>
