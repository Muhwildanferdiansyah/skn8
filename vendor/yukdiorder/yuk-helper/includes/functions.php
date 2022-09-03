<?php 
function yuk_tambah($a, $b){
    return $a+$b ;
}

function yuk_register_status() {

	$args = array(
		'label'                     => _x( 'dibayar', 'Status General Name', 'yukdiorder' ),
		'label_count'               => _n_noop( 'dibayar (%s)',  'dibayar (%s)', 'yukdiorder' ), 
		'public'                    => true,
		'show_in_admin_all_list'    => true,
		'show_in_admin_status_list' => true,
		'exclude_from_search'       => false,
	);
	register_post_status( 'dibayar', $args );

}
add_action( 'init', 'yuk_register_status', 0 );

// Using jQuery to add it to post status dropdown
add_action('admin_footer-post.php', 'wpb_append_post_status_list');
function wpb_append_post_status_list(){
global $post;
$complete = '';
$label = '';
if($post->post_type == 'post'){
if($post->post_status == 'dibayar'){
$complete = ' selected="selected"';
$label = '<span id="post-status-display">Di bayar</span>';
}
echo '
<script>
jQuery(document).ready(function($){
$("select#post_status").append("<option value=\"dibayar\" '.$complete.'>dibayar</option>");
$(".misc-pub-section label").append("'.$label.'");
});
</script>
';
}
}
?>