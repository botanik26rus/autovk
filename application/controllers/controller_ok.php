<?php
class Controller_Ok extends Controller{
	
	function action_index(){
		global $vk,$v_us;
		
		$this->view->title = 'Автоответчик';

		$vk = new vk();
		$response = $vk->api('users.get', array('uids'=> $v_us['vkid'],'fields'=>'sex,bdate,city,country,has_photo,photo_max_orig,online,has_mobile,last_seen,followers_count,counters,relation,exports','access_token'=>$v_us['token']));
 	
		//print_ex($response['response'][0]);

		$this->view->load_template('ok/list.htm');
		$this->view->set('{title}', '');
		
		$this->view->compile( 'static' ); 
		$this->view->clear();
		
		$this->view->load_template('ok/sidebar.htm');
		$this->view->set('{username}', $v_us['username']);
		$this->view->set('{img}', $response['response'][0]['photo_max_orig']);
		$this->view->compile( 'sidebar' ); 
		$this->view->clear();
		
		$this->view->page();
	}

 	
}


	
	

			
