<?php

class View{
	public  $vusid = '';
	var $title = 'none'; //Название страницы
    public  $dir = '.'; 
    public  $dirdefault = ''; 
    public  $template = null; 
    public  $copy_template = null; 
    public  $data = array(); 
    public  $block_data = array(); 
    public  $result = array('info' => '', 'content' => ''); 
    public  $template_parse_time = 0; 
	
	//public $template_view; // здесь можно указать общий вид по умолчанию.
	
	/*
	$content_file - виды отображающие контент страниц;
	$template_file - общий для всех страниц шаблон;
	$data - массив, содержащий элементы контента страницы. Обычно заполняется в модели.
	*/
	function generate($content_view, $template_view, $data = null)
	{
		global $tpl;
		
		if(is_array($data)) {
			// преобразуем элементы массива в переменные
			extract($data);
		}
		

		/*
		динамически подключаем общий шаблон (вид),
		внутри которого будет встраиваться вид
		для отображения контента конкретной страницы.
		*/
		//include 'application/views/'.$template_view;
	}
	
	public function page(){
		global $DB1,$v_us,$set,$user;

		$this->load_template('topmenu.htm');
		$this->compile( 'topmenu' ); 
		$this->clear();
		
		$this->load_template('empty.htm');
		$this->compile( 'sidebar' ); 
		$this->clear();
		
 
		$this->load_template('page.htm');
		$this-> set ( '{sidebar}', $this->result['sidebar']);
		$this-> set ( '{static}', $this->result['static']);
		$this->compile( 'page' ); 
		$this->clear();


			
		$this->load_template('main.htm'); 
		$this->set('{title}', $this->title);
		$this-> set ( '{page}', $this->result['page']);
		$this-> set ( '{sidebar}', $this->result['sidebar']);
		$this-> set ( '{topmenu}', $this->result['topmenu']);
		$this->compile('main'); 
		eval (' ?' . '>' . $this->result['main'] . '<' . '?php '); 
		$this->global_clear();
	}
	
	//Полная страница
	public function full_page(){
		global $DB1,$user,$v_us;

		
		$this->load_template('topmenu.htm');
		$this->set('{vusid}',$this->vusid);
		$this->set('{title}', "");
		$this->compile( 'topmenu' ); 
		$this->clear();
		
	

		//Чтоб не было ошибки
		$this->set('', '');
		$this->compile( 'sidebar' );
		$this->clear();
		
		//Чтоб не было ошибки
		$this->set('', '');
		$this->compile( 'static' );
		$this->clear();


		
		$this->load_template('full_page.htm');
		$this-> set ( '{sidebar}', '');
		$this-> set ( '{static}', '');
		$this->compile( 'page' ); 
		$this->clear();
		
			
		$this->load_template('main.htm'); 
		$this->set('{title}', $this->title);
		$this-> set ( '{page}', $this->result['page']);
		$this-> set ( '{sidebar}', $this->result['sidebar']);
		$this-> set ( '{topmenu}', $this->result['topmenu']);
		$this->compile('main'); 
		eval (' ?' . '>' . $this->result['main'] . '<' . '?php '); 
		$this->global_clear();
	}	
	//задаём параметры основных переменных подгрузки шаблона 
    public function set($name , $var) { 
        if (is_array($var) && count($var)) { 
            foreach ($var as $key => $key_var) { 
                $this->set($key , $key_var); 
            } } else $this->data[$name] = $var; 
    } 

	//обозначаем блоки 
    public function set_block($name , $var) { 
        if (is_array($var) && count($var)) { 
            foreach ($var as $key => $key_var) { 
                $this->set_block($key , $key_var); 
            } } else $this->block_data[$name] = $var; 
    } 

	//производим загрузку каркасного шаблона 
    public function load_template($tpl_name) { 
    $time_before = $this->get_real_time(); 
        if ($tpl_name == '' or !file_exists($this->dir . DIRECTORY_SEPARATOR . $tpl_name)) { 
			if ($tpl_name == '') { 
				die ("Невозможно загрузить основной шаблон: ". $tpl_name); 
				return false;
			}
			if (!file_exists($this->dir . DIRECTORY_SEPARATOR . $tpl_name)) { 
				##Если не возможно загрузить шаблон Берем из папки Default
				if (!file_exists($this->dirdefault . DIRECTORY_SEPARATOR . $tpl_name)) {			
					die ("Невозможно загрузить шаблон по умолчанию: ". $tpl_name); 
					return false;	
				}
				
				$this->template = file_get_contents($this->dirdefault . DIRECTORY_SEPARATOR . $tpl_name); 
			}
		}else{
		
			$this->template = file_get_contents($this->dir . DIRECTORY_SEPARATOR . $tpl_name); 
		}

        if ( stristr( $this->template, "{include file=" ) ) { 
            $this->template = preg_replace( "#\\{include file=['\"](.+?)['\"]\\}#ies","\$this->sub_load_template('\\1')", $this->template); 
        } 
        $this->copy_template = $this->template; 
    $this->template_parse_time += $this->get_real_time() - $time_before; 
    return true; 
    } 

	// этой функцией загружаем "подшаблоны" 

    public function sub_load_template($tpl_name) { 
        if ($tpl_name == '' or !file_exists($this->dir . DIRECTORY_SEPARATOR . $tpl_name)) { 
			die ("Невозможно загрузить шаблон: ". $tpl_name); 
			return false;
        } 
        $template = file_get_contents($this->dir . DIRECTORY_SEPARATOR . $tpl_name); 
        return $template; 
    } 

	// очистка переменных шаблона 
    public function _clear() { 
    $this->data = array(); 
    $this->block_data = array(); 
    $this->copy_template = $this->template; 
    } 

    public function clear() { 
    $this->data = array(); 
    $this->block_data = array(); 
    $this->copy_template = null; 
    $this->template = null; 
    } 
	//полная очистка включая результаты сборки шаблона 
    public function global_clear() { 
    $this->data = array(); 
    $this->block_data = array(); 
    $this->result = array(); 
    $this->copy_template = null; 
    $this->template = null; 
    } 
	//сборка шаблона в единое целое 
    public function compile($tpl) { 
    $time_before = $this->get_real_time(); 
    foreach ($this->data as $key_find => $key_replace) { 
                $find[] = $key_find; 
                $replace[] = $key_replace; 
            } 
    if(empty($find)){$find = array();} //Фикс для другого хостинка (в чем проблема хз)
	if(empty($replace)){$replace = array();} //Фикс для другого хостинка (в чем проблема хз)
    $result = str_replace($find, $replace, $this->copy_template); 
    if (count($this->block_data)) { 
        foreach ($this->block_data as $key_find => $key_replace) { 
                $find_preg[] = $key_find; 
                $replace_preg[] = $key_replace; 
                } 
    $result = preg_replace($find_preg, $replace_preg, $result); 
    } 
    if (isset($this->result[$tpl])) $this->result[$tpl] .= $result; else $this->result[$tpl] = $result; 
    $this->_clear(); 
    $this->template_parse_time += $this->get_real_time() - $time_before; 
    } 
	//счётчик времени выполнения запросов сборки 
    public function get_real_time() 
    { 
        list($seconds, $microSeconds) = explode(' ', microtime()); 
        return ((float)$seconds + (float)$microSeconds); 
    } 
}
