<?php

/**
 * Template Post Type: pgc_simply_gallery
 *
 * @version 5.3.5
 */

get_header();
?>

<div id="content" class="site-content container py-3">
  <div id="primary" class="content-area">
    <!-- Hook to add something nice -->
    <div class="row">
      <div class="col-12">
        <main id="main" class="site-main">
          <header class="entry-header d-none">
            <?php the_post(); ?>
            <h1><?php the_title(); ?></h1>
          </header>

          <div class="entry-content">
            <?php the_content(); ?>
          </div>
        </main>
      </div>
    </div>

  </div>
</div>

<?php
get_footer();
