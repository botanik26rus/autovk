<?php
##Функции для вк

#Количество друзей							
function friends_count($uid){	
	global $vk;
	$friends = $vk->api('friends.get', array('user_id'=>$uid,));
	if (isset($friends["response"])){
		$friends_count = count($friends["response"]);
	}
	if (isset($friends_count)){
		return $friends_count;
	}
}

#Количество подписчиков							
function podp_count($uid){	
	global $vk;
	$friends = $vk->api('friends.get', array('user_id'=>$uid,));
	if (isset($friends["response"])){
		$friends_count = count($friends["response"]);
	}
	if (isset($friends_count)){
		return $friends_count;
	}
}


##Город							
function city($city_ids){
	global $vk;

	$city = $vk->api('database.getCitiesById', array('city_ids'=> $city_ids));
	if (isset($city["response"][0]["name"])){
		return $city["response"][0]["name"];
	}
}
##Страна
function country($country_ids){
	global $vk;

	$country = $vk->api('database.getCountriesById', array('country_ids'=> $country_ids));
	
	if (isset($country["response"][0]["name"])){
		return $country["response"][0]["name"];
	}
}
##Токен пользователя
function token($vk_id){
	global $DB1;
	$result = $DB1->query("SELECT `token` FROM `vk_acc` WHERE `vkid` = $vk_id",'assoc'); 
	return $result['token'];
}

?>
