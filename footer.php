<?php

/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Bootscore
 *
 * @version 5.3.0
 */

?>

<footer>
  <div class="bootscore-footer text-bg-dark pt-4">
    <div class="container">
      <div class="row">
        <!-- Menú Accesos Rápidos -->
        <div class="d-none d-lg-block col-lg-4">
          <h2 class="h5">Acceso Rápido</h2>
          <?php
          wp_nav_menu(array(
            'theme_location'  => 'footer-menu-01',
            'container'       => false,
            'menu_class'      => 'footer-menu',
            'fallback_cb'     => '__return_false',
            'link_before'     => '<i class="fa-solid fa-caret-right me-2 fs-6"></i>',
            'depth'           => 1,
            'walker'          => new bootstrap_5_wp_nav_menu_walker()
          ));
          ?>
        </div>

        <!-- Menú COC en Redes -->
        <div class="col-md-6 col-lg-4">
          <h2 class="h5">COC en Redes</h2>
          <?php
          wp_nav_menu(array(
            'theme_location' => 'footer-menu-02',
            'container'      => false,
            'menu_class'     => 'footer-menu',
            'fallback_cb'    => '__return_false',
            'depth'          => 1,
            'walker'         => new bootstrap_5_wp_nav_menu_walker()
          ));
          ?>
        </div>

        <!-- Footer 1 Widget -->
        <div class="col-md-6 col-lg-4">
          <?php if (is_active_sidebar('footer-1')) : ?>
            <?php dynamic_sidebar('footer-1'); ?>
          <?php endif; ?>
        </div>

        <!-- Bootstrap 5 Nav Walker Footer Menu -->
        <?php
        wp_nav_menu(array(
          'theme_location' => 'footer-menu',
          'container'      => false,
          'menu_class'     => '',
          'fallback_cb'    => '__return_false',
          'items_wrap'     => '<ul id="footer-menu" class="nav %2$s">%3$s</ul>',
          'depth'          => 1,
          'walker'         => new bootstrap_5_wp_nav_menu_walker()
        ));
        ?>

      </div>
    </div>

    <div class="bootscore-info text-bg-dark border-top border-secondary border-opacity-25 py-2 text-center">
      <div class="container">
        <?php if (is_active_sidebar('footer-info')) : ?>
          <?php dynamic_sidebar('footer-info'); ?>
        <?php endif; ?>
        <small class="bootscore-copyright"><span class="cr-symbol">&copy;</span>&nbsp;<?= date('Y'); ?> <?php bloginfo('name'); ?></small>
      </div>
    </div>

</footer>

<?php if (is_front_page() || is_page('especialidades')):
  $tel_mesa_entradas = "5493512373748";
  $tel_epo = "5493517016644";
?>
  <!-- Check which number to use for each page -->
  <a href="https://api.whatsapp.com/send/?phone=<?php print(is_front_page() ? $tel_mesa_entradas : $tel_epo) ?>" target="_blank" id="whatsapp-shortcut" class="bg-success rounded-circle link-light"><i class="fa-brands fa-whatsapp"></i></a>
<?php endif; ?>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>

</html>