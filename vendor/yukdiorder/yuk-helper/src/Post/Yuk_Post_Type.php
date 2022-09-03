<?php 
namespace Yukdiorder\Helper\Post ;

class Yuk_Post_Type{
	
	public function __construct($nama, $args=null)
	{
		$this->nama = $nama;
		$this->args = $args;
		$this->labels= $labels = array(
			'name'                  =>  ucfirst($this->nama) ,
			'singular_name'         =>  ucfirst($this->nama) ,
			'menu_name'             =>  ucfirst($this->nama) ,
			'name_admin_bar'        =>  ucfirst($this->nama) ,
			'archives'              =>  'Item' . ucfirst($this->nama) ,
			'attributes'            =>  'Item Attributes', 'text_domain' ,
			'parent_item_colon'     =>  'Parent Item:', 'text_domain' ,
			'all_items'             =>  'Semua '. ucfirst($this->nama) ,
			'add_new_item'          =>  'Tambah '. $this->nama .' Baru' ,
			'add_new'               =>  'Tambah Baru' ,
			'new_item'              =>  'Item Baru' ,
			'edit_item'             =>  'Edit Item',  
			'update_item'           =>  'Update Item' ,
			'view_item'             =>  'Lihat Item', 
			'view_items'            =>  'Lihat Items', 
			'search_items'          =>  'Cari '. $this->nama, 
			'not_found'             =>  'Tidak ditemukan',
			'not_found_in_trash'    =>  'Not found in Trash', 
			'featured_image'        =>  'Featured Image', 
			'set_featured_image'    =>  'Set featured image', 
			'remove_featured_image' =>  'Remove featured image',
			'use_featured_image'    =>  'Use as featured image', 
			'insert_into_item'      =>  'Tambahkan ke item', 
			'uploaded_to_this_item' =>  'unggah item ini', 
			'items_list'            =>  'Items list', 
			'items_list_navigation' =>  'Items list navigation', 
			'filter_items_list'     =>  'Filter items list', 
		);
		if ($args == null) {
			$this->args = $args = array(
				'label'                 => ucfirst($this->nama),
				'description'           => 'type post'. $this->nama,
				'labels'                => $labels,
				'supports'              => array( 'title', 'editor' ),
				'taxonomies'            => array( 'category', 'post_tag' ),
				'hierarchical'          => false,
				'public'                => true,
				'show_ui'               => true,
				'show_in_menu'          => true,
				'menu_position'         => 5,
				'show_in_admin_bar'     => true,
				'show_in_nav_menus'     => true,
				'can_export'            => true,
				'has_archive'           => true,
				'exclude_from_search'   => false,
				'publicly_queryable'    => true,
				'capability_type'       => 'page',
			);
		}
	}

	public function init(){
		register_post_type($this->nama, $this->args);
	}
	
}
?>