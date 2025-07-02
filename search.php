<?php

/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Bootscore
 */

get_header();
?>
<div class="w-100 page-img-header border-bottom border-subtle-secondary">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <header class="py-3 py-lg-5">
          <h1 class="m-0 text-primary h2">
            <?php /* translators: %s: search query. */
            printf(esc_html__('Search Results for: %s', 'bootscore'), '<span class="text-secondary">' . get_search_query() . '</span>');
            ?>
          </h1>
        </header>
      </div>
    </div>
  </div>
</div>

<div id="content" class="site-content container py-5">
  <div id="primary" class="content-area">
    <div class="row">
      <div class="<?= apply_filters('bootscore/class/main/col', 'col'); ?>">
        <main id="main" class="site-main">
          <?php if (have_posts()) : ?>
          <?php
            /* Start the Loop */
            while (have_posts()) :
              the_post();

              /**
               * Run the loop for the search to output the results.
               * If you want to overload this in a child theme then include a file
               * called content-search.php and that will be used instead.
               */
              get_template_part('template-parts/content', 'search');

            endwhile;

            bootscore_pagination();

          else :

            get_template_part('template-parts/content', 'none');

          endif;
          ?>

        </main><!-- #main -->

      </div><!-- col -->
      <?php get_sidebar(); ?>
    </div><!-- row -->

  </div><!-- #primary -->
</div><!-- #content -->
<?php
get_footer();
