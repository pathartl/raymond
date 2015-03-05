<?php get_header(); ?>

<?php
if ( is_home() ) {
  global $wp_query;
  $args = array_merge( $wp_query->query, array( 'post__not_in' => get_featured_posts() ) );
  query_posts( $args );
}
?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
  <article <?php post_class( get_post_format() ); ?>>
    <header>
      <h1><?php the_title(); ?></h1>
    </header>
    <div class="post-content">
          <?php
          // Subpage sidebar related goodness

          // Set up the objects needed
          $my_wp_query = new WP_Query();
          $all_wp_pages = $my_wp_query->query(array('post_type' => 'page', 'posts_per_page' => -1));

          // Filter through all pages and find Portfolio's children
          $portfolio_children = array_reverse(get_page_children( get_the_ID(), $all_wp_pages ));
          if (count($portfolio_children)) {
          ?>
          <div class="subpage-sidebar">
            <section>Related Pages</section>
            <?php
              foreach ($portfolio_children as $portfolio_child) {
                echo "<div><a href=" . $portfolio_child->guid . ">" . $portfolio_child->post_title . "</a></div>";
              }
            ?>
          </div>
          <?php } ?>
      <?php the_content('Read more...'); ?>
    </div>
  </article>
  <?php endwhile; ?>
  <div class="pagenav"><span class="leftnav"><?php next_posts_link('&lsaquo; Older Entries') ?></span><span class="rightnav"><?php previous_posts_link('Newer Entries &rsaquo;') ?></span></div>
<?php endif; ?>

<?php get_footer(); ?>