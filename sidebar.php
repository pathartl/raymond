<aside id="sidebar">
	<?php
		if ( !is_main_site() ) {
			echo "<h2>Raymond School</h2>";
			global $blog_id;
			$current_site = $blog_id;
			switch_to_blog(1);
			wp_nav_menu( array( 'theme_location' => 'raymond-menu' ) );
			switch_to_blog($current_site);
		}
	?>
	<ul id="widgets">
	<?php
		if ( !is_main_site() && is_front_page() ) {
			dynamic_sidebar('Left Sidebar');
		} else if ( is_front_page() ) {
			dynamic_sidebar('Front Page Sidebar');
		} else if ( is_page('students') ) {
			dynamic_sidebar('Students Sidebar');
		} else if ( is_page('parents') ) {
			dynamic_sidebar('Parents Sidebar');
		} else {
			dynamic_sidebar('Left Sidebar');
		}
	?>
	</ul>
</aside>