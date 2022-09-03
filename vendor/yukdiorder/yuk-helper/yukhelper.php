<?php

/**
 * Plugin Name:     Yuk Helper
 * Plugin URI:      www.yukdiorder.com
 * Description:     Helper untuk development plugin wp
 * Author:          yukdiorder	
 * Author URI:      www.yukdiorder.com
 * Text Domain:     yukhelper
 * Domain Path:     /languages
 * Version:         0.9.0
 *
 * @package         Yukhelper
 */

// Your code starts here.
// Register Custom Status
require_once "vendor/autoload.php";

add_action('after_setup_theme', function () {
	require_once "vendor/autoload.php";
	\Carbon_Fields\Carbon_Fields::boot();
});

use Carbon_Fields\Container;
use Carbon_Fields\Field;
use Yukdiorder\Helper\Table\Yuk_List_Post as Tabel;
use Yukdiorder\Helper\Table\Yuk_List_Post_Kolom as Kolom;
use Yukdiorder\Helper\Post\Yuk_Post_Type;
use Yukdiorder\Helper\Post\Yuk_Taxonomy as Taxonomy;

//add_action('init' , array(new Taxonomy('Gudang', 'product'),'init'));

add_action('carbon_fields_register_fields', function () {

	$query =  get_posts(array(
		'post_type' => 'product',
		'numberposts' => -1,
	));

	$product = [];
	foreach ($query as $prod) {
		$product[$prod->ID] = $prod->post_title;
	}



	$form_po = Container::make('theme_options', 'Formulir PO');
	$html = "<p>iuhiuh<p>";
	$form_po->add_fields(array(

		Field::make('html', 'header_form', 'Header')
			->set_html($html),
		Field::make('select', 'produk', 'Produk')
			->add_options($product),
		Field::make('date', 'po_tanggal_mulai', 'Tanggal Mulai Preorder'),
		Field::make('date', 'po_tanggal_selesai', 'Tanggal Selesai Preorder')
	));
});



add_action('admin_menu', function () {
	add_menu_page('Coba', 'Coba', 'manage_options', 'coba', function () {
		$tabel = new Tabel('komisi', $args = null);
		$tabel->display();
	}, '', 2);
});


// add_filter('carbon_fields_should_save_field_value', function($save , $value, $field){
// $save = false ;
// $html = "
// <div>
// <label></label>
// [submit_kirim]
// </div>
// ";
// // wp_insert_post , post_type = wpcf7_contact_form 
// // $value di lempar ke Contact Form 7
// return $value ;
// });