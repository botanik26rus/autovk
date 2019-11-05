<?php

function Send_Post($post_url, $post_data, $refer){ 
  $ch = curl_init(); 
  curl_setopt($ch, CURLOPT_URL, $post_url); 
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
  //curl_setopt($ch, CURLOPT_REFERER, $refer); 
  curl_setopt($ch, CURLOPT_POST, 1); 
  curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data); 
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15); 
  curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Mobile Safari/537.36'); 
  
  $data = curl_exec($ch); 
  curl_close($ch); 
  
  return $data; 
} 

class vk {
	var $api_secret;
	var $app_id;
	var $api_url ='https://api.vk.com/method/';
	
	##Функция отправки методов вк
	function api_old($method,$params=false) {
		global $api_id,$api_secret,$api_url;
		$url = $api_url.$method;

		##В $result вернется id отправленного сообщения
		$result = file_get_contents($url, false, stream_context_create(array(
			'http' => array(
				'method'  => 'POST',
				'header'  => 'Content-type: application/x-www-form-urlencoded',
				'content' => http_build_query($params)
			)
		)));
		##Декодируем JSON
		$res = json_decode($result, true);
		##Если есть ошибки, если нет ошибок
		if (array_key_exists('error',$res)) {
			//echo "Есть ошибки";
		}else{
			//echo "Нет ошибок";
		}
		return $res;
	}
	
	##Функция отправки методов вк
	function api($method,$params=false) {
		global $api_id,$api_url;
		##url для получения метода
		
		$url = $this->api_url.$method;
		if(!isset($params['v'])){
			$params['v'] = '5.74';
		}
		$params['format'] = 'json';
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
		curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 1);
		//curl_setopt($curl, CURLOPT_HTTPPROXYTUNNEL, '104.28.2.91');
		curl_setopt($curl, CURLOPT_USERAGENT,"Mozilla/5.0 (Linux; U; Android 2.2; en-us; SCH-I800 Build/FROYO) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1"); 
		
		$res = curl_exec($curl);
		
		
		$res = json_decode($res, true);
		##Если есть ошибки, если нет ошибок

		return $res;	
		curl_close($curl);
	}
	
	##Послать сообщение ##$to кому ##$mess текст сообщения ##$token токен
	function send_mess($to,$mess,$token) {
		$res = $this->api('messages.send', array(
											'user_id'=> $to,
											'message'=>$mess,
											'access_token'=>$token
		));
		
		##Если есть ошибки, если нет ошибок
		if (array_key_exists('error',$res)) {
			echo "Есть ошибки";
			return $res;
		}else{
			echo "Нет ошибок";
			return $res;
		}
		
	}
	
	function errors($error){
		switch($mod){
			default:
			break;	
			case'1':
				$error = 'Произошла неизвестная ошибка.'; 
			break;
				case'2':
			$error = 'Приложение выключено.'; 
			break;
			case'3':
				$error = 'Передан неизвестный метод. '; 
			break;
			case'4':
				$error = 'Неверная подпись. '; 
			break;
			case'5':
				$error = 'Авторизация пользователя не удалась.'; 
			break;
			case'6':
				$error = 'Слишком много запросов в секунду. '; 
			break;
			case'7':
				$error = 'Нет прав для выполнения этого действия. '; 
			break;
			case'8':
				$error = 'Неверный запрос. '; 
			break;
			case'9':
				$error = 'Слишком много однотипных действий. '; 
			break;
			case'10':
				$error = 'Произошла внутренняя ошибка сервера. '; 
			break;
			case'11':
				$error = 'В тестовом режиме приложение должно быть выключено или пользователь должен быть залогинен.'; 
			break;
			case'14':
				$error = 'Требуется ввод кода с картинки (Captcha). '; 
			break;
			case'15':
				$error = 'Доступ запрещён. '; 
			break;
			case'16':
				$error = 'Требуется выполнение запросов по протоколу HTTPS, т.к. пользователь включил настройку, требующую работу через безопасное соединение. '; 
			break;
			case'17':
				$error = 'Требуется валидация пользователя. '; 
			break;
			case'18':
				$error = 'Страница удалена или заблокирована. '; 
			break;
			case'20':
				$error = 'Данное действие запрещено для не Standalone приложений. '; 
			break;
			case'21':
				$error = 'Данное действие разрешено только для Standalone и Open API приложений.'; 
			break;
			case'23':
				$error = 'Метод был выключен. '; 
			break;
			case'24':
				$error = 'Требуется подтверждение со стороны пользователя.'; 
			break;
			case'27':
				$error = 'Ключ доступа сообщества недействителен.'; 
			break;
			case'28':
				$error = 'Ключ доступа приложения недействителен.'; 
			break;
			case'100':
				$error = 'Один из необходимых параметров был не передан или неверен. '; 
			break;
			case'101':
				$error = 'Неверный API ID приложения. '; 
			break;
			case'113':
				$error = 'Неверный идентификатор пользователя. '; 
			break;
			case'150':
				$error = 'Неверный timestamp. '; 
			break;
			case'200':
				$error = 'Доступ к альбому запрещён. '; 
			break;
			case'201':
				$error = 'Доступ к аудио запрещён.'; 
			break;
			case'203':
				$error = 'Доступ к группе запрещён. '; 
			break;
			case'300':
				$error = 'Альбом переполнен.'; 
			break;
			case'500':
				$error = 'Действие запрещено. Вы должны включить переводы голосов в настройках приложения. '; 
			break;
			case'600':
				$error = 'Нет прав на выполнение данных операций с рекламным кабинетом.'; 
			break;
			case'603':
				$error = 'Произошла ошибка при работе с рекламным кабинетом.'; 
			break;
			case'900':
				$error = 'Нельзя отправлять сообщение пользователю из черного списка '; 
			break;
			case'901':
				$error = 'Нельзя первым писать пользователю от имени сообщества. '; 
			break;
			case'902':
				$error = 'Нельзя отправлять сообщения этому пользователю в связи с настройками приватности '; 
			break;
		}
	}
	
	
}

##Видимо этот класс не используется актуальный выше(пока не удалять)
class vkapi {
	var $api_secret;
	var $app_id;
	var $api_url;
	
	function vkapi($app_id, $api_secret, $api_url = 'api.vk.com/api.php') {
		$this->app_id = $app_id;
		$this->api_secret = $api_secret;
		if (!strstr($api_url, 'http://')) $api_url = 'http://'.$api_url;
		$this->api_url = $api_url;
	}


	function api($method,$params=false) {
		if (!$params) $params = array(); 
		$params['api_id'] = $this->app_id;
		$params['v'] = '5.62';
		$params['method'] = $method;
		$params['timestamp'] = time();
		$params['format'] = 'json';
		$params['random'] = rand(0,10000);
		
		ksort($params);
		$sig = '';
		foreach($params as $k=>$v) {
			$sig .= $k.'='.$v;
		}
		$sig .= $this->api_secret;
		$params['sig'] = md5($sig);
		$query = $this->api_url.'?'.$this->params($params);
		$res = file_get_contents($query);
		return json_decode($res, true);
	}



	
	function params($params) {
		$pice = array();
		foreach($params as $k=>$v) {
			$pice[] = $k.'='.urlencode($v);
		}
		return implode('&',$pice);
	}
}
?>
