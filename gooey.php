<?php /* Template name: Gooey Page */ ?>  

<?php get_header(); ?>

<?php
if ( is_home() ) {
	global $wp_query;
	
	$args = array_merge( $wp_query->query, array( 'post__not_in' => get_featured_posts() ) );

	if ( is_home() ){
		$args = array_merge( $wp_query->query, array( 'post__not_in' => get_featured_posts(), 'tag' => 'home' ) );
	}

	query_posts( $args );
}
?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

	<?php $page_id = get_the_ID(); ?>

	<article <?php post_class( get_post_format() ); ?>>
		<header>
			<h1><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
		</header>
		<div class="student-links">
			<?php get_links(get_post_meta($page_id, 'static_links_category', TRUE), '<div class="link-cell"><div>', '</div></div>', '<br />', FALSE, 'id', TRUE, FALSE, -1, TRUE, TRUE); ?>
		</div>
		<div class="clear"></div>
		<div class="post-content"><?php the_content('Read more...'); ?></div>
	</article>

	<?php endwhile; ?>

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

<?php

$posts_per_page = get_option('posts_per_page');

if ( $_GET['page'] ) {
	$page_offset = ($_GET['page']) * $posts_per_page;
} else {
	$page_offset = 0;
}

$gooey_category = get_post_meta($page_id, 'raymond_blog_post_page_category', TRUE);

$post_count=get_categories ('include=' . $gooey_category);
$post_count=$post_count[0]->category_count;

preg_match("/\/(\d+)\//", $_SERVER['REQUEST_URI'], $output_array);

if ($output_array[1]) {
	$current_page_num = $output_array[1];
} else {
	$current_page_num = 1;
}

$args = array(
	'numberposts' => $posts_per_page,
	'post_status' => 'publish',
	'category'    => $gooey_category,
	'offset'      => ($current_page_num - 1) * $posts_per_page);

$lastposts = get_posts( $args );
?>

<?php foreach($lastposts as $post) : setup_postdata($post); ?>

	<article <?php post_class( get_post_format() ); ?>>
		<header>
			<h1><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
			<span class="meta">Posted: <?php the_time('M d'); ?><sup><?php the_time('S'); ?></sup>, <?php the_time('Y'); ?></span>
		</header>
		<div class="post-content"><?php the_content('Read more...'); ?></div>
	</article>

<?php endforeach; ?>

<div class="pagenav">

	<?php

	// Get the number of pages total
	$num_of_pages = ceil($post_count / $posts_per_page);

	if ($output_array[1] < $num_of_pages) { ?>
		<a class="older-posts" href="?page=<?php echo ($current_page_num+1); ?>">Older Posts</a>
	<?php }
	if ($output_array[1] > 1) { ?>
		<a class="newer-posts" href="?page=<?php echo ($current_page_num-1); ?>">Newer Posts</a>
	<?php } ?>
	
</div>

<?php get_footer(); ?>