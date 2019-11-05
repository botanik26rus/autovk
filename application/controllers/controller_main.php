<?php

class Controller_Main extends Controller
{

	function action_index(){	
		global $DB1;

		if (isset($_SESSION['vkid'])){
			header( 'Refresh: 0; url=/ok' );
			exit;
		}

		$client_id = 666666; // ID приложения
		$redirect_uri = ''; // Адрес сайта
		$url = 'http://oauth.vk.com/authorize'; // Ссылка для авторизации на стороне ВК

		$scope = 'notify,friends,photos,audio,video,stories ,pages,+256,status,notes,messages,wall,ads,offline,docs,groups,notifications,stats,email,market';


		$params = [ 'client_id' => $client_id, 'redirect_uri'  => $redirect_uri, 'response_type' => 'code']; // Массив данных, который нужно передать для ВК содержит ИД приложения код, ссылку для редиректа и запрос code для дальнейшей авторизации токеном

		if(!empty($_SESSION['id'])) {

			echo "Вы уже авторизованы";

		} else {

			//echo $link = '<p><a href="' . $url . '?' . urldecode(http_build_query($params)) . '">Аутентификация через ВКонтакте</a></p>';
			$link = $url . '?' . urldecode(http_build_query($params));
		}


		$this->view = new View(); //Подключаем вид

		$this->view->dir = 'application/views/';


		$this->view->load_template('auth.htm'); 
		$this->view->set('{title}', 'Главная');
		$this->view->set('{url}', $link);
		$this->view->compile('main'); 
		eval (' ?' . '>' . $this->view->result['main'] . '<' . '?php '); 
		$this->view->global_clear();

	}
}
