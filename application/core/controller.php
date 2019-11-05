<?php

class Controller {
	
	var $model;
	var $view;
	var $route;
	function __construct(){
		
		GLOBAL $DB1;

		$this->view = new View(); //Подключаем вид
		$this->view->dir = 'application/views/'; //Директория вида
		
		if(Route::$controllers == 'Main'){
			if (isset($_SESSION['vkid'])){
				header("Location: ". "/ok");
				exit;
			}
			
		}else{
			
			if (empty($_SESSION['vkid'])){
				header("Location: ". "/");
				exit;
			}	
		}
		
		$this->view->compile( 'tags' ); 
		$this->view->clear();

	}
	
	function action_index(){
	}

	function ErrorPage404(){
			include "application/controllers/controller_404.php";
			$controller = new Controller_404;
			$controller->action_index();
			exit;
    }
}
