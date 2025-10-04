  <?php

	/**
	 * Front-Page Template
	 *
	 * @package Bootscore
	 */

	use function PHPSTORM_META\map;

	get_header();
	?>

  <div id="content" class="site-content">
  	<div id="hero-unit">
  		<?php $args = array(
				'post_type' => 'home_slides',
				'posts_per_page' => -1,
				'orderby' => 'meta_value_num',
				'meta_key' => 'orden_publicacion',
				'order' => 'ASC'
			);

			$slide_query = new WP_Query($args);

			if ($slide_query->have_posts()) : ?>
  			<div id="front-carousel" class="carousel slide" data-bs-ride="carousel" data-bs-touch="true">
  				<div class="carousel-inner">
  					<?php $c = 0; ?>
  					<?php while ($slide_query->have_posts()) : $slide_query->the_post(); ?>
  						<div class="carousel-item position-relative <?php if ($c == 0) : ?>active<?php endif; ?>" data-order="<?php echo get_post_meta(get_the_ID(), 'orden_publicacion', true) ?>">
  							<?php $link = get_field('content_link');
								if ($link) : // Verifica si el slide tiene un link asociado 
								?>
  								<a class="d-block w-100" href="<?php echo $link['url']; ?>">
  								<?php endif; ?>
  								<picture>
  									<?php
										$slide_mobile = get_field('slide_mobile');
										$slide_desktop = get_field('slide_desktop');
										?>

  									<source srcset="<?php echo esc_url($slide_mobile['url']); ?>" class="d-block w-100" media="(max-width:768px)">
  									<img src="<?php echo esc_url($slide_desktop['url']); ?>" class="d-block w-100" fetchpriority="high">
  								</picture>
  								<?php if ($link) : // Verificación para cerrar el <a> y agregar un boton 
									?>
  								</a>
  							<?php endif; ?>
  						</div><!-- .carousel-item -->
  						<?php $c++; ?>
  					<?php endwhile; ?>
  				</div><!-- .carousel-inner -->

  				<button class="carousel-control-prev" type="button" data-bs-target="#front-carousel" data-bs-slide="prev">
  					<span class="carousel-control-prev-icon" aria-hidden="true"><i class="fa-solid fa-circle-chevron-left"></i></span>
  					<span class="visually-hidden">Anterior</span>
  				</button>

  				<button class="carousel-control-next" type="button" data-bs-target="#front-carousel" data-bs-slide="next">
  					<span class="carousel-control-next-icon" aria-hidden="true"><i class="fa-solid fa-circle-chevron-right"></i></span>
  					<span class="visually-hidden">Siguiente</span>
  				</button>
  			</div><!-- #front-carousel -->

  			<?php wp_reset_postdata(); ?>
  		<?php endif; ?>
  	</div>
  	<div id="primary" class="content-area">
  		<main id="main" class="site-main">
  			<div class="entry-content">
  				<div class="container">
  					<?php the_post(); ?>
  					<?php the_content(); ?>
  				</div><!-- .container -->
  			</div><!-- .entry-content -->
  		</main><!-- .site-main -->
  	</div><!-- #primary -->
  </div><!-- #content -->

  <?php get_footer();
