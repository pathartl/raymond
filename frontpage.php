<?php /* Template name: Front Page */ ?>  

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
<?php echo do_shortcode( "[anything_slides order=RAND]" ); ?>
  <article <?php post_class( get_post_format() ); ?> id="front-content">
    <div class="post-content"><?php the_content('Read more...'); ?></div>
  </article>
  <section id="frontpage-ticker">
    <div>
      <h1>What's New At Raymond</h1>
      <?php if ( function_exists('insert_newsticker') )  { insert_newsticker(); } ?>
    </div>
  </section>
  <?php endwhile; ?>
  <section id="frontpage-featured">
  <?php
    $args = array( 'post__in' => get_featured_posts(), 'post_status' => 'publish', 'numberposts' => 3);
    query_posts( $args ); ?>
  <?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>
    <?php $checked = get_post_meta($post->ID, '_featured_status', true); ?>
    <?php if($checked[0]): ?>
    <div class="card">
      <article <?php post_class( get_post_format() ); ?>>
        <div class="front" style="background-image: url(<?php echo wp_get_attachment_url( get_post_thumbnail_id($post->ID) ); ?>)">
          <h1><?php the_title(); ?></h1>
        </div>
        <div class="back">
          <div class="post-content"><?php the_excerpt(); ?></div>
          <div class="read-more"><a href="<?php the_permalink() ?>">Read More</a></div>
        </div>
      </article>
    </div>
   <?php endif; endwhile; endif; ?>
   <?php wp_reset_query(); ?>
  </section>
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