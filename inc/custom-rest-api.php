<?php
register_rest_route('coc-api/v1', '/capacitaciones/vigentes', array(
  'methods' => 'GET',
  'callback' => 'get_capacitaciones_vigentes',
  'permission_callback' => '__return_true'
));

register_rest_route('coc-api/v1', '/capacitaciones/iniciadas', array(
  'methods' => 'GET',
  'callback' => 'get_capacitaciones_iniciadas',
  'permission_callback' => '__return_true'
));
