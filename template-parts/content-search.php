<?php

/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 * @version 5.3.4
 */


// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

?>


<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <a class="text-body text-decoration-none" href="<?php the_permalink(); ?>">
    <?php the_title('<h2 class="blog-post-title h5">', '</h2>'); ?>
  </a>

  <?php if ('post' === get_post_type()) : ?>
    <p class="meta small mb-2 text-body-tertiary">
      <?php
      bootscore_date();
      bootscore_author();
      bootscore_comments();
      bootscore_edit();
      ?>
    </p>
  <?php endif; ?>

  <p><a class="text-body text-decoration-none" href="<?php the_permalink(); ?>"><?php the_excerpt(); ?></a></p>

  <p><a class="read-more btn btn-secondary" href="<?php the_permalink(); ?>">Leer más <i class="fa-solid fa-angles-right fa-xs"></i></a></p>

  <?php bootscore_tags(); ?>
</article>
<!-- #post-<?php the_ID(); ?> -->