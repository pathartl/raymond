<?php

add_action( 'after_setup_theme', 'rs_setup' );
function rs_setup() {
	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// This theme uses post thumbnails
	add_theme_support( 'post-thumbnails' );

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	// This theme utilizes post formats
	add_theme_support( 'post-formats', array( 'aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio' ) );

	// Add post-formats to post_type 'page'
	add_post_type_support( 'page', 'post_formats' );

	// Add post-formats to post-type 'my_custom_post_type'
	add_post_type_support( 'my_custom_post_type', 'post-formats' );
	
	// Make sure all my dates are in the timezone set in WordPress
	date_default_timezone_set(get_option('timezone_string', 'America/Chicago'));
}

add_action('add_meta_boxes', 'rs_add_custom_box');
add_action('save_post', 'rs_save_postdata');
add_filter('excerpt_length', 'rs_excerpt_length');
add_action( 'init', 'featured_post_expire' );

add_action('wp_head', 'absolute_admin', 999);

function absolute_admin() {
	?>
	<style type="text/css" media="screen">
		#wpadminbar {
			position: absolute !important;
		}
	</style>
	<?php
}

register_sidebar( array(
		'id'          => 'left-sidebar',
		'name'        => __( 'Left Sidebar', $text_domain ),
		'description' => __( 'This sidebar is located on the left side of the page, or maybe the top when the window is small', $text_domain ),
) );

if ( is_main_site() ) {

	register_sidebar( array(
			'id'          => 'frontpage-sidebar',
			'name'        => __( 'Front Page Sidebar', $text_domain ),
			'description' => __( 'This section can be found on the front page', $text_domain ),
	) );

	register_sidebar( array(
			'id'          => 'header-sidebar',
			'name'        => __( 'Header Sidebar', $text_domain ),
			'description' => __( 'This section can be found on the top of every page', $text_domain ),
	) );

	register_sidebar( array(
			'id'          => 'students-sidebar',
			'name'        => __( 'Students Sidebar', $text_domain ),
			'description' => __( 'This section can be found on the left side of the students page', $text_domain ),
	) );

	register_sidebar( array(
			'id'          => 'parents-sidebar',
			'name'        => __( 'Parents Sidebar', $text_domain ),
			'description' => __( 'This section can be found on the left side of the parents page', $text_domain ),
	) );

function register_my_menus() {
	register_nav_menus(
		array( 'header-menu' => __( 'Header Menu' ), 'raymond-menu' => __( 'Raymond Menu' ) )
	);
}
add_action( 'init', 'register_my_menus' );

}

function login_stylesheet() { ?>
		<link rel="stylesheet" id="custom_wp_admin_css"  href="<?php echo get_bloginfo( 'stylesheet_directory' ) . '/css/login.css'; ?>" type="text/css" media="all" />
<?php }
add_action( 'login_enqueue_scripts', 'login_stylesheet' );

/* Prints the box content */
function rs_inner_custom_box() {

	// Use nonce for verification
	wp_nonce_field( plugin_basename(__FILE__), 'rs_noncename' );
	
	if(get_post_meta($_GET['post'], '_featured_status', true) == 'true') {
		$isFeatured = " checked";
	}
	
	if(get_post_meta($_GET['post'], '_featured_date', true)) {
		$dateValue = date('M d, Y', get_post_meta($_GET['post'], '_featured_date', true));
	}

	// The actual fields for data entry
	echo '<input type="checkbox" id="_featured_status" name="_featured_status" value="true"'. $isFeatured . '>';
	echo '&nbsp;<input type="text" id="_featured_date" name="_featured_date" value="'.$dateValue.'" placeholder="Expiration: +5 days, tomorrow, 3/13/2037" size="29" />';
}

/* When the post is saved, saves our custom data */
function rs_save_postdata( $post_id ) {

	// verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times

	if ( !wp_verify_nonce( $_POST['rs_noncename'], plugin_basename(__FILE__) )) {
		return $post_id;
	}

	// verify if this is an auto save routine. If it is our form has not been submitted, so we dont want
	// to do anything
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
		return $post_id;

	
	// Check permissions
	if ( 'page' == $_POST['post_type'] ) {
		if ( !current_user_can( 'edit_page', $post_id ) )
			return $post_id;
	} else {
		if ( !current_user_can( 'edit_post', $post_id ) )
			return $post_id;
	}

	// OK, we're authenticated: we need to find and save the data
	
	$isFeatured = $_POST['_featured_status']; 
	
	$date = $_POST['_featured_date'];
	
	if($date) {
		$date = strtotime($date);

		$month = date('m', $date);
		$day = date('d', $date);
		$year = date('Y', $date);

		$date = mktime(23,59,59,$month,$day,$year);
		if($year < date('Y', time())) $date = null;
	}
	

	// Do something with $mydata 
	// probably using add_post_meta(), update_post_meta(), or 
	// a custom table (see Further Reading section below)
	update_post_meta($post_id, '_featured_status', $isFeatured);
	update_post_meta($post_id, '_featured_date', $date);
	
	return $mydata;
}

/* Adds a box to the main column on the Post and Page edit screens */
function rs_add_custom_box() {
		add_meta_box( 'rs_sectionid', __( 'Featured', 'rs_textdomain' ), 
								'rs_inner_custom_box', 'post' );
}

function rs_excerpt_length($length) {
	return 30;
}

function featured_post_expire(){
		$args = array(  'post_status' => 'publish',
										'meta_query' => array(
											array(
												'key' => '_featured_date',
												'value' => array(0, time()),
												'compare' => 'BETWEEN',
											)
										),
								 );
		$posts = get_posts( $args );

		if ( count($posts) > 0 )
		foreach( $posts as $p ) {
				wp_update_post( array( 'ID' => $p->ID, 'post_status' => 'draft' ) );
		}
}

function get_featured_posts() {
		$args = array(  'post_status' => 'publish',
										'meta_query' => array(
											array(
												'key' => '_featured_status',
												'value' => 'true'
											)
										),
								 );
		$posts = get_posts( $args );
		$ids = array();
		
		if ( count($posts) > 0 )
		foreach( $posts as $p ) {
			$ids[] = $p->ID;
		}
		return $ids;
}

function starkers_filter_wp_title( $title, $separator ) {
	// Don't affect wp_title() calls in feeds.
	if ( is_feed() )
		return $title;

	// The $paged global variable contains the page number of a listing of posts.
	// The $page global variable contains the page number of a single post that is paged.
	// We'll display whichever one applies, if we're not looking at the first page.
	global $paged, $page;

	if ( is_search() ) {
		// If we're a search, let's start over:
		$title = sprintf( __( 'Search results for %s', 'starkers' ), '"' . get_search_query() . '"' );
		// Add a page number if we're on page 2 or more:
		if ( $paged >= 2 )
			$title .= " $separator " . sprintf( __( 'Page %s', 'starkers' ), $paged );
		// Add the site name to the end:
		$title .= " $separator " . get_bloginfo( 'name', 'display' );
		// We're done. Let's send the new title back to wp_title():
		return $title;
	}

	// Otherwise, let's start by adding the site name to the end:
	$title .= get_bloginfo( 'name', 'display' );

	// If we have a site description and we're on the home/front page, add the description:
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title .= " $separator " . $site_description;

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		$title .= " $separator " . sprintf( __( 'Page %s', 'starkers' ), max( $paged, $page ) );

	// Return the new title to wp_title():
	return $title;
}
add_filter( 'wp_title', 'starkers_filter_wp_title', 10, 2 );

function get_members($group=FALSE,$inclusive=FALSE) {
	// Active Directory server
	$ldap_host = "server";
	$ldap_fqdn = "ad.domain.com";

	// Active Directory DN
	$ldap_dn = "Insert correct OU here";

	// Domain, for purposes of constructing $user
	$ldap_usr_dom = "@".$ldap_fqdn;

	// Active Directory user
	$user = "user";
	$password = "password";

	// User attributes we want to keep
	// List of User Object properties:
	// http://www.dotnetactivedirectory.com/Understanding_LDAP_Active_Directory_User_Object_Properties.html
	$keep = array(
			"samaccountname",
			"displayname",
			"title",
			"mail",
			"telephonenumber",
			"wwwhomepage",
			"department"
	);

	// Connect to AD
	$ldap = ldap_connect($ldap_host) or die("Could not connect to LDAP");
	ldap_bind($ldap,$user . "@" . $ldap_fqdn,$password) or die("Could not bind to LDAP");

	 // Begin building query
	 if($group) $query = "(&"; else $query = "";

	 $query .= "(&(objectClass=user)(objectCategory=person)(title=*))";


	// Filter by memberOf, if group is set
	if(is_array($group)) {
		// Looking for a members amongst multiple groups
		if($inclusive) {
			// Inclusive - get users that are in any of the groups
			// Add OR operator
			$query .= "(|";
		} else {
			// Exclusive - only get users that are in all of the groups
			// Add AND operator
			$query .= "(&";
		}

		// Append each group
		foreach($group as $g) $query .= "(memberOf=CN=$g,$ldap_dn)";

		$query .= ")";
	} elseif($group) {
		// Just looking for membership of one group
		$query .= "(memberOf=CN=$group,$ldap_dn)";
	}

	// Close query
	if($group) $query .= ")"; else $query .= "";

	// Uncomment to output queries onto page for debugging
	// print_r($query);

	// Search AD
	$results = ldap_search($ldap,$ldap_dn,$query);
	ldap_sort($ldap, $results, 'sn');
	ldap_sort($ldap, $results, 'department');
	$entries = ldap_get_entries($ldap, $results);

	// Remove first entry (it's always blank)
	array_shift($entries);

	$output = array(); // Declare the output array

	$i = 0; // Counter
	// Build output array
	foreach($entries as $u) {
		foreach($keep as $x) {
			// Check for attribute
			if(isset($u[$x][0])) $attrval = $u[$x][0]; else $attrval = NULL;

			// Append attribute to output array
			$output[$i][$x] = $attrval;
		}
		$i++;
	}

	return $output;
}

// Custom Meta Fields
add_action( 'load-post.php', 'raymond_meta_boxes_setup' );
add_action( 'load-post-new.php', 'raymond_meta_boxes_setup' );

function raymond_meta_boxes_setup() {

	// Add the meta boxes using add_meta_boxes()
	add_action( 'add_meta_boxes', 'raymond_add_meta_boxes' );

	// Save Meta
	add_action( 'save_post', 'save_raymond_meta_box', 10, 2 );

}

function raymond_add_meta_boxes() {
	$post_id = $_GET['post'] ? $_GET['post'] : $_POST['post_ID'] ;
	$pagetemplate = esc_attr( get_post_meta( $post_id, '_wp_page_template', true ) );
	if ( $pagetemplate == 'blog.php' || $pagetemplate == 'gooey.php' ) {
		add_meta_box(
			'raymond-blog-post-page-category',
			esc_html__( 'Blog Category', 'example' ),
			'raymond_meta_box',
			'page',
			'side',
			'default'
		);
	}
}

function raymond_meta_box($object, $box) { ?>
	<?php 
	wp_nonce_field( basename( __FILE__ ), 'raymond_blog_post_page_category_nonce' ); ?>

	<p>
		<label for="raymond-blog-post-page-category"><?php _e( "Set the category of blog posts this page will display", 'example' ); ?></label>
		<br />
		<?php wp_dropdown_categories('name=raymond-blog-post-page-category&id=raymond-blog-post-page-category&selected='.
		esc_attr( get_post_meta( $object->ID, 'raymond_blog_post_page_category', true ) )); ?>
	</p>
<?php }

/* Save the meta box's post metadata. */
function save_raymond_meta_box( $post_id, $post ) {

	/* Verify the nonce before proceeding. */
	if ( !isset( $_POST['raymond_blog_post_page_category_nonce'] ) || !wp_verify_nonce( $_POST['raymond_blog_post_page_category_nonce'], basename( __FILE__ ) ) )
		return $post_id;

	/* Get the post type object. */
	$post_type = get_post_type_object( $post->post_type );

	/* Check if the current user has permission to edit the post. */
	if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
		return $post_id;

	/* Get the posted data and sanitize it for use as an HTML class. */
	$new_meta_value = ( isset( $_POST['raymond-blog-post-page-category'] ) ? sanitize_html_class( $_POST['raymond-blog-post-page-category'] ) : '' );

	/* Get the meta key. */
	$meta_key = 'raymond_blog_post_page_category';

	/* Get the meta value of the custom field key. */
	$meta_value = get_post_meta( $post_id, $meta_key, true );

	/* If a new meta value was added and there was no previous value, add it. */
	if ( $new_meta_value && '' == $meta_value )
		add_post_meta( $post_id, $meta_key, $new_meta_value, true );

	/* If the new meta value does not match the old value, update it. */
	elseif ( $new_meta_value && $new_meta_value != $meta_value )
		update_post_meta( $post_id, $meta_key, $new_meta_value );

	/* If there is no new meta value but an old value exists, delete it. */
	elseif ( '' == $new_meta_value && $meta_value )
		delete_post_meta( $post_id, $meta_key, $meta_value );
}

?>
