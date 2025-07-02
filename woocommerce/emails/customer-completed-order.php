<?php

/**
 * Customer completed order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-completed-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 9.9.0
 */

use Automattic\WooCommerce\Utilities\FeaturesUtil;

if (! defined('ABSPATH')) {
	exit;
}

$email_improvements_enabled = FeaturesUtil::feature_is_enabled('email_improvements');

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action('woocommerce_email_header', $email_heading, $email); ?>

<p>
	<?php
	if (! empty($order->get_billing_first_name())) {
		/* translators: %s: Customer first name */
		printf(esc_html__('Hi %s,', 'woocommerce'), esc_html($order->get_billing_first_name()));
	} else {
		printf(esc_html__('Hi,', 'woocommerce'));
	}
	?>
</p>
<?php
$variation_check = false;

$order_items = $order->get_items();

foreach ($order_items as $order_item) {
	$item_data = $order_item->get_data();

	if ($item_data['variation_id'] !== 0) $variation_check = true;
}

if ($variation_check): ?>
	<p>Tu pedido de inscripción ha sido procesado correctamente.</p>
<?php else : ?>
	<p>Tu pedido de pre-inscripción ha sido procesado correctamente.</p>
<?php endif; ?>

<div style="margin-top: 1.5rem; margin-bottom: 1.5rem;">
	<h3>Información importante</h3>

	<?php if ($variation_check): ?>
		<p>Tener presente que en caso de no asistir a la Actividad, deberá <strong>comunicar vía mail</strong> a <a href="mailto:epo@coc-cordoba.com.ar">epo@coc-cordoba.com.ar</a> con una <strong>anticipación de 72 horas hábiles de la fecha del inicio de la misma</strong>. Dicho importe, quedará pendiente para aplicar a otra actividad de su elección durante el mismo año. De <strong>no optar por ninguna actividad y finalizado el año calendario</strong>, perderá el valor, <strong>sin derecho a reclamos</strong>.</p>

		<p>La <strong>falta de comunicación</strong> en forma escrita y con la antelación mencionada, hará que <strong>se pierda el importe, sin derecho a reclamos</strong>.</p>
	<?php else: ?>
		<p>Tener presente que en caso de no asistir a la Actividad, deberá <strong>comunicar vía mail</strong> a <a href="mailto:epo@coc-cordoba.com.ar">epo@coc-cordoba.com.ar</a> con una <strong>anticipación de 72 horas hábiles de la fecha del inicio de la misma</strong>. Dicho importe, quedará pendiente para aplicar a otra actividad de su elección durante el mismo año. De <strong>no optar por ninguna actividad y finalizado el año calendario</strong>, perderá el valor, <strong>sin derecho a reclamos</strong>.</p>

		<p>La <strong>falta de comunicación</strong> en forma escrita y con la antelación mencionada, hará que <strong>se pierda el importe, sin derecho a reclamos</strong>.</p>

		<p>El <strong>pago de la cuota</strong> se realiza <strong>48 hs hábiles previas al inicio del Posgrado</strong>, cualquier consulta puede comunicarse al sector contable al <a href="https://api.whatsapp.com/?phone=5493512372986" target="_blank">+54 9 3512 37-2986</a>.</p>
	<?php endif; ?>
</div>
<?php

/*
 * @hooked WC_Emails::order_details() Shows the order details table.
 * @hooked WC_Structured_Data::generate_order_data() Generates structured data.
 * @hooked WC_Structured_Data::output_structured_data() Outputs structured data.
 * @since 2.5.0
 */
do_action('woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email);

/*
 * @hooked WC_Emails::order_meta() Shows order meta data.
 */
do_action('woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email);

/*
 * @hooked WC_Emails::customer_details() Shows customer details
 * @hooked WC_Emails::email_address() Shows email address
 */
do_action('woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email);

/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ($additional_content) {
	echo wp_kses_post(wpautop(wptexturize($additional_content)));
}

/*
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action('woocommerce_email_footer', $email);
