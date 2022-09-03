<?php

/**
 * Plugin Name:     Membership-Skn8
 * Plugin URI:      PLUGIN SITE HERE
 * Description:     PLUGIN DESCRIPTION HERE
 * Author:          YOUR NAME HERE
 * Author URI:      YOUR SITE HERE
 * Text Domain:     skn8
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Skn8
 */

// Your code starts here.
use Yukdiorder\Helper\Table\Yuk_List_Post as Tabel;


add_filter('woocommerce_checkout_fields', 'custom_checkout_fields', 20, 1);

function custom_checkout_fields($fields)
{
    unset($fields['billing']['billing_company']);
    unset($fields['billing']['last_name']);
    unset($fields['billing']['billing_email']);
    return $fields;
}


add_action('woocommerce_cart_calculate_fees', 'discount_role', 10, 1);
function discount_role($cart_object)
{
    if (is_admin() && !defined('DOING_AJAX'))
        return;

    $total = $cart_object->cart_contents_total; // Cart total

    $range_1_produk = ($total <= 719000);
    $range_2_paket = ($total >= 720000 && $total <= 1439000);
    $gold = ($total >= 1440000 && $total < 4679000);
    $diamond = ($total >= 4680000);

    if ($range_1_produk) {
        $percent = 0;
    } else if ($range_2_paket) {
        $percent = 8;
    } else if (($gold) || current_user_can('gold')) {
        $percent = 15;
    } else if (($diamond) || current_user_can('diamond')) {
        $percent = 25;
    } else {
    }

    if ($percent != 0) {
        $discount = $total * $percent / 100;
        $cart_object->add_fee("Discount ($percent%)", -$discount, true);
    }
}


// Register Custom Post Type

add_action('admin_menu', 'komisi_menu');

function komisi_menu()
{
    add_menu_page('Komisi', 'Komisi', 'manage_options', 'komisi', 'komisi', 10);
}

function komisi()
{
    echo do_shortcode('[wpdatatable id=2]');
}


//create order

add_action('woocommerce_new_order', function ($order_id) {
    global $woocommerce;
    $order   = new WC_Order($order_id);
    $orderid = $order->get_id();
    $nama     = $order->get_billing_first_name();
    $tgl     = $order->get_date_created()->format("F j, Y, g:i:s A T");
    $subtotal = $woocommerce->cart->subtotal;
    $total   =  $order->get_total();
    $user_id = $order->get_user_id();
    $sponsor_id = get_user_meta($user_id, '_select_sponsor', true);
    $sponsor_user =  get_userdata($sponsor_id);
    $sponsor_name = $sponsor_user->display_name;
    global $wpdb;
    $tabel_komisi = $wpdb->prefix . 'wpdatatable_2';


    if (current_user_can('diamond')) {
        // Get completed orders by customer id
        $orders_by_customer_id = wc_get_orders(array(
            'customer_id' => get_current_user_id(),
            'status' => array('wc-completed'),
        ));

        if (($orders_by_customer_id  < 2) && current_user_can('diamond')) {
            $komisi = 0.05;
        } elseif (($orders_by_customer_id >= 2) && current_user_can('diamond')) {
            $komisi = 0.02;
        } else {
            $komisi = 0;
        }

        $persent = $komisi * $subtotal;

        $args = array(
            'kodetransaksi'   => $orderid,
            'tgl'             => $tgl,
            'nama'            => $nama,
            'sponsor'         => $sponsor_name,
            'totalbelanja'    => $total,
            'komisi'          => $persent
        );

        $wpdb->insert($tabel_komisi, $args);
        $komisi_terakhir = get_user_meta($sponsor_id, '_deposit', true) + $persent;
        update_user_meta($sponsor_id, '_deposit', $komisi_terakhir);
    }
});

add_action('after_setup_theme', 'crb_load');
function crb_load()
{
    require_once('vendor/autoload.php');
    \Carbon_Fields\Carbon_Fields::boot();
}

use Carbon_Fields\Container;
use Carbon_Fields\Field;

add_action('carbon_fields_register_fields', 'crb_attach_theme_options');
function crb_attach_theme_options()
{
    if (current_user_can('administrator')) {

        $container = Container::make('user_meta', 'Sponsor');
        $select_sponsor = Field::make('select', 'select_sponsor', 'Pilih Sponsor');
        // buat ooptins 
        $data = ['- Pilih Sponsor -'];
        $args = [
            'role' => 'diamond'
        ];
        $users = get_users($args);
        foreach ($users as $key => $user) {
            $data[$user->id] = $user->display_name;
        }
        $select_sponsor->set_options($data);
        // taambah fields pada container 
        $container->add_fields([$select_sponsor]);
    }
}


function wpdocs_remove_users_columns($columns)
{
    unset($columns['name']);
    unset($columns['email']);
    $columns['administrasi'] = 'Sponsor';
    $columns['diamond'] = 'Deposit';
    return $columns;
}

add_filter('manage_users_columns', 'wpdocs_remove_users_columns');


function custom_show_user_id_column_content($value, $column_name, $user_id)
{
    $sponsor_id = get_user_meta($user_id, '_select_sponsor', true);
    $sponsor_user =  get_userdata($sponsor_id);

    if ('administrasi' == $column_name)
        return $sponsor_user->display_name;
    return $value;

    $deposit_id = get_user_meta($user_id, '_deposit', true);
    $deposit_user =  get_userdata($deposit_id);

    if ('diamond' == $column_name)
        return $deposit_user;
    return $value;
}
add_filter('manage_users_custom_column',  'custom_show_user_id_column_content', 10, 3);



function deposit($value, $column_name, $user_id)
{
    $sponsor_id = get_user_meta($user_id, 'deposit', true);
    $sponsor_user =  get_userdata($sponsor_id);

    if ('diamond' == $column_name)
        return $sponsor_user->display_name;
    return $value;
}
add_filter('manage_users_custom_column',  'deposit', 10, 3);

add_action('admin_menu', 'affiliasi_menu');

function affiliasi_menu()
{
    add_menu_page('affiliasi', 'affiliasi', 'manage_options', 'affiliasi', 'affiliasi', 10);
}

function affiliasi()
{
    // $tabel = new Tabel('affiliasi', $args = null);
    // $tabel->display();
}

add_action('admin_menu', 'poin_menu');

function poin_menu()
{
    add_menu_page('Poin & Hadiah', 'Poin & Hadiah', 'manage_options', 'point_hadiah', 'point_hadiah', 10);
}

function point_hadiah()
{
    // $tabel = new Tabel('affiliasi', $args = null);
    // $tabel->display();
}

add_action('carbon_fields_register_fields', 'user_meta_deposit');

function user_meta_deposit()
{

    Container::make('user_meta', 'Deposit')
        ->add_fields(array(
            Field::make('text', 'deposit', 'Deposit')
        ));
}


// The code for displaying WooCommerce Product Custom Fields
add_action('woocommerce_product_options_general_product_data', 'woocommerce_product_custom_fields');

// Following code Saves  WooCommerce Product Custom Fields
add_action('woocommerce_process_product_meta', 'woocommerce_product_custom_fields_save');

function woocommerce_product_custom_fields()
{
    global $woocommerce, $post;
    echo '<div class="product_custom_field">';

    woocommerce_wp_text_input(
        array(
            'id' => '_poin',
            'placeholder' => 'Poin Produk',
            'label' => __('Poin Produk', 'woocommerce'),
            'type' => 'number',
            'custom_attributes' => array(
                'step' => 'any',
                'min' => '0'
            )
        )
    );
}

function woocommerce_product_custom_fields_save($post_id)
{
    $woocommerce_poin = $_POST['_poin'];
    if (!empty($woocommerce_poin))
        update_post_meta($post_id, '_poin', esc_attr($woocommerce_poin));
}

function woocommerce_custom_fields_display()
{
    global $post;
    $product = wc_get_product($post->ID);
    $poin = $product->get_meta('_poin');
    if ($poin) {
        printf(
            
            esc_html($poin)
        );
    }
}

add_action('woocommerce_before_add_to_cart_button', 'woocommerce_custom_fields_display');


add_filter('woocommerce_account_menu_items', 'misha_log_history_link', 40);
function misha_log_history_link($menu_links)
{

    $menu_links = array_slice($menu_links, 0, 5, true)
        + array('poin-hadiah' => 'Poin Hadiah')
        + array_slice($menu_links, 5, NULL, true);

    return $menu_links;
}

add_action('init', 'misha_add_endpoint');
function misha_add_endpoint()
{

    add_rewrite_endpoint('poin-hadiah', EP_PAGES);
}


add_action('woocommerce_account_log-history_endpoint', 'misha_my_account_endpoint_content');
function misha_my_account_endpoint_content()
{

    // of course you can print dynamic content here, one of the most useful functions here is get_current_user_id()
    echo 'Last time you logged in: yesterday from Safari.';
}
