<?php
function get_capacitaciones_vigentes(WP_REST_Request $request)
{
  $today = current_time('Ymd');

  $per_page = $request->get_param('per_page');
  $page = $request->get_param('page');
  $offset = ($page - 1) * $per_page;
  $especialidad = $request->get_param('especialidad');

  $query_args = array(
    'post_type' => 'capacitaciones',
    'posts_per_page' => $per_page,
    'offset' => $offset,
    'meta_query' => array(
      array(
        'key' => 'fecha_inicio_dateformat',
        'value' => $today,
        'compare' => '>',
        'type' => 'DATE',
      ),
    ),
    'tax_query' => $especialidad ? array(
      array(
        'taxonomy' => 'especialidad',
        'field' => 'slug',
        'terms' => array($especialidad),
      ),
    ) : array(),
    'orderby' => 'meta_value',
    'order' => 'ASC',
    'meta_key' => 'fecha_inicio_dateformat'
  );

  return process_capacitaciones($query_args, $page, $per_page);
}

function get_capacitaciones_iniciadas(WP_REST_Request $request)
{
  $today = current_time('Ymd');

  $per_page = $request->get_param('per_page');
  $page = $request->get_param('page');
  $offset = ($page - 1) * $per_page;
  $especialidad = $request->get_param('especialidad');

  $query_args = array(
    'post_type' => 'capacitaciones',
    'posts_per_page' => $per_page,
    'offset' => $offset,
    'meta_query' => array(
      array(
        'key' => 'fecha_inicio_dateformat',
        'value' => $today,
        'compare' => '<=',
        'type' => 'DATE',
      ),
    ),
    'tax_query' => $especialidad ? array(
      array(
        'taxonomy' => 'especialidad',
        'field' => 'slug',
        'terms' => array($especialidad),
      ),
    ) : array(),
    'orderby' => 'meta_value',
    'order' => 'DESC',
    'meta_key' => 'fecha_inicio_dateformat'
  );

  return process_capacitaciones($query_args, $page, $per_page);
}

function process_capacitaciones($query_args, $page, $per_page)
{
  $count_args = $query_args;
  $count_args['posts_per_page'] = -1;
  $count_args['fields'] = 'ids';
  unset($count_args['offset']); // Limpia el offset original para que rescate todos los elementos

  $count_query = new WP_Query($count_args);

  $total_posts = $count_query->found_posts;
  $total_pages = ceil($total_posts / $per_page);

  $capacitaciones = new WP_Query($query_args);
  $data = array();

  if ($capacitaciones->have_posts()) {
    while ($capacitaciones->have_posts()) {
      $capacitaciones->the_post();
      $post = get_post();
      $capacitacion = get_capacitacion_info($post);

      if ($capacitacion) {
        $data[] = $capacitacion;
      }
    }

    wp_reset_postdata(); // Importante: resetear datos globales
  }

  return new WP_REST_Response(array(
    'data' => $data,
    'pagination' => array(
      'total' => $total_posts,
      'pages' => $total_pages,
      'page' => $page,
      'per_page' => $per_page,
    )
  ), 200);
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
