<?php get_header(); ?>

<?php
if ( is_home() ) {
	global $wp_query;
	
	$args = array_merge( $wp_query->query, array( 'post__not_in' => get_featured_posts() ) );
	if ( is_home() )
		$args = array_merge( $wp_query->query, array( 'post__not_in' => get_featured_posts(), 'tag' => 'home' ) );
	query_posts( $args );
}
?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

	<article <?php post_class( get_post_format() ); ?>>
		<header>
			<h1><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
			<span class="meta">Posted: <?php the_time('M d'); ?><sup><?php the_time('S'); ?></sup>, <?php the_time('Y'); ?></span>
		</header>
		<div class="post-content"><?php the_content('Read more...'); ?></div>
	</article>

	<?php endwhile; ?>

	<div class="pagenav">
		<span class="leftnav"><?php next_posts_link('&lsaquo; Older Entries') ?></span>
		<span class="rightnav"><?php previous_posts_link('Newer Entries &rsaquo;') ?></span>
	</div>

<?php else: ?>

	<article>
		<header>
			<h1>No Posts</h1>
		</header>
		<div class="post-content">
			<p>Sorry, there aren't any posts in this section yet. Check the <a href="<?php bloginfo('siteurl'); ?>">home page</a> for a complete listing of the posts.</p>
		</div>
	</article>
	
<?php endif; ?>

<?php get_footer(); ?>