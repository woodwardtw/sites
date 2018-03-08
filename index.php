<?php
/**
 * Plugin Name: Sites Maker
 * Plugin URI: https://github.com/woodwardtw/
 * Description: lets you create screenshots from URLs with various additional data

 * Version: 1.7
 * Author: Tom Woodward
 * Author URI: http://bionicteaching.com
 * License: GPL2
 */
 
 /*   2016 Tom  (email : bionicteaching@gmail.com)
 
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.
 
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
 
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
 
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

		if(!function_exists('load_sites_script')){
		    function load_sites_script() {		    	
		        global $post;
		        if (get_post_type($post->ID) === 'site'){
			        $deps = array('jquery');
			        $version= '1.0'; 
			        $in_footer = false;
			        wp_enqueue_script('sites', plugins_url( '/js/sites.js', __FILE__), $deps, $version, $in_footer);			
			    }
			}
		}
		add_action('wp_enqueue_scripts', 'load_sites_script');

		function add_sites_stylesheet() {
			global $post;
			if (get_post_type($post->ID) === 'site'){
		    	wp_enqueue_style( 'sites-css', plugins_url( '/css/sites.css', __FILE__ ) );
		    }
		}

		add_action('wp_enqueue_scripts', 'add_sites_stylesheet');
	

//CUSTOM POST TYPES

// Register Custom Post Type project
// Post Type Key: sites
function create_site_cpt() {

	$labels = array(
		'name' => __( 'sites', 'Post Type General Name', 'textdomain' ),
		'singular_name' => __( 'Site', 'Post Type Singular Name', 'textdomain' ),
		'menu_name' => __( 'Sites', 'textdomain' ),
		'name_admin_bar' => __( 'Site', 'textdomain' ),
		'archives' => __( 'Site Archives', 'textdomain' ),
		'attributes' => __( 'Site Attributes', 'textdomain' ),
		'parent_item_colon' => __( 'Parent Site:', 'textdomain' ),
		'all_items' => __( 'All Sites', 'textdomain' ),
		'add_new_item' => __( 'Add New Site', 'textdomain' ),
		'add_new' => __( 'Add New', 'textdomain' ),
		'new_item' => __( 'New Site', 'textdomain' ),
		'edit_item' => __( 'Edit Site', 'textdomain' ),
		'update_item' => __( 'Update Site', 'textdomain' ),
		'view_item' => __( 'View Site', 'textdomain' ),
		'view_items' => __( 'View Sites', 'textdomain' ),
		'search_items' => __( 'Search Sites', 'textdomain' ),
		'not_found' => __( 'Your site was not found', 'textdomain' ),
		'not_found_in_trash' => __( 'Not found in Trash', 'textdomain' ),
		'featured_image' => __( 'Featured Image', 'textdomain' ),
		'set_featured_image' => __( 'Set featured image', 'textdomain' ),
		'remove_featured_image' => __( 'Remove featured image', 'textdomain' ),
		'use_featured_image' => __( 'Use as featured image', 'textdomain' ),
		'insert_into_item' => __( 'Insert into site', 'textdomain' ),
		'uploaded_to_this_item' => __( 'Uploaded to this site', 'textdomain' ),
		'items_list' => __( 'sites list', 'textdomain' ),
		'items_list_navigation' => __( 'sites list navigation', 'textdomain' ),
		'filter_items_list' => __( 'Filter sites list', 'textdomain' ),
	);
	$args = array(
		'label' => __( 'site', 'textdomain' ),
		'description' => __( 'Student Sites', 'textdomain' ),
		'labels' => $labels,
		'menu_icon' => '',
		'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'author', 'trackbacks', 'page-attributes', 'custom-fields', ),
        'taxonomies' => array('category','post_tag'),
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'menu_position' => 5,
		'show_in_admin_bar' => true,
		'show_in_nav_menus' => true,
		'can_export' => true,
		'has_archive' => true,
		'hierarchical' => false,
		'exclude_from_search' => false,
		'show_in_rest' => true,
		'publicly_queryable' => true,
		'capability_type' => 'post',
		'menu_icon' => 'dashicons-admin-users',
	);
	register_post_type( 'site', $args );

}
add_action( 'init', 'create_site_cpt', 0 );


//add sites to archive
function sites_namespace_add_sites( $query ) {
  if( is_category() || is_tag() && empty( $query->query_vars['suppress_filters'] ) ) {
    $query->set( 'post_type', array(
     'post', 'nav_menu_item', 'site'
		));
	  return $query;
	}
}
add_filter( 'pre_get_posts', 'sites_namespace_add_sites' );




//FROM https://codex.wordpress.org/Plugin_API/Filter_Reference/single_template
/* Filter the single_template with our custom function*/
function sites_get_custom_post_type_template($single_template) {
     global $post;

     if ($post->post_type == 'site') {
          $single_template = dirname( __FILE__ ) . '/site.php';
     }
     return $single_template;
}
add_filter( 'single_template', 'sites_get_custom_post_type_template' );



//ADD THE SITE URL METABOX TO POSTS***************************
function site_url_meta_box() {
	add_meta_box(
		'site_url_meta_box', // $id
		'Site URL', // $title
		'show_site_url_meta_box', // $callback
		'site', // $screen
		'normal', // $context
		'high' // $priority
	);
}
add_action( 'add_meta_boxes', 'site_url_meta_box' );


function save_site_url_meta( $post_id ) {   
	// verify nonce
	if ( !wp_verify_nonce( $_POST['site_url_meta_box_nonce'], basename(__FILE__) ) ) {
		return $post_id; 
	}
	// check autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return $post_id;
	}
	// check permissions
	if ( 'site' === $_POST['post_type'] ) {
		if ( !current_user_can( 'edit_page', $post_id ) ) {
			return $post_id;
		} elseif ( !current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}  
	}
	
	$old = get_post_meta( $post_id, 'site-url', true );
	$new = $_POST['site-url'];

	if ( $new && $new !== $old ) {
		update_post_meta( $post_id, 'site-url', $new );
	} elseif ( '' === $new && $old ) {
		delete_post_meta( $post_id, 'site-url', $old );
	}
}
add_action( 'save_post', 'save_site_url_meta' );

function show_site_url_meta_box() {
	global $post;  
	$meta = get_post_meta( $post->ID, 'site-url', true ); 
	?>
	<input type="hidden" name="site_url_meta_box_nonce" value="<?php echo wp_create_nonce( basename(__FILE__) ); ?>">

    <!-- All fields will go here -->
    <p>
	<label for="site-url[text]">Site URL</label>
	<input type="text" name="site-url[text]" id="site-url[text]" class="regular-text" value="<?php  if ( isset ( $meta['text'] ) ) echo $meta['text'];?>">
    </p>
   
	<?php }

//with the site-url you can do anything

//get and update the post title

function siteUpdateData($post_id, $post, $update){

	$post_type = get_post_type($post_id);	

    // If this isn't a 'site' post, don't update it.
    if ( "site" != $post_type ) {    	
    	return;
    } 
    else {
    	if (get_post_meta($post_id, 'site-url', true)){
	    	$url = get_post_meta($post_id, 'site-url', true);
			update_post_meta($post_id, 'full-api-url', verifySlash($url['text']) . 'wp-json/' );			
		}
		
	$currentTitle = get_the_title($post_id);
		//get WP API - the @ sign quites the warning about that not existing on non-modern wp sites
	if(@file_get_contents($url['text'] . 'wp-json')){
			$json = file_get_contents($url['text'] . 'wp-json');
			$data = json_decode($json, true);
			
			//break up the JSON
			$jsonTitle = $data['name'];//set title if has WP API data
			$ddm_tag = $data['ddm_tag']; //tags
			$description = $data['description'];//site description

			if ($jsonTitle != $currentTitle){
				update_post_meta($post_id, 'child-title', $data['name']);
				} 
			if ($description != get_post_meta($post_id, 'child-description', true)){
				update_post_meta($post_id, 'child-description', $description);
			} 	
			if ($ddm_tag){
				updateTags($post_id, $data);
			} 
			totalPosts($post_id);	
			totalPages($post_id);	
		} 
	else {
		if ($url['text']){
				$htmlTitle = wptexturize(get_title($url['text'])); //no api, look for html title element
		        if ($htmlTitle != $currentTitle){
		        	update_post_meta($post_id, 'child-title', $htmlTitle);
		        	} 
		        else {
		    		update_post_meta($post_id, 'child-title', 'No Title Available');
		        	}
		    }
		}
		updateTags($post_id, $data);
		totalPosts($post_id);
		totalPages($post_id);			
	}
}

add_action( 'save_post', 'siteUpdateData', 10, 3);


//if NOT IN JSON GET SITE TITLE VIA TITLE TAG	
function get_title($url){
  $str = file_get_contents($url);
  if(strlen($str)>0){
    $str = trim(preg_replace('/\s+/', ' ', $str)); // supports line breaks inside <title>
    preg_match("/\<title\>(.*)\<\/title\>/i",$str,$title); // ignore case
    return $title[1];
  }
}	

//DDM Tags
function updateTags($post_id,$data){
	if ($data['ddm_tag']){
		$extra = $data['ddm_tag'];
		$postTags = wp_get_post_tags($id);	  
		$postTagsArray = [];
		for ($i = 0; $i < count($postTags); $i++){
		  	array_push($postTagsArray, $postTags[$i]->name);
		}
		if (count(array_diff($extra,$postTagsArray))>0) {
				$tags = implode(",",$extra); //stringify it 
		  		wp_set_post_tags($post_id, $tags, true );
		  	}	
		 } 
	else {
	 	missingResponse($post_id, 'no-base-tags', false);
	 }
}


//adds defined tag to sites that don't respond for filtering purposes, can be used for filtering in wp_query
function missingResponse($post_id, $status, $replace){
	  	//update_post_meta( $id, 'the-tag', $status);
	  	wp_set_post_tags( $post_id, $status, $replace ); //opted not to reset tags to null but might change back to true . . . 
}

function verifySlash($url){
	if(substr($url,-1) != '/'){
		$url = $url.'/';
		return $url;
	} else {
		return $url;
	}
}


//GET TOTAL POST COUNT (total-posts) and the date of most recent post published (recent-update-posts)
function totalPosts($post_id){
	$siteURL = get_post_meta( $post_id, 'full-api-url', true );
	$posts = $siteURL . 'wp/v2/posts?per_page=1' ;	
	$response = wp_remote_get($posts);	
	if(is_wp_error( $response ) || $response == 404){ //on failure add tag no-posts
		missingResponse($post_id, 'no-posts', false);
	} else {
		$total = $response['headers']['x-wp-total'];	
		if ($total){
			update_post_meta( $post_id, 'total-posts', $total);
		}
		//recent update date for posts
		$data = json_decode( wp_remote_retrieve_body( $response ) );
		if ($data != ""){ //make sure it's WP
			if ($data->code === false || $data->code != 'rest_no_route'){//make sure it's not an old version of WP
				update_post_meta( $post_id, 'recent-update-posts', $data[0]->date );
			}
		}
	}
}


//gets total pages (total-pages) and the date of last publish for pages (recent-update-pages)
function totalPages($post_id){
	$siteURL = get_post_meta( $post_id, 'full-api-url', true );
	$response = wp_remote_get($siteURL . 'wp/v2/pages?per_page=1' );	
	if(is_wp_error( $response ) || $response == 404){ //on failure add tag 404
		missingResponse($post_id, 'no-pages', false);
	} else {
		$total = $response['headers']['x-wp-total'];
		if($total){
			update_post_meta( $post_id, 'total-pages', $total);
			$data = json_decode( wp_remote_retrieve_body( $response ) );
			update_post_meta( $post_id, 'recent-update-pages', $data[0]->date );
		}
	}
}

/*

add_filter( 'wp_insert_post_data' , 'modify_site_title' , '99', 2 ); // Grabs the inserted post data so you can modify it.

function modify_site_title( $data, $postarr ){
	$post_id = $postarr['ID']; 
	var_dump($post_id);
	$title = get_post_meta($post_id, 'child-title', true);
	var_dump($title);

  if($data['post_type'] === 'site' && $data['post_title'] != $title) { 
   
    $data['post_title'] = $title ; //Updates the post title to your new title.
    $data['post_name'] = $title;//passes empty string to force regeneration of permalink based on title change

  }
  return $data; // Returns the modified data.
}
*/