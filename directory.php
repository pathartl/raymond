<?php /* Template name: Directory */ ?>  

<?php get_header(); ?>

<style type="text/css">
	#directory {
		padding: 10px;
	}

	.staff-member:target {
		box-shadow: inset 0 0 5px 0 rgba(0,0,0,0.15);
		background: #F2F2F2;
		border: 1px solid #c0c0c0;
		border-top: 0;
	}

	.staff-member {
		padding: 10px;
		border-bottom: 1px solid #c0c0c0;
		font-size: 12px;
		clear: both;
	}

	.ad-displayname {
		font-weight: bold;
		font-size: 14px;
		margin-bottom: 3px;
		width: 50%;
		float: left;
	}

	.ad-extension {
		width: 50%;
		float: left;
		text-align: right;
	}

	.ad-title {
		width: 50%;
		float: left;
	}

	.ad-email {
		width: 50%;
		float: left;
		text-align: right;
	}
	iframe {
		width: 300px;
		height: 400px;
	}

	@media screen and (max-width: 660px) {
		.ad-email {
			float: none;
			text-align: left;
		}
	}

</style>

<?php
if ( is_home() ) {
	global $wp_query;
	
	$args = array_merge( $wp_query->query, array( 'post__not_in' => get_featured_posts() ) );

	if ( is_home() ) {
		$args = array_merge( $wp_query->query, array( 'post__not_in' => get_featured_posts(), 'tag' => 'home' ) );
	}

	query_posts( $args );
}
?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<article <?php post_class( get_post_format() ); ?> id="front-content">

		<header>
			<h1><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
		</header>

		<div class="post-content"><?php the_content('Read more...'); ?></div>

	</article>
	<section id="directory">
		<?php $directory = get_members(); ?>

		<?php foreach ($directory as $staff_member) : ?>

			<div class="staff-member" id="<?php echo $staff_member['samaccountname']; ?>">
				<div class="ad-displayname">
					<?php
						if ( $staff_member['wwwhomepage'] ) {
							echo '<a href="' . $staff_member['wwwhomepage'] . '">' . $staff_member['displayname'] . '</a>';
						} else {
							echo $staff_member['displayname'];
						}

					?>
				</div>
				<div class="ad-extension">
					<?php if ($staff_member['telephonenumber']) echo "Extension " . $staff_member['telephonenumber']; ?>
				</div>
				<div class="clear"></div>
				<div class="ad-title">
					<?php echo $staff_member['title']; ?>
				</div>
				<div class="ad-email">
					<a href="mailto:<?php echo $staff_member['mail']; ?>"><?php echo $staff_member['mail'] ; ?></a>
				</div>
				<div class="clear"></div>
			</div>

		<?php endforeach; ?>

	</section>
<?php endwhile; ?>
	<div class="pagenav"><span class="leftnav"><?php next_posts_link('&lsaquo; Older Entries') ?></span><span class="rightnav"><?php previous_posts_link('Newer Entries &rsaquo;') ?></span></div>
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