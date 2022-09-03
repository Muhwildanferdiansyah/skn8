<?php 
namespace Yukdiorder\Helper\Woocommerce ;

class Yuk_Order_Status {

    public function __construct($label , $args = null){
        
        $this->label    = $label ;
        $this->nama     = 'wc-'.strtolower(str_replace(' ','-', $this->label ) ); 
        $this->args = $args ;
        if ( is_null($args) ) {
            $this->args = array(
                'label'                     => _x($this->label, 'Order Status', 'woocommerce') ,
                'public'                    => true,
                'exclude_from_search'       => false,
                'show_in_admin_all_list'    => true,
                'show_in_admin_status_list' => true,
                'label_count'               => _n_noop( ucfirst($label).' <span class="count">(%s)</span>', ucfirst($label).'<span class="count">(%s)</span>', 'woocommerce' )
                ) ;
            }
    }
    
    public function init(){
        register_post_status($this->nama, $this->args);
        add_filter( 'wc_order_statuses', function($order_statuses){
            $order_statuses[$this->nama] = _x( $this->label, 'Order status', 'woocommerce' );
            return $order_statuses ;
        } );
        add_filter( 'bulk_actions-edit-shop_order', function($actions){
            $nama = 'mark_'.strtolower(str_replace(' ','-', $this->label ));
            $actions[$nama] = 'Ubah ke '.$this->label;
            return $actions;
        }, 10,1);

    }
}
?>