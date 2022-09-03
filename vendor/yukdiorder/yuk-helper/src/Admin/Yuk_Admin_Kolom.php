<?php 
namespace Yukdiorder\Helper\Admin ;

class Yuk_Admin_Kolom {

	public $post_type, $hook, $hook_custom , $posisi= 0 , $kolom = [] , $slug ;

	public function __construct($post_type = 'posts'){
		if($post_type !== 'posts')
		{
			$this->hook = 'manage_edit-'.$post_type.'_columns';
			$this->hook_custom = 'manage_'.$post_type.'_posts_custom_column';
			$this->post_type = $post_type ;
			return $this ;
		}
		$this->hook = 'manage_'.$post_type.'_columns';
		$this->hook_custom = 'manage_posts_custom_column';
		$this->post_type = $post_type ;
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
			
			$this->callback = function($column, $post_id) use ($cb)
			{ if($column == $this->slug )
				{ 
					$cb($post_id); 
				}
			} ;
		}
		return $this ;
	}

	public function run() {
		add_filter($this->hook , [$this, 'header']);
		add_action($this->hook_custom ,$this->callback,10,2 );
	} 
}