<?php /* Template name: Staff Page */ ?>  

<?php get_header(); ?>

<?php $page_id = get_the_ID(); ?>

<?php if ( is_user_logged_in() ) { ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
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

<?php
$args = array( 'numberposts' => 6,
               'post_status' => 'publish',
               'tag' => '' . get_post_meta($page_id, 'static_slug', TRUE) );
$lastposts = get_posts( $args );
foreach($lastposts as $post) : setup_postdata($post); ?>
  <article <?php post_class( get_post_format() ); ?>>
    <header>
      <h1><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
      <span class="meta">Posted: <?php the_time('M d'); ?><sup><?php the_time('S'); ?></sup>, <?php the_time('Y'); ?></span>
    </header>
    <div class="post-content"><?php the_content('Read more...'); ?></div>
  </article>
<?php endforeach; ?>

<?php } else { ?>

  <article <?php post_class( get_post_format() ); ?>>
    <header>
      <h1><?php echo get_post_meta($page_id, 'access_denied_title', TRUE); ?></h1>
    </header>
    <div class="post-content"><?php echo get_post_meta($page_id, 'access_denied_content', TRUE); ?></div>
    <div class="login"><a href="<?php echo wp_login_url( get_permalink() ); ?>" title="Login">Login</a></div>
  </article>

<?php } ?>

<?php get_footer(); ?>