<?php
register_rest_route('coc-api/v1', '/capacitaciones/vigentes', array(
  'methods' => 'GET',
  'callback' => 'get_capacitaciones_vigentes',
  'permission_callback' => '__return_true',
  'args' => array(
    'per_page' => array(
      'validate_callback' => function ($param) {
        return is_numeric($param) && $param > 0 && $param <= 100;
      },
      'default' => 100,
    ),
    'page' => array(
      'validate_callback' => function ($param) {
        return is_numeric($param) && $param > 0;
      },
      'default' => 1,
    ),
    'especialidad' => array(
      'validate_callback' => function ($param) {
        return is_string($param);
      },
      'default' => '',
    ),
  ),
));

register_rest_route('coc-api/v1', '/capacitaciones/iniciadas', array(
  'methods' => 'GET',
  'callback' => 'get_capacitaciones_iniciadas',
  'permission_callback' => '__return_true',
  'args' => array(
    'per_page' => array(
      'validate_callback' => function ($param) {
        return is_numeric($param) && $param > 0 && $param <= 100;
      },
      'default' => 10,
    ),
    'page' => array(
      'validate_callback' => function ($param) {
        return is_numeric($param) && $param > 0;
      },
      'default' => 1,
    ),
    'especialidad' => array(
      'validate_callback' => function ($param) {
        return is_string($param);
      },
      'default' => '',
    )
  ),
));

register_rest_route('coc-api/v1', '/beneficios', array(
  'methods' => 'GET',
  'callback' => 'get_beneficios',
  'permission_callback' => '__return_true',
  'args' => array(
    'per_page' => array(
      'validate_callback' => function ($param) {
        return is_numeric($param) && $param > 0 && $param <= 100;
      },
      'default' => 10,
    ),
    'page' => array(
      'validate_callback' => function ($param) {
        return is_numeric($param) && $param > 0;
      },
      'default' => 1,
    ),
    'rubro' => array(
      'validate_callback' => function ($param) {
        return is_string($param);
      },
      'default' => '',
    ),
  ),
));

register_rest_route(
  'coc-api/v1',
  '/sociedades',
  array(
    'methods' => 'GET',
    'callback' => 'get_sociedades',
    'permission_callback' => '__return_true',
  ),
);
