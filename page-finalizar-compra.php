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

<div id="content" class="site-content container pb-5 mt-5">
  <div id="primary" class="content-area">
    <div class="row">
      <div class="col-12">
        <main id="main" class="site-main">
          <header class="entry-header">
            <?php the_post(); ?>
            <h1><?php the_title(); ?></h1>
            <?php bootscore_post_thumbnail(); ?>
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
