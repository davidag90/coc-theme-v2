const SITE_URL = envParams.SITE_URL;
const API_BASE_URL = SITE_URL + "wp-json/coc-api/v1/";
const WP_API_BASE_URL = SITE_URL + "wp-json/coc-api/v1/";
const THEME_URL = SITE_URL + "wp-content/themes/coc-theme/";
const API_CAPACITACIONES_VIGENTES_URL =
  API_BASE_URL + "capacitaciones/vigentes";
const API_CAPACITACIONES_INICIADAS_URL =
  API_BASE_URL + "capacitaciones/iniciadas";
const API_BENEFICIOS_URL =
  WP_API_BASE_URL + "beneficio?_embed&per_page=100&acf_format=standard";
const API_SOCIEDADES_URL =
  WP_API_BASE_URL + "sociedad?_embed&per_page=100&acf_format=standard";
const API_MEDIA_BASE = API_BASE_URL + "media/";
const CHECKOUT_URL = SITE_URL + "finalizar-compra/?add-to-cart=";
