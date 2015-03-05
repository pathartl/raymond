<!DOCTYPE html> 
<html> 
<head> 
	<meta charset="UTF-8"> 
	<meta name="viewport" content="width=device-width"> 
	<title><?php wp_title(' &mdash; ', true, 'right'); ?></title>
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>">
	<?php if ( is_front_page() ) echo '<link rel="stylesheet" href="' . get_bloginfo('template_directory') . '/css/frontpage.css" />'; ?>
	<link rel="author" href="<?php bloginfo('template_directory'); ?>/humans.txt" />
	
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
	<script src="<?php bloginfo('template_directory'); ?>/js/modernizr-1.7.min.js"></script>
	
	<script src="<?php bloginfo('template_directory'); ?>/js/jquery.fitvids.js"></script>
	<script>
	$(function() {
		$("article").fitVids();
	});
	</script>
	
	<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
	<?php wp_head(); ?>
</head> 
<body <?php body_class(); ?>>

	<div id="login">
		<?php if ( !is_user_logged_in() ) { ?>
		<a href="<?php echo network_site_url('/wp-login.php?redirect_to=' . get_site_url()) . '/wp-admin'; ?>">Log In</a>
		<?php } ?>
	</div>

	<div id="quick-menu">
		<?php 
			Multisite_Global_Search::ms_global_search_horizontal_form("../search",1,1);
			if ( !is_main_site() ) {
				global $blog_id;
				$current_site = $blog_id;
				switch_to_blog(1);
				wp_nav_menu( array( 'theme_location' => 'header-menu' ) );
				switch_to_blog($current_site);
			} else {
				wp_nav_menu( array( 'theme_location' => 'header-menu' ) );
			}
		?>
	</div>

	<header id="head">
		<a id="logo" href="<?php echo network_site_url(); ?>"><img src="<?php bloginfo('template_directory'); ?>/images/logo.png" /></a>
	</header>
	
	<div id="page">
		
		<?php 
		if ( is_front_page() ) {
			get_sidebar('frontpage');
		} else {
			get_sidebar();
		}
		?>
		<section id="main">
