<?php 
namespace Yukdiorder\Helper\Admin ;

class Yuk_Admin_Notice {
    public $pesan ; 

    public function __construct($pesan, $type = null ){
        if (is_null($type)) {
            $this->type = 'notice-success' ;
        }
        switch ($type) {
            case 'warning':
                $this->type = 'notice-warning' ;
                break;
            case 'info':
                $this->type = 'notice-info' ;
                break;
            case 'danger':
                $this->type = 'notice-danger' ;
                break; 
            default:
                $this->type = 'notice-success' ;
                break;
        }
        $this->pesan = $pesan ;
        $this->class = "notice $this->type is-dismissible";
        
    }

    public function init(){
        ?>
        <div class="<?php echo $this->class ?>">
            <p><?php echo $this->pesan ?></p>
        </div>
        <?php
    }
}

?>