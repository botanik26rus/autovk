<?php
class Telegram
{
	public $token;
	
	const BASE_API_URL = 'https://api.telegram.org/bot' ;

	/**
	 * @param String $hookUrl - адрес на нашем сервере, куда будут приходить обновления
	 * @return mixed|null
	 */
	public function setWebHook($hookUrl,$certificate=NULL)
	{
		return $this->sendPost('setWebHook', ['url' => $hookUrl,'certificate' => $certificate]) ;
	}
        public function getWebhookInfo($hookUrl)
        {
                return $this->sendPost('getWebhookInfo', ['url' => $hookUrl]) ;
        }
	/**
	 * @return mixed
	 */
	public function getUpdates()
	{
		$data = file_get_contents($this->buildUrl('getUpdates')) ;
		return json_decode($data, true) ;
	}

	/**
	 * @param int $chatId - ID чата, в который отправляем сообщение
	 * @param String $message - текст сообщения
	 * @param array $params - дом.параметры (опционально)
	 * @return mixed
	 */
	public function sendMessage($chatId, $message, $params = [])
	{
        if(!is_array($params)) {
            $params = array() ;
        }

        $params['chat_id'] = $chatId ;
       // $params['text'] = strip_tags($message) ; // Telegram не понимает html-тегов
		$params['text'] = $message ;
		##parse_mode=HTML (Разрешить обробатывать html теги хз правда какие)
		$url = $this->buildUrl('sendMessage').'?parse_mode=HTML&'.http_build_query($params) ;

        $data = file_get_contents($url) ;
        return json_decode($data, true) ;
	}

	/**
	 * @param String $methodName - имя метода в API, который вызываем
	 * @param array $data - параметры, которые передаем, необязательное поле
	 * @return mixed|null
	 */
	private function sendPost($methodName, $data = [])
	{
		$result = null;
		
		if(is_array($data))
		{
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL, $this->buildUrl($methodName));
			curl_setopt($ch,CURLOPT_POST, count($data));
			curl_setopt($ch,CURLOPT_POSTFIELDS, http_build_query($data));
			$result = curl_exec($ch);
			curl_close($ch);
		}
		
		return $result;
		
	}

	/**
	 * @param String $methodName - имя метода в API, который вызываем
	 * @return string - Софрмированный URL для отправки запроса
	 */
	private function buildUrl($methodName)
	{
		return self::BASE_API_URL.$this->token.'/'.$methodName ;
	}
	
	
} // end class
