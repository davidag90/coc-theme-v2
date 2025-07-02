<?php
function get_capacitacion_data($id_capacitacion)
{
   $data = [];

   $terms = get_the_terms($id_capacitacion, 'especialidad');
   $data['especialidad_name'] = $terms[0]->name;
   $data['especialidad_slug'] = $terms[0]->slug;
   $data['img_destacada'] = get_the_post_thumbnail_url();

   $custom_fields = get_fields($id_capacitacion);

   foreach ($custom_fields as $field => $value) {
      $data[$field] = $value;
   }

   return $data;
}

function check_started($inicio)
{
   $hoy = date('Ymd');

   return $inicio <= $hoy;
}

function set_related_especialidades($related_especialidades, $especialidad_base_slug)
{
   $slugs = [$especialidad_base_slug];

   if ($related_especialidades) {
      foreach ($related_especialidades as $related_especialidad) {
         $slugs[] = $related_especialidad->slug;
      }
   }

   return $slugs;
}

function get_related_capacitaciones($limit, $related_especialidades, $id_capacitacion)
{
   ob_start();
   $query_args = array(
      'post_type' => 'capacitaciones',
      'posts_per_page' => $limit,
      'post__not_in' => array(get_the_ID()),
      'tax_query' => array(
         'relation' => 'OR',
         array(
            'taxonomy' => 'especialidad',
            'field' => 'slug',
            'terms' => $related_especialidades,
            'operator' => 'IN'
         )
      ),
      'orderby' => 'meta_value',
      'order' => 'ASC',
      'meta_key' => 'fecha_inicio_dateformat',
      'meta_value' => date('Ymd'),
      'meta_type' => 'DATE',
      'meta_compare' => '>'
   );

   $query = new WP_Query($query_args);

   $remaining = $limit - $query->post_count;

   $exclude = array($id_capacitacion);

   // Mostrar los posts relacionados
   if ($query->have_posts() || $remaining > 0) {
      while ($query->have_posts()) {
         $query->the_post();

         $exclude[] = get_the_ID(); // Almacenar el ID del post mostrado

         $img_top = get_the_post_thumbnail_url();
         $titulo = get_the_title();
         $inicio = get_field('fecha_inicio');
         $link = get_the_permalink();

         $terms = get_the_terms(get_the_ID(), 'especialidad');
         $especialidad_slug = '';
         $especialidad_nombre = '';

         if ($terms && !is_wp_error($terms)) {
            $especialidad_slug = $terms[0]->slug;
            $especialidad_nombre = $terms[0]->name;
         }

         $tipo_capacitacion = get_field('tipo_capacitacion');

         echo '<div class="card border-' . esc_attr($especialidad_slug) . ' h-100 capacitacion" coc-especialidad="' . esc_attr($especialidad_slug) . '">';
         echo '<img class="card-img-top" src="' . esc_url($img_top) . '" alt="' . esc_attr($titulo) . '" />';
         echo '<div class="card-body d-flex flex-column">';
         echo '<p class="mb-2"><small>' . $tipo_capacitacion . ' en ' . $especialidad_nombre . '</small></p>';
         echo '<h4 class="card-title h6">' . esc_html($titulo) . '</h4>';
         echo '<p class="card-text">' . esc_html($inicio) . '</p>';
         echo '<a href="' . esc_url($link) . '" class="btn btn-sm btn-' . esc_attr($especialidad_slug) . ' mt-auto ms-auto me-0 text-nowrap">Más información</a>';
         echo '</div>'; // .card-body
         echo '</div>'; // .card
      }

      if ($remaining > 0) {
         $remaining_query_args = array(
            'post_type' => 'capacitaciones',
            'posts_per_page' => $remaining,
            'post__not_in' => $exclude,
            'orderby' => 'meta_value',
            'meta_key' => 'fecha_inicio_dateformat',
            'meta_type' => 'DATE',
            'order' => 'ASC',
            'meta_value' => date('Ymd'),
            'meta_compare' => '>'
         );

         $remaining_query = new WP_Query($remaining_query_args);

         if ($remaining_query->have_posts()) {
            while ($remaining_query->have_posts()) {
               $remaining_query->the_post();

               $img_top = get_the_post_thumbnail_url();
               $titulo = get_the_title();
               $inicio = get_field('fecha_inicio');
               $link = get_the_permalink();

               $terms = get_the_terms(get_the_ID(), 'especialidad');
               $especialidad_slug = '';
               $especialidad_nombre = '';

               if ($terms && !is_wp_error($terms)) {
                  $especialidad_slug = $terms[0]->slug;
                  $especialidad_nombre = $terms[0]->name;
               }

               $tipo_capacitacion = get_field('tipo_capacitacion');

               echo '<div class="card border-' . esc_attr($especialidad_slug) . ' h-100 capacitacion" coc-especialidad="' . esc_attr($especialidad_slug) . '">';
               echo '<img class="card-img-top" src="' . esc_url($img_top) . '" alt="' . esc_attr($titulo) . '" />';
               echo '<div class="card-body d-flex flex-column">';
               echo '<p class="mb-2"><small>' . $tipo_capacitacion . ' en ' . $especialidad_nombre . '</small></p>';
               echo '<h4 class="card-title h6">' . esc_html($titulo) . '</h4>';
               echo '<p class="card-text">' . esc_html($inicio) . '</p>';
               echo '<a href="' . esc_url($link) . '" class="btn btn-sm btn-' . esc_attr($especialidad_slug) . ' mt-auto ms-auto me-0 text-nowrap">Más información</a>';
               echo '</div>'; // .card-body
               echo '</div>'; // .card
            }
         }
      }

      wp_reset_postdata();
   } else {
      // Si no se encuentran posts relacionados
      echo 'No se encontraron capacitaciones relacionadas.';
   }

   return ob_get_clean();
}

function set_inscripcion_data($product_id, $inscripcion_value, $inscripcion_label, $state_inscripcion, $iniciada)
{
   ob_start();

   $product = wc_get_product($product_id);
   $is_variable = false;

   if ($product) {
      $is_variable = $product->has_child();
   }

   if ($state_inscripcion === 'abiertas' && !$iniciada) {
      if ($is_variable) {
         $variations_ids = $product->get_children();

         echo '<h2>Inscripción</h2>';
         echo '<p>Selecciona una categoría con la cual inscribirte:</p>';
         echo '<div class="wrap-variable-prod mb-3">';
         echo '<select class="form-select" name="select-variable-prod" id="select-variable-prod">';
         echo '<option selected>Seleccionar categoría...</option>';
         foreach ($variations_ids as $variation) {
            $variation_product = wc_get_product($variation);
            echo '<option value="' . $variation_product->get_id() . '">' . $variation_product->get_attribute('inscripcion') . '</option>';
         }
         echo '</select>';
         echo '</div>';
         echo '<a id="btn-variable-prod" class="btn btn-warning w-100 py-2 link-light mb-2 d-block disabled" href="' . esc_url(home_url() . '/finalizar-compra/?add-to-cart=') . '">' . $inscripcion_label . ' &rarr;</a>';
      } else {
         echo '<a class="btn btn-warning w-100 py-2 link-light mb-2" href="' . esc_url(!empty($product_id) ? home_url() . '/finalizar-compra/?add-to-cart=' . strval($product_id) : 'https://wa.me/3512373661') . '">' . $inscripcion_label . ' &rarr;</a>';
      }
   } else {
      echo '<button class="btn btn-warning w-100 py-2 link-light mb-2" disabled><i class="fa-solid fa-ban"></i> INSCRIPCIONES CERRADAS</button>';
   }

   return ob_get_clean();
}
