<?php 
namespace Yukdiorder\Helper\Woocommerce ;

class Yuk_Product_Options {
    
    public function __construct($hook = null, $tipe= null, $select = null, $id = null, $label = null, $placeholder = null ){
        if($hook == null ) {
            $hook = "general_product_data";
        }
        if($label == null ) {
            $label = "Data produk";
        }
        $this->hook     = $hook ;
        $this->tipe     = $tipe ; // text, checkbox, textarea, select, radio
        $this->select   = [];
        if (is_array($select)) {
            foreach ($select as $key => $value) {
                $this->select[$key] = $value ;
            }
        }
        $this->id           = $id ;
        $this->label        = $label ;
        $this->placeholder  = $placeholder ;
        $this->content      = "ini konten";
        $this->args         = array(
            'id'            => $this->id ,
            'placeholder'   => $this->placeholder,
            'label'         => $this->label,
            'desc_tip'      => 'true',
            'options'       => $this->select ,
        );

        add_action('woocommerce_product_options_'.$hook , function(){
            $this->tambah_field($this->tipe, $this->args);
        } );

        add_action('woocommerce_process_product_meta', [$this, 'simpan_field'],10,2);
        
    }
    
    public function tambah_field($tipe, $args){
        switch ($tipe) {
            case 'text':
            woocommerce_wp_text_input($args);
            break;
            case 'checkbox':
            woocommerce_wp_checkbox($args);
            break;
            case 'textarea':
            woocommerce_wp_textarea_input($args);
            break;
            case 'select':
            woocommerce_wp_select($args);
            break;
            case 'radio':
            woocommerce_wp_radio($args);
            break;
            default:
            # code...
            break;
        }
    }

    public function simpan_field($id, $post){
        update_post_meta( $id, $this->id, $_POST[$this->id]);
    }
}

?>