<?php 
namespace Yukdiorder\Helper\Table ;

use Yukdiorder\Helper\Table\Yuk_List_Table ;

class Yuk_List_Post extends Yuk_List_Table {
    protected $args ; 
    public $id ;
    public function __construct($id, $args = null){
      parent::__construct();
      $this->id = $id ;
      $this->args = $args ;
      if (is_null($args)) {
          $this->args= array() ;
      }
    }
  
  function column_default( $item, $column_name ) {
      switch( $column_name ) { 
        case 'cb' :
        case 'ID':
            return $item['ID'] ;
        case 'post_title':
            return $item['post_title'] ;
        case 'post_content':
            return $item['post_content'] ;
        case 'post_date':
            return $item['post_date'] ;
        default:
          return print_r( $item) ; //Show the whole array for troubleshooting purposes
      }
    }
  
  function column_cb($item) {
      return sprintf(
          '<input type="checkbox" name="data[]" value="%s" />', $item['ID']
      );    
  }

  function get_columns(){
      $columns = array(
        'cb' => '<input type="checkbox" />',  
        'ID' => 'ID',
        'post_title'    => 'Judul',
        'post_content'  => 'Keterangan',
        'post_date'     => 'Tanggal'
      );
      $columns = apply_filters( 'yuk_list_post_header_'.$this->id , $columns ) ;
      return $columns;
    }
  
    function get_items(){
      $items = [] ;  
      $q = get_posts($this->args);
      foreach ($q as $key => $post) {
          $items[] = array(
              'cb'            => '',  
              'ID'            => $post->ID ,
              'post_title'    => $post->post_title,
              'post_content'  => $post->post_content,
              'post_date'     => $post->post_date,
          ) ;
      }
      return $items ; 
    }
    
    function prepare_items() {
      $columns = $this->get_columns();
      $hidden = array();
      $sortable = array();
      $this->_column_headers = array($columns, $hidden, $sortable);
      $this->items = $this->get_items() ;
    }

    function display(){
      $this->prepare_items();  
      parent::display();
    }
}

?>