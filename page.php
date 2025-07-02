<?php

/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 */

get_header();
?>

<?php the_post(); ?>
<div class="w-100 page-img-header border-bottom border-subtle-secondary">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <header class="py-3 py-lg-5">
          <h1 class="m-0 text-primary"><?php the_title(); ?></h1>
        </header>
      </div>
    </div>
  </div>
</div>

<div id="content" class="site-content container py-5">
  <div id="primary" class="content-area">
    <main id="main" class="site-main">
      <div class="entry-content">
        <?php the_content(); ?>
      </div>
    </main>
  </div>
</div>

<?php get_footer();
