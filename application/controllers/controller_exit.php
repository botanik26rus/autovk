<?php

class Controller_Exit extends Controller
{

	function __construct(){
		unset($_SESSION['vkid']);
		header ('Location: /');
		exit;
	}
}
