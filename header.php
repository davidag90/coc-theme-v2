<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Bootscore
 *
 * @version 5.3.0
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<!-- Favicons -->
	<link rel="apple-touch-icon" sizes="180x180" href="<?= get_stylesheet_directory_uri(); ?>/assets/img/favicon/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="<?= get_stylesheet_directory_uri(); ?>/assets/img/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="<?= get_stylesheet_directory_uri(); ?>/assets/img/favicon/favicon-16x16.png">
	<link rel="manifest" href="<?= get_stylesheet_directory_uri(); ?>/assets/img/favicon/site.webmanifest">
	<link rel="mask-icon" href="<?= get_stylesheet_directory_uri(); ?>/assets/img/favicon/safari-pinned-tab.svg" color="#0d6efd">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="theme-color" content="#ffffff">
	<!-- Google Tag Manager -->
	<script>
		(function(w, d, s, l, i) {
			w[l] = w[l] || [];
			w[l].push({
				'gtm.start': new Date().getTime(),
				event: 'gtm.js'
			});
			var f = d.getElementsByTagName(s)[0],
				j = d.createElement(s),
				dl = l != 'dataLayer' ? '&l=' + l : '';
			j.async = true;
			j.src =
				'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
			f.parentNode.insertBefore(j, f);
		})(window, document, 'script', 'dataLayer', 'GTM-WLLNGKG');
	</script>
	<!-- End Google Tag Manager -->
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WLLNGKG" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->

	<?php wp_body_open(); ?>

	<div id="page" class="site">
		<header id="masthead" class="site-header">
			<div class="fixed-top bg-primary">
				<nav id="nav-main" class="navbar navbar-expand-lg navbar-dark">
					<div class="container">
						<!-- Navbar Brand -->
						<a class="navbar-brand xs d-md-none" href="<?= esc_url(home_url()); ?>"><img src="<?= esc_url(get_stylesheet_directory_uri()); ?>/assets/img/logo/logo-sm.png" alt="logo" class="logo xs"></a>
						<a class="navbar-brand md d-none d-md-block" href="<?= esc_url(home_url()); ?>"><img src="<?= esc_url(get_stylesheet_directory_uri()); ?>/assets/img/logo/logo.png" alt="logo" class="logo md"></a>

						<!-- Offcanvas Navbar -->
						<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvas-navbar">
							<div class="offcanvas-header bg-dark text-light">
								<span class="h5 offcanvas-title"><?php esc_html_e('Menu', 'bootscore'); ?></span>
								<button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa-regular fa-circle-xmark"></i></button>
							</div>
							<div class="offcanvas-body bg-primary flex-lg-column">
								<div id="plus-nav" class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-lg-end mb-2 mb-lg-0">
									<a href="http://certificaciones.coc-cordoba.org.ar/" class="btn btn-secondary btn-sm" target="_blank"><i class="fa-solid fa-file-lines me-1"></i> Certificaciones</a>
									<a href="http://www.coc-autogestion.com.ar/" class="btn btn-secondary btn-sm" target="_blank"><i class="fa-solid fa-user me-1"></i> Autogestión Socios</a>

									<?= get_search_form(); ?>
								</div>

								<!-- Bootstrap 5 Nav Walker Main Menu -->
								<?php
								wp_nav_menu(array(
									'theme_location' => 'main-menu',
									'container'      => false,
									'menu_class'     => '',
									'fallback_cb'    => '__return_false',
									'items_wrap'     => '<ul id="bootscore-navbar" class="navbar-nav ms-auto %2$s">%3$s</ul>',
									'depth'          => 2,
									'walker'         => new bootstrap_5_wp_nav_menu_walker()
								));
								?>

								<!-- Top Nav 2 Widget -->
								<?php if (is_active_sidebar('top-nav-2')) : ?>
									<?php dynamic_sidebar('top-nav-2'); ?>
								<?php endif; ?>
							</div>
						</div>

						<div class="header-actions d-flex align-items-center">
							<!-- Top Nav Widget -->
							<?php if (is_active_sidebar('top-nav')) : ?>
								<?php dynamic_sidebar('top-nav'); ?>
							<?php endif; ?>

							<!-- Navbar Toggler -->
							<button class="btn btn-outline-light opacity-75 d-lg-none ms-1 ms-md-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-navbar" aria-controls="offcanvas-navbar">
								<i class="fa-solid fa-bars"></i><span class="visually-hidden-focusable">Menu</span>
							</button>
						</div><!-- .header-actions -->
					</div><!-- bootscore_container_class(); -->
				</nav><!-- .navbar -->
				<div id="accesos-directos" class="d-lg-none">
					<div class="p-2 text-center text-bg-secondary">
						<a href="http://certificaciones.coc-cordoba.org.ar/" class="link-light text-decoration-none" target="_blank"><i class="fa-solid fa-file-lines me-1"></i> Certificaciones</a>
					</div>
					<div class="p-2 text-center text-bg-light">
						<a href="http://www.coc-autogestion.com.ar/" class="link-dark text-decoration-none" target="_blank"><i class="fa-solid fa-user me-1"></i> Autogestión Socios</a>
					</div>
				</div><!-- #accesos-directos -->
			</div><!-- .fixed-top .bg-light -->

		</header><!-- #masthead -->