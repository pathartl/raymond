
<aside id="sidebar">
  <?php if ( is_active_sidebar( 'header-sidebar' ) ) : ?>
    <ul id="header-sidebar">
      <?php
      if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Header Sidebar') ) :
      endif; ?>
    </ul>
  <?php endif; ?>
</aside>
