<?php  



// Shortcode practice

function myUrl($atts, $content = null) {
	extract(shortcode_atts(array(
		"href" => 'http://'
	), $atts));
	return '<a href="'.$href.'">'.$content.'</a>';
}
add_shortcode("url", "myUrl");

function lorem() {
	return 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.';
}
add_shortcode('lorem', 'lorem');


// Shortcode to add wide margins to a post page

function wide_margins_shortcode ($content = null) {
	//extract(shortcode_atts(array(
	//	"div class" => 'hello';
	//), $atts));
	return '<div class="widemargins">'.$content.'</div>';
}
add_shortcode("wide", "wide_margins_shortcode");


function strong ($atts, $content = null) {
	//if(in_category(array('news','latest', 'updates', 'blog'))){
		return '<strong>'.$content.'</strong>';
	//}
}
add_shortcode("bold", "strong");

// Importing extra objects

// Path constants
define('THEMELIB', get_template_directory() . '/library');

// Load widgets
require_once(THEMELIB . '/widgets.php');

// Load registered sidebars
require_once(THEMELIB . '/sidebars.php');

// Load options stylesheet
require_once(THEMELIB . '/option-stylesheet.php');

// Load theme options
require_once(THEMELIB . '/theme-options.php');

// Done importing

////////////
// IMAGES //
////////////

// This sets the Large image size to 900px

if ( ! isset( $content_width ) ) 
	$content_width = 900;

// Remove inline styles on gallery shortcode

function wpfolio_remove_gallery_css( $css ) {
	return preg_replace( "#<style type='text/css'>(.*?)</style>#s", '', $css );
	}
add_filter( 'gallery_style', 'wpfolio_remove_gallery_css' );

// END - Remove inline styles on gallery shortcode


// Thumbnail Function - make the first gallery image the default thumbnail if one is not specified
function get_thumb ($post_ID){
    $thumbargs = array(
    'post_type' => 'attachment',
    'numberposts' => 1,
    'post_status' => null,
    'post_parent' => $post_ID
    );
    $thumb = get_posts($thumbargs);
    if ($thumb) {
        return wp_get_attachment_image($thumb[0]->ID);
    }
} 

// This adds support for post thumbnails of 150px square
add_theme_support('post-thumbnails');
set_post_thumbnail_size( 150, 150,true );

// END - Thumbnail Function



/////////////////////
// ADDING FEATURES //
/////////////////////


// Adding some WP 3.0 features

// This theme uses wp_nav_menu()
add_theme_support( 'menus' );

register_nav_menus( array(
	'navbar' => __( 'Top Navigation Bar', 'navbar' ),
) );

// Add default posts and comments RSS feed links to head
add_theme_support( 'automatic-feed-links' );

// This theme allows users to set a custom background
add_custom_background();

// END WP 3.0 features


// enqueue jQuery
//if you have a script that need jquery ... you don't need to call it. You just have to put it in your dependancies.
//wp_enqueue_script('jquery');

wp_enqueue_script('hoverIntent', get_template_directory_uri().'/js/hoverIntent.js',array('jquery'));
wp_enqueue_script('superfish', get_template_directory_uri().'/js/superfish.js',array('hoverIntent'));
wp_enqueue_script('supersubs', get_template_directory_uri().'/js/supersubs.js',array('superfish'));
wp_enqueue_script('wpfolio', get_template_directory_uri().'/js/wpfolio.js',array('supersubs'));



// enable threaded comments

function wpfolio_enable_threaded_comments(){
	if (!is_admin()) {
		if (is_singular() AND comments_open() AND (get_option('thread_comments') == 1))
			wp_enqueue_script('comment-reply');
		}
}
add_action('get_header', 'wpfolio_enable_threaded_comments');


///////////////////////
// ADDING A TAXONOMY //
///////////////////////

// We don't use this to it's full extent yet, but we could (will?)

// enabling a taxonomy for Medium

function wpfolio_create_taxonomies() {
register_taxonomy('medium', 'post', array( 
	'label' => 'Medium',
	'hierarchical' => false,  
	'query_var' => true, 
	'rewrite' => true,
	'public' => true,
	'show_ui' => true,
	'show_tagcloud' => true,
	'show_in_nav_menus' => true,));
} add_action('init', 'wpfolio_create_taxonomies', 0);
	
	
	
	
/////////////////////////////////////
// ADMIN & THEME OPTIONS INTERFACE //
/////////////////////////////////////

// customize admin footer text to add wpfolio to links
function wpfolio_admin_footer() {
	echo 'Thank you for creating with <a href="http://wordpress.org/" target="_blank">WordPress</a>. | <a href="http://codex.wordpress.org/" target="_blank">Documentation</a> | <a href="http://wordpress.org/support/forum/4" target="_blank">Feedback</a> | <a href="http://wpfolio.visitsteve.com/">Theme by WPFolio</a>';
} 
add_filter('admin_footer_text', 'wpfolio_admin_footer');



// Add WPFolio Wiki site as a Dashboard Feed 
// Thanks to bavotasan.com: http://bavotasan.com/tutorials/display-rss-feed-with-php/ 

function custom_dashboard_widget() {

	$rss = new DOMDocument();
	$rss->load('http://wpfolio.visitsteve.com/wiki/feed');
	$feed = array();
	foreach ($rss->getElementsByTagName('item') as $node) {
		$item = array ( 
			'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
			// 'desc' => $node->getElementsByTagName('description')->item(0)->nodeValue,
			'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
			'date' => $node->getElementsByTagName('pubDate')->item(0)->nodeValue,
			);
		array_push($feed, $item);
	}
	$limit = 5; // change how many posts to display here
	echo '<ul>'; // wrap in a ul
	for($x=0;$x<$limit;$x++) {
		$title = str_replace(' & ', ' &amp; ', $feed[$x]['title']);
		$link = $feed[$x]['link'];
		// $description = $feed[$x]['desc'];
		$date = date('l F d, Y', strtotime($feed[$x]['date']));
		echo '<li><p><strong><a href="'.$link.'" title="'.$title.'">'.$title.'</a></strong>';
		echo ' - '.$date.'</p></li>';
		// echo '<p>'.$description.'</p>';
	}
	echo '</ul>';
	echo '<p class="textright"><a href="http://wpfolio.visitsteve.com/wiki/category/news" class="button">View all</a></p>'; // link to site
	
}
	
function add_custom_dashboard_widget() {
	wp_add_dashboard_widget('custom_dashboard_widget', 'WPFolio News', 'custom_dashboard_widget');
}
add_action('wp_dashboard_setup', 'add_custom_dashboard_widget');



?>