<?php /* Template name: Blog Page */ ?>  

<?php get_header(); ?>


<?php 
	global $query_string; // required
	$posts = query_posts($query_string.'&posts_per_page=5&cat=2&order=ASC');
?>

<?php
	$args = array(
		'numberposts' => 3,
		'post_status' => 'publish',
		'category'    => '' . get_post_meta($post->ID, 'raymond_blog_post_page_category', TRUE)
	);

	$lastposts = get_posts( $args );
?>

<?php foreach($lastposts as $post) : setup_postdata($post); ?>
	<article <?php post_class( get_post_format() ); ?>>

		<header>
			<h1>
				<a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a>
			</h1>
			<span class="meta">Posted: <?php the_time('M d'); ?><sup><?php the_time('S'); ?></sup>, <?php the_time('Y'); ?></span>
		</header>

		<div class="post-content"><?php the_content('Read more...'); ?></div>

		<?php next_posts_link(); ?>
		<?php previous_posts_link(); ?>

	</article>
<?php endforeach; ?>

<?php get_footer(); ?>