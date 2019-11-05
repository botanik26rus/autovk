<?php
class Route{	
	public static $controllers = ''; //Имя контроллера
	public static $route = '';
	static function start(){	
		GLOBAL $v_us,$DB1;

		//контроллер и метод(экшен) по умолчанию(Если незя составить из адреса)
		$controller_name = 'Main';
		$action_name = 'index';
		
		$URI = explode('/', $_SERVER['REQUEST_URI']);
		
 
		// получаем имя контроллера
		if ( !empty($URI[1]) ){	
			$controller_name = $URI[1];
  
		}

		// получаем имя экшена
		if ( !empty($URI[2]) ){
			$action_name = $URI[2];
			//	echo "<br>Экшен ".$action_name;
		}
		
		self::$controllers = $controller_name;
	 
		// добавляем префиксы к  контролеру экшену и модели (удобно так)
		$model_name = 'Model_'.$controller_name; //должно быть первым ибо не коректно потом имя модели(испр)
		$controller_name = 'Controller_'.$controller_name;
		$action_name = 'action_'.$action_name;


		// подцепляем файл с классом модели (файла модели может и не быть)
		$model_file = strtolower($model_name).'.php'; //В нижний регистр
		$model_path = "application/models/".$model_file; //Составляем путь для файла модели
		
		
		if(file_exists($model_path)){ //Если есть файл модели
			include "application/models/".$model_file; //То подключаем  класс с моделью
		}
		
		// подцепляем файл с классом контроллера
		$controller_file = strtolower($controller_name).'.php'; //В нижний регистр
		$controller_path = "application/controllers/".$controller_file; //Составляем путь для файла контроллера
		
		if(file_exists($controller_path)){ //Если есть файл контроллера
			include "application/controllers/".$controller_file;  //То подключаем  класс сконтроллером
			// создаем контроллер
			$controller = new $controller_name;
			$action = $action_name;
			$controller->route = $URI;//Свойство Класса (который будет создан) с массивом URI (что после домена)
			
			if(method_exists($controller, $action)){ //Проверяем есть ли метод 
				// вызываем действие контроллера
				$controller->$action();
			}else{
				self::ErrorPage404();
			}
		}else{
			/*
			правильно было бы кинуть здесь исключение,
			но для упрощения сразу сделаем редирект на страницу 404
			*/
			if (isset($_GET['code'])) {
				
				$client_id = 666666; // ID приложения
				$client_secret = ''; // Защищённый ключ
				$redirect_uri = ''; // Адрес сайта

				
				$result = true;
				$params = [
					'client_id' => $client_id,
					'client_secret' => $client_secret,
					'code' => $_GET['code'],
					'redirect_uri' => $redirect_uri
					
				];

				$token = json_decode(file_get_contents('https://oauth.vk.com/access_token' . '?' . urldecode(http_build_query($params))), true);

				if (isset($token['access_token'])) {
					$params = [
						'uids' => $token['user_id'],
						'fields' => 'uid,first_name,last_name,screen_name,sex,bdate,photo_big',
						'access_token' => $token['access_token'],
						'v' => '5.101'];

					$userInfo = json_decode(file_get_contents('https://api.vk.com/method/users.get' . '?' . urldecode(http_build_query($params))), true);
					if (isset($userInfo['response'][0]['id'])) {
						$userInfo = $userInfo['response'][0];
						$result = true;
					}
				}

				if ($result) {
					
					//Если есть в БД
					if ($DB1->query("SELECT * FROM `vk_acc` WHERE  `vkid` =  '{$userInfo['id']}'", 'num_row')==1) {

						if($DB1->query ("UPDATE  `vk_acc` SET `token` = '{$token['access_token']}' WHERE `vkid` = '{$userInfo['id']}'")){
							//зафигачить лог
							//echo (0);
						}else{
							//зафигачить лог
							//echo (1);
						}
					}else{
						$username = $userInfo['last_name'].' '.$userInfo['first_name'];
						if($DB1->query("INSERT INTO `vk_acc` (`vkid`, `username`, `time`, `token`) VALUES ('{$userInfo['id']}', '{$username}', '', '{$token['access_token']}')")){
							//зафигачить лог
							//echo (0);
						}else{
							//зафигачить лог
							//echo (1);
						} 
					}	
						
		
					////echo "ID пользователя: " . $userInfo['id'] . '<br />';
					//echo "Фамилия пользователя: " . $userInfo['last_name'] . '<br />';
					//echo "Имя пользователя: " . $userInfo['first_name'] . '<br />';
					//echo "Ссылка на профиль: " . $userInfo['screen_name'] . '<br />';
					//echo "Пол: " . $userInfo['sex'] . '<br />';
					//echo "День Рождения: " . $userInfo['bdate'] . '<br />';
					//echo '<img src="' . $userInfo['photo_big'] . '" />'; echo "<br />";
					
					$_SESSION['vkid'] = $userInfo['id'];
					header( 'Refresh: 0; url=/ok' );
					exit;
				}
				
			}else{
				self::ErrorPage404();
			}
			
		}
		

	}

	static function ErrorPage404(){
			include "application/controllers/controller_404.php";
			$controller = new Controller_404;
			$controller->action_index();
			exit;
    }
    

}
