<?php
//mysqli.class.php
class mysqlDB {
	##Подключение к mysqli
	function connect($db_host, $db_login, $db_passwd, $true = NULL ) {
		## устанавливаем подключение с бд
		$this->conn = mysqli_connect($db_host, $db_login, $db_passwd, $true) or die(mysqli_error());		
	}
	##Выбор базы
	function select_db($db_name,$chars=NULL){
		## выбираем базу данных
		if(mysqli_select_db($this->conn,$db_name)){ 
			if($chars){ 
				## указываем что передаем данные в utf8
				mysqli_set_charset($this->conn, $chars);
				//mysqli_query("set names $chars") or die ("<br>Неверный запрос: " . mysqli_error()); 
			}
		}else{
			print '<br>Ошибка подключения к базе: '. mysqli_error($this->conn);
			return false;
			
		}
	}
	#	Запрос к базе и его производные
	function query($query, $type = Null, $num = Null) {
		global $connect;
		
		if ($q=mysqli_query($this->conn,$query)) {
			switch ($type) {
				case 'num_row' : 
					return mysqli_num_rows($q); 
				break;
				case 'result' : 
					return mysqli_result($q, $num); 
				break;
				case 'assoc' : 
					return mysqli_fetch_assoc($q); 
				break;
				case 'array' : 
					return mysqli_fetch_array($q); 
				break;
				case 'none' : 
					return $q;
				default: return $q;
			}
		} else {
			print '<br>Ошибка mysqli: '.mysqli_errno($this->conn).' : '.mysqli_error($this->conn). "<br>";
			return false;
		}
	}
	
	function insertid(){
		return mysqli_insert_id($this->conn);
	}
		
	#	экранирование данных 
	function screening($data) {
		##удаление пробелов из начала и конца строки
		$data = trim($data); 
		##экранирование символов
		return mysqli_real_escape_string($data); 
	}
	##Вставить строку в массив столбец=> значение(Старая)
	function insert_array($table,$param){
		global $connect;
		
		//$value =  '(\''.implode("','", $param).'\')';
		$value =  '('.implode(",", $param).')';
		$param = array_flip($param);
		$data =  '(`'.implode("`,`", $param).'`)';
		echo $value.'<br>';
		echo $data;
		//print_r($param);
		if (mysqli_query("INSERT INTO $table $data  VALUES $value",$this->conn)) {
			return $this->insertid();
		} else {
			print '<br>Ошибка mysqli: '.mysqli_errno($this->conn).' '.mysqli_error($this->conn);
			return false;
		}
		

	}
	
	##Вставить строку в массив столбец=> значение
	function insert($table,$keyValue){ 
	if(is_array($keyValue)){
		foreach($keyValue as $key => $value){
			$fields[] = '`'.$key.'`';
			$values[] = ''.$value;
		}
		
		//return 'INSERT INTO '.$table.' ('.implode(' , ',$fields).') VALUES '.'('.implode(' , ',$values).')';
		
		if (mysqli_query($this->conn,'INSERT INTO '.$table.' ('.implode(' , ',$fields).') VALUES '.'('.implode(' , ',$values).')')) {
			return $this->insertid();
		} else {
			echo 'INSERT INTO '.$table.' ('.implode(' , ',$fields).') VALUES '.'('.implode(' , ',$values).')';
			print '<br>Ошибка mysqli: '.mysqli_errno($this->conn).' : '.mysqli_error($this->conn). "<br>";
			return false;
		}
	}
		return '';
	}

	function insert_string($string){
		$value = "'";
		$value .= $string ;
		$value .= "'";
		return $value;
	}
	
	function mysqli_real_escape_string_ex($value){
		$value = mysqli_real_escape_string($this->conn,$value);
		return $value;
	}

}

?>
