<?php
// Base style and scripts
add_action('wp_enqueue_scripts', 'bootscore_child_enqueue_styles');
function bootscore_child_enqueue_styles()
{
	// style.css
	wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');

	// Compiled main.css
	$modified_bootscoreChildCss = date('YmdHi', filemtime(get_stylesheet_directory() . '/assets/css/main.css'));
	wp_enqueue_style('main', get_stylesheet_directory_uri() . '/assets/css/main.css', array('parent-style'), $modified_bootscoreChildCss);
}

// Prevent CF7 JS and CSS loading in pages where isn't need
add_filter('wpcf7_load_js', '__return_false');
add_filter('wpcf7_load_css', '__return_false');

// Include custom scripts and libs
add_action('wp_enqueue_scripts', 'custom_scripts_libs');

function custom_scripts_libs()
{
	wp_enqueue_script('custom-js', get_stylesheet_directory_uri() . '/assets/js/custom.js', false, '', true);
	wp_localize_script('custom-js', 'envParams', array(
		'SITE_URL' => esc_url(home_url()) . '/'
	));

	wp_enqueue_script('env', get_stylesheet_directory_uri() . '/assets/js/env.js', array(), false, false);
	wp_localize_script('env', 'envParams', array(
		'SITE_URL' => esc_url(home_url()) . '/'
	));

	if (is_front_page()) :
		wp_enqueue_script('splide-js', get_stylesheet_directory_uri() . '/assets/js/splide.min.js', array(), null, true);
		wp_enqueue_style('splide-css', get_stylesheet_directory_uri() . '/assets/css/splide-default.min.css', array(), null);

		wp_enqueue_script('capacitaciones-front', get_stylesheet_directory_uri() . '/assets/js/capacitaciones-front.js', false, array('env', 'splide-js'), true);
	endif;

	if (is_page('capacitaciones-iniciadas')) :
		wp_enqueue_script('capacitaciones-iniciadas', get_stylesheet_directory_uri() . '/assets/js/capacitaciones-iniciadas.js', array('env'), null, true);
	endif;

	if (is_page('especialidades')) :
		wp_enqueue_script('capacitaciones-inside', get_stylesheet_directory_uri() . '/assets/js/capacitaciones-inside.js', array('env'), null, true);
	endif;

	if (is_page('beneficios')) :
		wp_enqueue_script('beneficios', get_stylesheet_directory_uri() . '/assets/js/beneficios.js', array('env'), null, true);
	endif;

	if (is_page('sociedades-filiales')) :
		wp_enqueue_script('sociedades', get_stylesheet_directory_uri() . '/assets/js/sociedades.js', array('env'), null, true);
	endif;

	if (is_singular('capacitaciones')) :
		wp_enqueue_script('handler-inscripcion', get_stylesheet_directory_uri() . '/assets/js/handler-inscripcion.js', array('env'), null, true);
	endif;

	// Load CF7 libs only in specific pages
	if (is_page('contacto')) {
		if (function_exists('wpcf7_enqueue_scripts')) {
			wpcf7_enqueue_scripts();
		}

		if (function_exists('wpcf7_enqueue_styles')) {
			wpcf7_enqueue_styles();
		}
	}

	if (is_checkout()) {
		wp_enqueue_script('coc-checkout', get_stylesheet_directory_uri() . '/assets/js/coc-checkout.js', array(), null, true);
	}

	function add_module_attribute($tag, $handle, $src)
	{
		$handle_check = (
			$handle === 'capacitaciones-inside' ||
			$handle === 'capacitaciones-front' ||
			$handle === 'capacitaciones-iniciadas' ||
			$handle === 'sociedades' ||
			$handle === 'beneficios'
		);

		if ($handle_check) {
			$tag = '<script type="module" src="' . esc_url($src) . '"></script>';

			return $tag;
		}

		return $tag;
	}

	add_filter('script_loader_tag', 'add_module_attribute', 10, 3);
}


add_action('after_setup_theme', 'include_custom_shortcodes');

function include_custom_shortcodes()
{
	require_once(__DIR__ . '/inc/shortcodes.php');
}

add_action('rest_api_init', 'include_custom_api_routes');

function include_custom_api_routes()
{
	require_once(__DIR__ . '/inc/custom-rest-api.php');
}

/**
 * Custom REST API functions
 */
function get_capacitaciones_vigentes(WP_REST_Request $request)
{
	$today = current_time('Ymd');

	$query_args = array(
		'post_type' => 'capacitaciones',
		'nopaging' => 'true',
		'meta_query' => array(
			array(
				'key' => 'fecha_inicio_dateformat',
				'value' => $today,
				'compare' => '>',
				'type' => 'DATE',
			),
		),
		'tax_query' => array(
			array(
				'taxonomy' => 'especialidad',
				'field' => 'slug',
				'operator' => 'EXISTS',
			),
		),
	);

	$capacitaciones = new WP_Query($query_args);

	return process_capacitaciones($capacitaciones);
}

function process_capacitaciones(WP_Query $capacitaciones)
{
	$data = [];

	if ($capacitaciones->have_posts()) {
		while ($capacitaciones->have_posts()) {
			$capacitaciones->the_post();

			$post = get_post();
			$capacitacion = get_capacitacion_info($post);

			$data[] = $capacitacion;
		}
	}

	wp_reset_postdata();

	if (!count($data) > 0) {
		return 'No se han encontrado contenidos de este tipo';
	}

	return $data;
}

function get_capacitaciones_iniciadas(WP_REST_Request $request)
{
	$today = current_time('Ymd');

	$query_args = array(
		'post_type' => 'capacitaciones',
		'posts_per_page' => -1, // Retrieve all posts
		'meta_query' => array(
			array(
				'key' => 'fecha_inicio_dateformat',
				'value' => $today,
				'compare' => '<=',
				'type' => 'DATE',
			),
		),
		'tax_query' => array(
			array(
				'taxonomy' => 'especialidad',
				'field' => 'slug',
				'operator' => 'EXISTS',
			),
		),
	);

	$capacitaciones = new WP_Query($query_args);

	return process_capacitaciones($capacitaciones);
}

function get_capacitacion_info($post = null)
{
	if (!$post || !is_object($post)) {
		return null;
	}

	$post_id = $post->ID;

	$post_title = $post->post_title;
	$post_tipo_capacitacion = get_post_meta($post_id, 'tipo_capacitacion', true) ?: '';
	$post_especialidad_terms = get_the_terms($post_id, 'especialidad');
	if ($post_especialidad_terms && !is_wp_error($post_especialidad_terms)) {
		$post_especialidad_slug = $post_especialidad_terms[0]->slug ?? '';
		$post_especialidad_name = $post_especialidad_terms[0]->name ?? '';
	}
	$post_dictante_principal =  get_post_meta($post_id, 'dictante_principal_txt', true) ?: '';
	$post_fecha_inicio =        get_post_meta($post_id, 'fecha_inicio', true) ?: '';
	$post_fecha_inicio_df =     get_post_meta($post_id, 'fecha_inicio_dateformat', true) ?: '';
	$post_link = get_permalink($post_id);
	$post_thumbnail = get_the_post_thumbnail_url($post_id, 'medium');

	$info_capacitacion = [
		"id" => $post_id,
		"titulo" => sanitize_text_field($post_title),
		"tipo_capacitacion" => sanitize_text_field($post_tipo_capacitacion),
		"especialidad_slug" => sanitize_text_field($post_especialidad_slug),
		"especialidad_name" => sanitize_text_field($post_especialidad_name),
		"dictante_principal" => sanitize_text_field($post_dictante_principal),
		"fecha_inicio" => sanitize_text_field($post_fecha_inicio),
		"fecha_inicio_df" => sanitize_text_field($post_fecha_inicio_df),
		"link" => esc_url($post_link),
		"thumbnail" => esc_url($post_thumbnail),
	];

	return $info_capacitacion;
}



add_action('widgets_init', 'manage_custom_sidebars', 11);

// Desactiva Footer Widget 4 que viene por defecto para evitar confusiones
function manage_custom_sidebars()
{
	unregister_sidebar('footer-2');
	unregister_sidebar('footer-3');
	unregister_sidebar('footer-4');
	unregister_sidebar('top-footer');
	register_sidebar(array(
		'name'          => 'Páginas internas',
		'id'            => 'sidebar-page-basic',
		'description'   => 'Sidebar para mostrar en páginas internas',
		'before_widget' => '<div class="widget bg-light border shadow-sm rounded overflow-hidden mb-4 p-3" id="widget-%1s">',
		'after_widget'  => '</div>'
	));
}


function register_footer_menus()
{
	register_nav_menus(
		array(
			'footer-menu-01' => 'Footer Menu 01',
			'footer-menu-02' => 'Footer Menu 02'
		)
	);
}

add_action('after_setup_theme', 'register_footer_menus', 0);

/* Empty cart before adding any new product to prevent buying multiple items
since its no allowed buy the site owner */
function single_item_cart($new_item, $product_id, $quantity)
{
	if (!WC()->cart->is_empty()) {
		WC()->cart->empty_cart();
	}

	return $new_item;
}

add_filter('woocommerce_add_to_cart_validation', 'single_item_cart', 20, 3);

// Disable AJAX Cart
function register_ajax_cart() {}

add_action('after_setup_theme', 'register_ajax_cart');

// Filter what post types should appear in the search results
function custom_search_filter($query)
{

	// Check query is a search but not from the admin dashboard
	if (!is_admin() && $query->is_search) {
		// Set the post types to be included in the search results
		$query->set('post_type', array('post', 'page', 'beneficio', 'capacitaciones'));
	}

	// Return the query
	return $query;
}

// Add the new search filter to the pre_get_posts hook
add_filter('pre_get_posts', 'custom_search_filter');


// Set orders payed with MercadoPago to "completed" status
add_action('woocommerce_payment_complete', 'autocomplete_mercado_pago_orders');

function autocomplete_mercado_pago_orders($order_id)
{
	if (!$order_id) {
		return;
	}

	$order = wc_get_order($order_id);
	$pay_method = $order->get_payment_method();

	if ($pay_method === 'woo-mercado-pago-basic') { // Chequea solamente las ordenes de MercadoPago
		$order->update_status('completed');
	}
}


// Validate CUIT/CUIL checkout field
add_action('woocommerce_after_checkout_validation', 'coc_validate_checkout_cuit_cuil', 10, 2);

function coc_validate_checkout_cuit_cuil($fields, $errors)
{

	if (strlen($fields['billing_cuit_cuil']) < 13) {
		$errors->add('validation', '<strong>Numero de CUIT/CUIL incorrecto:</strong> revise que conste de exactamente 13 caracteres');
	}
}

/**
 * Filtro para autocompletado de ordenes en productos especificos
 */

function process_free_orders($payment_complete_status, $order_id, $order)
{
	if (! $order->get_total() >= 0) {
		return 'completed';
	}

	return $payment_complete_status;
}

add_filter('woocommerce_payment_complete_order_status', 'process_free_orders', 10, 3);
