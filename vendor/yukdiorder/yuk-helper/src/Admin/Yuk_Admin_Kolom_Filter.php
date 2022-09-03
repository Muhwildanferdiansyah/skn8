<?php
namespace Yukdiorder\Helper\Admin ;

class Yuk_Admin_Kolom_Filter {

private $hook = 'restrict_manage_posts';
private $post_type ;
private $select_array = [];
private $select_name ;
private $select_default_value ;
private $select_default_label ;
private $params ;
private $isAktif = true ;

public function __construct($post_type = null ){
    if (empty($post_type)) {
        $this->post_type = 'posts';
        return $this ;
    }
    $this->post_type = $post_type;
    return $this ;
}

public function set_select_name($name){
    $this->select_name = $name ; 
    $this->params = $name ; 
    return $this ;
}
public function set_default_label($label){
    $this->select_default_label = $label ; 
    return $this ;
}

public function set_default_value($value){
    $this->select_default_value = $value ;
    return $this ; 
}

public function set_select_array($array = null){
    if (is_array($array)) {
        $this->select_array = $array ;
        return $this ;
    }
}

public function set_params($params) {
    $this->params = $params ;
    return $this ;
}

public function get_params_value(){
    if (isset($_GET[$this->params])) {
        $this->params = sanitize_text_field($_GET[$this->params]) ;
    }
    return $this->params ;
}

public function html($post_type){

    if ($post_type !== $this->post_type ) {
        return ;
    }

    if ( ! empty( $_GET[$this->params] ) ) {
        $params  = sanitize_text_field( $_GET[$this->params] );
    }
    ?> 
    <select name='<?= $this->select_name?>'>
    <option value='<?=$this->select_default_value ?>'><?= $this->select_default_label?></option>
    <?php 
        foreach($this->select_array as $key => $value ){
            ?> 
            <option <?php selected( $params, $key ); ?> value='<?php echo $key; ?>'><?php echo $value; ?></option>
            <?php
    }?>
    
    </select>
    <?php
}

public function set_aktif($bool = true){
    $this->isAktif = $bool ;
    return $this ;
}

public function run(){

    if ($this->isAktif) {			
        add_action($this->hook, [$this, 'html']);
    }
}

}

?>