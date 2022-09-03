<?php 
namespace Yukdiorder\Helper\User ;

class Yuk_Admin_Kolom_User {

	public $hook, $hook_custom , $posisi= 0 , $kolom = [] , $slug ;

	public function __construct(){
		$this->hook = 'manage_users_columns';
		$this->hook_custom = 'manage_users_custom_column';
	}

	public function set_header($slug, $label){
		$this->slug = $slug ;
		$this->kolom[$slug] = $label ;
	}

	public function set_posisi($number){
		$this->posisi = $number ;
	}
	
	public function header($columns){
		
		if ($this->posisi > 0){			
			$kolom = array_slice($columns, 0, $this->posisi, true) + $this->kolom ;
			return array_merge($kolom , $columns ) ;
		}
		return $columns ;
	}
	
	public function set_isi($cb = null ){
		
		if(is_callable($cb)){
			
			$this->callback = function($value, $column_name, $user_id) use ($cb)
			{ 
				if($column_name == $this->slug )
				{ 
					$module = $cb($user_id);
					return $module['data'] ;
				}
				return $value ; 
			} ;
			return $this ;
		}
		return $this ;
	}

	public function run() {
		add_filter($this->hook , [$this, 'header']);
		add_filter($this->hook_custom ,$this->callback,10,3 );
	} 
}