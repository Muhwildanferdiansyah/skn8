<?php
namespace Yukdiorder\Helper\Admin ;

class Yuk_Admin_Page {

    public function __construct($nama = null , $caps = null, $position = null, $file = null ){
        $this->nama = $nama ;
        $this->caps = $caps ;
        $this->slug = strtolower(str_replace(' ','_', $this->nama ));
        $this->position = $position ;
        $this->submenus = [];
        $this->file = $file;
    }

    public function init(){
        add_menu_page( 
            $this->nama , 
            $this->nama, 
            $this->caps, 
            $this->slug,
            function(){
                require plugin_dir_path(__FILE__). $this->file;
            }, 
            '' , 
            $this->position ) ;
        
        foreach ($this->submenus as $index => $val) {

                add_submenu_page(
                    $this->slug,
                    $val['nama'] ,
                    $val['nama'] ,
                    $this->caps,
                    strtolower(str_replace(' ','_', $val['nama'])),
                    function($v) use ($val){
                        require plugin_dir_path(__FILE__). $val['file'];
                    },
                    '',
                    null
                );
        }

    }

    public function main_page(){
        require plugin_dir_path(__FILE__). $this->file;
    }

    public function submenu_page(){
        
    }
    
    public function submenu($nama , $file ){
        array_push($this->submenus , [
            "nama"  => $nama ,
            "file"  => $file
        ]);
    }
}
?>