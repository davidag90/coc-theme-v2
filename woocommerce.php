<?php

/**
 * The template for displaying all WooCommerce pages
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

<div id="content" class="site-content container pb-5 mt-4">
  <div id="primary" class="content-area">
    <main id="main" class="site-main">

      <!-- Breadcrumb -->
      <?php woocommerce_breadcrumb(); ?>
      <div class="row">
        <div class="col-12">
          <?php woocommerce_content(); ?>
        </div>
      </div>
    </main><!-- #main -->
  </div><!-- #primary -->
</div><!-- #content -->
<?php
get_footer();
