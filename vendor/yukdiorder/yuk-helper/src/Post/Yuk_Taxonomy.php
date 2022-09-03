<?php 
namespace Yukdiorder\Helper\Post ;

class Yuk_Taxonomy
{
    // Register Custom Taxonomy
    public function __construct($nama, $post_type , $args = null)
    {
        $this->post_type = [];
        if(is_array($post_type)){
            foreach($post_type as $type ){
                array_push($this->post_type, $type);
            }
        } else {
            array_push($this->post_type,  $post_type);
        }

        $this->nama = $nama;
        $this->labels = array(
            'name'                       => ucfirst($this->nama),
            'singular_name'              => ucfirst($this->nama),
            'menu_name'                  => ucfirst($this->nama),
            'all_items'                  => __('All Items', 'text_domain'),
            'parent_item'                => __('Parent Item', 'text_domain'),
            'parent_item_colon'          => __('Parent Item:', 'text_domain'),
            'new_item_name'              => __('New ' . ucfirst($this->nama)),
            'add_new_item'               => __('Add New ' . ucfirst($this->nama)),
            'edit_item'                  => __('Edit ' . ucfirst($this->nama)),
            'update_item'                => __('Update ' . ucfirst($this->nama)),
            'view_item'                  => __('View ' . ucfirst($this->nama)),
            'separate_items_with_commas' => __('Separate ' . ucfirst($this->nama) . ' with commas', 'text_domain'),
            'add_or_remove_items'        => __('Add or remove ' . $this->nama, 'text_domain'),
            'choose_from_most_used'      => __('Choose from the most used', 'text_domain'),
            'popular_items'              => __('Popular ' . ucfirst($this->nama), 'text_domain'),
            'search_items'               => __('Search ' . ucfirst($this->nama), 'text_domain'),
            'not_found'                  => __('Not Found', 'text_domain'),
            'no_terms'                   => __('No ' . ucfirst($this->nama), 'text_domain'),
            'items_list'                 => __(ucfirst($this->nama) . ' list', 'text_domain'),
            'items_list_navigation'      => __(ucfirst($this->nama) . 'list navigation', 'text_domain'),
        );

        if(is_null($args)){
            $this->args = array(
                'labels'                     => $this->labels,
                'hierarchical'               => false,
                'public'                     => true,
                'show_ui'                    => true,
                'show_admin_column'          => true,
                'show_in_nav_menus'          => true,
                'show_tagcloud'              => true,
            );

        }
        
        
    }
	public function init(){
		register_taxonomy($this->nama, $this->post_type, $this->args);

	}
}

?>