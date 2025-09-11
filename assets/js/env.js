const SITE_URL = envParams.SITE_URL;
const API_BASE_URL = SITE_URL + "wp-json/wp/v2/";
const THEME_URL = SITE_URL + "wp-content/themes/coc-theme/";
const API_CAPACITACIONES_URL = API_BASE_URL + "capacitaciones?_embed&per_page=150";
const API_BENEFICIOS_URL = API_BASE_URL + "beneficio?_embed&per_page=100&acf_format=standard";
const API_SOCIEDADES_URL = API_BASE_URL + "sociedad?_embed&per_page=100&acf_format=standard";
const API_MEDIA_BASE = API_BASE_URL + "media/";
const CHECKOUT_URL = SITE_URL + "finalizar-compra/?add-to-cart=";
