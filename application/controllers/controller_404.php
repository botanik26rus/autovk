<?php

class Controller_404 extends Controller
{
	//страница ошибки
	function action_index(){
		global $DB1,$v_us;
		 

		$this->view->title = 'Страница не найдена';		
		$this->view->load_template('error/404.htm');
		//if($v_us['sex'] == 0){
			$this->view->set('{image}', '/images/404.jpg');
		//}else{
			//$this->view->set('{image}', '/images/404.png');
		//}
		$this->view->compile( 'page' ); 
		$this->view->clear();
		$this->view->full_page();
	}
	
	//Сайт на обслуживании
	function action_service(){

	}

}
